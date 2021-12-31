<?php

namespace BadHabit\LoginManagement\App;

use BadHabit\LoginManagement\App\Handler;
use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Service\SessionService;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{

    private Handler $auth;

    protected function setUp(): void
    {
        $this->auth = new Handler();
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
