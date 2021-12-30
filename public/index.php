<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BadHabit\LoginManagement\App\Router;
use BadHabit\LoginManagement\Controller\HomeController;
use BadHabit\LoginManagement\Controller\UserController;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Middleware\MustLoginMiddleware;
use BadHabit\LoginManagement\Middleware\MustNotLoginMiddleware;

Database::getConnection('production');

Router::add('GET', '/', HomeController::class, 'index');


Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'updatePassword', [MustLoginMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', [MustLoginMiddleware::class]);


Router::run();