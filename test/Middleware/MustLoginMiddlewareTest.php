<?php

namespace BadHabit\LoginManagement\Middleware;

require_once __DIR__ . "/../Helper/helper.php";

use BadHabit\LoginManagement\App\Auth;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\Session;
use BadHabit\LoginManagement\Domain\User;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;
use PHPUnit\Framework\TestCase;

class MustLoginMiddlewareTest extends TestCase
{

    private MustLoginMiddleware $mustLoginMiddleware;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    private SessionService $sessionService;

    public function setUp(): void
    {
        $this->mustLoginMiddleware = new MustLoginMiddleware();
        putenv("mode=test");

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(new Auth());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionService->destroy();
        $this->userRepository->deleteAll();

    }

    public function testBeforeLogin()
    {
        $this->mustLoginMiddleware->before();

        $this->expectOutputRegex("[X-BHB-SESSION: ]");
    }

    public function testBeforeLoggedIn()
    {
        $user = new User();
        $user->username = "test";
        $user->fullName = "test";
        $user->password = "test";
        $user->email = "user@mail.com";
        $this->userRepository->save($user);

        $this->sessionService->create($user->username);
        $token = $this->sessionRepository->getToken($user->username);
        $_COOKIE[SessionService::$COOKIE_NAME] = $token;
        $cookie_name = SessionService::$COOKIE_NAME;

        $this->mustLoginMiddleware->before();
        $this->expectOutputRegex("[$cookie_name: $token]");

    }
}
