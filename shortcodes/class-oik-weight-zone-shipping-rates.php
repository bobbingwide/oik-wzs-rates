<?php // (C) Copyright Bobbing Wide 2017


/**
 * Class: OIK_Weight_Zone_Shipping_Rates
 * Implements the [rates] shortcode for WooCommerce 2.6+
 */
 
class OIK_Weight_Zone_Shipping_Rates {
	public $atts;
	public $content;
	public $tag;
	public $shipping_zones;

	function __construct( $atts, $content, $tag ) {
		$this->atts = $atts;
		$this->content = $content;
		$this->tag = $tag;
	
	}
	
	function rates() {
		$zone_count = $this->load_shipping_zones();
		e( "Zones: " . $zone_count );
		$this->display_zones();
	}
	
	/**
	 * Display the shipping zones
	 */
	function display_zones( ) {
		foreach ( $this->shipping_zones as $shipping_zone => $data ) {
			$this->display_zone( $shipping_zone );
			$this->display_methods( $data['shipping_methods'] );
		}
		$rotw = $this->display_zone( 0 );
		$this->display_methods( $rotw->get_shipping_methods( true ) );
	}
	
	/**
	 * Display the weight shipping methods for the zone
	 * 
	 * @param integer $shipping_zone The shipping zone ID. 0 for Rest of the world
	 * @return object The shipping zone object
	 */
	function display_zone( $shipping_zone=0 ) {
		$zone = new WC_Shipping_Zone( $shipping_zone );
		//e( $zone->get_zone_id() );
		h3( $zone->get_zone_name() );
		p( $zone->get_formatted_location() );
		return( $zone );
	}
	
	/**
	 * Displays the shipping rates for each Weight Zone shipping method
	 * 
	 * @param array $shipping_methods Active methods for the zone.
	 */
	function display_methods( $shipping_methods ) {
		foreach ( $shipping_methods as $instance_id => $shipping_method ) {
			if ( "oik_weight_zone_shipping" == $shipping_method->id ) {
				p( $shipping_method->title );
				$rates = $shipping_method->get_rates();
				$this->display_rates( $rates );
			}	
		}
	}
	
	/**
	 * Load libs
	 * 
	 * We need to load "bobbforms" in order to be able to use bw_tablerow.
	 * 
	 */
	function load_libs() {
		$bobbforms = oik_require_lib( "bobbforms" );
		bw_trace2( $bobbforms, "bobbforms", false );
		return( $bobbforms );
	}
	
	/**
	 * Displays the shipping rates for the method
	 * 
	 * @param array $rates 
	 
	 */
	function display_rates( $rates ) {
		$this->load_libs();
		//e( print_r( $rates, true ) );
		stag( "table" );
		stag( "thead" );
		bw_tablerow( array( "Max weight", "Cost" ,"Method title" ), "tr", "th" );
		etag( "thead" );
		foreach ( $rates as $rate ) {
			bw_trace2( $rate, "rate -3/4?", false );
			$rate = $this->format_rate( $rate );
			bw_tablerow( $rate );
		}
		etag( "table" );
		
	}
	
	/**
	 * Format rate fields for front-end display
	 *
	 * @param array $rate
	 * @return array formatted rates
	 */
	function format_rate( $rate ) {
		if ( isset( $rate[0] ) ) {
			$rate[0] = wc_format_localized_decimal( $rate[0] );
		} else {
			$rate[0] = __( "Missing value for weight", "oik-wzs-rates" );
		}
		if ( isset( $rate[1] ) ) {
			if ( is_numeric( $rate[1] ) ) {
				$rate[1] = wc_price( $rate[1] );
			} else {
				$rate[1] = stripslashes( $rate[1] );
			}
		} else {
			$rate[1] = __( "Missing value for rate", "oik-wzs-rates" );
		}
		
		if ( isset( $rate[2] ) ) {
			$rate[2] = stripslashes( $rate[2] );
		}
			
		return $rate;
	}
	
	/**
	 * Load the shipping zones
	 * 
	 * @return integer count of shipping zones - does not include Rest of the World
	 */
	function load_shipping_zones() {
		$this->shipping_zones = WC_Shipping_Zones::get_zones();
		bw_trace2( $this->shipping_zones, "Shipping Zones", false );
		return( count( $this->shipping_zones ) );
	}
	
	/**
	 * Load our shipping methods
	 * 
	 * @return integer count of shipping methods
	 */
	function load_shipping_methods() {
		$count = 0;
		$this->shipping_methods = array();
		if ( $this->shipping_zones ) {
			foreach ( $this->shipping_zones as $shipping_zone ) {
				$zone_id = $shipping_zone['zone_id'];
				$zone = new WC_Shipping_Zone( $zone_id );
				$methods = $zone->get_shipping_methods();
				bw_trace2( $methods, "methods", false );
				foreach ( $methods as $method ) {
					if ( is_object( $method ) && ( $method instanceof OIK_Weight_Zone_Shipping_Pro ) ) {
						$this->shipping_methods[] = $method;
						$count++;
					}
				}
			}
		}
		return( $count );
	}

}
