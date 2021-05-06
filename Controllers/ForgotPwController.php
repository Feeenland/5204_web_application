<?php
/**
 * ForgotPwController.php handles the function to change the password.
 */

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

    /** check the user */
    public function checkUser($name, $card, $nick, $pwd)
    {
        $u = new UserModel();
        $nickexist = $u->getUsersByNickname($nick);

        $valid = new Validation();
        $errors = $valid->validateFields($this->rules);

        if ($nickexist == false || count($errors) != 0) { //errors nickname not true
            if ($nickexist == false){
                $generalerr ='This nickname does not exist!';
            }
            $info ='Please enter Your correct user data!';
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

                if ($u->getFieldValue('favourite_card') == $card){ //card true , create user
                    $values['id'] = $u->getFieldValue('id');
                    $values['name'] = $u->getFieldValue('name');
                    $values['password'] = password_hash($pwd, PASSWORD_DEFAULT);
                    $u->updateSave($values);
                    echo json_encode(array(
                        'status' => true
                    ));
                    return true;

                }else{ // card not true
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

    /** show errors */
    public function showErrorsValues($info, $generalerr, $errors = [], $values = [])
    {
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
