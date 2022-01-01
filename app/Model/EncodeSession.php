<?php

namespace BadHabit\LoginManagement\Model;

use BadHabit\LoginManagement\Domain\Decoded;

class EncodeSession
{
    public string $key;
    public string $issued;
    public string $expires;
    public Decoded $data;
}