<?php


namespace Controllers;


use Views\HomeView;

class HomeController
{
    protected $view;

    public function __construct()
    {
        $view = $this->view = new HomeView();
        $view->showTemplate();
    }

}