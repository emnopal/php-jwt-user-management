<?php

namespace BadHabit\LoginManagement\Middleware;

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\App\View;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;

class MustAdminMiddleware implements Middleware
{

    private SessionService $sessionService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());

        $auth = new Handler();
        $sessionRepository = new SessionRepository($auth);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function before(): void
    {
        try {
            $user = $this->sessionService->currentAdmin();
        } catch (\Exception $e) {
            View::render("not_found", [
                'url' => "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
            ]);
            exit;
        }
    }


}