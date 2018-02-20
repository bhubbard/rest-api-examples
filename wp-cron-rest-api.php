<?php
/*
 ----------------------------------------------------------------------------------------------------------------------
  Plugin Name: WP Cron Rest API
  Version: 0.0.1
  Plugin URI: 
  Description: A plugin to setup rest endpoints for WP Cron.
  Author: Brandon Hubbard
  Author URI: https://brandonhubbard.com
  Text Domain: wpcron-restapi
  License: GPL v3
  License URI: https://www.gnu.org/licenses/gpl-3.0.html
  
  // https://codex.wordpress.org/Category:WP-Cron_Functions
 ----------------------------------------------------------------------------------------------------------------------
*/	
	
/**
 * Rest Routes initialization class.
 */
class WP_Cron_REST {

	/**
	 * Create the rest API routes.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		
		//  view, create, delete, update cron schedule
		add_action( 'rest_api_init', function(){
			register_rest_route( 'cron/v1', 'schedules/', array(
				'methods'  => array('get', 'post', 'put', 'patch', 'delete'),
				'callback' => array( $this, 'get_cron_schedules' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'name' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'string',
					),
					'display_name' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'string',
					),
					'interval' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'int',
					),
					
				),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'cron/v1', 'schedule/(?P<id>\d+)', array(
				'methods'  => array('get', 'put', 'patch', 'delete'),
				'callback' => array( $this, 'schedules' ),
				// 'permission_callback' => array( $this, 'permission_check' ),				
		
			));
		});
		
		// view, create, delete, update, run - cron event
		add_action( 'rest_api_init', function(){
			register_rest_route( 'cron/v1', 'crons', array(
				'methods'  => array('get', 'post', 'put', 'patch', 'delete'),
				'callback' => array( $this, 'get_crons' ),
				// 'permission_callback' => array( $this, 'permission_check' ),
				'args'     => array(
					'hook_name' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'string',
					),
					'arguments' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'string',
					),
					'actions' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'int',
					),
					'next_run' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'int',
					),
					'recurrence' => array(
						'required'    => false,
						'default'     => '', 
						'description' => '.',
						'type'        => 'int',
					),
				),

			));
		});
				
	}
	

	public function get_cron_schedules() {
		
		
		$response = wp_get_schedules();
		
		return rest_ensure_response( $response );
		
	
	}
	
	public function get_crons() {
		
		$crons  = _get_cron_array();
		
		return rest_ensure_response( $crons );
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
new WP_Cron_REST();