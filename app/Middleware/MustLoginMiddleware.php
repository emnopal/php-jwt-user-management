<?php

namespace BadHabit\LoginManagement\Middleware;

use BadHabit\LoginManagement\App\Auth;
use BadHabit\LoginManagement\App\View;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;

class MustLoginMiddleware implements Middleware
{

    private SessionService $sessionService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());

        $auth = new Auth();
        $sessionRepository = new SessionRepository($auth);
        $this->sessionService = new SessionService(sessionRepository: $sessionRepository, userRepository: $userRepository);
    }

    public function before(): void
    {
        try {
            $user = $this->sessionService->current();
        } catch (\Exception $e) {
            View::redirect('/users/login');
        }
    }


}