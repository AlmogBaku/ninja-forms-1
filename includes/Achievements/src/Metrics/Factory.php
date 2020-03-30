<?php

namespace NinjaForms\Achievements\Metrics;

class Factory
{
    public static function makeFormCount()
    {
        global $wpdb;
        $formCount = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}nf3_forms");
        return new Count( $formCount );
    }

    public static function makeSubmissionCount()
    {
        $submissionCount = wp_count_posts( $type = 'nf_sub' );
        return new Count( $submissionCount->publish );
    }
}