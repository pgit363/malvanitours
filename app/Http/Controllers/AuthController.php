<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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

        $user = Auth::user();
        $roles = Roles::whereIn('name', ['superadmin', 'admin'])->get();
        if (
            Str::startsWith($request->route()->getPrefix(), 'admin') &&
            !in_array($user->roles->id, array_column($roles->toArray(), 'id'))
        ) {
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
            $prefix = $request->route()->getPrefix();

            // return [$prefix];
            $validator = Validator::make($request->all(), [
                'role_id' => [
                    'required',
                    'exists:roles,id',
                    function ($attribute, $value, $fail) use ($prefix) {
                        if ($value == Roles::where("name", "superadmin")->pluck('id')->first()) { // Change 1 to the ID of your superadmin role
                            $fail('The selected :attribute is invalid. 1');
                        }

                        if ($prefix === 'admin' && $value !== Roles::where("name", $prefix)->pluck('id')->first()) {
                            $fail('The selected :attribute is invalid. 2');
                        }

                        if ($prefix !== 'admin' && in_array($value, array_column(Roles::whereIn('name', ['superadmin', 'admin'])->get()->toArray(), 'id'))) {
                            $fail('The selected :attribute is invalid. 5');
                        }
                    },
                ],
                'project_id' => 'nullable|numeric|exists:projects,id',
                'name' => 'required|string|between:2,60',
                'email' => 'required_if:mobile,null|nullable|string|email|max:100|unique:users',
                'mobile' => 'required_if:email,null|nullable|string|unique:users,mobile|digits:10',
                'password' => 'nullable|string|required_with:email|confirmed|min:6',
                // 'profile_picture' => 'nullable|mimes:jpeg,jpg,png,webp|max:2048',
                'profile_picture' => 'nullable|string',
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
            if ($request->has('profile_picture')) {

                $directory = config('constants.upload_path.profile_picture') . $request->name;

                $image_64 = $request->input('profile_picture');

                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];

                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

                $image = str_replace($replace, '', $image_64);

                $image = str_replace(' ', '+', $image);

                $imageName = Str::random(10) . '.' . $extension;

                Storage::put($directory . '/' . $imageName, base64_decode($image));

                $input['profile_picture'] = Storage::url($directory . $imageName);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                'profile_picture' => 'sometimes|required|string'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), '', 200);
            }

            $input = $validator->validated();

            if ($request->has('profile_picture')) {

                $directory = config('constants.upload_path.profile_picture') . $request->name;

                $image_64 = $request->input('profile_picture');

                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];

                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

                $image = str_replace($replace, '', $image_64);

                $image = str_replace(' ', '+', $image);

                $imageName = Str::random(10) . '.' . $extension;

                Storage::put($directory . '/' . $imageName, base64_decode($image));

                $input['profile_picture'] = Storage::url($directory . $imageName);

                Log::info("FILE STORED" . $input['profile_picture']);
            }

            $user->update($input);

            return $this->sendResponse($user, 'User successfully updated');
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
        $user = auth()->user();
        $user->load('favourites');
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

            $user = User::where($where_condition)->first();

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
