<?php

require_once ROOT.'twig-master/lib/Twig/Autoloader.php';
require_once ROOT.'models/SignInModel.php';

/**
 * Created by PhpStorm.
 * User: vitmazin
 * Date: 29.11.16
 * Time: 10:07
 */
class Controller
{
    private $twig;
    private $model;
    public function __construct()
    {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(ROOT.'templates');
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
        echo $this->twig->render('template.twig');
    }

    public function error404() {
        echo "404";
    }

    public function signin() {
        $this->model = new SignInModel();
        if(isset($_POST['uzivatel'])) {
            $uzivatel = $_POST['uzivatel'];
            $login = $uzivatel['login'];
            $pwd = $uzivatel['heslo'];

            $retUzivatel = $this->model->autorizuj($login, $pwd);

            if($retUzivatel != null) {
                $_SESSION['uzivatel'] = $retUzivatel;
                header("location:".$this->makeURL("index"));
            }

            else {
                //TODO presmerovat spatne jmeno nebo heslo
            }
        }

        else {
            header("location:".$this->makeURL("index"));
        }
    }

    public function signout() {
        $_SESSION['uzivatel'] = array();
        unset($_SESSION['uzivatel']);
        header("location:".$this->makeURL("index"));
    }
}