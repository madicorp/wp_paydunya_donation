<?php
settings_fields( 'pd-api-setting-settings' );        
require_once plugin_dir_path( __FILE__ ).'/paydunya-php/paydunya.php' ;

register_setting("pd-api-setting-settings", "pd_master_key");
register_setting("pd-api-setting-settings", "pd_public_key");
register_setting("pd-api-setting-settings", "pd_private_key");
register_setting("pd-api-setting-settings", "pd_token_key");
register_setting("pd-api-setting-settings", "pd_is_test");
register_setting("pd-api-setting-settings", "pd_name");
register_setting("pd-api-setting-settings", "pd_tagline");
register_setting("pd-api-setting-settings", "pd_phone");
register_setting("pd-api-setting-settings", "pd_address");
//API KEYS
Paydunya_Setup::setMasterKey(get_option("pd_master_key"));
Paydunya_Setup::setPublicKey(get_option("pd_public_key"));
Paydunya_Setup::setPrivateKey(get_option("pd_private_key"));
Paydunya_Setup::setToken(get_option("pd_token_key"));
if ("test" == get_option("pd_is_test")) {Paydunya_Setup::setMode("test");} // Optionnel. Utilisez cette option pour les paiements tests.
// Ets Informations
Paydunya_Checkout_Store::setName(get_option("pd_name")); // Seul le nom est requis
Paydunya_Checkout_Store::setTagline(get_option("pd_tagline"));
Paydunya_Checkout_Store::setPhoneNumber(get_option("pd_phone"));
Paydunya_Checkout_Store::setPostalAddress(get_option("pd_address"));
Paydunya_Checkout_Store::setWebsiteUrl(get_site_url());
Paydunya_Checkout_Store::setLogoUrl(get_option("pd_logo_url"));