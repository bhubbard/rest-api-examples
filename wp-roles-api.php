<?php
/*
 ----------------------------------------------------------------------------------------------------------------------
  Plugin Name: WP Roles Rest API
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
 * WP_Roles_API.
 */
class WP_Roles_API {

    /**
     * Create the rest API routes.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
    }

    /**
     * Register Rest Routes.
     *
     * @access public
     * @return void
     */
    public function register_rest_routes() {
        register_rest_route( 'roles/v1', '/roles', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_roles' ),
            'permission_callback' => array( $this, 'permission_check' ),
        ) );

        register_rest_route( 'roles/v1', '/roles', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'add_role' ),
            'args'     => array(
                'role' => array(
                    'required'    => true,
                    'default'     => '',
                    'description' => '',
                    'type'        => 'string',
                ),
                'display_name' => array(
                    'required'    => true,
                    'default'     => '',
                    'description' => '',
                    'type'        => 'string',
                ),
                'capabilities' => array(
                    'required'    => false,
                    'default'     => '',
                    'description' => '',
                    'type'        => 'array',
                ),
            ),
            'permission_callback' => array( $this, 'permission_check' ),
        ) );

        register_rest_route( 'roles/v1', '/roles/(?P<id>\d+)', array(
            'methods'  => 'DELETE',
            'callback' => array( $this, 'remove_role' ),
            'permission_callback' => array( $this, 'permission_check' ),
        ) );

        register_rest_route( 'roles/v1', '/roles/(?P<id>\d+)', array(
            'methods'  => 'PUT',
            'callback' => array( $this, 'update_role' ),
            'permission_callback' => array( $this, 'permission_check' ),
        ) );
    }

    /**
     * GET Roles.
     *
     * @access public
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_roles( $request ) {
        $roles = get_roles(); // Replace with your logic to fetch roles
        return rest_ensure_response( $roles );
    }

    /**
     * Add Role.
     *
     * @access public
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function add_role( $request ) {
        $role = $request->get_param( 'role' );
        $display_name = $request->get_param( 'display_name' );
        $capabilities = $request->get_param( 'capabilities' );

        $role = add_role( $role, $display_name, $capabilities );

        return rest_ensure_response( $role );
    }

    /**
     * Delete Role.
     *
     * @access public
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function remove_role( $request ) {
        $role = $request->get_param( 'role' );
        $removed_role = remove_role( $role );
        return rest_ensure_response( $removed_role );
    }

    /**
     * Update Role.
     *
     * @access public
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update_role( $request ) {
        // Update Role logic goes here
        return rest_ensure_response();
    }

    public function add_role_to_user( $request ) {

    }

    /**
     * Check whether the function is allowed to be run.
     *
     * Must have either capabilities to enact action, or a valid nonce.
     *
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function permission_check( $request ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_Error( 'forbidden', 'You are not allowed to do that.', array( 'status' => 403 ) );
        }
        return true;
    }
}

new WP_Roles_API();
