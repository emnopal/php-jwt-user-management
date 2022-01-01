<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\Model\DecodeSession;
use BadHabit\LoginManagement\Domain\Decoded;
use BadHabit\LoginManagement\Domain\Encode;
use BadHabit\LoginManagement\Model\EncodeSession;

class SessionRepository
{

    private ?string $url;
    private Handler $auth;

    public function __construct(Handler $handler, ?string $url = null)
    {
        $this->auth = $handler;

        if (!$url) {
            if (!isset($_SERVER['HTTP_HOST']) && !isset($_SERVER['REQUEST_URI'])) {
                $this->url = "https://example.com";
            } else {
                $this->url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            }
        } else {
            $this->url = $url;
        }
    }

    public function getToken(Decoded $decoded): EncodeSession
    {
        $encode = new Encode();
        $encode->iss = $this->url;
        $encode->data = $decoded;
        return $this->auth->encode($encode);
    }

    public function decodeToken(DecodeSession $decodeSession): Decoded
    {
        return $this->auth->decode($decodeSession);
    }

}