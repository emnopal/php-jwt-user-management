<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\App\Auth;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Service\SessionService;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase
{

    private SessionRepository $sessionRepository;
    private SessionService $sessionService;

    public function setUp(): void
    {
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(new Auth());
        $this->sessionService = new SessionService($this->sessionRepository, $userRepository);
    }

    public function testGetToken()
    {
        self::assertIsString($this->sessionRepository->getToken('test'));
    }

    public function testDecodeToken()
    {
        $token = $this->sessionRepository->getToken('test');
        $decodedToken = $this->sessionRepository->decodeToken($token);
        self::assertEquals('test', $decodedToken);
    }

    public function testGetExpired()
    {
        $token = $this->sessionRepository->getToken('test');
        $expired = $this->sessionRepository->getExpire($token);
        self::assertEquals(time() + 3600*24, $expired);
    }

}
