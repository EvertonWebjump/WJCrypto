<?php


namespace App\controllers;


use Framework\Controller;

class AddressController extends Controller
{

    protected function getModel(): string
    {
        return 'address_model';
    }
}