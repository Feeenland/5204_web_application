<?php
/**
 * DeckMaskController.php is responsible for creating a new deck.
 */

namespace Controllers;


use Helpers\disinfect;
use Helpers\Validation;
use Models\ColorModel;
use Models\DecksModel;
use Models\FormatsModel;
use Views\DeckMaskView;

class DeckMaskController extends UserController
{
    public $view;
    protected $fields = [
        'user_id',
        'format_id',
        'name',
        'description'
    ];
    public $rules = [
        'name' => ['required', 'max35chars'],
        'description' => ['required', 'min5chars'],
        'format' => ['required', 'number']
    ];

    public function __construct()
    {
        $user = $this->getUserByNicknameSession();

        if (isset($_POST['addDecks'])) {
            $deck_name = $_REQUEST['name'];
            $deck_description = $_REQUEST['description'];
            if (isset($_REQUEST['color'])){
                $colors = $_REQUEST['color'];
            }else{
                $colors = null;
            }
            $format = $_REQUEST['format'];
            $d = new Disinfect();
            $deck_name = $d->disinfect($deck_name);
            $deck_description = $d->disinfect($deck_description);
            $this->createNewDeck($deck_name, $deck_description, $colors, $format);
        } else {
            //print 'else';
            $this->showDeckMaskView();
        }
    }

    /** create a new deck */
    public  function createNewDeck($deck_name, $deck_description, $colors, $format)
    {
        $valid = new Validation();
        $errors = $valid->validateFields($this->rules);

        if (count($errors) != 0) {
            return $this->showErrorsValues(
                $errors,
                [
                    'name' => $_REQUEST['name'],
                    'description' => $_REQUEST['description'],
                    'format' => $_REQUEST['format']
                ]);
        } else{
            //print 'no errors';
            $d = new DecksModel();
            $values['name'] = $deck_name;
            $values['description'] = $deck_description;
            $values['format_id'] = $format;
            $values['user_id'] = $this->userId;
            $d->updateSave($values,$colors);

            echo json_encode(array(
                'status' => 'true'
            ));
            return true;
        }
    }

    /** show the errors */
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

    /** show the create deck template */
    public function showDeckMaskView()
    {
        $c = new ColorModel();
        $colors = $c->getAllColors();
        $f = new FormatsModel();
        $formats = $f->getAllFormats();
        $this->view = new DeckMaskView();
        $this->view->addToKey('colors', $colors);
        $this->view->addToKey('formats', $formats);
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->showTemplate();
    }
}
