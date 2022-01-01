<?php

namespace BadHabit\LoginManagement\Middleware;

require_once __DIR__ . "/../Helper/helper.php";

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\Decoded;
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
    private SessionService $sessionService;

    public function setUp(): void
    {
        $this->mustNotLoginMiddleware = new MustNotLoginMiddleware();
        putenv("mode=test");

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(new Handler());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionService->destroy();
        $this->userRepository->deleteAll();

    }

    public function testBeforeLogin()
    {
        $this->mustNotLoginMiddleware->before();
        $this->expectOutputRegex("[X-BHB-SESSION: ]");

    }

    public function testBeforeLoggedIn()
    {
        $user = new User();
        $user->username = "test";
        $user->fullName = "test";
        $user->password = "test";
        $user->email = "user@test.com";
        $user->role = "user";

        $this->userRepository->save($user);

        $decodeSession = new Decoded();
        $decodeSession->user_id = $user->username;
        $decodeSession->role = $user->role;

        $this->sessionService->create($decodeSession);
        $token = $this->sessionRepository->getToken($decodeSession);
        $_COOKIE[SessionService::$COOKIE_NAME] = $token->key;

        $this->mustNotLoginMiddleware->before();
        $this->expectOutputRegex("[Location: ]");
    }

    public function testBeforeLoggedInAdmin()
    {
        $user = new User();
        $user->username = "test";
        $user->fullName = "test";
        $user->password = "test";
        $user->email = "user@test.com";
        $user->role = "admin";

        $this->userRepository->save($user);

        $decodeSession = new Decoded();
        $decodeSession->user_id = $user->username;
        $decodeSession->role = $user->role;

        $this->sessionService->create($decodeSession);
        $token = $this->sessionRepository->getToken($decodeSession);
        $_COOKIE[SessionService::$COOKIE_NAME] = $token->key;

        $this->mustNotLoginMiddleware->before();
        $this->expectOutputRegex("[Location: ]");

    }
}



