<?php

/**
 * Created by PhpStorm.
 * User: vitmazin
 * Date: 30.11.16
 * Time: 12:35
 */
class UserModel extends Model
{
    public function zaregistruj($reg) {
        $prava = 3;
        if($this->validace($reg)) {
            $q = $this->db->prepare("INSERT INTO `uzivatele` (login, pass, jmeno, email, prava) 
                                     VALUES (:login, :pass, :jmeno, :email, :prava)");
            $q->bindParam(':login', htmlspecialchars(stripslashes($reg['login']), ENT_QUOTES, 'UTF-8'));
            $q->bindParam(':pass',  sha1(htmlspecialchars(stripslashes($reg['pass1']),  ENT_QUOTES, 'UTF-8')));
            $q->bindParam(':jmeno', htmlspecialchars(stripslashes($reg['jmeno']), ENT_QUOTES, 'UTF-8'));
            $q->bindParam(':email', htmlspecialchars(stripslashes($reg['email']), ENT_QUOTES, 'UTF-8'));
            $q->bindParam(':prava', $prava);
            if($q->execute()) {
                return true;
            }

            else {
                return false;
            }
        }

        else {
            return false;
        }
    }

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

    private function validace($reg) {
        if(!empty($reg)) {
            if($reg['pass1'] == $reg['pass2'] AND !$this->jeVDB($reg)) {
                return true;
            }

            else {
                return false;
            }
        }

        else {
            return false;
        }
    }

    private function jeVDB($reg) {
        $q = $this->db->prepare("SELECT `id` FROM `uzivatele` WHERE `login` = :login");
        $q->bindParam(':login', $reg['login']);
        $q->execute();

        if($q->rowCount() == 1) {
            return true;
        }

        else {
            return false;
        }
    }

    private function selectUser($login, $password) {
        $q = $this->db->prepare("SELECT * FROM `uzivatele` WHERE `login` = :login AND `pass` = :pass");
        $q->bindParam(':login', htmlspecialchars(stripslashes($login), ENT_QUOTES, 'UTF-8'));
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