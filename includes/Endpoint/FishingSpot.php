<?php
/**
 * WP-Reactivate
 *
 *
 * @package   WP-Reactivate
 * @author    Pangolin
 * @license   GPL-3.0
 * @link      https://gopangolin.com
 * @copyright 2017 Pangolin (Pty) Ltd
 */

namespace Pangolin\WPR\Endpoint;
use Pangolin\WPR;

/**
 * @subpackage REST_Controller
 */
class FishingSpot {
    /**
	 * Instance of this class.
	 *
	 * @since    0.8.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.8.1
	 */
	private function __construct() {
        $plugin = WPR\Plugin::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
	}

    /**
     * Set up WordPress hooks and filters
     *
     * @return void
     */
    public function do_hooks() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.8.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$instance->do_hooks();
		}

		return self::$instance;
	}

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        $version = '1';
        $namespace = $this->plugin_slug . '/v' . $version;
        $endpoint = '/fishing-spots/';

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::READABLE,
                'callback'              => array( $this, 'get_example' ),
//                'permission_callback'   => array( $this, 'example_permissions_check' ),
                'args'                  => array(),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => array( $this, 'create_fishing_spot' ),
//                'permission_callback'   => array( $this, 'example_permissions_check' ),
                'args'                  => array(

                ),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::EDITABLE,
                'callback'              => array( $this, 'update_example' ),
                'permission_callback'   => array( $this, 'example_permissions_check' ),
                'args'                  => array(),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::DELETABLE,
                'callback'              => array( $this, 'delete_example' ),
                'permission_callback'   => array( $this, 'example_permissions_check' ),
                'args'                  => array(),
            ),
        ) );

    }

    /**
     * Get FishingSpot
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function get_example( $request ) {
        $example_option = get_option( 'wpr_example_setting' );

        // Don't return false if there is no option
        if ( ! $example_option ) {
            return new \WP_REST_Response( array(
                'success' => true,
                'value' => ''
            ), 200 );
        }

        return new \WP_REST_Response( array(
            'success' => true,
            'value' => $example_option
        ), 200 );
    }

    /**
     * Create OR Update FishingSpot
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function create_fishing_spot( $request ) {
//        return var_export($request['coords'][0]);


        $postarr = array(
            post_type => 'fishingspots',
            post_title =>  $request['title'],
            post_author => $request['author'],
            post_content => $request['content'],
            post_latitude => $request['coords'][0],
            post_longitude => $request['coords'][1]

        );
        $coords = json_encode([ $postarr['post_longitude'], $postarr['post_latitude']]);

        $id = wp_insert_post( $postarr, $wp_error = true );
//        { "type": "Feature", "geometry": { "type": "Point", "coordinates": [125.6, 10.1] }, "properties": { "name": "Dinagat Islands" } }
        $geo_json_value = '{ "type": "Feature", "geometry": { "type": "Point", "coordinates":' . $coords . '}, "properties": { "name":"' . $postarr['post_title'] . '"} }';

        update_post_meta( $id, '_fishing_spot_geojson', $geo_json_value);

        return new \WP_REST_Response( array(
            'success'   => true,
            'value'     => $id,
        ), 200 );
    }

    /**
     * Delete FishingSpot
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function delete_example( $request ) {
        $deleted = delete_option( 'wpr_example_setting' );

        return new \WP_REST_Response( array(
            'success'   => $deleted,
            'value'     => ''
        ), 200 );
    }

    /**
     * Check if a given request has access to update a setting
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function example_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }
}
