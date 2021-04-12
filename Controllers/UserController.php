<?php


namespace Controllers;

use Views\UserView;

class UserController
{
    protected $view;

    public function __construct()
    {
        $view = $this->view = new UserView();
        $view->showTemplate();
    }

}