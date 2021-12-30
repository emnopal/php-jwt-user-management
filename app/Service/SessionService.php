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

    public function create(string $user_id): bool
    {
        try {

            $token = $this->sessionRepository->getToken($user_id);
            $expire = $this->sessionRepository->getExpire($user_id);

            // Use cookie to store session id
            // path "/" means the cookie is available for all pages
            setcookie(self::$COOKIE_NAME, $token, $expire, "/");

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy()
    {
        // Delete cookie
        // Set to first epoch in 1970
        // to delete all cookie(s)
        setcookie(self::$COOKIE_NAME, "", 1, "/");
    }

    public function current(): ?User
    {
        if (!isset($_COOKIE[self::$COOKIE_NAME])) {
            throw new \Exception("No session found");
        }
        if ($_COOKIE[self::$COOKIE_NAME]) {
            $jwt = $_COOKIE[self::$COOKIE_NAME];
            try {
                $payload = $this->sessionRepository->decodeToken($jwt);
                return $this->userRepository->findById($payload);
            } catch (\Exception) {
                throw new \Exception("User is not login");
            }
        } else {
            throw new \Exception("User is not login");
        }
    }

}