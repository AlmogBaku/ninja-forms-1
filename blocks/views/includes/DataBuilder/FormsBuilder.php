<?php

namespace NinjaForms\Blocks\DataBuilder;

class FormsBuilder {

    protected $forms;

    public function __construct( $forms ) {
        $this->forms = $forms;
    }

    public function get() {
        $forms = array_map( [ $this, 'toArray' ], $this->forms );
        return array_reduce( $forms, function( $forms, $form ) {
            $forms[ $form[ 'formId' ] ] = $form;
            return $forms;
        }, []);
    }

    protected function toArray( $form ) {
        extract($form);
        return [
            'formId' => $id,
            'formTitle' => $title,
        ];
    }
}
