<?php

namespace BadHabit\LoginManagement\Controller;

use BadHabit\LoginManagement\Auth\Handler;
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
        $auth = new Handler();
        $sessionRepository = new SessionRepository($auth);
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function index()
    {
        try{
            $user = $this->sessionService->current();
            View::render('home/dashboard', [
                'title' => "Welcome to Dashboard",
                "user" => [
                    "fullName" => $user->fullName,
                ]
            ]);
        } catch (\Exception $e){
            View::render('home/index', [
                'title' => 'PHP Login Management',
                'error' => $e->getMessage()
            ]);
        }
    }

}