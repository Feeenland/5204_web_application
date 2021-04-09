<?php

use Controllers\LoginController;
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
    }else if($_GET['p'] == 'home'){
        $page = 'views/home.php';
        $pageTitle = 'Home';
    }else if($_GET['p'] == 'admin'){
        $page ='views/admin.php';
        $pageTitle = 'Magic user';
    }
}else{

    $view = new HomeView();
    $view->showTemplate();
}

$card = new CardsModel();
$card->getCardByName('clariomultimatum');
print $card->toString();



