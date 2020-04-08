<?php

return apply_filters( 'ninja_forms_available_actions', array(

    'mailchimp'             => array(
        'group'             => 'marketing',
        'name'              => 'mailchimp',
        'nicename'          => 'MailChimp',
        'link'              => 'https://ninjaforms.com/extensions/mail-chimp/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=MailChimp',
        'plugin_path'       => 'ninja-forms-mail-chimp/ninja-forms-mail-chimp.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/mail-chimp.png"/>
                                    <p>In order to use this action, you need MailChimp for Ninja Forms.</p>
                                    <p>The MailChimp extension allows you to quickly create newsletter signup forms for your MailChimp account using the power and flexibility that Ninja Forms provides.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/mail-chimp/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=MailChimp" title="MailChimp" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'zapier'                => array(
        'group'             => 'misc',
        'name'              => 'zapier',
        'nicename'          => 'Zapier',
        'link'              => 'https://ninjaforms.com/extensions/zapier/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Zapier',
        'plugin_path'       => 'ninja-forms-zapier/ninja-forms-zapier.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/zapier.png"/>
                                <p>In order to use this action, you need Zapier for Ninja Forms.</p>
                                <p>Zapier is the perfect ‘middle-man’ solution for connecting WordPress to almost any service that does not yet have an official integration. Simply install and activate this extension, and your WordPress forms become the bridge between your website and Zapier. From there, they connect you to the service of your choice.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/zapier/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Zapier" title="Zapier" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'file_uploads'          => array(
        'group'             => 'popular',
        'name'              => 'file_uploads',
        'nicename'          => 'File Uploads',
        'link'              => 'https://ninjaforms.com/extensions/file-uploads/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=File+Uploads',
        'plugin_path'       => 'ninja-forms-uploads/file-uploads.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/file-uploads.png"/>
                                <p>In order to use this action, you need File Uploads for Ninja Forms.</p>
                                <p>Add file upload fields to save files to your server or send them to <strong>Dropbox</strong> or <strong>Amazon S3</strong> securely. The ability to collect data from your visitors is an important tool for any site owner. Sometimes the information you need comes in the form of images, videos, or documents like PDFs, Word or Excel files, etc.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/file-uploads/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=File+Uploads" title="File Uploads" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'createposts'           => array(
        'group'             => 'management',
        'name'              => 'createposts',
        'nicename'         => 'Front-End Posting',
        'link'              => 'https://ninjaforms.com/extensions/front-end-posting/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Front-End+Posting',
        'plugin_path'       => 'ninja-forms-post-creation/ninja-forms-post-creation.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/front-end-posting.png"/>
                                <p>In order to use this action, you need Front-End Posting for Ninja Forms.</p>
                                <p>Front-End Posting gives you the power of the WordPress post editor on any publicly viewable page you choose. You can allow users the ability to create content and have it assigned to any publicly available built-in or custom post type, taxonomy, and custom meta field.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/front-end-posting/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Front-End+Posting" title="Front-End Posting" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'trello'                => array(
        'group'             => 'workflow',
        'name'              => 'trello',
        'nicename'          => 'Trello',
        'link'              => 'https://ninjaforms.com/extensions/trello/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Trello',
        'plugin_path'       => 'ninja-forms-trello/ninja-forms-trello.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/trello.png"/>
                                <p>In order to use this action, you need Trello for Ninja Forms.</p>
                                <p>This extension allows you to create Trello cards from you Ninja Forms submissions. You can control the card name and description from your form inputs, assign members to the card by default, set the card labels and its position. You can also set form inputs to be links attached to the cards.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/trello/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Trello" title="Trello" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'slack'                 => array(
        'group'             => 'notifications',
        'name'              => 'slack',
        'nicename'          => 'Slack',
        'link'              => 'https://ninjaforms.com/extensions/slack/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Slack',
        'plugin_path'       => 'ninja-forms-slack/ninja-forms-slack.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/slack.png"/>
                                    <p>In order to use this action, you need Slack for Ninja Forms.</p>
                                    <p>Add users to Slack teams and send form submission data to a Slack channel using Ninja Forms.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/slack/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Slack" title="Slack" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'webhooks'              => array(
        'group'             => 'misc',
        'name'              => 'webhooks',
        'nicename'          => 'WebHooks',
        'link'              => 'https://ninjaforms.com/extensions/webhooks/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebHooks',
        'plugin_path'       => 'ninja-forms-webhooks/ninja-forms-webhooks.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/webhooks.png"/>
                                <p>In order to use this action, you need WebHooks for Ninja Forms.</p>
                                <p>Do you need to integrate your form with several different web services? Maybe you’d like to subscribe a user to a Feedblitz email list, or populate a remote CRM with your user’s submitted data. The Webhooks extension allows you to send form data to a remote URL using either a GET or POST request.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/webhooks/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebHooks" title="WebHooks" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'campaignmonitor'       => array(
        'group'             => 'marketing',
        'name'              => 'campaignmonitor',
        'nicename'          => 'Campaign Monitor',
        'link'              => 'https://ninjaforms.com/extensions/campaign-monitor/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Campaign+Monitor',
        'plugin_path'       => 'ninja-forms-campaign-monitor/ninja-forms-campaign-monitor.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/campaign-monitor.png"/>
                                    <p>In order to use this action, you need Campaign Monitor for Ninja Forms.</p>
                                    <p>The Campaign Monitor extension allows you to quickly create newsletter signup forms for your Campaign Monitor account. Create an unlimited number of subscribe forms and begin growing your mailing lists.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/campaign-monitor/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Campaign+Monitor" title="Campaign Monitor" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'constantcontact'       => array(
        'group'             => 'marketing',
        'name'              => 'constantcontact',
        'nicename'          => 'Constant Contact',
        'link'              => 'https://ninjaforms.com/extensions/constant-contact/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Constant+Contact',
        'plugin_path'       => 'ninja-forms-constant-contact/ninja-forms-constant-contact.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/constant-contact.png"/>
                                    <p>In order to use this action, you need Constant Contact for Ninja Forms.</p>
                                    <p>The Constant Contact extension allows you to quickly create newsletter signup forms for your Constant Contact account. Create an unlimited number of subscribe forms and grow your mailing lists.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/constant-contact/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Constant+Contact" title="Constant Contact" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'aweber'                => array(
        'group'             => 'marketing',
        'name'              => 'aweber',
        'nicename'          => 'AWeber',
        'link'              => 'https://ninjaforms.com/extensions/aweber/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=AWeber',
        'plugin_path'       => 'ninja-forms-aweber/ninja-forms-aweber.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/aweber.png"/>
                                    <p>In order to use this action, you need AWeber for Ninja Forms.</p>
                                    <p>The AWeber extension allows you to quickly create newsletter signup forms for your AWeber account. Create an unlimited number of subscribe forms and grow your mailing lists.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/aweber/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=AWeber" title="AWeber" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'emma'                  => array(
        'group'             => 'marketing',
        'name'              => 'emma',
        'nicename'         => 'Emma',
        'link'              => 'https://ninjaforms.com/extensions/emma/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Emma',
        'plugin_path'       => '',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/emma.png"/>
                                <p>In order to use this action, you need Emma for Ninja Forms.</p>
                                <p>The Emma extension allows you to quickly create newsletter signup forms for your Emma account. Create an unlimited number of subscribe forms and grow your mailing lists.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/emma/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Emma" title="Emma" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'webmerge'              => array(
        'group'             => 'workflow',
        'name'              => 'webmerge',
        'nicename'          => 'WebMerge',
        'link'              => 'https://ninjaforms.com/extensions/webmerge/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebMerge',
        'plugin_path'       => 'ninja-forms-webmerge/ninja-forms-webmerge.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/webmerge.png"/>
                                <p>In order to use this action, you need WebMerge for Ninja Forms.</p>
                                <p>With the WebMerge extension for Ninja Forms, you can send form data directly to the awesome <a href="https://webmerge.me" target="_blank">webmerge.me</a> service. This lets you easily populate PDFs, Excel spreadsheets, Word docs, or PowerPoint presentations.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/webmerge/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebMerge" title="WebMerge" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'twilio_sms'            => array(
        'group'             => 'notifications',
        'name'              => 'twilio_sms',
        'nicename'          => 'Twilio SMS',
        'link'              => 'https://ninjaforms.com/extensions/twilio-sms/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Twilio+SMS',
        'plugin_path'       => 'ninja-forms-twilio/ninja-forms-twilio.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/twilio-sms.png"/>
                                <p>In order to use this action, you need Twilio SMS for Ninja Forms.</p>
                                <p>Send an SMS when someone submits your form via Twilio. SMS is a powerful way to send notifications to yourself or to your customers. Unlike other types of notifications,  90% of SMS are read within the first three minutes of delivery and have an open rate approaching 100%. </p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/twilio-sms/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Twilio+SMS" title="Twilio SMS" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'email_octopus'         => array(
        'group'             => 'marketing',
        'name'              => 'email_octopus',
        'nicename'          => 'EmailOctopus',
        'link'              => 'https://ninjaforms.com/extensions/emailoctopus/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=EmailOctopus',
        'plugin_path'       => 'ninja-forms-emailoctopus/ninja-forms-emailoctopus.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/emailoctopus.png"/>
                                <p>In order to use this action, you need EmailOctopus for Ninja Forms.</p>
                                <p>Automation, integration, analytics… EmailOctopus is the email management solution that fills every need, and it’s now available for WordPress! More than a simple email marketing tool, discover a new way to manage every aspect of your email strategy from marketing campaigns to automated employee onboarding. <strong>Save time, save money, be an email rockstar!</strong></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/emailoctopus/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=EmailOctopus" title="EmailOctopus" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'stripe'                => array(
        'group'             => 'payments',
        'name'              => 'stripe',
        'nicename'          => 'Stripe',
        'link'              => 'https://ninjaforms.com/extensions/stripe/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Stripe',
        'plugin_path'       => 'ninja-forms-stripe/ninja-forms-stripe.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/stripe.png"/>
                                <p>In order to use this action, you need Stripe for Ninja Forms.</p>
                                <p>The Stripe extension allows you to <strong>accept credit card payments</strong> directly from your WordPress website using the secure Stripe Checkout process. Customers never leave your site, instead completing their payments from the customizable Stripe Checkout modal window.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/stripe/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Stripe" title="Stripe" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'paypal'                => array(
        'name'              => 'paypal',
        'nicename'          => 'PayPal',
        'link'              => 'https://ninjaforms.com/extensions/paypal-express/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PayPal',
        'plugin_path'       => 'ninja-forms-paypal-express/ninja-forms-paypal-express.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/paypal-express.png"/>
                                <p>In order to use this action, you need PayPal Express for Ninja Forms.</p>
                                <p>The PayPal Express extension allows you to <strong>accept credit card payments</strong> using the secure PayPall Checkout process.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/paypal-express/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PayPal" title="PayPal" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'elavon'                => array(
        'group'             => 'payments',
        'name'              => 'elavon',
        'nicename'          => 'Elavon',
        'link'              => 'https://ninjaforms.com/extensions/elavon/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Elavon',
        'plugin_path'       => 'ninja-forms-elavon-payment-gateway/ninja-forms-elavon-payment-gateway.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/elavon.png"/>
                                <p>In order to use this action, you need Elavon for Ninja Forms.</p>
                                <p>With the Ninja Forms Elavon extension, you can connect your WordPress website directly to your merchant bank account and process credit card payments directly from your site.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/elavon/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Elavon" title="Elavon" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'pipelinedeals-crm'      => array(
        'group'             => 'marketing',
        'name'              => 'pipelinedeals-crm',
        'nicename'          => 'PipelineDeals CRM',
        'link'              => 'https://ninjaforms.com/extensions/pipelinedeals-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PipelineDeals+CRM',
        'plugin_path'       => 'ninja-forms-pipeline-deals-crm/ninja-forms-pipeline-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/pipelinedeals-crm.png"/>
                                <p>In order to use this action, you need PipelineDeals CRM for Ninja Forms.</p>
                                <p>Sick of transferring customer data manually between your website and PipelineDeals? Tired of maintaining an unstable custom integration? You can now connect your website directly to PipelineDeals through Ninja Forms with this fully automated solution!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/pipelinedeals-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PipelineDeals+CRM" title="PipelineDeals CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'active-campaign'       => array(
        'group'             => 'marketing',
        'name'              => 'active-campaign',
        'nicename'          => 'Active Campaign',
        'link'              => 'https://ninjaforms.com/extensions/active-campaign/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Active+Campaign',
        'plugin_path'       => 'ninja-forms-active-campaign/ninja-forms-active-campaign.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/active-campaign.png"/>
                                <p>In order to use this action, you need Active Campaign for Ninja Forms.</p>
                                <p>Active Campaign shines for sales teams that require insightful, intelligent customer relationship management. There’s no reason your integration should deliver any less. Integrate today and combine effortless, intelligent marketing automation with your WordPress website!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/active-campaign/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Active+Campaign" title="Active Campaign" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'insightly-crm'         => array(
        'group'             => 'marketing',
        'name'              => 'insightly-crm',
        'nicename'          => 'Insightly CRM',
        'link'              => 'https://ninjaforms.com/extensions/insightly-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Insightly+CRM',
        'plugin_path'       => 'ninja-forms-insightly-crm/ninja-forms-insightly-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/insightly-crm.png"/>
                                <p>In order to use this action, you need Insightly CRM for Ninja Forms.</p>
                                <p>The Insightly CRM extension for Ninja Forms enables you to send your form submission data directly into your Insightly CRM account, managing your sales leads effectively.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/insightly-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Insightly+CRM" title="Insightly CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'user-management'       => array(
        'group'             => 'management',
        'name'              => 'user-management',
        'nicename'          => 'User Management',
        'link'              => 'https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management',
        'plugin_path'       => 'ninja-forms-user-management/ninja-forms-user-management.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/user-management.png"/>
                                <p>In order to use this action, you need User Management for Ninja Forms.</p>
                                <p>With User Management for Ninja Forms, you can:<ul><li>Register new users</li><li>Login registered users</li><li>Allow users to update their existing profiles</li></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management" title="User Management" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'salesforce-crm'        => array(
        'group'             => 'marketing',
        'name'              => 'salesforce-crm',
        'nicename'          => 'Salesforce CRM',
        'link'              => 'https://ninjaforms.com/extensions/salesforce-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Salesforce+CRM',
        'plugin_path'       => 'ninja-forms-salesforce-crm/ninja-forms-salesforce-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/salesforce-crm.png"/>
                                <p>In order to use this action, you need Salesforce CRM for Ninja Forms.</p>
                                <p>When the world’s most used CMS and the industry leading CRM come together, great things are bound to happen for your organization. WordPress and Salesforce is an integration that you need working for you!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/salesforce-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Salesforce+CRM" title="Salesforce CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'capsule-crm'           => array(
        'group'             => 'marketing',
        'name'              => 'capsule-crm',
        'nicename'          => 'Capsule CRM',
        'link'              => 'https://ninjaforms.com/extensions/capsule-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Capsule+CRM',
        'plugin_path'       => 'ninja-forms-capsule-crm/ninja-forms-capsule-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/capsule-crm.png"/>
                                <p>In order to use this action, you need Capsule CRM for Ninja Forms.</p>
                                <p>Connecting your WordPress website to your CRM account shouldn’t be a time sink for your team, but it too often can be. Take that pain away with effortless integration between WordPress and your CRM with Ninja Forms’ official Capsule CRM addon!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/capsule-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Capsule+CRM" title="Capsule CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'recurly'               => array(
        'group'             => 'payments',
        'name'              => 'recurly',
        'nicename'          => 'Recurly',
        'link'              => 'https://ninjaforms.com/extensions/recurly/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Recurly',
        'plugin_path'       => 'ninja-forms-recurly/ninja-forms-recurly.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/recurly.png"/>
                                <p>In order to use this action, you need Recurly for Ninja Forms.</p>
                                <p>Recurly delivers agile enterprise-class subscription management to thousands of businesses worldwide. Together with Ninja Forms, any form on any page of your site can now become an avenue to introduce your customers to the subscription management umbrella of Recurly.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/recurly/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Recurly" title="Recurly" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'highrise-crm'          => array(
        'group'             => 'marketing',
        'name'              => 'highrise-crm',
        'nicename'          => 'Highrise CRM',
        'link'              => 'https://ninjaforms.com/extensions/highrise-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Highrise+CRM',
        'plugin_path'       => 'ninja-forms-highrise-crm/ninja-forms-highrise-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/highrise-crm.png"/>
                                <p>In order to use this action, you need Highrise CRM for Ninja Forms.</p>
                                <p></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/highrise-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Highrise+CRM" title="Highrise CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'onepage-crm'           => array(
        'group'             => 'marketing',
        'name'              => 'onepage-crm',
        'nicename'          => 'OnePage CRM',
        'link'              => 'https://ninjaforms.com/extensions/onepage-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=OnePage+CRM',
        'plugin_path'       => 'ninja-forms-onepagecrm/ninja-forms-onepage-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/onepage-crm.png"/>
                                <p>In order to use this action, you need OnePage CRM for Ninja Forms.</p>
                                <p>OnePage CRM is designed to keep your sales team focused on sales instead of navigating complex software. Ninja Forms’ official integration has been built with that ideal in mind and delivers in kind!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/onepage-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=OnePage+CRM" title="OnePage CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),
) );