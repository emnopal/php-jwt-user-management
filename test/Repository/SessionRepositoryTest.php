<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\Session;
use BadHabit\LoginManagement\Domain\User;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase
{

    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $userRepository->deleteAll();

        $user = new User();
        $user->username = "test";
        $user->fullName = "Test Account";
        $user->password = "test123";
        $user->email = "user@mail.com";
        $userRepository->save($user);
    }

    public function testSaveSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "test";

        $this->sessionRepository->save($session);
        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->user_id, $result->user_id);
    }

    public function testDeleteByIdSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "test";

        $this->sessionRepository->save($session);
        $this->sessionRepository->deleteById($session->id);
        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testFindByIdNotFound()
    {
        $result = $this->sessionRepository->findById("not_found");
        self::assertNull($result);
    }

}
