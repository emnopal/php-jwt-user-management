<?php

namespace BadHabit\LoginManagement\Service;

require_once __DIR__ . '/../Helper/helper.php';

use BadHabit\LoginManagement\App\Auth;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\Session;
use BadHabit\LoginManagement\Domain\User;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(new Auth());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $userRepository);

        $this->sessionService->destroy();
        $userRepository->deleteAll();

        $user = new User();
        $user->username = "test";
        $user->fullName = "Test Account";
        $user->password = "test123";
        $user->email = "user@mail.com";
        $userRepository->save($user);
    }

    public function testCreateSession(): void
    {
        $session = $this->sessionService->create("test");
        $token = $this->sessionRepository->getToken("test");
        $this->expectOutputRegex("[X-BHB-SESSION: $token]");

        $decode = $this->sessionRepository->decodeToken($token);

        self::assertEquals("test", $decode);
    }

    public function testDestroySession(): void
    {
        $this->sessionService->create("test");
        $token = $this->sessionRepository->getToken("test");
        $_COOKIE[SessionService::$COOKIE_NAME] = $token;
        $this->sessionService->destroy();
        $this->expectOutputRegex("[X-BHB-SESSION: ]");
    }

    public function testCurrentSession(): void
    {
        $this->sessionService->create("test");
        $token = $this->sessionRepository->getToken("test");
        $_COOKIE[SessionService::$COOKIE_NAME] = $token;
        $user = $this->sessionService->current();
        self::assertEquals("test", $user->username);

    }

}
