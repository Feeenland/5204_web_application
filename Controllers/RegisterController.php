<?php
/**
 * RegisterController.php checks the registration.
 */

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

    /** create new user */
    public function createNewUser($name, $card, $nick, $pwd)
    {
        $valid = new Validation();
        $errors = $valid->validateFields($this->rules);

        if (count($errors) != 0) { //nickname not free
                return $this->showErrorsValues(
                    $errors,
                    [
                        'name' => $_REQUEST['name'],
                        'nickname' => $_REQUEST['nickname'],
                        'favourite_card' => $_REQUEST['favourite_card'],
                        'password' => $_REQUEST['password']
                    ]);
        }else{ //no errors
            $u = new UserModel();
                //print 'create usr';
                $values['name'] = $name;
                $values['nickname'] = $nick;
                $values['favourite_card'] = $card;
                $values['password'] = password_hash($pwd, PASSWORD_DEFAULT);
                $u->updateSave($values);;
                echo json_encode(array(
                    'status' => true
                ));
                return true;
        }
    }

    /** show errors */
    public function showErrorsValues($errors = [], $values = [])
    {
        echo json_encode(array(
            'status' => 'error',
            'errors' => $errors,
            'values' => $values
        ));
        return [
            'errors' => $errors,
            'values' => $values
        ];
    }
}


