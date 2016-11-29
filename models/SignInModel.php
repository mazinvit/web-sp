<?php

/**
 * Created by PhpStorm.
 * User: vitmazin
 * Date: 29.11.16
 * Time: 13:00
 */

require_once ROOT."models/Model.php";

class SignInModel extends Model
{
    public function autorizuj($login, $password) {
        $usr = $this->selectUser($login, $password);
        $data = $usr[0];

        if($usr != null)
        {
            return $data;
        }

        else
        {
            return null;
        }
    }

    private function selectUser($login, $password) {
        $q = $this->db->prepare("SELECT * FROM `uzivatele` WHERE `login` = :login AND `pass` = :pass");
        $q->bindParam(':login', $login);
        $q->bindParam(':pass', sha1($password));
        $q->execute();

        if($q->rowCount() != 0) {
            return $q->fetchAll();
        }

        else {
            return null;
        }
    }
}