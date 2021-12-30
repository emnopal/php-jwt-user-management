<?php

namespace BadHabit\LoginManagement\Controller;

use BadHabit\LoginManagement\App\View;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;

class HomeController
{


    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function index()
    {
        $user = $this->sessionService->current();
        if (!$user) {
            View::render('home/index', [
                'title' => 'PHP Login Management',
            ]);
        } else {
            View::render('home/dashboard', [
                'title' => "Welcome to Dashboard",
                "user" => [
                    "fullName" => $user->fullName,
                ]
            ]);
        }


    }

}