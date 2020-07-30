<?php


namespace App\controllers;


use Framework\Controller;

class UserController extends Controller
{

    protected function getModel(): string
    {
        return 'user_model';
    }
}