<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\Decode;
use BadHabit\LoginManagement\Domain\DecodeSession;
use BadHabit\LoginManagement\Domain\EncodeSession;
use BadHabit\LoginManagement\Service\SessionService;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase
{

    private SessionRepository $sessionRepository;
    private SessionService $sessionService;

    public function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(new Handler());
    }

    public function testGetToken()
    {
        $decodeSession = new DecodeSession();
        $decodeSession->user_id = 'test';
        $decodeSession->role = 'user';
        $encodeSession = $this->sessionRepository->getToken($decodeSession);

        self::assertInstanceOf(EncodeSession::class, $encodeSession);
    }

    public function testDecodeToken()
    {
        $decodeSession = new DecodeSession();
        $decodeSession->user_id = 'test';
        $decodeSession->role = 'user';
        $encodeSession = $this->sessionRepository->getToken($decodeSession);

        $decode = new Decode();
        $decode->token = $encodeSession->key;
        $decodeSession = $this->sessionRepository->decodeToken($decode);

        self::assertInstanceOf(DecodeSession::class, $decodeSession);
        self::assertEquals('test', $decodeSession->user_id);
        self::assertEquals('user', $decodeSession->role);
    }

}
