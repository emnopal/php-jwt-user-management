<?php

namespace BadHabit\LoginManagement\Service;

use BadHabit\LoginManagement\Domain\UserSession;
use BadHabit\LoginManagement\Domain\Decode;
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

    public function create(UserSession $userSession): bool
    {
        try {

            $token = $this->sessionRepository->getToken($userSession);

            // Use cookie to store session id
            // path "/" means the cookie is available for all pages
            setcookie(self::$COOKIE_NAME, $token->key, $token->expireAt, "/");

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

                $decode = new Decode();
                $decode->token = $jwt;
                $token_result = $this->sessionRepository->decodeToken($decode);
                $result = $token_result->payload->data;

                return $this->userRepository->findById($result->user_id);
            } catch (\Exception) {
                throw new \Exception("User is not login");
            }
        } else {
            throw new \Exception("User is not login");
        }
    }

    public function currentAdmin(): ?User
    {
        if (!isset($_COOKIE[self::$COOKIE_NAME])) {
            throw new \Exception("No session found");
        }

        if ($_COOKIE[self::$COOKIE_NAME]) {
            $jwt = $_COOKIE[self::$COOKIE_NAME];

            try {

                $decode = new Decode();
                $decode->token = $jwt;
                $token_result = $this->sessionRepository->decodeToken($decode);
                $result = $token_result->payload->data;

                if ($result->role == 'admin') {
                    return $this->userRepository->findById($result->user_id);
                } else {
                    throw new \Exception("User is not admin");
                }
            } catch (\Exception) {
                throw new \Exception("User is not login");
            }
        } else {
            throw new \Exception("User is not login");
        }
    }

}