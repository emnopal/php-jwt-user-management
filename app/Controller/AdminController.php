<?php

namespace BadHabit\LoginManagement\Controller;

use BadHabit\LoginManagement\App\View;

class AdminController
{
    function index():void
    {
        View::render("Admin/admin", []);
    }
}