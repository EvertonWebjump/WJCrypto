<?php


namespace Framework\Auth;

use \Firebase\JWT\JWT;

class JWTWrapper
{
    const KEY = '78fad2A345V9';

    public static function encode(array $options)
    {
        $issuedAt = time();
        $expire = $issuedAt + $options['expiration_sec'];

        $tokenParam = [
            'iat'  => $issuedAt,
            'iss'  => $options['iss'],
            'exp'  => $expire,
            'nbf'  => $issuedAt - 1,
            'data' => $options['userdata'],
        ];

        return JWT::encode($tokenParam, JWTWrapper::KEY);
    }

    public static function decode($jwt)
    {
        return JWT::decode($jwt, JWTWrapper::KEY, ['HS256']);
    }
}