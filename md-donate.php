<?php
/*
Plugin Name: Donation Plugin via Paydunya
Plugin URI: https://github.com/madicorp/wp_paydunya_donation
Description: Simple donation form via paydunya
Version: 1.0
Author: Madicorp
Author URI: http://madi-corp.net
*/

add_action('admin_menu', 'wp_paydunya_donation_menu');

function wp_paydunya_donation_menu(){
       add_menu_page( 'WP Paydunya Donation', 'Paydunya Donation', 'manage_options', 'paydunya-donation', 'wp_paydunya_donation_init' );
       add_action( 'admin_init', 'register_wp_paydunya_settings' );
       
}

function register_wp_paydunya_settings(){
    register_setting("pd-api-setting-settings", "pd_master_key");
    register_setting("pd-api-setting-settings", "pd_public_key");
    register_setting("pd-api-setting-settings", "pd_private_key");
    register_setting("pd-api-setting-settings", "pd_token_key");
    register_setting("pd-api-setting-settings", "pd_is_test");
    register_setting("pd-api-setting-settings", "pd_name");
    register_setting("pd-api-setting-settings", "pd_tagline");
    register_setting("pd-api-setting-settings", "pd_phone");
    register_setting("pd-api-setting-settings", "pd_address");
    register_setting("pd-api-setting-settings", "pd_logo_url");
    
}

function wp_paydunya_donation_init(){
    ?>
    <div class="container">
    <h1>Paydunya Api Settings</h1>
       <form method="post" action="options.php">
         <?php settings_fields( 'pd-api-setting-settings' ); ?>
         <?php do_settings_sections( 'pd-api-setting-settings' ); ?>
         <h3>API KEYS</h3>
         <ul>
             <li>
                <label for="pd_master_key">Master Key<span> *</span>: </label>
                <input id="pd_master_key" required name="pd_master_key" value="<?= get_option("pd_master_key") ?>" />
             </li>
             <li>
                <label for="pd_public_key">Public key<span> *</span>: </label>
                <input id="pd_public_key" required name="pd_public_key" value="<?= get_option("pd_public_key") ?>" />
             </li>
             <li>
                <label for="pd_private_key">Private Key<span> *</span>: </label>
                <input id="pd_private_key" required  name="pd_private_key" value="<?= get_option("pd_private_key") ?>" />
             </li>
             <li>
                <label for="pd_token_key">Token <span> *</span>: </label>
                <input id="pd_token_key" required name="pd_token_key" value="<?= get_option("pd_token_key") ?>" />
             </li>
             <li>
                <label for="pd_is_test">Test mode ?: </label>
                <input id="pd_is_test"  name="pd_is_test" type="checkbox" value="test" value="test"<?php checked( "test" == get_option("pd_is_test") ); ?>/>
             </li>
         </ul>
         <h3>Compnay Info</h3>
         <ul>
             <li>
                <label for="pd_name">Name<span> *</span>: </label>
                <input id="pd_name"  name="pd_name" value="<?= get_option("pd_name") ?>" />
             </li>
             <li>
                <label for="pd_tagline">Tagline </label>
                <input id="pd_tagline" required name="pd_tagline" value="<?= get_option("pd_tagline") ?>" />
             </li>
             <li>
                <label for="pd_phone">Phone Number: </label>
                <input id="pd_phone"  name="pd_phone" value="<?= get_option("pd_phone") ?>" />
             </li>
             <li>
                <label for="pd_address">Address: </label>
                <input id="pd_address"  name="pd_address" value="<?= get_option("pd_address") ?>" />
             </li>
             <li>
                <label for="pd_logo_url">Logo Url: </label>
                <input id="pd_logo_url"  name="pd_logo_url" value="<?= get_option("pd_logo_url") ?>" />
             </li>
         </ul>
         <?php submit_button(); ?>
       </form>
    </div>
     <?php
}

function html_form_code() {
echo'<form id="donation_form" class="form_donation" action="'. $_SERVER['REQUEST_URI'] .'" method="post">'
.'<label>Montant Donation : <span style="color: red;">*</span></label>'
.'<input type="number" name="donation_amount" required="required">'
.'<br>'
.'<label>Commentaires :</label> <textarea name="donation_comment"></textarea>'
.'<br>'
.'<input value="Envoyer" name="cf-wp-donation" class="btn btn-success" type="submit"/>'
.'</form>'
.'<div class="image_paydunya">'
.'<img class="img_paydunya" src="https://developers.paydunya.com/img/branding/logo_pay_with_paydunya3.png">'
.'</div>';
}

function donate()
{
       
    if ( isset( $_POST['cf-wp-donation'] ) ) 
    {
        
        include_once plugin_dir_path( __FILE__ )."/paydunya_config.php";
               
        Paydunya_Checkout_Store::setCallbackUrl($_SERVER['REQUEST_URI']);
        $invoice = new Paydunya_Checkout_Invoice();
        $invoice->addItem("DONATION", 1, $_POST['donation_amount'], $_POST['donation_amount']);
        $invoice->setDescription( $_POST['donation_comment']);
        
        $invoice->setTotalAmount($_POST['donation_amount']);
        if($invoice->create()) {
            header("Location: ".$invoice->getInvoiceUrl());
          }else{
            echo $invoice->response_text;
          }
          
    }
}

function cf_shortcode() {
    ob_start();
    donate();
    html_form_code();

    return ob_get_clean();
}

add_shortcode( 'paydunya_donate_form', 'cf_shortcode' );
