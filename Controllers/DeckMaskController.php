<?php


namespace Controllers;


use Models\DecksModel;
use Views\DeckMaskView;

class DeckMaskController extends UserController
{
    public $view;
    protected $infos;

    public function __construct()
    {
        $user = $this->getUserByNicknameSession();

        $this->view = new DeckMaskView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->showTemplate();

    }

}
