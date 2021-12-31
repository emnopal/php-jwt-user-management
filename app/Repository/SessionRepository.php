<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\Domain\Decode;
use BadHabit\LoginManagement\Domain\DecodeSession;
use BadHabit\LoginManagement\Domain\Encode;
use BadHabit\LoginManagement\Domain\EncodeSession;

class SessionRepository
{

    private ?string $url;
    private Handler $auth;

    public function __construct(Handler $handler, ?string $url = null)
    {
        $this->auth = $handler;
        if (!$url) {
            $this->url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        } else {
            $this->url = $url;
        }
    }

    public function getToken(DecodeSession $decodeSession): EncodeSession
    {
        $encode = new Encode();
        $encode->iss = $this->url;
        $encode->data = $decodeSession;
        return $this->auth->encode($encode);
    }

    public function decodeToken(Decode $decode): DecodeSession
    {
        return $this->auth->decode($decode);
    }

}