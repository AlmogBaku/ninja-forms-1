<?php
if (!defined('ABSPATH')) {
    exit;
}

class NF_Php_Version_Whip
{
    /**
     * NF_Whip constructor.
     * Hooks into the WordPress admin notice system and calls our whip_message.
     * @Since 3.2.19
     */
    public function __construct()
    {
        // Gets our transient
        $transient = get_transient('nf_dismiss_php_version_whip');

        // Checks our transient and fires the message.
        if (false == $transient && current_user_can('administrator')) {
            add_action('admin_notices', array( $this, 'whipMessage'));
            $this->set_dismiss();
        }
    }

    /**
     * Whip Message
     * Builds and outputs our message.
     * @Since 3.2.19
     */
    public function whipMessage()
    {
        // Builds our Whip message.
        $message = array();
        $message[] = '<strong>' . esc_html__('Hey, we\'ve noticed that you\'re running an outdated version of PHP.', 'ninja-forms') . "</strong><br /><br />";
        $message[] = esc_html__('PHP is the programming language that WordPress, Ninja Forms, and themes are built on. The version that is currently used for your site is no longer supported. In fact, your version of PHP no longer receives security updates, which is why we\'re sending you to this notice.', 'ninja-forms') . "<br/><br/>";
        $message[] = '<strong>' . esc_html__('Your site could be 3 times faster with a newer PHP version.', 'ninja-forms') . '</strong><br/><br/>';
        $message[] = sprintf( esc_html__('You should update your PHP version to verison 7.2 or greater. It has been shown that PHP 7.3 or higher runs WordPress up to three times faster than PHP 5.6. On a normal WordPress site, switching to PHP 7.2 or above should never cause issues.  There are some plugins that are not ready for PHP 7 though, so do some testing first. Yoast have an article on how to test whether that\'s an option for you %1$shere%2$s. At this time, PHP 7.2 is the oldest version of PHP supported by the %3$sPHP Open Source Project%2$s.', 'ninja-forms') . '<br /><br/>',
        '<a href="https://yoa.st/wg" target="_blank">',
        '</a>',
        '<a href="https://www.php.net/supported-versions.php" target="_blank">'
			);
        $message[] = '<strong>' . esc_html__('Can\'t update? Ask your host!', 'ninja-forms') . '</strong><br /><br />';
        $message[] = sprintf( esc_html__('If you cannot upgrade your PHP version yourself, you can send an email to your host. Yoast has %1$sexamples here%2$s. If they don\'t want to upgrade your PHP version, we would suggest you switch hosts. Have a look at one of the recommended %3$sWordPress hosting partners%2$s.','ninja-forms') . '<br /><br /><br />',
        '<a href="https://yoa.st/wh" target="_blank">',
        '</a>',
        sprintf('<a href="%1$s" target="_blank">', esc_url('https://wordpress.org/hosting/'))
        );

        $dismiss_url = add_query_arg(
            array(
                'page' => 'ninja-forms',
                'dismiss-php-version-whip-message' => 'true'
            ),
            admin_url() . 'admin.php'
        );
        // Builds our
        $message[] = sprintf( esc_html__('%1$sDismiss this for 4 weeks.%2$s', 'ninja-forms') . '<br />',
            '<a href="' . esc_url($dismiss_url) . '" target="_self">',
            '</a>'
        );

        // Change our array to string to be displayed.
        $message = implode($message, "\n");

        // Output our message.
        echo '<div class="notice notice-error" style="padding: 20px">' . $message . '</div>';
    }

    /**
     * Set Dismiss
     * Sets a transient for 4 weeks out that will remove the whip notice.
     * @Since 3.2.19
     */
    public function set_dismiss()
    {
        if (isset($_GET[ 'page' ]) && 'ninja-forms' == $_GET['page']
            && isset($_GET['dismiss-php-version-whip-message']) && 'true' == $_GET['dismiss-php-version-whip-message']) {
                set_transient('nf_dismiss_php_version_whip', 1, 60 * 60 * 24 * 28);
        }
    }
}
