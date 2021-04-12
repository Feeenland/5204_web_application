<?php


namespace Controllers;


use Views\ForgotPwView;

class ForgotPwController
{
    protected $view;

    public function __construct()
    {
        $view = $this->view = new ForgotPwView();
        $view->showTemplate();
    }

}