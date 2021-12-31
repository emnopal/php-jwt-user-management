<?php

namespace BadHabit\LoginManagement\Controller;

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\App\View;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;

class AdminController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());

        $auth = new Handler();
        $sessionRepository = new SessionRepository($auth);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function index():void
    {
        try{
            $user = $this->sessionService->currentAdmin();
            View::render("Admin/admin", []);
        } catch (\Exception $e) {
            if (!isset($_SERVER["HTTP_HOST"]) && !isset($_SERVER["REQUEST_URI"])){
                $url = "http://localhost/admin";
            } else {
                $url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            }
            View::render("not_found", [
                'url' => $url
            ]);
        }


    }
}