<?php

namespace NinjaForms\Achievements;

add_action('admin_init', function() {

    // Check user permissions.
    if(!current_user_can( apply_filters( 'ninja_forms_admin_parent_menu_capabilities', 'manage_options' ) )) {
        return;
    }

    // Check site settings.
    if( 1 === Ninja_Forms()->get_setting( 'disable_admin_notices', 0 ) ) { 
        return;
    }

    $formCount = Metrics\Factory::makeFormCount();
    $submisisonCount = Metrics\Factory::makeSubmissionCount();
    $formDisplayCount = Metrics\Factory::makeFormDisplayCount();

    $collection = ModelFactory::collectionFromArray(
            include_once plugin_dir_path(__FILE__) . 'achievements.php'
        )
        ->where( 'metric', 'formDisplayCount' )
        ->whereCallback( 'threshold', function( $item ) use ( $formDisplayCount ) {
            return $formDisplayCount->isAtLeast( $item->get('threshold') );
        })
    ;

    if($achievement = $collection->pop()) {
        add_action( 'admin_notices', function() use  ( $achievement ) {
            $view = new Views\AdminNotice( $achievement->message );
            echo $view->render();
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

add_action( 'wp_ajax_ninja_forms_dismiss_notification', function() {
    if(isset($_POST['noticeId'])){
        $noticeId = sanitize_text_field( $_POST['noticeId'] );
        // @todo log notice as dismissed.
        wp_die(1); // Don't forget to stop execution afterward.
    }
} );

add_action( 'admin_enqueue_scripts', function() {
    $script = include( plugin_dir_path( __FILE__ ) . 'assets/script.asset.php');
    wp_register_script( 'ninja-forms-achievements', $script['source'], $script['dependencies'], $script['version'], $script['in_footer'] );
});
