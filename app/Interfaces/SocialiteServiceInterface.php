<?php

namespace App\Interfaces;

interface SocialiteServiceInterface
{
    /**
     * get redirect url
     * @param $provider
     * @return string
     */
    public function getRedirectUrlByProvider(String $provider);

    /**
     * @param $provider
     * @return mixed
     */
    public function loginWithSocialite(String $provider);
}
