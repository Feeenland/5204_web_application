<?php


namespace Controllers;

use Models\UserModel;
use Views\CardsView;
use Views\DeckMaskView;
use Views\DecksView;
use Views\UserView;

class UserController
{
    public $view;
    protected $userId;
    protected $userNick;
    protected $userName;
    protected $userCard;



    public function getUserByNicknameSession()
    {

        $userNick = $_SESSION['userNick'];
        $usr = new UserModel();
        $user =$usr->getUsersByNickname($userNick);

        $this->userNick = $usr->getFieldValue('nickname');
        $this->userName = $usr->getFieldValue('name');
        $this->userId = $usr->getFieldValue('id');
        $this->userCard = $usr->getFieldValue('favourite_card');

        return $usr;
    }

}
