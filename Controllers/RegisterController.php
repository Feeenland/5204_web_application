<?php


namespace Controllers;


use Helpers\Validation;
use Models\UserModel;
use Views\RegisterView;

class RegisterController
{
    protected $view;
    protected $infos;
    protected $errorMessages;
    protected $register = false;

    public function __construct()
    {
        if(isset($_POST['register']) )
        {
            $name = $_REQUEST['name'];
            $card = $_REQUEST['card'];
            $nick = $_REQUEST['nickname'];
            $pwd = $_REQUEST['password'];

           $this->createNewUser($name, $card, $nick, $pwd);
        }else{
            $view = $this->view = new RegisterView();
            $view->showTemplate();
        }
    }

    public function createNewUser($name, $card, $nick, $pwd)
    {
       $this->validateNewUsers($name, $card, $nick, $pwd);

       if ($this->register == true){
            print 'new user?';
            $u = new UserModel();
            $nickexist =$u->getUsersByNickname($nick);
           if ($nickexist == false) {
               print 'create usr';
               $values['name'] = $name;
               $values['nickname'] = $nick;
               $values['favourite_card'] = $card;
               $values['password'] = $pwd; // TODO hash PW
               $u->updateSave($values);
               // TODO prepared statement error?!
           }else{
               print 'nickname is not free';
               $view = $this->view = new RegisterView();
               $this->infos = 'I am sorry this nickname is already taken';
               $this->errorMessages = 'This Nickname is not free!';
               $this->view->addInfos($this->infos);
               $this->view->addErrorMessages($this->errorMessages);
               $view->showTemplate();
           }
       }
    }


    public function validateNewUsers($name, $card, $nick, $pwd)
    {
        $view ='';
        $infos ='';
       $errorMessages ='';

        $valid = new Validation();
        if ($valid->isValid($name)){
            if ($valid->isValid($card)){
                if ($valid->isValid($nick)){
                    if ($valid->isValid($pwd)){
                        $this->register = true;
                    }else{
                        $this->isNotValid();
                    }
                }else{
                    $this->isNotValid();
                }
            }else{
                $this->isNotValid();
            }
        }else{
            $this->isNotValid();
        }
        $view = $this->view = new RegisterView();
        $this->view->addInfos($this->infos);
        $this->view->addErrorMessages($this->errorMessages);
        $view->showTemplate();

    }

    public function isNotValid()
    {
        $this->infos = 'Please enter everywhere something between 1-30 letters';
        $this->errorMessages = 'Something was entered incorrectly!';
    }
}