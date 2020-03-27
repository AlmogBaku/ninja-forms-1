<?php

namespace NinjaForms\Achievements\Views;

class AdminNotice
{
    /** @var array */
    public $classes = [ 'notice' ];

    /** @var string */
    public $message = '';

    public function __construct( $message, $classes = [] )
    {
        $this->message = $message;
        $this->classes = array_merge( $classes, $this->classes );
    }

    public function render()
    {
        return "<div class='{$this->getClasses()}'><p>{$this->message}</p></div>";
    }

    public function getClasses()
    {
        return implode(' ', $this->classes);
    }
}