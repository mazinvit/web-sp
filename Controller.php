<?php

/**
 * Created by PhpStorm.
 * User: vitmazin
 * Date: 29.11.16
 * Time: 10:07
 */

require_once ROOT.'config.php';

class Controller
{
    private $twig;
    private $modelUser = null;

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
            return "index.php?page=" . $stranka . "&param=" . $parametr;
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

    public function login_page() {
        echo $this->twig->render('login_page.twig');
    }

    public function after_change($changes) {
        $template = $this->twig->loadTemplate('administration/after_change.twig');
        $params['changes'] = $changes;
        echo $template->render($params);
    }

    public function administration() {
        if($this->modelUser == null) {
            $this->modelUser = new UserModel();
        }

        if($this->modelUser->isAdmin()) {
            echo $this->twig->render('administration/administration.twig');
        }

        else {
            $this->redirection();
        }
    }

    public function set_rights() {
        if($this->modelUser == null) {
            $this->modelUser = new UserModel();
        }

        $id = $_POST['id'];
        $rights = $_POST['rights'];

        if($this->modelUser->setRigths($id, $rights)) {
            $this->after_change(1);
        }

        else {
            $this->after_change(0);
        }
    }

    public function delete_user() {
        if($this->modelUser == null) {
            $this->modelUser = new UserModel();
        }

        $id = $_POST['id'];

        $this->modelUser->deleteUser($id);

        $this->redirection("user_administration");
    }

    public function user_administration() {
        if($this->modelUser == null) {
            $this->modelUser = new UserModel();
        }

        $arr = $this->modelUser->selectAllUsers();

        if ($arr != null) {
            $template = $this->twig->loadTemplate('administration/user_administration.twig');
            $params['users'] = $arr;
            echo $template->render($params);
        }

        else {
            $this->redirection("administration");
        }
    }

    public function user_detail_administration() {
        if($this->modelUser == null) {
            $this->modelUser = new UserModel();
        }

        $id = $_POST['id'];

        $user = $this->modelUser->selectUserByID($id)[0];

        if($user != null) {
            $template = $this->twig->loadTemplate('administration/user_detail_administration.twig');
            $params['user'] = $user;
            echo $template->render($params);
        }

        else {
            $this->redirection("user_administration");
        }
    }

    public function signin() {

        if(isset($_POST['uzivatel'])) {

            if($this->modelUser == null) {
                $this->modelUser = new UserModel();
            }

            $uzivatel = $_POST['uzivatel'];
            $login = stripslashes($uzivatel['login']);
            $pwd = stripslashes($uzivatel['heslo']);

            $retUzivatel = $this->modelUser->authorization($login, $pwd);

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

            if($this->modelUser == null) {
                $this->modelUser = new UserModel();
            }

            if($this->modelUser->registrate($reg)) {
                $this->redirection("okregister");
            }

            else {
                $this->redirection("errorregister");
            }
        }

        else {
            $this->redirection("errorregister");
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