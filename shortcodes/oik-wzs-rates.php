<?php // (C) Copyright Bobbing Wide 2017

/**
 * Implements [rates] shortcode for oik-weight-zone-shipping 
 */
function oik_wzs_rates( $atts=null, $content=null, $tag=null ) {

	if ( did_action( "woocommerce_init" ) ) {
	
		if ( oik_wzs_check_woo_version() ) {
			if ( did_action( "woocommerce_shipping_init" ) ) {
				h3( "Shipping Rates" );
			} else {	
				//e( "Not done woocommerce_shipping_init yet" );
				do_action( "woocommerce_shipping_init" );
				require_once( dirname( __FILE__ ) . "/class-oik-weight-zone-shipping-rates.php" );
				$oik_wzs_rates = new OIK_Weight_Zone_Shipping_Rates( $atts, $content, $tag );
				$oik_wzs_rates->rates();
			}
		} else {
			e( "[rates] shortcode not available for current version of WooCommerce" );
		}	
			
	} else {
		e( "[rates] shortcode is inactive when WooCommerce is not activated." );
	}										 
	return( bw_ret() );
}


function rates__help( $tag="rates" ) {
	return( "Display Shipping rate tables" );
}

/**
 * Syntax hook for [rates] shortcode
 */
function rates__syntax( $tag="rates" ) {
	$syntax = array( "zones" => bw_skv( "all", "<i>zones</i>", "Zones" )
								 , "address" => bw_skv( null, "<i>Country,Region,Postal code</i>", "Pre-selected destination" )
								 , "weight" => bw_skv( null, "<i>cart weight</i>", "Pre-selected cart weight" )
								 , "form" => bw_skv( "n", "y", "Display shipping calculator form" )
								 );
	return( $syntax );
}								 



 
