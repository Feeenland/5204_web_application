<?php

use Controllers\ForgotPwController;
use Controllers\HomeController;
use Controllers\LoginController;
use Controllers\RegisterController;
use Models\CardsModel;
use Views\HomeView;

include('bootstrap.php');

session_start();
//error_reporting(E_WARNING); // don't show the warnings
$pageElement= null;
$p ='home';

// from the get param, is p set and not empty ? load the page
if(isset($_GET['p']) && $_GET['p'] != ''){
    $p =$_GET['p'] ;
    if($_GET['p'] == 'login'){
        $controller = new LoginController();
    }else if($_GET['p'] == 'register'){
        $controller = new RegisterController();
    }else if($_GET['p'] == 'forgotPw'){
        $controller = new ForgotPwController();
    }else if($_GET['p'] == 'home'){
        $view= new HomeController();
        $pageTitle = 'Home';
    }else if($_GET['p'] == 'user'){
        $view= new HomeController();
        $page ='views/admin.php';
    }
}else{

    $view = new HomeView();
    $view->showTemplate();
}

/*$card = new CardsModel();
$card->getCardByName('clariomultimatum');
print $card->getFieldValue('name');
print $card->toString();*/

$c = new \Controllers\CardController();
$c->getCardDetailByName();



