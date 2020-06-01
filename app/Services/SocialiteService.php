<?php
namespace App\Services;

use App\Interfaces\SocialiteServiceInterface;
use App\Jobs\WriteSocialUserCredentials;
use App\Models\SocialAccount;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;

class SocialiteService implements SocialiteServiceInterface
{

    /**
     * @param String $provider
     * @return string
     * @throws Exception
     */
    public function getRedirectUrlByProvider(String $provider)
    {
        try {
            return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param String $provider
     * @return array[]
     */
    public function loginWithSocialite(String $provider)
    {
        try {
            $userSocial = Socialite::driver($provider)->stateless()->user();
            $user = $this->searchUserByEmail($userSocial->getEmail());
            if( is_null( $user ) ){
                try{
                    $user = new User();
                    $user->email = $userSocial->getEmail();
                    $user->name = $userSocial->getName();
                    $user->status = User::STATUS_APPROVED;
                    $user->cover_photo = $userSocial->getAvatar();
                    $user->password = $password = Str::random(16);
                    $user->save();

                    $account = new SocialAccount();
                    $account->provider_user_id = $userSocial->getId();
                    $account->provider = $provider;
                    $account->profileUrl = $userSocial->profileUrl ?? null;
                    $account->user()->associate($user);
                    $account->password = $password;
                    $account->token = $userSocial->token ?? Str::random(60);
                    $account->save();

                    return $this->prepareSuccessResult($user);
                }
                catch( Exception $exception ){
                    return $this->prepareErrorResult( $exception->getMessage() );
                }
            }elseif (is_null($user->social)){
                return $this->prepareErrorResult('This email address is already registered');
            }
            return $this->prepareSuccessResult( $user );
        }catch (Exception $exception){
            return $this->prepareErrorResult( $exception->getMessage() );
        }
    }

    /**
     * @param String $email
     * @return mixed
     */
    private function searchUserByEmail( String $email )
    {
        return User::with('social')
            ->where(['email'=>$email])
            ->first();
    }

    /**
     * @param User $user
     * @param String|null $url
     * @return mixed
     */
    private function prepareSuccessResult(User $user )
    {
        WriteSocialUserCredentials::dispatch($user);
        return $this->makeAuthenticationCookie([
            'user' => $user,
            'redirectUrl' => '/login/'.$user->social->token
        ]);
    }

    /**
     * @param String $exception
     * @return mixed
     */
    private function prepareErrorResult(String  $exception )
    {
        return $this->makeAuthenticationCookie([
            'error' => $exception,
            'redirectUrl' => '/login',
        ]);
    }

    /**
     * @param $result
     * @return mixed
     */
    private function makeAuthenticationCookie($result)
    {
        return $result;
    }
}
