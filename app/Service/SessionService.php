<?php

namespace BadHabit\LoginManagement\Service;

use BadHabit\LoginManagement\Config\Database;
use BadHabit\LoginManagement\Domain\Session;
use BadHabit\LoginManagement\Domain\User;
use BadHabit\LoginManagement\Repository\SessionRepository;
use BadHabit\LoginManagement\Repository\UserRepository;

class SessionService
{

    public static string $COOKIE_NAME = 'X-BHB-SESSION';

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }


    public function create(string $user_id): Session
    {
        Database::beginTransaction();
        try{

            $session = new Session();
            $session->id = uniqid();
            $session->user_id = $user_id;

            $this->sessionRepository->save($session);

            // Use cookie to store session id
            // path "/" means the cookie is available for all pages

            setcookie(self::$COOKIE_NAME, $session->id, time() + (60 * 60 * 24), "/");
            Database::commitTransaction();

            return $session;
        } catch (\Exception $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function destroy()
    {
        $session_id = $_COOKIE[self::$COOKIE_NAME] ?? "";
        $this->sessionRepository->deleteById($session_id);

        // Delete cookie
        // Set to first epoch in 1970
        // to delete all cookie(s)
        setcookie(self::$COOKIE_NAME, "", 1, "/");
    }

    public function current(): ?User
    {
        $session_id = $_COOKIE[self::$COOKIE_NAME] ?? "";
        $session = $this->sessionRepository->findById($session_id);

        if (!$session) {
            return null;
        }

        return $this->userRepository->findById($session->user_id);
    }

}