<?php

require_once ROOT.'twig-master/lib/Twig/Autoloader.php';

/**
 * Created by PhpStorm.
 * User: vitmazin
 * Date: 29.11.16
 * Time: 10:07
 */
class Controller
{
    private $twig;
    public function __construct()
    {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(ROOT.'templates');
        $this->twig = new Twig_Environment($loader);
        $this->twig->addFunction(new Twig_SimpleFunction("makeURL", array($this, "makeURL")));
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
        if(isset($_POST['uzivatel'])) {
            $uzivatel = $_POST['uzivatel'];
            $login = $uzivatel['login'];
            $pwd = $uzivatel['heslo'];

            echo $login . PHP_EOL;
            echo $pwd . PHP_EOL;
        }

        else {
            header("location:".$this->makeURL("index"));
        }
    }
}