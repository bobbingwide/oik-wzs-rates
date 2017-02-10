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
	 * Displays the shipping rates for the method
	 * 
	 * @param array $rates 
	 
	 */
	function display_rates( $rates ) {
		//e( print_r( $rates, true ) );
		stag( "table" );
		stag( "thead" );
		bw_tablerow( array( "Max weight", "Cost" ,"Method title" ), "tr", "th" );
		etag( "thead" );
		foreach ( $rates as $rate ) {
			bw_tablerow( $rate );
		}
		etag( "table" );
		
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
