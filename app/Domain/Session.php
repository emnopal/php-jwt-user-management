<?php

namespace BadHabit\LoginManagement\Domain;

class Session
{
    public function __construct(public string $user_id)
    {
    }
}