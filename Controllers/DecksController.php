<?php


namespace Controllers;


use Models\DecksModel;
use Models\UserModel;
use Views\DecksView;

class DecksController extends UserController
{
    public $view;


    public function __construct()
    {
        $user = $this->getUserByNicknameSession();

        $this->view = new DecksView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->showTemplate();

    }


}
