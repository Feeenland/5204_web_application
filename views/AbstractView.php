<?php


abstract class AbstractView
{
    protected $template;
    protected $contents;

    /* put something in the template */
    public function assignValue($name, $value)
    {
        $this->contents[$name] = $value;
    }

    // this generates the output
    public function render()
    {
       extract($this->contents);

    }
}