<?php

namespace NinjaForms\Achievements;

$formCount = Metrics\Factory::makeFormCount();
$submisisonCount = Metrics\Factory::makeSubmissionCount();

$collection = ModelFactory::collectionFromArray(
        include_once plugin_dir_path(__FILE__) . 'achievements.php'
    )
    ->where( 'metric', 'submissionCount' )
    ->whereCallback( 'threshold', function( $item ) use ( $submisisonCount ) {
        return $submisisonCount->isAtLeast( $item->get('threshold') );
    })
;

if($achievement = $collection->pop()) {
    add_action( 'admin_notices', function() use  ( $achievement ) {
        $view = new Views\AdminNotice( $achievement->message );
        echo $view->render();
    });
}

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
