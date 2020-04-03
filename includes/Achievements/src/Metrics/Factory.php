<?php

namespace NinjaForms\Achievements\Metrics;

class Factory
{
    public static function makeFormCount()
    {
        global $wpdb;
        $formCount = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}nf3_forms");
        return new Count( $formCount );
    }

    public static function makeSubmissionCount()
    {
        $submissionCount = wp_count_posts( $type = 'nf_sub' );
        return new Count( $submissionCount->publish );
    }

    public static function makeFormDisplayCount()
    {
        $formDisplayCount = get_option('ninja_forms_display_count', $default = 0);
        return new Count( $formDisplayCount );
    }
}