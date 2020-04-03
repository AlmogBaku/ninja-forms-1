<?php

namespace NinjaForms\Milestones;

add_action('admin_init', function() {

    $metrics = [
        'formCount' => Metrics\Factory::makeFormCount(),
        'submissionCount' => Metrics\Factory::makeSubmissionCount(),
        'formDisplayCount' => Metrics\Factory::makeFormDisplayCount(),
    ];

    $milestones = include_once plugin_dir_path(__FILE__) . 'milestones.php';
    $collection = ModelFactory::collectionFromArray( $milestones )
        ->whereCallback( 'threshold', function( $item ) use ( $metrics ) {
            return $metrics[ $item->get('metric') ]->isAtLeast( $item->get('threshold') );
        })
    ;

    if($milestone = $collection->pop()) {

        add_filter( 'nf_admin_notices', function( $notices ) use ( $milestone )  {
            $notices[ $milestone->uid ] = [
                'title' => esc_html__( $milestone->title, 'ninja-forms' ),
                'msg' => $milestone->message,
                'link' => $milestone->links,
                'int' => 0,
                'blacklist' => array( 'ninja-forms-three' ),
            ];
            return $notices;
        });
    }
});

/**
 * Update form display count (excludes form previews).
 */
add_action( 'ninja_forms_before_container', function( $formId ) {
    $formDisplayCount = get_option('ninja_forms_display_count', $default = 0);
    $formDisplayCount++;
    update_option('ninja_forms_display_count', $formDisplayCount);
}, 10, 1 );
