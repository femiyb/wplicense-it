<?php

defined( 'ABSPATH' ) || exit;

class WP_License_It_Product_Admin {
    private static $_instance = null;
    
    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    public function __construct() {
        add_action( 'save_post', array( $this, 'save_metabox' ), 1, 2 );
        add_action ('add_meta_boxes', array ($this, 'wplit_add_metabox' ));
        add_action('post_edit_form_tag', array ($this, 'update_edit_form'));

    }


      // Add Meta Boxes
	public function wplit_add_metabox() {  
        add_meta_box(
            'wp_license_it_product_info',
            __( 'Product License Information', 'wp-license-it' ),
            array( $this, 'wplit_render_metabox' ),
            'wplit_product',
            'advanced'
        );
    }


    public function wplit_render_metabox( $post, $args ) {

        // Add an nonce field so we can check for it later.
		wp_nonce_field( 'wplit_inner_custom_box', 'wplit_inner_custom_box_nonce' );
        $wplit_product_name = get_post_meta( $post->ID, 'wplit_product_name', true );
        $wplit_product_version = get_post_meta( $post->ID, 'wplit_product_version', true );

        $wplit_product_file = get_post_meta( $post->ID, 'wplit_product_file_upload', true );
        
        $wplit_product_api_key = get_post_meta( $post->ID, 'wplit_product_api_key', true );

        $wplit_tested_wp_version = get_post_meta( $post->ID, 'wplit_tested_wp_version', true );

        $wplit_required_wp_version = get_post_meta( $post->ID, 'wplit_required_wp_version', true );

        $wplit_product_description = get_post_meta( $post->ID, 'wplit_product_description', true );

        $wplit_product_price = get_post_meta( $post->ID, 'wplit_product_price', true );

        $file_dir_location = get_post_meta( $post->ID, 'file_dir_location', true );

        $file_dir_path = get_post_meta( $post->ID, 'file_dir_path', true );

        $file_name = get_post_meta( $post->ID, 'file_name', true );

        $wplit_product_banner_url = get_post_meta( $post->ID, 'wplit_product_banner_url', true );


        $wplit_product_logo_url = get_post_meta( $post->ID, 'wplit_product_logo_url', true );


        ?>
        <p>
            <label for="wplit_product_api_key">
            <h3> <?php _e( 'Plugin/Theme API Key', 'wp-license-it' ); ?></h3>
            <input type="text" id="wplit_product_api_key" name="wplit_product_api_key" value="<?php echo esc_attr( $wplit_product_api_key ); ?>" size="25" />
            </label> 
        </p>

        <p>
            <label for="wplit_product_name">
            <h3> <?php _e( 'Plugin/Theme Name', 'wp-license-it' ); ?></h3>
            <input type="text" id="wplit_product_name" name="wplit_product_name" value="<?php echo esc_attr( $wplit_product_name ); ?>" size="25" />
            </label> 
        </p>

        <p>
            <label for="wplit_tested_wp_version">
            <h3> <?php _e( 'Tested with WP Version', 'wp-license-it' ); ?></h3>
            <input type="text" id="wplit_tested_wp_version" name="wplit_tested_wp_version" value="<?php echo esc_attr( $wplit_tested_wp_version ); ?>" size="25" />
            </label> 
        </p>

        <p>
            <label for="wplit_required_wp_version">
            <h3> <?php _e( 'Required WP Version', 'wp-license-it' ); ?></h3>
            <input type="text" id="wplit_required_wp_version" name="wplit_required_wp_version" value="<?php echo esc_attr( $wplit_required_wp_version ); ?>" size="25" />
            </label> 
        </p>

        <p>
            <label for="wplit_product_description">
            <h3> <?php _e( 'Plugin/Theme Description', 'wp-license-it' ); ?></h3>
            <input type="text" id="wplit_product_description" name="wplit_product_description" value="<?php echo esc_attr( $wplit_product_description ); ?>" size="25" />
            </label> 
        </p>

        <p>
            <label for="wplit_product_version">
            <h3> <?php _e( 'Plugin/Theme Version', 'wp-license-it' ); ?></h3>
            <input type="text" id="wplit_product_version" name="wplit_product_version" value="<?php echo esc_attr( $wplit_product_version ); ?>" size="25" />
            </label> 
        </p>

        <p>
            <label for="wplit_product_price">
            <h3> <?php _e( 'Plugin/Theme Price (in USD)', 'wp-license-it' ); ?></h3>
            <input type="number" step="0.01" id="wplit_product_price" name="wplit_product_price" value="<?php echo esc_attr( $wplit_product_price ); ?>" size="25" />
            </label> 
        </p>

        <p>
            <label for="wplit_product_file_upload">
                <h3>Upload Plugin/Theme File</h3>
                <input type="file" id="wplit_product_file_upload" name="wplit_product_file_upload" value="" size="25" /> <br />
                <?php if ($file_name) { echo 'File Name: ' . $file_name; }?>
            </label> 
        </p>

        <p>
            <label for="wplit_product_logo">
                <h3>Plugin/Theme Logo</h3>
                <input type="file" id="wplit_product_logo" name="wplit_product_logo" value="" size="25" /> <br />
                <?php if ($wplit_product_logo_url) { echo '<img src="'. $wplit_product_logo_url . '" style="width: 50px;">';} ?>
            </label>
        </p>

        <p>
            <label for="wplit_product_banner">
                <h3>Plugin/Theme Banner</h3>
                <input type="file" id="wplit_product_banner" name="wplit_product_banner" value="" size="25" /> <br />
                <?php if ($wplit_product_banner_url) { echo '<img src="'. $wplit_product_banner_url . '" style="width: 200px;">';} ?>
            </label>
        </p>
        <?php

    }

