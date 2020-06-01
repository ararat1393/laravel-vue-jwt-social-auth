<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetPassword;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index(User $user)
    {
        //
        return new UserCollection($user->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request , User $user )
    {
        $user->loadAttributes( $request->all() );
        $user->setScenario(User::SCENARIO_CREATE);
        $user->role = User::ROLE_USER;
        if( $user->validate() && $user->save()){
            return response()->json(['user' => $user ], Response::HTTP_OK);
        }
        return response()->json(['errors'=>$user->getErrors()],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request ,User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request ,User $user)
    {
        return response()->json($user->delete(),Response::HTTP_OK);
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialUser( $token )
    {
        $user = SocialAccount::with('user')
            ->where(['token'=>$token])
            ->first();
        return response()->json(['data' => $user],Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword( Request $request )
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);
        if ($validator->passes()){
            ResetPassword::create([
                'email' => $request->email,
                'token' => Str::random(127)
            ]);
            return response()->json(['message' => 'Please check your email address'],Response::HTTP_OK);
        }
        return response()->json(['errors'=>$validator->errors()->first()],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
