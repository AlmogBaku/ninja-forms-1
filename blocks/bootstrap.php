<?php

add_action('init', function() {

    $token = NinjaForms\Blocks\Authentication\TokenFactory::make();
    $publicKey = NinjaForms\Blocks\Authentication\KeyFactory::make();
 
    // automatically load dependencies and version
    $block_asset_file = include(plugin_dir_path(__FILE__) . '../build/block.asset.php');

    wp_register_script(
        'ninja-forms/submissions-table/block',
        plugins_url('../build/block.js', __FILE__),
        $block_asset_file['dependencies'],
        $block_asset_file['version']
    );

    wp_localize_script('ninja-forms/submissions-table/block', 'ninjaFormsViews', [
        'token' => $token->create($publicKey),
    ]);

    $render_asset_file = include(plugin_dir_path(__FILE__) . '../build/render.asset.php');

    wp_register_script(
        'ninja-forms/submissions-table/render',
        plugins_url( '../build/render.js', __FILE__ ),
        $render_asset_file['dependencies'],
        $render_asset_file['version']
    );

    wp_localize_script('ninja-forms/submissions-table/render', 'ninjaFormsViews', [
        'token' => $token->create( $publicKey ),
    ]);
    
    register_block_type( 'ninja-forms/submissions-table', array(
        'editor_script' => 'ninja-forms/submissions-table/block',
        'render_callback' => function( $attributes, $content ) {
            if( isset( $attributes['formId'] ) && $attributes['formId']) {
                wp_enqueue_script('ninja-forms/submissions-table/render');
                $className = 'ninja-forms-views-submissions-table';
                if(isset($attributes['alignment'])) $className .= ' align'.$attributes['alignment'];
                return sprintf("<div class='%s' data-attributes='%s'></div>", esc_attr($className), esc_attr(wp_json_encode($attributes)));
            }
        }
    ) );
 
});

add_action( 'rest_api_init', function () {

    $tokenAuthenticationCallback = function( WP_REST_Request $request ) {
        $token = NinjaForms\Blocks\Authentication\TokenFactory::make();
        return $token->validate( $request->get_header('X-NinjaFormsViews-Auth') );
    };

    register_rest_route( 'ninja-forms-views/', 'forms', array(
        'methods' => 'GET',
        'callback' => function( WP_REST_Request $request ) {
            $formsBuilder = (new NinjaForms\Blocks\DataBuilder\FormsBuilderFactory)->make();
            return $formsBuilder->get();
        },
        'permission_callback' => $tokenAuthenticationCallback,
    ));

    register_rest_route( 'ninja-forms-views/', 'forms/(?P<id>\d+)/fields', [
        'methods' => 'GET',
        'args' => [
            'id' => [
                'required'    => true,
                'description' => __( 'Unique identifier for the object.' ),
                'type'        => 'integer',
                'validate_callback' => 'rest_validate_request_arg',
            ],
        ],
        'callback' => function( WP_REST_Request $request ) {
            $fieldsBuilder = (new NinjaForms\Blocks\DataBuilder\FieldsBuilderFactory)->make( 
                $request->get_param( 'id' )
             );
            return $fieldsBuilder->get();
        },
        'permission_callback' => $tokenAuthenticationCallback,
    ]);

    register_rest_route( 'ninja-forms-views/', 'forms/(?P<id>\d+)/submissions', [
        'methods' => 'GET',
        'args' => [
            'id' => [
                'required'    => true,
                'description' => __( 'Unique identifier for the object.' ),
                'type'        => 'integer',
                'validate_callback' => 'rest_validate_request_arg',
            ],
            'perPage' => [
                'description'       => __( 'Maximum number of items to be returned in result set.' ),
                'type'              => 'integer',
				'minimum'           => 1,
				'maximum'           => 100,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
            ],
            'page' => [
				'description'       => __( 'Current page of the collection.' ),
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
				'minimum'           => 1,
            ]
        ],
        'callback' => function( WP_REST_Request $request ) {
            $submissionsBuilder = (new NinjaForms\Blocks\DataBuilder\SubmissionsBuilderFactory)->make( 
                $request->get_param( 'id' ),
                $request->get_param( 'perPage' ),
                $request->get_param( 'page' )
            );
            return $submissionsBuilder->get();
        },
        'permission_callback' => $tokenAuthenticationCallback,
    ]);

} );
