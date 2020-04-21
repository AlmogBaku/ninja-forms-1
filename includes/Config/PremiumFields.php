<?php

return [
    'mailchimp_optin_modal' => [
        'id' => 'mailchimp_optin_modal',
        'nicename' => 'Mailchimp OptIn',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/mail-chimp.png"/>
                                    <p>In order to use this action, you need MailChimp for Ninja Forms.</p>
                                    <p>The MailChimp extension allows you to quickly create newsletter signup forms for your MailChimp account using the power and flexibility that Ninja Forms provides.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/mail-chimp/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=MailChimp" title="MailChimp" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ],

    'file_uploads_modal' => [
        'id' => 'file_uploads_modal',
        'nicename' => 'File Uploads',
        'modal_content'                 => '<div class="available-action-modal">
                                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/file-uploads.png"/>
                                                <p>In order to use this feature, you need File Uploads for Ninja Forms.</p>
                                                <p>
                                                Let users upload any file to your website with File Uploads! Restrict by file type and size. Upload to server, media library, or your favorite cloud service.</p>
                                                <div class="actions">
                                                    <a target="_blank" href="https://ninjaforms.com/extensions/stripe/?utm_source=Ninja+Forms+Plugin&utm_medium=Add+New&utm_campaign=Dashboard+New+Form+Template&utm_content=File+Upload
                                                    " title="File Uploads" class="primary nf-button">Learn More</a>
                                                </div>
                                            </div>',
    ],
];