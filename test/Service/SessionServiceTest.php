<?php

namespace BadHabit\LoginManagement\Service;

require_once __DIR__ . '/../Helper/helper.php';

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
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $userRepository);

        $this->sessionRepository->deleteAll();
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
        $this->expectOutputRegex("[X-BHB-SESSION: $session->id]");

        $this->sessionRepository->findById($session->id);

        self::assertEquals("test", $session->user_id);
    }

    public function testDestroySession(): void
    {

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "test";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-BHB-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);

    }

    public function testCurrentSession(): void
    {

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "test";

        $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();
        self::assertEquals($session->user_id, $user->username);

    }

}
