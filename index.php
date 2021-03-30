<?php
/**
 * The Index.php controls the whole website.
 * or to say so index.php is the whole website with different content each time.
 */

// database connection
include('database/DBConnection.php');
// helpers
include('helpers/validations.php');
include('helpers/disinfect.php');

// models
include('models/AbstractModel.php');
include('models/MUser.php');

// views
include('views/AbstractView.php');
include('views/LoginView.php');


session_start();
//error_reporting(E_WARNING); // don't show the warnings
$pageElement= null;
$p ='home';

$user = new MUser();
$user->getUsersByNickname('test');

if(isset($_GET['p']) && $_GET['p'] != ''){// from the get param, is p set and not empty ? load the page
    $p =$_GET['p'] ;
    if($_GET['p'] == 'login'){
        include('controllers/login/CLogin.php');
        $pageTitle = 'Login';
    }else if($_GET['p'] == 'home'){
        $page = 'views/home.php';
        $pageTitle = 'Home';
    }else if($_GET['p'] == 'admin'){
        $page ='views/admin.php';
        $pageTitle = 'Magic user';
    }
}else{
    $page = 'views/home.php';
}

require 'views/layout.php';
