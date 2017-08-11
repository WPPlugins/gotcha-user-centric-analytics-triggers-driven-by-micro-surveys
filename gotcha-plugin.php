<?php
   /*
   Plugin Name: Gotcha: User-centric analytics & triggers, driven by micro-surveys.
   Plugin URI: https://www.gotcha.io
   Description:  A plugin to embed Gotcha.io on your site, made for user experience, analytics and marketing.
   Version: 1.5
   Author: Gotcha.io
   Author URI: https://www.gotcha.io
   */
define( 'GOTCHA_VERSION', '1.5' );

/**
 * Adds the Gotcha tracking code to the footer of your site asynchronously.
 */
function gtio_gotcha_embed_code() { 
    $embedcodeset = esc_attr( get_option('embed_code'));
    $appidset = esc_attr( get_option('app_id'));
    
    if (
        isset($embedcodeset) && !is_null($embedcodeset) &&
        isset($appidset) && !is_null($appidset) 
    ) {
?>
    <script type="text/javascript" id="gotcha-embed">
        window.gotchaSettings = {
            app_id: "<?php echo esc_attr( get_option('embed_code') ); ?>"
        };
        (function() {function async_load(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;var theUrl = "https://api.gotcha.io/widgets/<?php echo esc_attr( get_option('app_id') ); ?>/gotcha.js";s.src = theUrl + ( theUrl.indexOf("?") >= 0 ? "&" : "?") + "ref=" + encodeURIComponent(window.location.href);var embedder = document.getElementById("gotcha-embed");embedder.parentNode.insertBefore(s, embedder);}if (window.attachEvent)window.attachEvent("onload", async_load);else window.addEventListener("load", async_load, false);})();
    </script>
<?php } }
add_action("wp_footer", "gtio_gotcha_embed_code");


/**
 * Adds the Gotcha plugin settings menu
 */
add_action('admin_menu', 'gtio_gotcha_create_menu');

function gtio_gotcha_create_menu() {

	//create new top-level menu
	//add_menu_page('Gotcha.io', 'Gotcha.io', 'administrator', __FILE__, 'gotcha_settings_page' , plugins_url('/images/icon.png', __FILE__) );
    //create new menu item under SETTINGS
    add_submenu_page( 'options-general.php', 'Gotcha.io', 'Gotcha.io', 'administrator', __FILE__, 'gtio_gotcha_settings_page', plugins_url('/images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'gtio_register_gotcha_settings' );
}


function gtio_register_gotcha_settings() {
	//register our settings
	register_setting( 'gotcha-settings-group', 'app_id' );
	register_setting( 'gotcha-settings-group', 'embed_code' );
}

function gtio_gotcha_settings_page() {
?>
<div class="wrap">
    <table class="form-table">
        <tr valign="top">
        <th scope="row" style="width: 70px;text-align: right;">
            <a target="_blank" href="https://www.gotcha.io/?utm_source=WordpressPlugin&utm_medium=SettingsLogo&utm_campaign=WordpressPlugin" title="Gotcha.io">
                <img src="<?php echo plugins_url('/images/gotchacon.svg', __FILE__); ?>" alt="Gotcha.io" style="width: 60px;">
            </a>
        </th>
            <td>
                <h1 style="text-align: left";>Gotcha.io Wordpress Integration</h1>
            </td>
        </tr>
    </table>

<p>In a nutshell, our embeddable micro-surveys, employ multiple gamification methods to get answers from your visitors.<br />
Trigger automated pop-ups, lead forms, coupons and videos based on your visitors’ behavior and personality.<br />
Set triggers by if-this-then-that rules and drive monetization and growth to your website.</p>
<p>Get the <strong>App ID</strong> and <strong>Embed code</strong> from your 
   <code><a target="_blank" href="https://app.gotcha.io/#/settings?utm_source=WordpressPlugin&utm_medium=SettingsLogo&utm_campaign=WordpressPlugin">Gotcha Dashboard</a> > Settings > Tracking Code</code>
</p>
<form method="post" action="options.php">
    <?php settings_fields( 'gotcha-settings-group' ); ?>
    <?php do_settings_sections( 'gotcha-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">App ID</th>
        <td><input type="text" name="app_id" value="<?php echo esc_attr( get_option('app_id') ); ?>" placeholder="123" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Embed code</th>
        <td><input type="text" name="embed_code" value="<?php echo esc_attr( get_option('embed_code') ); ?>" placeholder="GOTC-XXXXXXX" /></td>
        </tr>
       
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>