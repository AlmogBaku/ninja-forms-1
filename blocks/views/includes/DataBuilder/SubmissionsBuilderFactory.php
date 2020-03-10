<?php

namespace NinjaForms\Blocks\DataBuilder;

class SubmissionsBuilderFactory {

    /**
     * @param int $formId
     * @param int $perPage
     * @param int $page
     * 
     * @return SubmissionsBuilder
     */
    public function make( $formId, $perPage = -1, $page = 0 ) {

        $args = [
            'posts_per_page' => $perPage,
            'paged' => $page,
            'post_type' => 'nf_sub',
            'meta_query' => [[
                'key' => '_form_id',
                'value' => $formId
            ]]
        ];
        
        $submissions = array_map( function( $post ) {
            return array_map( [ self::class, 'flattenPostmeta' ], get_post_meta( $post->ID ) );
        }, get_posts( $args ) );

        return new SubmissionsBuilder( $submissions );
    }

    protected static function flattenPostmeta( $postmeta ) {
        $postmeta = (array) $postmeta;
        return reset( $postmeta );
    }
}