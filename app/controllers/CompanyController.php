<?php


namespace App\controllers;


use Framework\Controller;

class CompanyController extends Controller
{

    protected function getModel(): string
    {
        return 'company_model';
    }
}