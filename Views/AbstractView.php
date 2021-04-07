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

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($this->loader);
    }

    public function showTemplate(){
        $tmpl = $this->twig->load($this->tmpl);
        echo $tmpl->render($this->data);
    }

}