<?php


namespace App\controllers;


use Framework\Auth\JWTWrapper;
use Framework\Exceptions\HttpException;
use Framework\ResponseApi;

class AuthController
{
    public function login($container, $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $container['user_model']->getByEmail($email);
        if (!$user) {
            throw new HttpException("Forbidden", 401);
        }

        if (!password_verify($password, $user['password'])) {
            throw new HttpException("Forbidden", 401);
        }

        unset($user['password']);

        $data = [
            'expiration_sec' => 3600,
            'iss' => $user['email'],
            'userdata' => $user
        ];

        $token = JWTWrapper::encode($data);

        return ResponseApi::jsonResponse(false, 'token created',['token' => $token]);
    }

    public function register($container, $request)
    {
        $user = $container['user_model']->create($request->request->all());

        if (!$user) return ResponseApi::jsonResponse(true, 'data invalid', [], 400 );

        return ResponseApi::jsonResponse(false, 'user created', ['user' => $user]);
    }

    public function getUser($container)
    {
        $headers = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            $token = filter_input(\INPUT_GET, 'token');
        }

        if (!$token) {
            throw new HttpException("Forbidden", 401);
        }

        try {
            $data = JWTWrapper::decode($token);
        } catch(\Exception $e) {
            throw new HttpException("Forbidden", 401);
        }

        return (array)$data;
    }
}