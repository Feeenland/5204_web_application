<?php


namespace Controllers;


use Helpers\Validation;
use Models\UserModel;
use Views\ForgotPwView;
use Views\LoginView;

class ForgotPwController
{
    protected $view;
    protected $infos;
    protected $errorMessages;
    protected $user = false;

    public function __construct()
    {
        if(isset($_POST['forgotPw']) )
        {
            $name = $_REQUEST['name'];
            $card = $_REQUEST['card'];
            $nick = $_REQUEST['nickname'];
            $pwd = $_REQUEST['password'];

            $this->checkUser($name, $card, $nick, $pwd);
        }else{
            $view = $this->view = new ForgotPwView();
            $view->showTemplate();
        }
    }

    public function checkUser($name, $card, $nick, $pwd){

        $this->validateUserData($name, $card, $nick, $pwd);


        if ($this->user == true){
            print 'korrekt data';
            $u = new UserModel();
            $nickexist =$u->getUsersByNickname($nick);
            if ($nickexist == false) {
                print 'wrong user data';
                $this->infos = 'Sorry this is not a existing user';
                $this->errorMessages = 'Please enter your user data';
                $view = $this->view = new ForgotPwView();
                $this->view->addInfos($this->infos);
                $this->view->addErrorMessages($this->errorMessages);
                $view->showTemplate();
            }else{
                print_r($nickexist);
                print $nickexist; //  = 1
                if ($name == $u->getFieldValue('name')){
                    print 'name the same';
                    if ($card == $u->getFieldValue('favourite_card')){
                        print 'card the same';
                        print 'add new Pw';

                        $values['password'] = $pwd; //TODO hash password
                        //$u->updateSave($values); //TODO bind param is not working!
                        $view = $this->view = new LoginView();
                        $this->infos = 'Your new Password is saved, log in now';
                        $this->view->addInfos($this->infos);
                        $view->showTemplate();

                    }else{
                        $this->isNotValid();
                    }
                }else{
                    $this->isNotValid();
                }

            }

        }

    }

    public function validateUserData($name, $card, $nick, $pwd){
        $valid = new Validation();
        if ($valid->isValid($name)){
            if ($valid->isValid($card)){
                if ($valid->isValid($nick)){
                    if ($valid->isValid($pwd)){
                        $this->user = true;
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
    }

    public function isNotValid()
    {
        $this->infos = 'no match, Please enter your user data to change the password';
        $this->errorMessages = 'Something was entered incorrectly!';
        $view = $this->view = new ForgotPwView();
        $this->view->addInfos($this->infos);
        $this->view->addErrorMessages($this->errorMessages);
        $view->showTemplate();
    }

}