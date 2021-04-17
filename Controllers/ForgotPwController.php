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

    protected $fields = [
        'name',
        'nickname',
        'favourite_card',
        'password',
    ];

    public $rules = [
        'name' => ['required', 'max15chars'],
        'nickname' => ['required','freeNickname', 'max15chars'],
        'favourite_card' => ['required', 'max35chars'],
        'password' => ['required', 'min5chars']
    ];

    public function __construct()
    {
        if(isset($_POST['forgotPw']) )
        {
            $name = $_REQUEST['name'];
            $card = $_REQUEST['favourite_card'];
            $nick = $_REQUEST['nickname'];
            $pwd = $_REQUEST['password'];

            $this->checkUser($name, $card, $nick, $pwd);
        }else{
            $view = $this->view = new ForgotPwView();
            $view->showTemplate();
        }
    }

    public function checkUser($name, $card, $nick, $pwd){

        //$this->validateUserData($name, $card, $nick, $pwd);

        $u = new UserModel();
        $nickexist = $u->getUsersByNickname($nick);

        $valid = new Validation();
        $errors = $valid->validateFields($this->rules);

        //nickname abfrage überflüssig?!
        if ($nickexist == false || count($errors) != 0) { //errors !nickname
            print 'nickname not found';
            $view = $this->view = new LoginView();
            $nickerror = $this->errorMessages = 'Nickname not found';
            $this->view->addErrorMessagesMany('nickname',$this->errorMessages);

            // errors in fields! Show Field
            return $this->showErrorsValues(
                $errors,
                [
                    'name' => $_REQUEST['name'],
                    'nickname' => $_REQUEST['nickname'],
                    'favourite_card' => $_REQUEST['favourite_card'],
                    'password' => $_REQUEST['password']
                ]);
        }else{
            print 'no errors';
            $u = new UserModel();

            print 'create usr';
            $values['name'] = $name;
            $values['nickname'] = $nick;
            $values['favourite_card'] = $card;
            $values['password'] = $pwd; // TODO hash PW
            $u->updateSave($values);
            $view = $this->view = new LoginView();
            $this->infos = 'you are registered, log in :)';
            $this->view->addInfos($this->infos);
            $view->showTemplate();
        }




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



    public function showErrorsValues($errors = [], $values = [])
    {
        $view = $this->view = new LoginView();
        //print_r($errors);
        //print_r($values);


        foreach ($this->fields as $field){
            //print $field . '<br>';
            if (isset($errors[$field])){
                $this->view->addErrorMessagesMany($field, $errors[$field][0]);
            }
            if (isset($values[$field])){
                $this->view->addValuesMany($field, $values[$field]);
                //print $values[$field];
            }
        }

        $view->showTemplate();

        return [
            'errors' => $errors,
            'values' => $values
        ];
    }

}
