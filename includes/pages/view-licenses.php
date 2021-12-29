<?php


class WPLit_View_Licenses {
    public function __construct(){
        add_shortcode( 'wplit-licenses', array($this, 'view_licenses') );

    }
    public function view_licenses($content = null){
        global $wpdb;
        global $current_user;
        wp_get_current_user();

        $user = wp_get_current_user();
        $user_id = $user->ID;

        ob_start();

        $result =  $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wplit_product_licenses
            WHERE user_id =" . $user_id,
            );

            foreach ($result as $print){ 
                $license_key = $print->license_key;
                $license_email = $print->email;
                $product_api_key = $print->product_api_key;

                $user_id = $print->user_id;
                $product_id = $print->product_id;
                     echo '<div style="display: grid;">
                        <div> Product: '
                            . get_the_title( $product_id ) .
                        '</div>
                        <div> License Key: '
                            . $license_key .
                        '</div>
                        <div> License Email: '
                            . $license_email .
                        '</div>
                        <div> Download Product: 
                            <a href="/api/wplicense-it-api/v1/get?p=' . $product_id  .'&k=' . $product_api_key .'&e=' . $license_email .'&l=' . $license_key . '">Download</a>
                        </div>
                    </div>';    
            }

           // return $output;
           $content = ob_get_contents();
            ob_end_clean();

            return $content;   
    }
}
new WPLit_View_Licenses();