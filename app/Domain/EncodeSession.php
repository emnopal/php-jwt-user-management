<?php

namespace BadHabit\LoginManagement\Domain;

class EncodeSession
{
    public string $key;
    public string $issued;
    public string $expires;
    public DecodeSession $data;
}