    function update_edit_form() {
        echo ' enctype="multipart/form-data"'; 
    } // end update_edit_form
         

    public function save_metabox( $post_id, $post ) {
        if ( ! isset( $_POST['wplit_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
        
        $nonce = $_POST['wplit_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'wplit_inner_custom_box' ) ) {
            return $post_id;
        }
        
        /*
        * If this is an autosave, our form has not been submitted,
        * so we don't want to do anything.
        */
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (isset($_POST['wplit_product_name'])){
            $wplit_product_name = sanitize_text_field( $_POST['wplit_product_name'] );
        }

        if (isset($_POST['wplit_product_name'])){
            update_post_meta( $post_id, 'wplit_product_name', $wplit_product_name );
        }

        if (isset($_POST['wplit_tested_wp_version'])){
            $wplit_tested_wp_version = sanitize_text_field( $_POST['wplit_tested_wp_version'] );
        }

        if (isset($_POST['wplit_tested_wp_version'])){
            update_post_meta( $post_id, 'wplit_tested_wp_version', $wplit_tested_wp_version );
        }

        if (isset($_POST['wplit_required_wp_version'])){
            $wplit_required_wp_version = sanitize_text_field( $_POST['wplit_required_wp_version'] );
        }

        if (isset($_POST['wplit_required_wp_version'])){
            update_post_meta( $post_id, 'wplit_required_wp_version', $wplit_required_wp_version );
        }

        if (isset($_POST['wplit_product_price'])){
            $wplit_product_price = sanitize_text_field( $_POST['wplit_product_price'] );
        }

        if (isset($_POST['wplit_product_price'])){
            update_post_meta( $post_id, 'wplit_product_price', $wplit_product_price );
        }


        if (isset($_POST['wplit_product_description'])){
            $wplit_product_description = sanitize_text_field( $_POST['wplit_product_description'] );
        }

        if (isset($_POST['wplit_product_description'])){
            update_post_meta( $post_id, 'wplit_product_description', $wplit_product_description );
        }

        if (isset($_POST['wplit_product_version'])){
            $wplit_product_version = sanitize_text_field( $_POST['wplit_product_version'] );
        }

        if (isset($_POST['wplit_product_version'])){
            update_post_meta( $post_id, 'wplit_product_version', $wplit_product_version );
        }

        if (isset($_POST['wplit_product_api_key'])){
            $wplit_product_api_key = sanitize_text_field( $_POST['wplit_product_api_key'] );
        }

        if (empty($_POST['wplit_product_api_key'])){
            $wplit_product_api_key = wp_generate_password(24, false, false);
        }

        if (isset($_POST['wplit_product_name'])){
            update_post_meta( $post_id, 'wplit_product_api_key', $wplit_product_api_key );
        }

        // Move Uploaded Files to WPLit Files Folder
        global $wp_filesystem;
        WP_Filesystem();
                            
        // Create File Path
        $content_directory = $wp_filesystem->wp_content_dir() . 'uploads/';
        $uploads_content_url = content_url() . '/uploads/';

        $wp_filesystem->mkdir( $content_directory . 'wplit-files' );
        $wp_files_directory = $content_directory . 'wplit-files/';

        // Create File Path for product folder
        $product_slug = $post->post_name;
        $wp_files_directory_slug = $content_directory . 'wplit-files/' . $product_slug . '/';
        $wplit_files_directory_url = $uploads_content_url . 'wplit-files/' . $product_slug . '/';

        if (! is_dir($wp_files_directory_slug)){

        mkdir( $wp_files_directory_slug, 0755 );
        }

        // Create File For for product version
        $wp_files_directory_path = $wp_files_directory_slug  . 'v' . $wplit_product_version . '/';
        $wplit_files_version_url = $wplit_files_directory_url . 'v' . $wplit_product_version . '/';

        if(! is_dir($wp_files_directory_path)) {
        mkdir( $wp_files_directory_path, 0755 );
        }

        // Create Product Logo Folders and move files after upload
        if(!empty($_FILES['wplit_product_logo']['name'])){
            $supported_file_type = array('image/png');

            $file_type = wp_check_filetype(basename($_FILES['wplit_product_logo']['name']));
            $upload_file_type = $file_type['type'];

            if(in_array($upload_file_type, $supported_file_type)) {
                $upload = wp_upload_bits($_FILES['wplit_product_logo']['name'], null, file_get_contents($_FILES['wplit_product_logo']));

                if(isset($upload['error']) && $upload['error'] != 0){
                    wp_die('There was an error uploading the logo' . $upload['error'] . '.');
                } else {
                    add_post_meta($post_id, 'wplit_product_logo', $upload);

                    update_post_meta($post_id, 'wplit_product_logo', $upload);
                }

                $thefile = $_FILES['wplit_product_logo']['name'];
                $tmp_name = $_FILES['wplit_product_logo']['tmp_name'];

                if( $wp_files_directory_slug ) {

                    $wplit_files_logo_directory = $wp_files_directory_slug . 'logo/';
                }

                if( $wplit_files_directory_url ) {

                    $wplit_files_logo_url = $wplit_files_directory_url . 'logo/';
                }


                if (! is_dir($wplit_files_logo_directory)){
    
                mkdir( $wplit_files_logo_directory, 0755 );
                }

                if( $wplit_files_logo_directory ) {
                    move_uploaded_file($tmp_name, $wplit_files_logo_directory . $thefile);
                } else {
                    wp_die('There was an error uploading the product to the directory.');
                }

                $wplit_product_logo_url = $wplit_files_logo_url . $thefile;
                update_post_meta( $post_id, 'wplit_product_logo_url', $wplit_product_logo_url ); 

            } else {
                wp_die('Incorrect File Format');
            }

        }


        // Create Product Banner Folders and move files after upload
        if(!empty($_FILES['wplit_product_banner']['name'])){
            $supported_file_type = array('image/png');

            $file_type = wp_check_filetype(basename($_FILES['wplit_product_banner']['name']));
            $upload_file_type = $file_type['type'];

            if(in_array($upload_file_type, $supported_file_type)) {
                $upload = wp_upload_bits($_FILES['wplit_product_banner']['name'], null, file_get_contents($_FILES['wplit_product_logo']));

                if(isset($upload['error']) && $upload['error'] != 0){
                    wp_die('There was an error uploading the logo' . $upload['error'] . '.');
                } else {
                    add_post_meta($post_id, 'wplit_product_banner', $upload);

                    update_post_meta($post_id, 'wplit_product_banner', $upload);
                }

                $thefile = $_FILES['wplit_product_banner']['name'];
                $tmp_name = $_FILES['wplit_product_banner']['tmp_name'];

                if( $wp_files_directory_slug ) {

                $wplit_files_banner_directory = $wp_files_directory_slug . 'banner/';
                }

                if( $wplit_files_directory_url ) {

                    $wplit_files_banner_url = $wplit_files_directory_url . 'banner/';
                }

                if (! is_dir($wplit_files_banner_directory)){
    
                mkdir( $wplit_files_banner_directory, 0755 );
                }

                if( $wplit_files_banner_directory ) {
                    move_uploaded_file($tmp_name, $wplit_files_banner_directory . $thefile);
                } else {
                    wp_die('There was an error uploading the product to the directory.');
                }

                $wplit_product_banner_url = $wplit_files_banner_url . $thefile;
                update_post_meta( $post_id, 'wplit_product_banner_url', $wplit_product_banner_url );                   


            } else {
                wp_die('Incorrect File Format');
            }

        }

        // Create Plugin Theme folders
        if(!empty($_FILES['wplit_product_file_upload']['name'])){
            $supported_file_type = array('application/zip');

            $file_type = wp_check_filetype(basename($_FILES['wplit_product_file_upload']['name']));
            $upload_file_type = $file_type['type'];

            if(in_array($upload_file_type, $supported_file_type)) {
                $upload = wp_upload_bits($_FILES['wplit_product_file_upload']['name'], null, file_get_contents($_FILES['wplit_product_file_upload']));

                if(isset($upload['error']) && $upload['error'] != 0){
                    wp_die('There was an error uploading the product' . $upload['error'] . '.');
                } else {
                    add_post_meta($post_id, 'wplit_product_file_upload', $upload);

                    update_post_meta($post_id, 'wplit_product_file_upload', $upload);
                }

                $target_dir_location = $wp_files_directory_path;

                $thefile = $_FILES['wplit_product_file_upload']['name'];
                $tmp_name = $_FILES['wplit_product_file_upload']['tmp_name'];

                if( $target_dir_location ) {
                    move_uploaded_file($tmp_name, $target_dir_location . $thefile);
                } else {
                    wp_die('There was an error uploading the product to the directory.');
                }
                
                $file_dir_location = $wplit_files_version_url . $thefile;
                $file_dir_path = 'wplit-files/' .$product_slug. '/v' .$wplit_product_version. '/' . $thefile;

                update_post_meta( $post_id, 'file_dir_location', $file_dir_location );
                update_post_meta( $post_id, 'file_dir_path', $file_dir_path );
                update_post_meta( $post_id, 'file_name', $thefile );                   

            } else {
                wp_die('Incorrect File Format');
            }
        }
    }
}

    new WP_License_It_Product_Admin();

