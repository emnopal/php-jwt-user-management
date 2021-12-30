<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\App\Auth;

class SessionRepository
{

    private ?string $url;
    private Auth $auth;

    public function __construct(Auth $auth, ?string $url = "http://localhost/")
    {
        $this->auth = $auth;
        if ($url) {
            $this->url = $url;
        }else {
            if (isset($_SERVER['PATH_INFO'])){
                $this->url = $url + $_SERVER['PATH_INFO'];
            }else{
                $this->url = $url;
            }

        }
    }

    private function getJWT(string $user_id): array|string
    {
        $data = [
            'user_id' => $user_id
        ];

        return $this->auth->encode($this->url, $data);
    }

    private function decodeJWT(string $token): array|string
    {
        return $this->auth->decode($token);
    }

    public function getToken(string $user_id): string
    {
//        return $this->getJWT($user_id)['key'];
        $encodedToken = $this->getJWT($user_id);
        $encodedResult = &$encodedToken;
        return $encodedResult['key'];
    }

    public function decodeToken(string $token): string
    {
//        return $this->decodeJWT($token)['user_id'];
        $decodedToken = $this->decodeJWT($token);
        $decodedResult = &$decodedToken;
        return $decodedResult['user_id'];
    }

    public function getExpire(string $user_id): string
    {
//        return $this->getJWT($user_id)['expire'];
        $encodedToken = $this->getJWT($user_id);
        $encodedResult = &$encodedToken;
        return $encodedResult['expire'];
    }

}