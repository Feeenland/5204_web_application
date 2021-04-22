<?php

use Controllers\CardsController;
use Controllers\CardSingleController;
use Controllers\DeckMaskController;
use Controllers\DecksController;
use Controllers\ForgotPwController;
use Controllers\HomeController;
use Controllers\LoginController;
use Controllers\RegisterController;
use Controllers\UserController;
use Views\DefaultView;
use Views\HomeView;

include('bootstrap.php');

/*
session_name('user');*/
session_start();

//print_r($_SESSION);

//error_reporting(E_WARNING); // don't show the warnings
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$pageElement= null;
$loggedInUser = '';

// from the get param, is p set and not empty ? load the page
if(isset($_GET['p']) && $_GET['p'] != ''){
    if($_GET['p'] == 'login'){
        $controller = new LoginController();
    }else if($_GET['p'] == 'register'){
        $controller = new RegisterController();
    }else if($_GET['p'] == 'forgotPw'){
        $controller = new ForgotPwController();
    }else if($_GET['p'] == 'home') {
        $view = new HomeController();
    }else if($_GET['p'] == 'user'){
        $view= new UserController();
    }else if($_GET['p'] == 'addDecks'){
        $view= new DeckMaskController();
    }else if($_GET['p'] == 'decks'){
        $view= new DecksController($_GET['method']);
    }else if($_GET['p'] == 'cards'){
        $view= new CardsController($_GET['method']);
    }else if($_GET['p'] == 'allCards'){
        $view= new CardsController($_GET['method']);
    }else if($_GET['p'] == 'cardSingle'){
        $view= new CardSingleController();
    }else{
        $view= new DefaultView();
        $view->showTemplate();
    }
}else{
    $view = new HomeView();
    $view->showTemplate();
}


/*
$c = new \Controllers\CardsController();
$c->getCardDetailByName();
$c->getCardDetailByName($name);?*/



