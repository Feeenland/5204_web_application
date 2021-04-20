<?php


namespace Controllers;


use Views\HomeView;

class HomeController extends UserController
{
    public $view;

    public function __construct()
    {
        $user = $this->getUserByNicknameSession();

        $view = $this->view = new HomeView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $view->showTemplate();
    }

}
