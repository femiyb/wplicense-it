<?php

class WP_License_It_Protect_File {

    public function __construct(){

    }


     /**
     * @usgae Block http access to a dir
     * @param $upload_dir
     */
    function blockHTTPAccess($upload_dir, $fileType = '*')
    {
        $cont = "RewriteEngine On\r\n<Files {$fileType}>\r\nDeny from all\r\n</Files>\r\n";
        @file_put_contents($upload_dir . '/.htaccess', $cont);
        //@file_put_contents($upload_dir . '/web.config', $_cont);
    }



}




