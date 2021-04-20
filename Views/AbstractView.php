<?php
/**
 *this file loads twig,and is inherited from the other view classes
 */
namespace Views;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractView
{

    protected $loader;
    protected $twig;

    protected $tmpl = 'main.html.twig';
    protected $data= array();
    // TODO can i also add a string?

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($this->loader);


        if (isset($_SESSION)){
            if (isset($_SESSION['userId']))
            {
                $this->assignData('session', 'true');
            }
            /*if ($_SESSION['userID']){
                $this->assignData('session', 'true');
            }*/
        }

    }

    public function showTemplate(){
        $tmpl = $this->twig->load($this->tmpl);

        echo $tmpl->render($this->data);
    }

    public function assignData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function addToKey($key, $value)
    {
        $this->data[$key][] = $value;
    }

    public function addInfos($infos)
    {
        $this->data['infos'][] = $infos;
    }
    public function addCards($card)
    {
        $this->data['cards'][] = $card;
    }
    public function addErrorMessages($errorMessages)
    {
        $this->data['errorMessages'][] = $errorMessages;
    }
    public function addErrorMessagesMany($errorKey, $value)
    {
        $this->data['errorMessages'][] = $errorKey;
        $this->data['errorMessages'][$errorKey][] = $value;
    }
    public function addValuesMany($valueKey, $value)
    {
        $this->data['values'][] = $valueKey;
        $this->data['values'][$valueKey][] = $value;
    }
}
