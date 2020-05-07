# Laravel JWt AUTH

## Installation

#Laravel Application Setup
Use the following composer command to create your application..

```bash
composer create-project laravel/laravel laravel_jwt_vuejs --prefer-dist
```

## Add JWT Auth Package
   You can use the composer command to add a package in your application. It is one of the easy ways to add any package into the Laravel application.

```bash
composer require tymon/jwt-auth:dev-develop
```

## Publish JWT Configuration
  Here, we can use an artisan command to publish the JWT package configuration
```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

// You can also use this command without provider flag, and select provider from the given list.
```

## Create JWT Secret
  You can create the JWT secret using the following artisan command
```bash
php artisan jwt:secret 
.env //JWT_SECRET=9fSvd27OQoqDWCIb7spYgtRcrFushNrzUQb87OvMSxGmuP7Gcy55QeLLGKACEqKY
//JWT_TTL=10
```

## Update Auth Config
  Open your `config/auth.php` then update the default guard and API driver.
 ```php
<?php
// Default Guard
'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
],

// Driver of the API Guard
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
?>

 ```

## Update User Model
  Open user model, it’s provided with the Laravel application. As I already mention about the changes of the custom Model directory, You need to update the following code into your model file.
Please make sure to update tests as appropriate.

```php
<?php 

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
}
?>
```
## Update Authentication Middleware
  Here, I’m updating my `app/Http/Middleware/Authenticate.php` middleware. I’m overriding the handle and authenticate method and I’m setting out to return the JSON formatted responses. Because this setup will be used in Vue Js application where I need JSON formatted responses and login page will be handled by the Vue Js.
  
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    // Override handle method
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards) === 'authentication_failed') {
            return response()->json(['error'=>'Unauthorized'],400);
        }
        return $next($request);
    }

    // Override authentication method
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }
        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }
        return 'authentication_failed';
    }
}
```
## Laravel Routes
  Now, we will create required authentication routes in the `routes/api.php`. You just need to add the following routes:
  ```php
<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->namespace('Api')->group(function () {
    // Create New User
    Route::post('register', 'AuthController@register');
    // Login User
    Route::post('login', 'AuthController@login');
    // Refresh the JWT Token
    Route::get('refresh', 'AuthController@refresh');

    // Route::get('social/{provider}', 'AuthController@redirectToProvider');
    // Route::get('social/callback/{provider}', 'AuthController@handleProviderCallback');

    Route::middleware('auth:api')->group(function () {
        // Get user info
        Route::get('user', 'AuthController@user');
        // Logout user from application
        Route::post('logout', 'AuthController@logout');
    });
});
  ```
## Create Auth Controller
```bash
php artisan make:controller Api\AuthController
```
This will create an `AuthController.php` controller at `app/Http/Controllers`.
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Obtain the user information from social network
     * @param $provider
     * @return \Illuminate\Routing\Redirector
     */
    public function handleProviderCallback(String $provider ,SocialiteService $socialiteService)
    {
        $result = $socialiteService->loginWithSocialite($provider);
        return redirect()->back()->with( ['social' => $result] );
    }
}

```
In this controller, I’m applying the simple logic to make the registration and login process.

The `register()` method create a new user after validation.

The `login()` method use the `Auth::guard()` method that use the JWT. The attempt() method checks the given credentials then after the success, it will generate a token which will be returned in the headers of the response.

The `refresh()` method regenerate a token if the current token is expired. You can manage the duration in the `config/jwt.php`. And reset the duration as per your application requirement. You just need to add JWT_TTL in your `.env` file. By default, the JWT token is valid for 60 minutes (1 Hour).

```bash
JWT_TTL=10
```
I’m changing this limit to 10 minutes, from now our JWT token is valid only for the 10 minutes.

The `logout()` method simply unset the token.

The `user()` method returns the authenticated user details.

You can test those API in postman.
