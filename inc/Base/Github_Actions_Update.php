<?php
/**
 * @package GithubActions
*/

namespace GAP\Base;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Github_Actions_Update' ) ) {

    class Github_Actions_Update {

        public static $plugin_name;
        public static $plugin_version;
        public static $transient_key;
        public static $transient_allowed;

        public static function register() {

            self::$plugin_name   = GITHUB_ACTIONS_PLUGIN_NAME;
            self::$plugin_version       = GITHUB_ACTIONS_PLUGIN_VERSION;
            self::$transient_key     = 'github_actions_updater';
            self::$transient_allowed = false;

            add_filter( 'plugins_api', array( __CLASS__, 'plugin_info' ), 20, 3 );
            add_filter( 'site_transient_update_plugins', array( __CLASS__, 'plugin_update' ) );
            add_action( 'upgrader_process_complete', array( __CLASS__, 'plugin_purge' ), 10, 2 );

        }

        private static function request() {

            $remote = get_transient( self::$transient_key );

            if ( false === $remote || ! self::$transient_allowed ) {

                $remote = wp_remote_get(
                    'https://raw.githubusercontent.com/Wyllymk/Github-Actions-Plugin/main/info.json',
                    [
                        'timeout' => 10,
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                    ]
                );

                if ( is_wp_error( $remote ) || 200 !== wp_remote_retrieve_response_code( $remote ) || empty( wp_remote_retrieve_body( $remote ) ) ) {
                    return false;
                }

                set_transient( self::$transient_key, $remote, DAY_IN_SECONDS );

            }

            $remote = json_decode( wp_remote_retrieve_body( $remote ) );

            return $remote;

        }

        public static function plugin_info( $response, $action, $args ) {

            // do nothing if you're not getting plugin information right now
            if ( 'plugin_information' !== $action ) {
                return $response;
            }

            // do nothing if it is not our plugin
            if ( empty( $args->slug ) || self::$plugin_name !== $args->slug ) {
                return $response;
            }

            // get updates
            $remote = self::request();

            if ( ! $remote ) {
                return $response;
            }

            $response = new \stdClass();

            $response->name           = $remote->name;
            $response->slug           = $remote->slug;
            $response->version        = $remote->version;
            $response->tested         = $remote->tested;
            $response->requires       = $remote->requires;
            $response->author         = $remote->author;
            $response->author_profile = $remote->author_profile;
            $response->download_link  = $remote->download_url;
            $response->trunk          = $remote->download_url;
            $response->requires_php   = $remote->requires_php;
            $response->last_updated   = $remote->last_updated;

            $response->sections = [
                'description'  => $remote->sections->description,
                'installation' => $remote->sections->installation,
                'changelog'    => $remote->sections->changelog,
            ];

            if ( ! empty( $remote->banners ) ) {
                $response->banners = [
                    'low'  => $remote->banners->low,
                    'high' => $remote->banners->high,
                ];
            }

            return $response;

        }

        public static function plugin_update( $transient ) {

            if ( empty( $transient->checked ) ) {
                return $transient;
            }

            $remote = self::request();

            if ( $remote && version_compare( self::$plugin_version, $remote->version, '<' ) && version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' ) && version_compare( $remote->requires_php, PHP_VERSION, '<' ) ) {
                $response              = new \stdClass();
                $response->slug        = self::$plugin_name;
                $response->plugin      = self::$plugin_name . '/' . self::$plugin_name . '.php';
                $response->new_version = $remote->version;
                $response->tested      = $remote->tested;
                $response->package     = $remote->download_url;

                $transient->response[ $response->plugin ] = $response;

            }

            return $transient;

        }

        public static function plugin_purge( $upgrader, $options ) {

            if ( self::$transient_allowed && 'update' === $options['action'] && 'plugin' === $options['type'] ) {
                // just clean the cache when a new plugin version is installed
                delete_transient( self::$transient_key );
            }

        }

    }

}