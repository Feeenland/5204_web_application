<?php
/**
 * UserController.php  takes the user out of the session to display him on the page.
 */

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
        if (isset($_SESSION['userNick'])){
            $userNick = $_SESSION['userNick'];
            $usr = new UserModel();
            $user =$usr->getUsersByNickname($userNick);

            $this->userNick = $usr->getFieldValue('nickname');
            $this->userName = $usr->getFieldValue('name');
            $this->userId = $usr->getFieldValue('id');
            $this->userCard = $usr->getFieldValue('favourite_card');

            $this->userNick = html_entity_decode($this->userNick);
            $this->userName = html_entity_decode($this->userName);
            $this->userCard = html_entity_decode($this->userCard);
            return $usr;
        }
        return false;
    }
}
