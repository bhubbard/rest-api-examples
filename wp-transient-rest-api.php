<?php
/*
 ----------------------------------------------------------------------------------------------------------------------
  Plugin Name: WP Transiet Rest API
  Version: 0.0.1
  Plugin URI: 
  Description: A plugin to setup rest endpoints for WP Mail.
  Author: Brandon Hubbard
  Author URI: https://brandonhubbard.com
  Text Domain: wpmail-restapi
  License: GPL v3
  License URI: https://www.gnu.org/licenses/gpl-3.0.html
  
  // https://codex.wordpress.org/Category:WP-Cron_Functions
 ----------------------------------------------------------------------------------------------------------------------
*/	
	
/**
 * Rest Routes initialization class.
 */
class WP_Transiet_REST_API {

	/**
	 * Create the rest API routes.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'transients/v1', 'get', array(
				'methods'  => array( 'get', 'post' ),
				'callback' => array( $this, 'get_transient_details' ),
				/*
				'permission_callback' => array( $this, 'permission_check' ),
				*/
				'args'     => array(
					'name' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Name of the transient.',
						'type'        => 'string',
					),
				),
			));
		});
	}
	
	
	/**
	 * send_email function.
	 * 
	 * @access public
	 * @param WP_REST_Request $request
	 * @return void
	 */
	public function get_transient_details( WP_REST_Request $request ) {
		
		$name = $request['name'];

		$response = get_transient( $name );
		
		return rest_ensure_response( $response );
		
	
	}



	/**
	 * Check whether the function is allowed to be run.
	 *
	 * Must have either capabilities to enact action, or a valid nonce.
	 *
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function permission_check( $data ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'forbidden', 'You are not allowed to do that.', array( 'status' => 403 ) );
		}
		return true;
	}
	
}	
new WP_Transiet_REST_API();