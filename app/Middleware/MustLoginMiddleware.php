<?php

namespace BadHabit\LoginManagement\Middleware;

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
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService(sessionRepository: $sessionRepository, userRepository: $userRepository);
    }

    public function before(): void
    {
        $user = $this->sessionService->current();

        if (!$user || !isset($user)) {
            View::redirect('/users/login');
        }
    }


}