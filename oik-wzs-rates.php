<?php
/**
 * Plugin Name: Weight zone shipping rates shortcode
 * Plugin URI: http://www.oik-plugins.com/oik-plugins/weight-zone-shipping-rates-shortcode/
 * Description: Weight zone shipping rates shortcode
 * Version: 0.0.0
 * Author: bobbingwide
 * Author URI: http://www.oik-plugins.com/author/bobbingwide
 * License: GPL2
 * Text Domain: oik-wzs-rates
 * Domain Path: /languages
 
    Copyright Bobbing Wide 2017 ( email : herb@bobbingwide.com ) 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html
*/
oik_wzs_loaded();

/**
 * Function to invoke when loaded
 *
 * Only supports WooCommerce 2.6 and higher
 * We need to check the WooCommerce version
 * if WooCommerce is active.
 */
function oik_wzs_loaded() { 
	add_action( "woocommerce_init", "oik_wzs_woocommerce_init" );
}

/** 
 * Implement "woocommerce_init"
 *
 * Only enable the logic if the minimum required version of WooCommerce is active  
 */
function oik_wzs_woocommerce_init() {
	if ( oik_wzs_check_woo_version() ) {
		add_action( "oik_loaded", "oik_wzs_oik_loaded" );
	}
}

/**
 * Check the WooCommerce version against the minimum required level
 *
 * @TODO Decide whether or not to check against a defined constant instead of $version; either 'WC_VERSION' or 'WOOCOMMERCE_VERSION'.
 * And if so, whether we need to call WC(). 
 * What if someone has fiddled with the constants?
 * 
 * @param string $minimum_required Minimum required level
 * @return bool true if the minimum level is active
 */
function oik_wzs_check_woo_version( $minimum_required = "2.6" ) {
	$woocommerce = WC();
	$version = $woocommerce->version;	
	$active = version_compare( $version, $minimum_required, "ge" );
	return( $active );
}

/**
 * Implement "oik_loaded" 
 * 
 * We now believe that it's safe to respond to "oik_add_shortcodes"
 */
function oik_wzs_oik_loaded() {
	add_action( "oik_add_shortcodes", "oik_wzs_oik_add_shortcodes" );
}

/**
 * Implement "oik_add_shortcodes" for oik-wzs-rates
 * 
 */
function oik_wzs_oik_add_shortcodes() {
	bw_add_shortcode( "rates", "oik_wzs_rates", oik_path( "shortcodes/oik-wzs-rates.php", "oik-wzs-rates" ), false );
}

