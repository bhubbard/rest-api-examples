<?php
/*
 ----------------------------------------------------------------------------------------------------------------------
  Plugin Name: WP Options Rest API
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
class WP_Options_REST_API {

	/**
	 * Create the rest API routes.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'options/v1', 'add', array(
				'methods'  => array( 'get', 'post' ),
				'callback' => array( $this, 'add_a_option' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'name' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Array or comma-separated list of email addresses to send message.',
						'type'        => 'string',
					),
					'value' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Email subject',
						'type'        => 'string',
					),
					'deprecated' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '',
						'type'        => 'string',
					),
					'autoload' => array(
						'required'    => false,
						'default'     => 'yes', 
						'description' => 'Should this option be automatically loaded.',
						'type'        => 'string',
					),
				),
			));
		});
		
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'options/v1', 'get', array(
				'methods'  => array( 'get' ),
				'callback' => array( $this, 'get_a_option' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'option' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Array or comma-separated list of email addresses to send message.',
						'type'        => 'string',
					),
					'default' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Email subject',
						'type'        => 'string',
					),
				),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'options/v1', 'delete', array(
				'methods'  => array( 'delete' ),
				'callback' => array( $this, 'get_a_option' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'option' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Array or comma-separated list of email addresses to send message.',
						'type'        => 'string',
					),
					'default' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Email subject',
						'type'        => 'string',
					),
				),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'options/v1', 'update', array(
				'methods'  => array( 'POST', 'PUT' ),
				'callback' => array( $this, 'get_a_option' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'option' => array(
						'required'    => true,
						'default'     => '', 
						'description' => '',
						'type'        => 'string',
					),
					'default' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '',
						'type'        => 'string',
					),
				),
			));
		});



	}
	
	
	/**
	 * add a option
	 * 
	 * @access public
	 * @param WP_REST_Request $request
	 * @return void
	 */
	public function add_a_option( WP_REST_Request $request ) {
		
		$option = $request['option'];
		$value = $request['value'];
		$deprecated = $request['deprecated'];
		$autoload = $request['autoload'];
			
		$response = add_option( $option, $value, $deprecated, $autoload );
		
		return rest_ensure_response( $response );
		
	
	}
	
	public function get_a_option( WP_REST_Request $request ) {
		
		$option = $request['option'];
		
		$response = get_option( $option, $default = false );
		
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
new WP_Options_REST_API();
