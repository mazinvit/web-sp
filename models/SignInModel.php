<?php

/**
 * Created by PhpStorm.
 * User: vitmazin
 * Date: 29.11.16
 * Time: 13:00
 */
class SignInModel
{
    public function autorizuj($login, $password) {
        //najit uzivatele v db, kdyz tam je vratit pole se jmenem a heslem
        //jinak vratit prazdne pole
        $log = "admin";
        $pwd = "heslo";
        if($login == $log AND $pwd == $password) {
            return ["login" => $log, "heslo" => $pwd];
        }

        else
        {
            return null;
        }
    }
}