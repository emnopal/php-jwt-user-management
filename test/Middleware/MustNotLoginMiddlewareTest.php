<?php

namespace BadHabit\LoginManagement\Middleware;

require_once __DIR__ . "/../Helper/helper.php";

use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\Session;
use BadHabit\LoginManagement\Domain\User;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;
use PHPUnit\Framework\TestCase;

class MustNotLoginMiddlewareTest extends TestCase
{

    private MustNotLoginMiddleware $mustNotLoginMiddleware;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        $this->mustNotLoginMiddleware = new MustNotLoginMiddleware();
        putenv("mode=test");

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

    }

    public function testBeforeLogin()
    {
        $this->mustNotLoginMiddleware->before();
        $this->expectOutputString("");

    }

    public function testBeforeLoggedIn()
    {
        $user = new User();
        $user->username = "test";
        $user->fullName = "test";
        $user->password = "test";
        $user->email = "user@test.com";
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->username;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->mustNotLoginMiddleware->before();
        $this->expectOutputRegex("[Location: ]");

    }
}



