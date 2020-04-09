<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'ninja_forms_from_settings_types', array(

    'display' 			=> array(
        'id' 			=> 'display',
        'nicename' 		=> esc_html__( 'Display Settings', 'ninja-forms' ),
    ),

    'restrictions' 		=> array(
        'id' 			=> 'restrictions',
        'nicename' 		=> esc_html__( 'Restrictions', 'ninja-forms' )
    ),

    'calculations' 		=> array(
    	'id' 			=> 'calculations',
    	'nicename' 		=> esc_html__( 'Calculations', 'ninja-forms')
    ),

    'uploads' 		=> array(
    	'id' 			=> 'uploads',
        'nicename' 		=> esc_html__( 'File Uploads', 'ninja-forms'),
        'modal-content'                 => '<div class="available-settings-modal">
                                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/file-uploads.png"/>
                                                <p>In order to use this template, you need File Uploads for Ninja Forms.</p>
                                                <p>
                                                Let users upload any file to your website with File Uploads! Restrict by file type and size. Upload to server, media library, or your favorite cloud service.</p>
                                                <div class="actions">
                                                    <a target="_blank" href="https://ninjaforms.com/extensions/stripe/?utm_source=Ninja+Forms+Plugin&utm_medium=Add+New&utm_campaign=Dashboard+New+Form+Template&utm_content=File+Upload
                                                    " title="File Uploads" class="primary nf-button">Learn More</a>
                                                </div>
                                            </div>',
    ),

));
