<?php
    define('ROOT',dirname(__FILE__). '/../');

    require ROOT."Controller.php";

    $controller = new Controller();
    
    $page = @$_REQUEST['page'];
    $param = @$_REQUEST['param'];

    if(empty($page)) {
        $controller->index();
    }

    else {
        if (method_exists($controller, $page)) {
            if (empty($param)) {
                $controller->{$page}();
            } else {
                call_user_func_array([$controller, $page], $param);
            }
        }
        else {
            $controller->error404();
        }
    }