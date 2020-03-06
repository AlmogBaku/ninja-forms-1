<?php

namespace NinjaForms\Views\DataBuilder;

class FieldsBuilderFactory {

    public function make( $formId ) {
        $fields = array_map( function($field) {
            return array_merge([ 'id' => $field->get_id(), ], $field->get_settings() );
        }, Ninja_Forms()->form( $formId )->get_fields() );
        return new FieldsBuilder( $fields );
    }
}