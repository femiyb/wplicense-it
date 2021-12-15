<?php

    /**
     * The API handler for handling API requests from themes and plugins using
     * the license manager.
     *
     * @package    Wp_License_It_API
     */
    class Wp_License_It_API {
        /**
         * @var     License_Manager_API     The API handler
         */
        private $api;


        /**
         * Initialize the class and set its properties.
         *
         * @var      string    $plugin_name       The name of the plugin.
         * @var      string    $version    The version of this plugin.
         */
        public function __construct() {
//$this->plugin_name = $plugin_name;
           // $this->version = $version;

            add_filter('query_vars', array($this, 'add_api_query_vars'));
            add_action('parse_request', array($this, 'sniff_api_requests'));

            add_action('init', array($this, 'add_api_endpoint_rules'));
        }

        /**
         * Returns a list of variables used by the API
         *
         * @return  array    An array of query variable names.
         */
        public function get_api_vars() {
            return array( 'l',  'e', 'p', 'k' );
        }


        /**
         * Defines the query variables used by the API.
         *
         * @param $vars     array   Existing query variables from WordPress.
         * @return array    The $vars array appended with our new variables
         */
        public function add_api_query_vars( $vars ) {
            // The parameter used for checking the action used
            $vars []= '__wp_license_api';

            // Additional parameters defined by the API requests
            $api_vars = $this->get_api_vars();

            return array_merge( $vars, $api_vars );
        }


        /**
         * A sniffer function that looks for API calls and passes them to our API handler.
         */
        public function sniff_api_requests() {
            global $wp;
            if ( isset( $wp->query_vars['__wp_license_api'] ) ) {
                $action = $wp->query_vars['__wp_license_api'];
                $this->handle_request( $action, $wp->query_vars );

                exit;
            }
        }


        /**
         * Generates and returns a simple error response. Used to make sure every error
         * message uses same formatting.
         *
         * @param $msg      string  The message to be included in the error response.
         * @return array    The error response as an array that can be passed to send_response.
         */
        private function error_response( $msg ) {
            return array( 'error' => $msg );
        }

        /**
         * The permalink structure definition for API calls.
         */
        public function add_api_endpoint_rules() {
            add_rewrite_rule( 'api/wp-license-it-api/v1/(info|get|status)/?',
                'index.php?__wp_license_api=$matches[1]', 'top' );

            // If this was the first time, flush rules
           /* if ( get_option( 'wp-wp-license-it-api-rewrite-rules-version' ) != '1.3' ) {
                flush_rewrite_rules();
                update_option( 'wp-wp-license-it-api-rewrite-rules-version', '1.3' );
            }  */
        }


        /**
         * Checks the parameters and verifies the license, then forwards the request to the
         * actual API request handlers.
         *
         * @param $action_function  callable    The function (or array with class and function) to call
         * @param $params           array       The WordPress request parameters.
         * @return array            API response.
         */
        private function verify_license_and_execute( $action_function, $params ) {
            if ( ! isset( $params['p'] ) || ! isset( $params['e'] ) || ! isset( $params['l'] ) || ! isset( $params['k'] ) ) {
                return $this->error_response( 'Invalid request' );
            }

            $product_id = $params['p'];
            $email = $params['e'];
            $license_key = $params['l'];
            $product_api_key = $params['k'];


            // Find product
            $product_post = get_post($product_id,
                array (
                    'post_type' => 'wplit_product',
                    'post_status' => 'publish',
                )
            );

            if ( ! isset( $product_post ) ) {
                return $this->error_response( 'Product not found.' );
            }

            // Verify license
            if ( ! $this->verify_license( $product_post->ID, $email, $license_key, $product_api_key ) ) {
                return $this->error_response( 'Invalid license or license expired.' );
            } 

            // Call the handler function
            return call_user_func_array( $action_function, array( $product_post, $product_id, $email, $license_key, $product_api_key) );
        }

        private function verify_license_status( $action_function, $params ) {
            if ( ! isset( $params['p'] ) || ! isset( $params['e'] ) || ! isset( $params['l'] ) || ! isset( $params['k'] ) ) {
                return $this->error_response( 'Invalid request' );
            }

            $product_id = $params['p'];
            $email = $params['e'];
            $license_key = $params['l'];
            $product_api_key = $params['k'];


            // Find product
            $product_post = get_post($product_id,
                array (
                    'post_type' => 'wplit_product',
                    'post_status' => 'publish',
                )
            );

            // Verify license
            if ( ! $this->verify_license( $product_post->ID, $email, $license_key, $product_api_key ) ) {
                $status = 'inactive';
            } elseif ( $this->verify_license( $product_post->ID, $email, $license_key, $product_api_key ) ) {
                $status = 'active';
            }

            return $status;
        }


        /**
         * Looks up a license that matches the given parameters.
         *
         * @param $product_id   int     The numeric ID of the product.
         * @param $email        string  The email address attached to the license.
         * @param $license_key  string  The license key
         * @return mixed                The license data if found. Otherwise false.
         */
        private function find_license( $product_id, $email, $license_key, $product_api_key ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'wplit_product_licenses';

            $licenses = $wpdb->get_results(
                $wpdb->prepare( "SELECT * FROM $table_name WHERE product_id = %d AND email = '%s' AND license_key = '%s' AND product_api_key = '%s'",
                    $product_id, $email, $license_key, $product_api_key ), ARRAY_A );

            if ( count( $licenses ) > 0 ) {
                return $licenses[0];
            }

            return false;
        }

         /**
         * The handler for the "info" request. Checks the user's license information and
         * returns information about the product (latest version, name, update url).
         *
         * @param   $product        WP_Post   The product object
         * @param   $product_id     string    The product id (slug)
         * @param   $email          string    The email address associated with the license
         * @param   $license_key    string  The license key associated with the license
         *
         * @return  array           The API response as an array.
         */
        private function product_info( $product, $product_id, $email, $license_key, $product_api_key ) {
            // Collect all the metadata we have and return it to the caller
            //$meta = get_post_meta( $product->ID, 'wp_license_manager_product_meta', true );

            // $version = isset( $meta['version'] ) ? $meta['version'] : ''; 
            $version = get_post_meta( $product_id, 'wplit_product_version', true );
            $tested = get_post_meta( $product_id, 'wplit_tested_wp_version', true );
            $description = get_post_meta( $product_id, 'wplit_product_description', true );



       
            // $tested = isset( $meta['tested'] ) ? $meta['tested'] : '';
            // $last_updated = isset( $meta['updated'] ) ? $meta['updated'] : '';
            // $author = isset( $meta['author'] ) ? $meta['author'] : '';
            // $banner_low = isset( $meta['banner_low'] ) ? $meta['banner_low'] : '';
            // $banner_high = isset( $meta['banner_high'] ) ? $meta['banner_high'] : '';

            return array(
                'name' => $product->post_title,
                'description' => $description,
                'version' => $version,
                'tested' => $tested,
                //'author' => $author,
               // 'last_updated' => $last_updated,
               // 'banner_low' => $banner_low,
               // 'banner_high' => $banner_high,
                "package_url" => home_url( '/api/wp-license-it-api/v1/get?p=' . $product_id . '&k=' . urlencode( $product_api_key ) . '&e=' . $email . '&l=' . urlencode( $license_key ) ),
                // "description_url" => get_permalink( $product->ID ) . '#v=' . $version
            );
        }

        /**
         * The handler for the "get" request. Redirects to the file download.
         *
         * @param   $product    WP_Post     The product object
         */
        // private function get_product( $product, $product_id, $email, $license_key ) {
        //     // Get the AWS data from post meta fields
        //     $meta = get_post_meta( $product->ID, 'wp_license_manager_product_meta', true );
        //     $bucket = isset ( $meta['file_bucket'] ) ? $meta['file_bucket'] : '';
        //     $file_name = isset ( $meta['file_name'] ) ? $meta['file_name'] : '';

        //     if ( $bucket == '' || $file_name == '' ) {
        //         // No file set, return error
        //         return $this->error_response( 'No download defined for product.' );
        //     }

        //     // Use the AWS API to set up the download
        //     // This API method is called directly by WordPress so we need to adhere to its
        //     // requirements and skip the JSON. WordPress expects to receive a ZIP file...

        //     $s3_url = Wp_License_Manager_S3::get_s3_url( $bucket, $file_name );
        //     wp_redirect( $s3_url, 302 );
        // }


            /**
         * The handler function that receives the API calls and passes them on to the
         * proper handlers.
         *
         * @param $action   string  The name of the action
         * @param $params   array   Request parameters
         */
        public function handle_request( $action, $params ) {
            switch ( $action ) {
                case 'info':
                    $response = $this->verify_license_and_execute( array( $this, 'product_info' ), $params );
                    break;

                case 'get':
                    $response = $this->verify_license_and_execute( array( $this, 'get_product_file_url' ), $params );
                    break;

                case 'status':
                    $response = $this->verify_license_status( array( $this, 'product_info' ), $params );
                    break;

                default:
                    $response = $this->error_response( 'No such API action' );
                    break;
            }

            $this->send_response( $response );
        }

        /**
         * Prints out the JSON response for an API call.
         *
         * @param $response array   The response as associative array.
         */
        private function send_response( $response ) {
            echo json_encode( $response );
        }


        /**
         * Checks whether a license with the given parameters exists and is still valid.
         *
         * @param $product_id   int     The numeric ID of the product.
         * @param $email        string  The email address attached to the license.
         * @param $license_key  string  The license key.
         * @return bool                 true if license is valid. Otherwise false.
         */
        private function verify_license( $product_id, $email, $license_key, $product_api_key ) {
            $license = $this->find_license( $product_id, $email, $license_key, $product_api_key );
            if ( ! $license ) {
                return false;
            }

            $valid_until = strtotime( $license['valid_until'] );
            if ( $license['valid_until'] != '0000-00-00 00:00:00' && time() > $valid_until ) {
                return false;
            }

            return true;
        }

        public static function get_product_file_url($product, $product_id, $email, $license_key, $product_api_key){
            // $wplit_product_file = get_post_meta( $product_id, 'wplit_product_file_upload', true );
            // print_r($wplit_product_file);
            // $wplit_product_file_url = $wplit_product_file['url'];

           // $file_dir_location = get_post_meta( $product_id, 'file_dir_location', true );
            $file_dir_path = get_post_meta( $product_id, 'file_dir_path', true );



            $wpDir = ABSPATH; //Applications/MAMP/htdocs/abc/cdf/wordpress-2/
            $upload_base_dir = wp_upload_dir()['basedir']; //Applications/MAMP/htdocs/abc/cdf/wordpress-2/wp-content/uploads
          // $filePath = $upload_base_dir . 
            $filePath = $upload_base_dir . '/' . $file_dir_path; 


            // header('Content-Description: File Transfer');
            // header('Content-Type: application/octet-stream');
            // header("Cache-Control: no-cache, must-revalidate");
            // header("Expires: 0");
            // header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
            // header('Content-Length: ' . filesize($filePath));
            // header('Pragma: public');
            
            if (file_exists($filePath)) {
                header('Content-type: application/zip');
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.basename($filePath));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                ob_clean();
                flush();
                readfile($filePath);
                exit;
            }else{
                      echo "File not found";
              }


            // header('Pragma: public');
            // header('Expires: 0');
            // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            // header('Cache-Control: private', false);
            // header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
            // header("Content-Type: application/octet-stream");
            // header('Content-Transfer-Encoding: binary');
            // readfile($filePath);
            
            // print $filePath;
            // die;

            // wp_redirect($file_dir_location, 302);
            //print_r($wplit_product_file);

        }

    
        
    }

    new Wp_License_It_API();
    $api = new Wp_License_It_API();