<?php

/**
 * Created by PhpStorm.
 * User: n5ver
 * Date: 29.11.2016
 * Time: 20:41
 */

require_once ROOT."models/Model.php";

class RegisterModel extends Model
{
    public function zaregistruj($reg) {
        $prava = 3;
        if($this->validace($reg)) {
            $q = $this->db->prepare("INSERT INTO `uzivatele` (login, pass, jmeno, email, prava) 
                                     VALUES (:login, :pass, :jmeno, :email, :prava)");
            $q->bindParam(':login', stripslashes($reg['login']));
            $q->bindParam(':pass', sha1(stripslashes($reg['pass1'])));
            $q->bindParam(':jmeno', stripslashes($reg['jmeno']));
            $q->bindParam(':email', stripslashes($reg['email']));
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
}