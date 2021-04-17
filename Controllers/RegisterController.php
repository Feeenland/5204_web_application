<?php


namespace Controllers;

use Helpers\Disinfect;
use Helpers\Validation;
use Models\UserModel;
use Views\LoginView;
use Views\RegisterView;

class RegisterController
{
    protected $view;
    protected $infos;
    protected $errorMessages;

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
        if (isset($_POST['register'])) {
            $name = $_REQUEST['name'];
            $card = $_REQUEST['favourite_card'];
            $nick = $_REQUEST['nickname'];
            $pwd = $_REQUEST['password'];

            $d = new Disinfect();
            $name = $d->disinfect($name);
            $card = $d->disinfect($card);
            $nick = $d->disinfect($nick);
            $pwd = $d->disinfect($pwd);

            $this->createNewUser($name, $card, $nick, $pwd);
        } else {
            $view = $this->view = new RegisterView();
            $view->showTemplate();
        }
    }

    public function createNewUser($name, $card, $nick, $pwd)
    {

       // $u = new UserModel();
        //$nickexist = $u->getUsersByNickname($nick);

        $valid = new Validation();
        $errors = $valid->validateFields($this->rules);

        if (count($errors) != 0) { //nickname not free
            print 'errors';
            $view = $this->view = new RegisterView();
            //$this->errorMessages = 'This Nickname is not free!';
            //$this->view->addErrorMessagesMany('nickname',$this->errorMessages);

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

    }


    public function showErrorsValues($errors = [], $values = [])
    {
        $view = $this->view = new RegisterView();
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


