<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController as BaseController;
use Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'sendOtp', 'verifyOtp']]);
    }

    public function index(Request $request)
    {
        // if ($request->privilage == 'superadmin') {
        $user = User::with('roles', 'commentsOfUser', 'commentsOnUser', 'project', 'projects')
            ->paginate(10);
        return $this->sendResponse($user, 'User successfully registered');
        // }
        // else{
        //     return $this->sendError('Unauthorized', '', 401);
        // }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), '', 400);
        }

        if (!$token = auth()->attempt($validator->validated(), ['exp' => \Carbon\Carbon::now()->addDays(7)->timestamp])) {
            return $this->sendError('Unauthorized', '', 401);
        }

        return $this->createNewToken($token, 'Logged In...!');
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'project_id' => 'numeric',
            'name' => 'required|string|between:2,100',
            'email' => 'sometimes|string|email|max:100|unique:users',
            'mobile' => 'sometimes|string|between:2,100',
            'password' => 'string|confirmed|min:6',
            'profile_picture' => 'mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), '', 400);
        }

        if ($request->password == "") {
            $password = "password";
        } else {
            $password = $request->password;
        }

        $input = $validator->validated();
        $date = currentDate();
        Log::info("upload file starting");

        //upload profile Image      
        if ($image = $request->file('profile_picture')) {
            Log::info("inside upload profile_picture");

            $profile_picture = date('YmdHis') . "." . $image->getClientOriginalExtension();

            $path = $request->file('profile_picture')->store(config('constants.upload_path.profile_picture') . $request->category_id . '/' . $request->name);

            $input['profile_picture'] = Storage::url($path);

            Log::info("FILE STORED" . $input['profile_picture']);
        }

        $input['password'] = bcrypt($password);

        $user = User::create($input);

        return $this->sendResponse($user, 'User successfully registered');
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->sendResponse(null, 'User successfully signed out');

        // return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh(), 'Refreshed token...!');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return $this->sendResponse(auth()->user(), 'User Fetched..!');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token, $message)
    {
        $response = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 100,
            'user' => auth()->user()
        ];

        return $this->sendResponse($response, $message);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|email',
            'mobile' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), '', 400);
        }

        $otp =  random_int(100000, 999999);

        $data = [
            'subject' => 'otp',
            'mobile' => $request->mobile,
            'email' =>  $request->email,
            'content' => 'Hello your otp is ' . $otp
        ];

        if ($request->has('email')) {
            User::where('email', $request->email)->update(array('otp' => $otp));

            Mail::send('email-template', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject($data['subject']);
                $message->from('kamblepranav460@gmail.com', 'Pranav Kamble');
            });
        }
        if ($request->has('mobile')) {
            User::where('mobile', $request->mobile)->update(array('otp' => $otp));
        }

        return response(['message' => 'OTP successfully sent!']);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|email',
            'mobile' => 'sometimes',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), '', 400);
        }

        if ($request->has('email')) {
            $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

            if ($user) {
                User::where('email', $request->email)->update(array('otp' => null));
                return $this->createNewToken(auth()->refresh(), 'Refreshed token...!');
            } else {
                return $this->sendError('Invalid OTP', [], 400);
            }
        }
        if ($request->has('mobile')) {
            $user = User::where('mobile', $request->mobile)->where('otp', $request->otp)->first();

            if ($user) {
                User::where('mobile', $request->mobile)->update(array('otp' => null));
                return $this->createNewToken(auth()->refresh(), 'Refreshed token...!');
            } else {
                return $this->sendError('Invalid OTP', [], 400);
            }
        }

        return response(['user' => 'failed']);
    }

    public function getAllFavourites($id)
    {
        $favourites  = User::
            // select(\DB::raw('favouritable_id, favouritable_id'))
            withCount('favourites')
            ->with('favourites')
            ->groupBy('favourites.favouritable_id')
            // ->groupBy('favouritable_type')
            // ->orderBy('created_at', 'desc')
            ->latest()
            ->whereId($id);


        logger($favourites->toSql());
        // ->get();

        if (is_null($favourites)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($favourites, 'Favourites successfully Retrieved...!');
    }
}
