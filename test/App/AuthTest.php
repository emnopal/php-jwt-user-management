<?php

namespace BadHabit\LoginManagement\App;

use BadHabit\LoginManagement\App\Auth;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Service\SessionService;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{

    private Auth $auth;

    protected function setUp(): void
    {
        $this->auth = new Auth();
    }

    public function testEncode()
    {
        self::assertIsArray($this->auth->encode("https://example.com", ["data" => "test"]));
    }

    public function testDecode()
    {
        $encode = $this->auth->encode("https://example.com", ["data" => "test"]);
        self::assertIsArray($this->auth->decode($encode['key']));
        self::assertEquals(["data" => "test"], $this->auth->decode($encode['key']));
    }


}
