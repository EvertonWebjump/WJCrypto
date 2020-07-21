<?php


namespace App\models;


use Framework\Model;

class User extends Model
{
    public function getByEmail($email)
    {
        return parent::get(['email' => $email]);
    }

    public function setPassword($password)
    {
        return password_hash($password, \PASSWORD_DEFAULT);
    }

    public function setDtbirth($date)
    {
        $time = strtotime($date);
        $newformat = date('Y-m-d',$time);

        return $newformat;
    }
}