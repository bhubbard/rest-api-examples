<?php
/*
 ----------------------------------------------------------------------------------------------------------------------
  Plugin Name: WP Object Cache Rest API
  Version: 0.0.1
  Plugin URI: 
  Description: A plugin to setup rest endpoints for WP Mail.
  Author: Brandon Hubbard
  Author URI: https://brandonhubbard.com
  Text Domain: wpmail-restapi
  License: GPL v3
  License URI: https://www.gnu.org/licenses/gpl-3.0.html
  
  // https://codex.wordpress.org/Class_Reference/WP_Object_Cache
 ----------------------------------------------------------------------------------------------------------------------
*/	
	
/**
 * Rest Routes initialization class.
 */
class WP_Object_Cache_REST_API {

	/**
	 * Create the rest API routes.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'objectcache/v1', 'get', array(
				'methods'  => array( 'get' ),
				'callback' => array( $this, 'get_object_cache' ),
				/*
				'permission_callback' => array( $this, 'permission_check' ),
				*/
				'args'     => array(
					'key' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Cache Key.',
						'type'        => 'string',
					),
					'group' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Cache Group.',
						'type'        => 'string',
					),
				),
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'objectcache/v1', 'flush', array(
				'methods'  => array( 'get', 'post' ),
				'callback' => array( $this, 'cache_flush' ),
				/*
				'permission_callback' => array( $this, 'permission_check' ),
				*/
			));
		});
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'objectcache/v1', 'add', array(
				'methods'  => array( 'get', 'post' ),
				'callback' => array( $this, 'cache_add' ),
				/*
				'permission_callback' => array( $this, 'permission_check' ),
				*/
				'args'     => array(
					'key' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Cache Key.',
						'type'        => 'string',
					),
					'data' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Cache Data.',
						'type'        => 'string',
					),
					'group' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Cache Group.',
						'type'        => 'string',
					),
					'expire' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Cache Expire.',
						'type'        => 'string',
					),
				),

			));
		});
		
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'objectcache/v1', 'set', array(
				'methods'  => array( 'get', 'post' ),
				'callback' => array( $this, 'cache_set' ),
				/*
				'permission_callback' => array( $this, 'permission_check' ),
				*/
				'args'     => array(
					'key' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Cache Key.',
						'type'        => 'string',
					),
					'data' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Cache Data.',
						'type'        => 'string',
					),
					'group' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Cache Group.',
						'type'        => 'string',
					),
					'expire' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Cache Expire.',
						'type'        => 'string',
					),
				),

			));
		});
		
		
		
		add_action( 'rest_api_init', function(){
			register_rest_route( 'objectcache/v1', 'replace', array(
				'methods'  => array( 'get', 'post' ),
				'callback' => array( $this, 'cache_set' ),
				/*
				'permission_callback' => array( $this, 'permission_check' ),
				*/
				'args'     => array(
					'key' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Cache Key.',
						'type'        => 'string',
					),
					'data' => array(
						'required'    => true,
						'default'     => '', 
						'description' => 'Cache Data.',
						'type'        => 'string',
					),
					'group' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Cache Group.',
						'type'        => 'string',
					),
					'expire' => array(
						'required'    => false,
						'default'     => '', 
						'description' => 'Cache Expire.',
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
	public function get_object_cache( WP_REST_Request $request ) {
		
		$key = $request['key'];
		$group = $request['group'];
		$force = $request['force'];
		$found = $request['found'];

		$response = wp_cache_get( $key, $group = '', $force = true, $found = null );
		
		return rest_ensure_response( $response );
		
	
	}
	
	
	public function cache_flush() {
		
		$response = wp_cache_flush();
		
		return rest_ensure_response( $response );
		
	}
	
	public function cache_add(  WP_REST_Request $request ) {
		
		$key = $request['key'];
		$data = $request['data'];
		$group = $request['group'];
		$expire = $request['expire'];
		
		$response = wp_cache_add( $key, $data, $group, $expire );
		
		$details = wp_cache_get( $key, $group, $force = false, $found = null );

		
		return rest_ensure_response( array( "Added" => $response, "Key" => $key, "Group" => $group, "Expire" => $expire ) );
		
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
new WP_Object_Cache_REST_API();