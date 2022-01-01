<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\Model\DecodeSession;
use BadHabit\LoginManagement\Domain\Decoded;
use BadHabit\LoginManagement\Model\EncodeSession;
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
        $decodeSession = new Decoded();
        $decodeSession->user_id = 'test';
        $decodeSession->role = 'user';
        $encodeSession = $this->sessionRepository->getToken($decodeSession);

        self::assertInstanceOf(EncodeSession::class, $encodeSession);
    }

    public function testDecodeToken()
    {
        $decodeSession = new Decoded();
        $decodeSession->user_id = 'test';
        $decodeSession->role = 'user';
        $encodeSession = $this->sessionRepository->getToken($decodeSession);

        $decode = new DecodeSession();
        $decode->token = $encodeSession->key;
        $decodeSession = $this->sessionRepository->decodeToken($decode);

        self::assertInstanceOf(Decoded::class, $decodeSession);
        self::assertEquals('test', $decodeSession->user_id);
        self::assertEquals('user', $decodeSession->role);
    }

}
