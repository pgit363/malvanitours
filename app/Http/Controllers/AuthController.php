<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            return $this->sendError($validator->errors(), '', 200);
        }

        if (!$token = auth()->attempt($validator->validated(), ['exp' => JWTAuth::factory()->setTTL(60 * 60 * 24 * 100)])) {
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
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required',
                'project_id' => 'nullable|numeric|exists:projects,id',
                'name' => 'required|string|between:2,60',
                'email' => 'required_if:mobile,null|nullable|string|email|max:100|unique:users',
                'mobile' => 'required_if:email,null|nullable|string|unique:users,mobile|digits:10',
                'password' => 'nullable|string|required_with:email|confirmed|min:6',
                'profile_picture' => 'nullable|mimes:jpeg,jpg,png,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), '', 200);
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
        } catch (\Throwable $th) {
            throw $th;
            Log::error($th->getMessage());
        }
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
            'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 1,
            'user' => JWTAuth::setToken($token)->authenticate()
        ];

        return $this->sendResponse($response, $message);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|nullable|required_without:mobile|email|exists:users,email',
            'mobile' => 'sometimes|nullable|required_without:email|exists:users,mobile',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), '', 200);
        }

        $otp =  random_int(100000, 999999);

        $data = [
            'subject' => 'otp',
            'mobile' => $request->mobile,
            'email' =>  $request->email,
            'content' => 'Hello your otp is ' . $otp
        ];

        $where_condition = array_filter($request->all());

        $user = User::where($where_condition)->first();

        if ($user) {
            User::where($where_condition)->update(array('otp' => $otp));

            if ($request->has('email')) {
                Mail::send('email-template', $data, function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['subject']);
                    $message->from('kamblepranav460@gmail.com', 'Pranav Kamble');
                });
            }

            if ($request->has('mobile')) {
                #send otp using sms gateway
                // return $otp;
            }
        }

        return $this->sendResponse($data, 'OTP successfully sent!');
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'sometimes|nullable|required_without:mobile|email|exists:users,email',
                'mobile' => 'sometimes|nullable|required_without:email|exists:users,mobile',
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), '', 200);
            }

            $where_condition = array_filter($request->all());

            $user = User::where($where_condition)->where('otp', $request->otp)->first();

            if ($user)
                User::where($where_condition)->update(array('otp' => null));
            else
                return $this->sendError('Invalid OTP', [], 400);

            $token = JWTAuth::fromUser($user);

            return $this->createNewToken($token, 'Refreshed token...!');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
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
