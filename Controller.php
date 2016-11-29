<?php

/**
 * Created by PhpStorm.
 * User: vitmazin
 * Date: 29.11.16
 * Time: 10:07
 */

require_once ROOT.'twig-master/lib/Twig/Autoloader.php';
require_once ROOT.'models/SignInModel.php';
require_once ROOT.'models/RegisterModel.php';
require_once ROOT.'config.php';

class Controller
{
    private $twig;
    private $modelSignIn;
    private $modelRegister;
    public function __construct()
    {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(ROOT.'views');
        $this->twig = new Twig_Environment($loader);
        $this->twig->addFunction(new Twig_SimpleFunction("makeURL", array($this, "makeURL")));
        $this->twig->addGlobal("session", $_SESSION);
    }

    public function makeURL($stranka, $parametr = null) {
        if(!isset($parametr)) {
            return "index.php?page=".$stranka;
        }

        else {
            return "index.php?page=" . $stranka . "?param=" . $parametr;
        }
    }

    public function index() {
        echo $this->twig->render('uvod.twig');
    }

    public function wrongsign() {
        echo $this->twig->render('wrongsign.twig');
    }

    public function error404() {
        echo $this->twig->render('404.twig');
    }

    public function okregister() {
        echo $this->twig->render('okregister.twig');
    }

    public function errorregister() {
        echo $this->twig->render('errorregister.twig');
    }

    public function signin() {
        $this->modelSignIn = new SignInModel();

        if(isset($_POST['uzivatel'])) {
            $uzivatel = $_POST['uzivatel'];
            $login = stripslashes($uzivatel['login']);
            $pwd = stripslashes($uzivatel['heslo']);

            $retUzivatel = $this->modelSignIn->autorizuj($login, $pwd);

            if($retUzivatel != null) {
                $_SESSION['uzivatel'] = $retUzivatel;
                $this->redirection();
            }

            else {
                $this->redirection("wrongsign");
            }
        }

        else {
            $this->redirection();
        }
    }

    public function register_page() {
        echo $this->twig->render('register_page.twig');
    }

    public function register() {
        if(isset($_POST['reg'])) {
            $reg = $_POST['reg'];
            $this->modelRegister = new RegisterModel();
            if($this->modelRegister->zaregistruj($reg)) {
                $this->redirection("okregister");
            }

            else {
                $this->redirection("errorrregister");
            }
        }

        else {
            $this->redirection("errorrregister");
        }
    }

    public function signout() {
        $_SESSION['uzivatel'] = array();
        unset($_SESSION['uzivatel']);
        $this->redirection();
    }

    private function redirection($page = "index") {
        header("location:".$this->makeURL($page));
    }
}