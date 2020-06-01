<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Response;
use App\Services\SocialiteService;


class AuthController extends Controller
{
    protected $guard ='api';

    public function __construct()
    {

    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return response()->json(compact('errors'),Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return response()->json(['user' => $user ], Response::HTTP_OK);
    }

    /**
     * Login user and return a token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->responseWithToken($token)->header('Authorization', $token);
        }
        return response()->json(['message' => 'Invalid Email or Password'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Logout User
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['status' => 'success', 'msg' => 'Logged out Successfully.'], Response::HTTP_OK);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(['status' => 'success', 'data' => auth()->user()],Response::HTTP_OK);
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'success'], Response::HTTP_OK)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Return auth guard
     */
    private function guard()
    {
        return Auth::guard( $this->guard );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithToken($token)
    {
        return response()->json([
            'user' => $this->guard()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 6000000
        ],Response::HTTP_OK);
    }

    /**
     * Redirect the user to the social network authentication page.
     * @param String $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToProvider(String $provider , SocialiteService $socialiteService)
    {
        return response()->json([
            'redirectUrl' => $socialiteService->getRedirectUrlByProvider($provider)
        ],Response::HTTP_OK);
    }

    /**
     * @param String $provider
     * @param SocialiteService $socialiteService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(String $provider ,SocialiteService $socialiteService)
    {
        $result = $socialiteService->loginWithSocialite($provider);
        return Redirect::to($result['redirectUrl']);
    }
}
