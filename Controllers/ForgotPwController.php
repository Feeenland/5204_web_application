<?php


namespace Controllers;


use Helpers\disinfect;
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
        'nickname' => ['required', 'max15chars'],
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

            $d = new Disinfect();
            $name = $d->disinfect($name);
            $card = $d->disinfect($card);
            $nick = $d->disinfect($nick);
            $pwd = $d->disinfect($pwd);

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

        if ($nickexist == false || count($errors) != 0) { //errors !nickname
            //print 'nickname not found';

            if ($nickexist == false){
                $generalerr ='This nickname does not exist!';
            }
            $info ='Please enter Your correct user data!';

            // errors in fields! Show Field
            return $this->showErrorsValues(
                $info, $generalerr, $errors,
                [
                    'name' => $_REQUEST['name'],
                    'nickname' => $_REQUEST['nickname'],
                    'favourite_card' => $_REQUEST['favourite_card'],
                    'password' => $_REQUEST['password']
                ]);
        }else{ // nickname exist

            if ($u->getFieldValue('name') == $name){ // name true
                //print 'name true ';
                //print $u->getFieldValue('favourite_card');

                if ($u->getFieldValue('favourite_card') == $card){ //card true , create user
                    //print 'create usr ';
                    $values['id'] = $u->getFieldValue('id');
                    $values['name'] = $u->getFieldValue('name');
                    $values['password'] = password_hash($pwd, PASSWORD_DEFAULT);
                    $u->updateSave($values);
                    $view = $this->view = new LoginView();
                    $this->infos = 'you are registered, log in :)';
                    $this->view->addInfos($this->infos);
                    //$view->showTemplate();
                    echo json_encode(array(
                        'status' => true
                    ));
                    return true;

                }else{ // card not true
                    //print 'card not true ';
                    $generalerr ='something does not match!';
                    $info ='Please enter Your correct user data!';
                    return $this->showErrorsValues(
                        $info, $generalerr, $errors,
                        [
                            'name' => $_REQUEST['name'],
                            'nickname' => $_REQUEST['nickname'],
                            'favourite_card' => $_REQUEST['favourite_card'],
                            'password' => $_REQUEST['password']
                        ]);
                }

            }else{ // name is not correct
                //print 'name not true!';
               $generalerr ='something does not match!';
                $info ='Please enter Your correct user data!';
                return $this->showErrorsValues(
                    $info, $generalerr, $errors,
                    [
                        'name' => $_REQUEST['name'],
                        'nickname' => $_REQUEST['nickname'],
                        'favourite_card' => $_REQUEST['favourite_card'],
                        'password' => $_REQUEST['password']
                    ]);
            }
        }
    }



    public function showErrorsValues($info, $generalerr, $errors = [], $values = [])
    {
        $view = $this->view = new ForgotPwView();

        if (isset($info)){
            $this->view->addInfos($info);
        }
        if (isset($generalerr)){
            $this->view->addErrorMessagesMany('generalError', $generalerr);
        }

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

        //$view->showTemplate();
        echo json_encode(array(
            'status' => 'error',
            'errors' => $errors,
            'generalerr' => $generalerr,
            'info' => $info,
            'values' => $values
        ));

        return [
            'errors' => $errors,
            'values' => $values
        ];
    }

}
