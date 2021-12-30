<?php

namespace BadHabit\LoginManagement\Controller;

use BadHabit\LoginManagement\App\View;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Exception\ValidationException;
use BadHabit\LoginManagement\Model\UserLoginRequest;
use BadHabit\LoginManagement\Model\UserPasswordRequest;
use BadHabit\LoginManagement\Model\UserProfileUpdateRequest;
use BadHabit\LoginManagement\Model\UserRegisterRequest;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;
use BadHabit\LoginManagement\Service\SessionService;
use BadHabit\LoginManagement\Service\UserService;

class UserController
{

    private UserService $userService;
    private SessionService $sessionService;


    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $userService = new UserService($userRepository);
        $this->userService = $userService;

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

    }

    public function register()
    {
        View::render('User/register', [
            'title' => 'Register'
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];
        $request->fullName = $_POST['fullName'];
        $request->email = $_POST['email'];

        try {
            $this->userService->register($request);
            View::render('User/login', [
                'success' => 'User registered successfully',
                'name' => $_POST['username']
            ]);
        } catch (ValidationException | \Exception $e) {
            // Handling error
            View::render('User/register', [
                'title' => 'Register',
                'error' => $e->getMessage()
            ]);
        }

    }

    public function login()
    {
        View::render('User/login', [
            'title' => 'Login'
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->username); // automate create cookie
            View::redirect('/');
        } catch (ValidationException | \Exception $e) {
            // Handling error
            View::render('User/login', [
                'title' => 'Login',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/');
    }

    public function updateProfile()
    {
        View::render('User/profile', [
            'title' => 'Update Profile',
            'user' => [
                "username" => $this->sessionService->current()->username,
                "fullName" => $this->sessionService->current()->fullName,
                "email" => $this->sessionService->current()->email
            ]
        ]);
    }

    public function postUpdateProfile()
    {
        $request = new UserProfileUpdateRequest();
        $request->username = $this->sessionService->current()->username;
        $request->fullName = $_POST['fullName'];
        $request->email = $_POST['email'];

        try{
            $this->userService->updateProfile($request);
            View::redirect('/');
        }catch (ValidationException | \Exception $e){
            // Handling error
            View::render('User/profile', [
                'title' => 'Update Profile',
                'error' => $e->getMessage(),
                'user' => [
                    "username" => $this->sessionService->current()->username,
                    "fullName" => $_POST['fullName'],
                    "email" => $_POST['email']
                ]
            ]);
        }

    }

    public function updatePassword()
    {
        View::render('User/password', [
            'title' => 'Update User Password',
            'user' => [
                "username" => $this->sessionService->current()->username
            ]
        ]);
    }

    public function postUpdatePassword()
    {
        $request = new UserPasswordRequest();
        $request->username = $this->sessionService->current()->username;
        $request->old = $_POST['old'];
        $request->new = $_POST['new'];

        try{
            $this->userService->updatePassword($request);
            View::redirect('/');
        }catch (ValidationException | \Exception $e){
            // Handling error
            View::render('User/password', [
                'title' => 'Update Password',
                'error' => $e->getMessage(),
                'user' => [
                    "username" => $this->sessionService->current()->username,
                ]
            ]);
        }
    }

}