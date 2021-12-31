<?php

namespace BadHabit\LoginManagement\Controller;

require_once __DIR__ . "/../Helper/helper.php";

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\DecodeSession;
use BadHabit\LoginManagement\Domain\User;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;
use BadHabit\LoginManagement\Service\UserService;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{

    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    private SessionService $sessionService;

    protected function setUp(): void
    {
        $this->homeController = new HomeController();

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(new Handler());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionService->destroy();
        $this->userRepository->deleteAll();
    }

    public function testGuess(): void
    {
        $this->homeController->index();
        $this->expectOutputRegex("[Login Management]");
    }

    public function testUserLogin(): void
    {
        $user = new User();
        $user->username = "test";
        $user->password = "test";
        $user->fullName = "Test User";
        $user->email = "test@gmail.com";

        $this->userRepository->save($user);

        $this->sessionService->create($user->username);
        $token = $this->sessionRepository->getToken($user->username);
        $_COOKIE[SessionService::$COOKIE_NAME] = $token;

        $this->homeController->index();
        $this->expectOutputRegex("[Hello, <br>" . ucwords($user->fullName) . "]");
    }


}
