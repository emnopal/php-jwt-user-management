<?php

namespace BadHabit\LoginManagement\App;

require_once __DIR__ . "/../../config/DotEnv.php";

use DotEnv;
use Firebase\JWT\JWT;

class Auth
{
    /**
     * Handling all the JWT actions
     * like encoding and decoding tokens
     */

    protected string $jwt_secret;
    protected array $token;
    protected int|float $issuedAt;
    protected int|float $expireAt;
    protected string $jwt;

    public function __construct(int|float $validity_time = (60 * 60 * 24))
    {

        // set default timezone
        date_default_timezone_set("Asia/Jakarta");
        $this->issuedAt = time();

        // token validity default for 1 day
        $this->expireAt = $this->issuedAt + $validity_time;

        /*
         * make sure your jwt_secret
         * is secure and make sure people
         * hard to guess or brute force
         * do not use key 'secret' in production
         * use a random string or hash of your jwt_secret
         * */

        // initialize the secret key on the dotenv file
        $dotenv = new DotEnv(__DIR__ . "/../../.env");
        $dotenv->load();

        // Set signature
        $this->jwt_secret = getenv('JWT_SECRET');

    }

    public function encode(string $iss, array $data): array|string
    {

        /*
         * CAUTION:
         * Never store any credential or
         * sensitive information in the JWT
         * because it can be decoded by anyone
         * */

        $this->token = [
            // identifier to the token (who issued the token)
            'iss' => $iss,
            'aud' => $iss,

            // current timestamp to the token (when the token was issued)
            'iat' => $this->issuedAt,

            // token expiration time
            'exp' => $this->expireAt,

            // payload
            'data' => $data
        ];

        try {
            $this->jwt = JWT::encode($this->token, $this->jwt_secret);

            return [
                'issued' => $this->issuedAt,
                'expire' => $this->expireAt,
                'key' => $this->jwt
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function decode(string $jwt_token): array|string
    {
        try {
            $decode = JWT::decode($jwt_token, $this->jwt_secret, ['HS256']);
            return (array)$decode->data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}