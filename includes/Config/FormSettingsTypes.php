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

    'uploads_modal' 		=> array(
    	'id' 			=> 'uploads_modal',
        'nicename' 		=> esc_html__( 'File Uploads', 'ninja-forms'),
        'modal-content'                 => '<div class="available-settings-modal">
                                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/file-uploads.png"/>
                                                <p>In order to use this feature, you need File Uploads for Ninja Forms.</p>
                                                <p>
                                                Let users upload any file to your website with File Uploads! Restrict by file type and size. Upload to server, media library, or your favorite cloud service.</p>
                                                <div class="actions">
                                                    <a target="_blank" href="https://ninjaforms.com/extensions/stripe/?utm_source=Ninja+Forms+Plugin&utm_medium=Add+New&utm_campaign=Dashboard+New+Form+Template&utm_content=File+Upload
                                                    " title="File Uploads" class="primary nf-button">Learn More</a>
                                                </div>
                                            </div>',
    ),

    'pdf_modal' 		=> array(
    	'id' 			=> 'pdf_modal',
        'nicename' 		=> esc_html__( 'PDF Submissions', 'ninja-forms'),
        'modal-content'                 => '<div class="available-settings-modal">
                                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/pdf-form-submission.png"/>
                                                <p>In order to use this feature, you need PDF Form Submissions for Ninja Forms.</p>
                                                <p>Deliver by email or export any form submission as a customizable, business professional PDF file. Add company info, logo, and more!</p>
                                                <div class="actions">
                                                    <a target="_blank" href="https://ninjaforms.com/extensions/pdf-form-submissions/?utm_source=Ninja+Forms+Plugin&utm_medium=Add+New&utm_campaign=Dashboard+New+Form+Template&utm_content=PDF+Form+Submissions" title="PDF Form Submissions" class="primary nf-button">Learn More</a>
                                                </div>
                                            </div>',
    ),

    'layouts_styles_modal' 		=> array(
    	'id' 			=> 'layouts_styles_modal',
        'nicename' 		=> esc_html__( 'Layouts & Styles', 'ninja-forms'),
        'modal-content'                 => '<div class="available-settings-modal">
                                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/layout-styles.png"/>
                                                <p>In order to use this feature, you need Layouts & Styles for Ninja Forms.</p>
                                                <p>Drag & drop rows and columns, custom backgrounds, borders, & more without writing a single line of code. You just need Layout & Styles!</p>
                                                <div class="actions">
                                                    <a target="_blank" href="https://ninjaforms.com/extensions/layout-styles/?utm_source=Ninja+Forms+Plugin&utm_medium=Add+New&utm_campaign=Dashboard+New+Form+Template&utm_content=Layout+Styles" title="Layout & Styles" class="primary nf-button">Learn More</a>
                                                </div>
                                            </div>',
    ),

    'multi_part_forms_modal' 		=> array(
    	'id' 			=> 'multi_part_forms_modal',
        'nicename' 		=> esc_html__( 'Multi-Part Forms', 'ninja-forms'),
        'modal-content'                 => '<div class="available-settings-modal">
                                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/multi-part-forms.png"/>
                                                <p>In order to use this feature, you need Multi-Part Forms for Ninja Forms.</p>
                                                <p>Create multiple page forms effortlessly with a drag-and-drop interface. You don\'t need to code to build complex forms, just Multi-Part Forms!</p>
                                                <div class="actions">
                                                    <a target="_blank" href="https://ninjaforms.com/extensions/multi-part-forms/?utm_source=Ninja+Forms+Plugin&utm_medium=Add+New&utm_campaign=Dashboard+New+Form+Template&utm_content=Multi+Part+Forms" title="Multi-Part Forms" class="primary nf-button">Learn More</a>
                                                </div>
                                            </div>',
    ),

    'conditional_logic_modal' 		=> array(
    	'id' 			=> 'conditional_logic_modal',
        'nicename' 		=> esc_html__( 'Conditional Logic', 'ninja-forms'),
        'modal-content'                 => '<div class="available-settings-modal">
                                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/conditional-logic.png"/>
                                                <p>In order to use this feature, you need Conditional Logic for Ninja Forms.</p>
                                                <p>Show & hide fields and form pages, selectively send email, & much more! Build professional quality forms easily with Conditional Logic.</p>
                                                <div class="actions">
                                                    <a target="_blank" href="https://ninjaforms.com/extensions/conditional-logic/?utm_source=Ninja+Forms+Plugin&utm_medium=Add+New&utm_campaign=Dashboard+New+Form+Template&utm_content=Conditional+Logic" title="Conditional Logic" class="primary nf-button">Learn More</a>
                                                </div>
                                            </div>',
    ),

));
