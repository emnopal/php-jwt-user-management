<?php

namespace BadHabit\LoginManagement\App;

use BadHabit\LoginManagement\Model\DecodeSession;
use BadHabit\LoginManagement\Domain\Decoded;
use BadHabit\LoginManagement\Domain\Encode;
use BadHabit\LoginManagement\Model\EncodeSession;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{

    public function testEncode()
    {
        // Get Session of User
        $decodeSession = new Decoded();
        $decodeSession->user_id = 'test';
        $decodeSession->role = 'user';

        // Encode Session
        $encode = new Encode();
        $encode->iss = 'https://example.com';
        $encode->data = $decodeSession;

        $handler = new Handler();
        self::assertInstanceOf(EncodeSession::class, $handler->encode($encode));
        self::assertEquals('test', $handler->encode($encode)->data->user_id);
        self::assertEquals('user', $handler->encode($encode)->data->role);
    }

    public function testDecode()
    {
        // Get Session of User
        $decodeSession = new Decoded();
        $decodeSession->user_id = 'test';
        $decodeSession->role = 'user';

        // Encode Session
        $encode = new Encode();
        $encode->iss = 'https://example.com';
        $encode->data = $decodeSession;

        $handler = new Handler();
        $token = $handler->encode($encode);

        $decode = new DecodeSession();
        $decode->token = $token->key;

        $key = $handler->decode($decode);

        self::assertInstanceOf(Decoded::class, $key);
        self::assertEquals('test', $key->user_id);
        self::assertEquals('user', $key->role);
    }


}
