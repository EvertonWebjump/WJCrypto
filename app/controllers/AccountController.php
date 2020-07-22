<?php


namespace App\controllers;


use Framework\Controller;

class AccountController extends Controller
{

    protected function getModel(): string
    {
        return 'account_model';
    }
}