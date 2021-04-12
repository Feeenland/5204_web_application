<?php


namespace Controllers;


use Views\RegisterView;

class RegisterController
{
    protected $view;

    public function __construct()
    {
        $view = $this->view = new RegisterView();
        $view->showTemplate();
    }

}