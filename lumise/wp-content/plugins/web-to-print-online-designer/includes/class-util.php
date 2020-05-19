<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Necessary I/O functions
 * 
 */
class Nbdesigner_IO {
    public function __construct() {
        //TODO
    }
    /**
     * Get all images in folder by level
     * 
     * @param string $path path folder
     * @param int $level level scan dir
     * @return array Array path images in folder
     */
    public static function get_list_images($path, $level = 100){
        $list = array();
        $_list = self::get_list_files($path, $level);
        $list = preg_grep('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $_list);
        return $list;        
    }
    public static function get_list_files_by_type($path, $level = 100, $type){
        $list = array();
        $_list = self::get_list_files($path, $level);
        $list = preg_grep('/\.(' . $type . ')(?:[\?\#].*)?$/i', $_list);
        return $list;        
    }
    public static function get_list_files($folder = '', $levels = 100) {
        if (empty($folder))
            return false;
        if (!$levels)
            return false;        
        $files = array();
        if ($dir = @opendir($folder)) {
            while (($file = readdir($dir) ) !== false) {
                if (in_array($file, array('.', '..')))
                    continue;
                if (is_dir($folder . '/' . $file)) {
                    $files2 = self::get_list_files($folder . '/' . $file, $levels - 1);
                    if ($files2)
                        $files = array_merge($files, $files2);
                    else
                        $files[] = $folder . '/' . $file . '/';
                } else {
                    $files[] = $folder . '/' . $file;
                }
            }
        }
        @closedir($dir);
        return $files;
    }
    public static function get_list_folder($folder = '', $levels = 100){
        if (empty($folder)) return false;    
        if (!$levels) return false;          
        $folders = array();
        if ($dir = @opendir($folder)) {
            while (($file = readdir($dir) ) !== false) {
                if (in_array($file, array('.', '..')))
                    continue;
                if (is_dir($folder . '/' . $file)) {
                    $folders2 = self::get_list_folder($folder . '/' . $file, $levels - 1);
                    if ($folders2){
                        $folders = array_merge($folders, $folders2);
                    }else {
                        $folders[] = $folder . '/' . $file . '/';
                    }
                }    
            }
        }
        @closedir($dir);
        return $folders;        
    }
    public static function delete_folder($path) {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                self::delete_folder(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        } else if (is_file($path) === true) {
            return unlink($path);
        }
        return false;
    } 
    public static function copy_dir($src, $dst) {
        if (file_exists($dst)) self::delete_folder($dst);
        if (is_dir($src)) {
            wp_mkdir_p($dst);
            $files = scandir($src);
            foreach ($files as $file){
                if ($file != "." && $file != "..") self::copy_dir("$src/$file", "$dst/$file");
            }
        } else if (file_exists($src)) copy($src, $dst);
    } 
    public static function mkdir( $dir ){
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }        
    }
    public static function clear_file($path){
        $f = @fopen($path, "r+");
        if ($f !== false) {
            ftruncate($f, 0);
            fclose($f);
        }        
    }
    public static function create_index_html($path){
        if (!file_exists($path)) {
            $content = __('Silence is golden.', 'web-to-print-online-designer');
            file_put_contents($path, $content);
        }
    }
    public static function create_file_path($upload_path, $filename, $ext='', $force_override = false){
	$date_path = '';
        if (!file_exists($upload_path))
            mkdir($upload_path);
        $year = @date() === false ? gmdate('Y') : date('Y');
        $date_path .= '/' . $year . '/';
        if (!file_exists($upload_path . $date_path))
            mkdir($upload_path . $date_path);
        $month = @date() === false ? gmdate('m') : date('m');
        $date_path .= $month . '/';
        if (!file_exists($upload_path . $date_path))
            mkdir($upload_path . $date_path);
        $day = @date() === false ? gmdate('d') : date('d');
        $date_path .= $day . '/';
        if (!file_exists($upload_path . $date_path))
            mkdir($upload_path . $date_path);
        $file_path = $upload_path . $date_path . $filename;
        $file_counter = 1;
        $real_filename = $filename;
        if($force_override){
            if(file_exists($file_path . '.' . $ext) ) unlink($file_path . '.' . $ext);
        }else{
            while (file_exists($file_path . '.' . $ext)) {
                $real_filename = $file_counter . '-' . $filename;
                $file_path = $upload_path . $date_path . $real_filename;
                $file_counter++;
            }
        }
        return array(
            'full_path' => $file_path,
            'date_path' => $date_path . $real_filename
        );
    }   
    public static function secret_image_url($file_path){
        $type = pathinfo($file_path, PATHINFO_EXTENSION);
        $data = file_get_contents($file_path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);   
        return $base64;        
    }   
    /**
     * @deprecated 1.7.0 <br />
     * From 1.7.0 alternate by function wp_convert_path_to_url( $path )
     * @param type $path
     * @return string url
     */
    public static function convert_path_to_url($path){
        $upload_dir = wp_upload_dir();
        $basedir = $upload_dir['basedir'];
        $arr = explode('/', $basedir);
        $upload = $arr[count($arr) - 1];
        if(is_multisite() && !is_main_site()) $upload = $arr[count($arr) - 3].'/'.$arr[count($arr) - 2].'/'.$arr[count($arr) - 1];
        return content_url( substr($path, strrpos($path, '/' . $upload . '/nbdesigner')) );
    }
    /**
     * @deprecated 1.7.0
     * From 1.7.0 alternate by WP function wp_make_link_relative( $url )
     * @param type $url
     * @return path
     */
    public static function convert_url_to_path($url){
        $upload_dir = wp_upload_dir();
        $basedir = $upload_dir['basedir'];
        $arr = explode('/', $basedir);
        $upload = $arr[count($arr) - 1];
        if(is_multisite() && !is_main_site()) $upload = $arr[count($arr) - 3].'/'.$arr[count($arr) - 2].'/'.$arr[count($arr) - 1];
        $arr_url = explode('/'.$upload, $url);
        if( isset($arr_url[1]) ){
            return $basedir.$arr_url[1];
        }else{
            $path = str_replace(
                site_url(),
                wp_normalize_path( untrailingslashit( ABSPATH ) ),
                wp_normalize_path( $url )
            );
            return $path;
        }
    }
    public static function wp_convert_path_to_url( $path = '' ){
        $url = str_replace(
            wp_normalize_path( untrailingslashit( ABSPATH ) ),
            site_url(),
            wp_normalize_path( $path )
        );
        return esc_url_raw( $url );
//        $url = self::convert_path_to_url($path);
//        return $url;
    }
    public static function save_data_to_file($path, $data){
        if (!$fp = fopen($path, 'w')) {
            return FALSE;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);
        return TRUE;        
    }
    public static function checkFileType($file_name, $arr_mime) {
        $check = false;
        $filetype = explode('.', $file_name);
        $file_exten = $filetype[count($filetype) - 1];
        if (in_array(strtolower($file_exten), $arr_mime)) $check = true;
        return $check;
    }   
    public static function get_thumb_file( $ext, $path = '' ){
        $thumb = '';
        switch ( $ext ) {
            case 'jpg': 
            case 'jpeg': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/jpg.png';
                break;
            case 'png': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/png.png';
                break;             
            case 'psd': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/psd.png';
                break;       
            case 'pdf': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/pdf.png';
                break;
            case 'ai': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/ai.png';
                break;       
            case 'eps': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/eps.png';
                break;     
            case 'zip': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/zip.png';
                break; 
            case 'svg': 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/svg.png';
                break;             
            default: 
                $thumb = NBDESIGNER_ASSETS_URL . 'images/file_type/file.png';
                break;             
        }
        return $thumb;
    }
    public static function read_json_setting($fullname) {
        if (file_exists($fullname)) {
            $list = json_decode(file_get_contents($fullname));           
        } else {
            $list = '[]';
            file_put_contents($fullname, $list);
        }
        return $list;
    }
    public static function delete_json_setting($fullname, $id, $reindex = true) {
        $list = self::read_json_setting($fullname);
        if (is_array($list)) {
            array_splice($list, $id, 1);
            if ($reindex) {
                $key = 0;
                foreach ($list as $val) {
                    $val->id = (string) $key;
                    $key++;
                }
            }
        }
        $res = json_encode($list);
        file_put_contents($fullname, $res);
    }  
    public static function update_json_setting($fullname, $data, $id) {
        $list = self::read_json_setting($fullname);
        if (is_array($list))
            $list[$id] = $data;
        else {
            $list = array();
            $list[] = $data;
        }
        $_list = array();
        foreach ($list as $val) {
            $_list[] = $val;
        }
        $res = json_encode($_list);
        file_put_contents($fullname, $res);
    }
    public static function update_json_setting_depend($fullname, $id) {
        $list = self::read_json_setting($fullname);
        if (!is_array($list)) return;
        foreach ($list as $val) {             
            if (!((sizeof($val) > 0))) continue;       
            foreach ($val->cat as $k => $v) {
                if ($v == $id) {                   
                    array_splice($val->cat, $k, 1);
                    break;
                }
            }
            foreach ($val->cat as $k => $v) {
                if ($v > $id) {
                    $new_v = (string) --$v;
                    unset($val->cat[$k]);
                    array_splice($val->cat, $k, 0, $new_v);									
                }
            }
        }
        $res = json_encode($list);
        file_put_contents($fullname, $res);
    }    
}
class NBD_Image {
    public static function nbdesigner_resize_imagepng($file, $w, $h, $path = ''){
        list($width, $height) = getimagesize($file);
        if( $path != '' ) $h = round( $w / $width * $height );
        $src = imagecreatefrompng($file);
        $dst = imagecreatetruecolor($w, $h);
        imagesavealpha($dst, true);
        $color = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefill($dst, 0, 0, $color);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
        imagedestroy($src);
        if( $path == '' ){
            return $dst;   
        } else{
            imagepng($dst, $path );
            imagedestroy($dst);
        }
    }
    public static function nbdesigner_resize_imagejpg($file, $w, $h, $path = '') {
        list($width, $height) = getimagesize($file);
        if( $path != '' ) $h = round( $w / $width * $height );
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
        imagedestroy($src);
        if( $path == '' ){
            return $dst;   
        } else{
            imagejpeg($dst, $path );
            imagedestroy($dst);
        }
    }      
    public static function convert_png_to_jpg($input_file){
        $output_file = pathinfo($input_file) . '/'. basename($filename, '.png') . ".jpeg";
        $input = imagecreatefrompng($input_file);
        list($width, $height) = getimagesize($input_file);
        $output = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($output,  255, 255, 255);
        imagefilledrectangle($output, 0, 0, $width, $height, $white);
        imagecopy($output, $input, 0, 0, 0, 0, $width, $height);
        imagejpeg($output, $output_file);        
    }
    public function resample($image, $height, $width, $format = 'jpeg', $dpi = 300){
        if (!$image) {
            throw new \Exception('Attempting to resample an empty image');
        }
        if (gettype($image) !== 'resource') {
            throw new \Exception('Attempting to resample something which is not a resource');
        }
        //Use truecolour image to avoid any issues with colours changing
        $tmp_img = imagecreatetruecolor($width, $height);
        //Resample the image to be ready for print
        if (!imagecopyresampled($tmp_img, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image))) {
            throw new \Exception("Unable to resample image");
        }
        //Massive hack to get the image as a jpeg string but there is no php function which converts
        //a GD image resource to a JPEG string
        ob_start();
        imagejpeg($tmp_img, null, 100);
        $image = ob_get_contents();
        ob_end_clean();
        //change the JPEG header to 300 pixels per inch
        $image = substr_replace($image, pack("Cnn", 0x01, $dpi, $dpi), 13, 5);
        return $image;
    } 
    public static function gd_resample( $input_file, $ouput_file, $dpi ){
        $source = imagecreatefromjpeg($input_file);
        list($width, $height) = getimagesize($filename);
        $image = self::resample( $source, $height, $width, $dpi );
        file_put_contents( $ouput_file, $image );
    }
    public static function imagick_add_white_bg( $input_file, $ouput_file ){       
        try {
            $image = new Imagick( $input_file );
            $bg = new IMagick();
            $bg->newImage($image->getImageWidth(), $image->getImageHeight(), new ImagickPixel("white"));
            $bg->setImageBackgroundColor('#FFFFFF');
            $bg->compositeImage($image, IMagick::COMPOSITE_DEFAULT, 0, 0);     
            $bg->writeImage( $ouput_file );  
            $image->destroy(); 
            $bg->destroy(); 
        } catch (Exception $e) {
            die('Error when creating a thumbnail: ' . $e->getMessage());
        }
    }
    public static function imagick_convert_png_to_jpg( $input_file, $ouput_file ){
        try {
            $image = new Imagick( $input_file );
            $flattened = new IMagick();
            $flattened->newImage($image->getImageWidth(), $image->getImageHeight(), new ImagickPixel("white"));
            $flattened->compositeImage($image, IMagick::COMPOSITE_OVER, 0, 0);
            $flattened->setImageFormat("jpg");
            $flattened->writeImage( $ouput_file );  
            $image->destroy(); 
            $flattened->destroy(); 
        } catch( Exception $e ){
            die('Error when creating a thumbnail: ' . $e->getMessage());
        }  
    }    
    public static function imagick_convert_png2jpg_without_bg( $input_file, $ouput_file ){
        try {
            $image = new Imagick( $input_file );
            $image->setImageFormat("jpg");
            $image->writeImage( $ouput_file );  
            $image->destroy();             
        } catch (Exception $e) {
            die('Error when creating a thumbnail: ' . $e->getMessage());
        }
    }
    public static function imagick_convert_rgb_to_cymk( $input_file, $ouput_file ){
        try {
            $image = new Imagick( $input_file );
            $image->stripImage();
            $image->transformimagecolorspace(\Imagick::COLORSPACE_CMYK);
            $image->writeImage( $ouput_file );
            $image->destroy(); 
        } catch( Exception $e ){
            die('Error when creating a thumbnail: ' . $e->getMessage());
        }        
    }
    public static function imagick_resample( $input_file, $ouput_file, $dpi ){
        try {
            $image = new Imagick();
            //$image->setResolution($dpi,$dpi);
            $image->readImage($input_file);
            $image->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
            $image->setImageResolution($dpi,$dpi);
            $image->writeImage($ouput_file);
            $image->destroy(); 
        } catch( Exception $e ){
            die('Error when creating a thumbnail: ' . $e->getMessage());
        }
    }
    public static function imagick_change_icc_profile( $input_file, $ouput_file, $icc  ){
        try {
            $image = new Imagick( $input_file );
            $image->stripImage ();
            $icc_profile = file_get_contents( $icc ); 
            $image->profileImage('icc', $icc_profile); 
            unset($icc_profile); 
            $image->writeImage( $ouput_file );
            $image->destroy();
        } catch( Exception $e ){
            die('Error when creating a thumbnail: ' . $e->getMessage());
        }            
    } 
    public static function imagick_convert_pdf_to_jpg( $input_file, $ouput_file ){
        try {          
            $image = new Imagick();
            $image->setResolution(72,72);
            $image->readimage( $input_file.'[0]' );           
            $image->setImageFormat('jpeg');
            $image->writeImage( $ouput_file );
            $image->clear(); 
            $image->destroy();
        } catch( Exception $e ){
            die('Error when creating a thumbnail: ' . $e->getMessage());
        }        
    }
    public static function crop_image( $input_file, $ouput_file, $startX, $startY, $width, $height, $ext ){
        if( is_available_imagick() ){
            try {          
                $image = new Imagick( $input_file );
                $image->cropImage($width, $height, $startX, $startY);          
                $image->writeImage( $ouput_file );
                $image->clear(); 
                $image->destroy();
            } catch( Exception $e ){
                die('Error when creating a thumbnail: ' . $e->getMessage());
            }              
        }else{
            $src = $ext == 'png' ? imagecreatefrompng($input_file) : imagecreatefromjpeg($input_file);
            $dst = imagecrop($src, ['x' => $startX, 'y' => $startY, 'width' => $width, 'height' => $height]);
            if ($dst !== FALSE) {
                if($ext == 'png'){
                    imagepng($dst, $ouput_file );
                }else{
                    imagejpeg($dst, $ouput_file );            
                }
            }
            imagedestroy($dst); 
            imagedestroy($src);
        }
    }
    public static function pdf2image($path, $dpi = 300, $type = 'png'){
        $ext = $type == 'png' ? 'png32' : 'jpg';
        $document = new Imagick($path);
        $number_pages = $document->getNumberImages();
        for( $i = 0; $i < $number_pages; $i++ ){
            $im = new Imagick();
            $im->setResolution($dpi, $dpi);
            $im->readImage( $path . '['. $i .']' );           
            $im->setImageFormat('jpg');
            $im->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
            $im->setResolution($dpi, $dpi);
            if($type == 'jpg'){
                $im->setImageBackgroundColor('white');
                $im->setImageAlphaChannel(11);
                $profiles = $im->getImageProfiles('*', false); 
                $has_icc_profile = (array_search('icc', $profiles) !== false);
                if ($has_icc_profile === false) {
                    $icc_rgb = file_get_contents( NBDESIGNER_PLUGIN_DIR . 'data/icc/RGB/sRGB.icc' ); 
                    $im->profileImage('icc', $icc_rgb);
                    unset($icc_rgb);
                }
                $list_icc = nbd_get_icc_cmyk_list_file();
                $default_icc = nbdesigner_get_option('nbdesigner_default_icc_profile');
                if( $list_icc[$default_icc] != '' ){
                    $icc = NBDESIGNER_PLUGIN_DIR . 'data/icc/CMYK/' . $list_icc[$default_icc];
                    if( file_exists($icc) ){
                        $icc_profile = file_get_contents( $icc );
                        $im->profileImage('icc', $icc_profile);
                        unset($icc_profile);
                    }
                }
            }
            // this will drop down the size of the image dramatically (removes all profiles) 
            //$im->stripImage();
            $im->writeImage(str_replace(".pdf", "_". $i . "." . $type, $path));
            $im->clear();
            $im->destroy();
        }
        $document->clear();
        $document->destroy();
    }
}
class NBD_Download {
    public static function init() {
        add_action( 'nocache_headers', array( __CLASS__, 'ie_nocache_headers_fix' ) );
    }
    /**
     * Force download - this is the default method.
     * @param  string $file_path
     * @param  string $filename
     */
    public static function download_file( $file_path, $filename ) {
        $parsed_file_path = self::parse_file_path( $file_path );
        self::download_headers( $file_path, $filename );
        if ( ! self::readfile_chunked( $parsed_file_path['file_path'] ) ) {
            if ( $parsed_file_path['remote_file'] ) {
                self::download_file_redirect( $file_path );
            } else {
                self::download_error( __( 'File not found', 'web-to-print-online-designer' ) );
            }
        }
        exit;
    }
    /**
     * Redirect to a file to start the download.
     * @param  string $file_path
     * @param  string $filename
     */
    public static function download_file_redirect( $file_path, $filename = '' ) {
        header( 'Location: ' . $file_path );
        exit;
    }
    /**
     * Parse file path and see if its remote or local.
     * @param  string $file_path
     * @return array
     */
    public static function parse_file_path($file_path) {
        $wp_uploads = wp_upload_dir();
        $wp_uploads_dir = $wp_uploads['basedir'];
        $wp_uploads_url = $wp_uploads['baseurl'];
        /**
         * Replace uploads dir, site url etc with absolute counterparts if we can.
         * Note the str_replace on site_url is on purpose, so if https is forced
         * via filters we can still do the string replacement on a HTTP file.
         */
        $replacements = array(
            $wp_uploads_url => $wp_uploads_dir,
            network_site_url('/', 'https') => ABSPATH,
            str_replace('https:', 'http:', network_site_url('/', 'http')) => ABSPATH,
            site_url('/', 'https') => ABSPATH,
            str_replace('https:', 'http:', site_url('/', 'http')) => ABSPATH,
        );
        $file_path = str_replace(array_keys($replacements), array_values($replacements), $file_path);
        $parsed_file_path = parse_url($file_path);
        $remote_file = true;

        // See if path needs an abspath prepended to work
        if (file_exists(ABSPATH . $file_path)) {
            $remote_file = false;
            $file_path = ABSPATH . $file_path;
        } elseif ('/wp-content' === substr($file_path, 0, 11)) {
            $remote_file = false;
            $file_path = realpath(WP_CONTENT_DIR . substr($file_path, 11));
            // Check if we have an absolute path
        } elseif ((!isset($parsed_file_path['scheme']) || !in_array($parsed_file_path['scheme'], array('http', 'https', 'ftp')) ) && isset($parsed_file_path['path']) && file_exists($parsed_file_path['path'])) {
            $remote_file = false;
            $file_path = $parsed_file_path['path'];
        }
        return array(
            'remote_file' => $remote_file,
            'file_path' => $file_path,
        );
    }

    /**
     * Get content type of a download.
     * @param  string $file_path
     * @return string
     * @access private
     */
    private static function get_download_content_type($file_path) {
        $file_extension = strtolower(substr(strrchr($file_path, "."), 1));
        $ctype = "application/force-download";
        foreach (get_allowed_mime_types() as $mime => $type) {
            $mimes = explode('|', $mime);
            if (in_array($file_extension, $mimes)) {
                $ctype = $type;
                break;
            }
        }
        return $ctype;
    }
    /**
     * Set headers for the download.
     * @param  string $file_path
     * @param  string $filename
     * @access private
     */
    private static function download_headers($file_path, $filename) {
        self::check_server_config();
        self::clean_buffers();
        nocache_headers();
        header("X-Robots-Tag: noindex, nofollow", true);
        header("Content-Type: " . self::get_download_content_type($file_path));
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=\"" . $filename . "\";");
        header("Content-Transfer-Encoding: binary");
        if ($size = @filesize($file_path)) {
            header("Content-Length: " . $size);
        }
    }
    /**
     * Check and set certain server config variables to ensure downloads work as intended.
     */
    private static function check_server_config() {
        wc_set_time_limit(0);
        if (function_exists('get_magic_quotes_runtime') && get_magic_quotes_runtime() && version_compare(phpversion(), '5.4', '<')) {
            set_magic_quotes_runtime(0);
        }
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', 1);
        }
        @ini_set('zlib.output_compression', 'Off');
        @session_write_close();
    }
    /**
     * Clean all output buffers.

     * Can prevent errors, for example: transfer closed with 3 bytes remaining to read.
     *
     * @access private
     */
    private static function clean_buffers() {
        if (ob_get_level()) {
            $levels = ob_get_level();
            for ($i = 0; $i < $levels; $i++) {
                @ob_end_clean();
            }
        } else {
            @ob_end_clean();
        }
    }
    /**
     * readfile_chunked.
     *
     * Reads file in chunks so big downloads are possible without changing PHP.INI - http://codeigniter.com/wiki/Download_helper_for_large_files/.
     *
     * @param   string $file
     * @return 	bool Success or fail
     */
    public static function readfile_chunked($file) {
        $chunksize = 1024 * 1024;
        $handle = @fopen($file, 'r');

        if (false === $handle) {
            return false;
        }
        while (!@feof($handle)) {
            echo @fread($handle, $chunksize);

            if (ob_get_length()) {
                ob_flush();
                flush();
            }
        }
        return @fclose($handle);
    }
    public static function ie_nocache_headers_fix($headers) {
        if (is_ssl() && !empty($GLOBALS['is_IE'])) {
            $headers['Cache-Control'] = 'private';
            unset($headers['Pragma']);
        }
        return $headers;
    }
    /**
     * Die with an error message if the download fails.
     * @param  string $message
     * @param  string  $title
     * @param  integer $status
     * @access private
     */
    private static function download_error($message, $title = '', $status = 404) {
        if (!strstr($message, '<a ')) {
            $message .= ' <a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="wc-forward">' . esc_html__('Go to shop', 'woocommerce') . '</a>';
        }
        wp_die($message, $title, array('response' => $status));
    }
}
NBD_Download::init();
function nbd_alias($value) {
    $arr = array(
        'a' => 'à|ả|ã|á|ạ|ă|ằ|ẳ|ẵ|ắ|ặ|â|ầ|ẩ|ẫ|ấ|ậ',
        'd' => 'đ',
        'e' => 'è|ẻ|ẽ|é|ẹ|ê|ề|ể|ễ|ế|ệ',
        'i' => 'ì|ỉ|ĩ|í|ị',
        'o' => 'ò|ỏ|õ|ó|ọ|ô|ồ|ổ|ỗ|ố|ộ|ơ|ờ|ở|ỡ|ớ|ợ',
        'u' => 'ù|ủ|ũ|ú|ụ|ư|ừ|ử|ữ|ứ|ự',
        'y' => 'ỳ|ỷ|ỹ|ý|ỵ',
    );
    $newValue = mb_strtolower(trim($value), 'utf-8');
    foreach ($arr as $key => $val) {
        $pattern = '#(' . $val . ')#imu';
        $newValue = preg_replace($pattern, $key, $newValue);
    }
    $newValue = preg_replace('#[^0-9a-zA-Z\s\-.]#i', '', $newValue);
    $newValue = preg_replace('#(\s)+#im', '-', $newValue);
    return $newValue;
}
function is_available_imagick(){
    if(!class_exists("Imagick")) return false;
    return true;
}
function nbd_get_icc_cmyk_list(){
    return array(
        0   =>  'Don\'t Color Manage',
        1   =>  'Coated FOGRA27',
        2   =>  'Coated FOGRA39',
        3   =>  'Coated GRACoL 2006',
        4   =>  'Japan Color 2001 Coated',
        5   =>  'Japan Color 2001 Uncoated',
        6   =>  'Japan Color 2002 Newspaper',
        7   =>  'Japan Color 2003 Web Coated',
        8   =>  'Japan Web Coated',
        9   =>  'Uncoated FOGRA29',
        10   =>  'US Web Coated SWOP',
        11   =>  'US Web Uncoated',
        12   =>  'Web Coated FOGRA28',
        13   =>  'Web Coated SWOP 2006 Grade 3',
        14   =>  'Web Coated SWOP 2006 Grade 5'
    );
}
function nbd_get_icc_cmyk_list_file(){
    return array(
        0   =>  '',
        1   =>  'CoatedFOGRA27.icc',
        2   =>  'CoatedFOGRA39.icc',
        3   =>  'CoatedGRACoL2006.icc',
        4   =>  'JapanColor2001Coated.icc',
        5   =>  'JapanColor2001Uncoated.icc',
        6   =>  'JapanColor2002Newspaper.icc',
        7   =>  'JapanColor2003WebCoated.icc',
        8   =>  'JapanWebCoated.icc',
        9   =>  'UncoatedFOGRA29.icc',
        10   =>  'USWebCoatedSWOP.icc',
        11   =>  'USWebUncoated.icc',
        12   =>  'WebCoatedFOGRA28.icc',
        13   =>  'WebCoatedSWOP2006Grade3.icc',
        14   =>  'WebCoatedSWOP2006Grade5.icc'
    );
}
function nbd_file_get_contents($url){
    $response = wp_remote_get( $url );
    if ( is_array( $response ) && ! is_wp_error( $response ) ) {
        $result   = trim($response['body']);
        return $result;
    }
    if(ini_get('allow_url_fopen')){
        $checkPHP = version_compare(PHP_VERSION, '5.6.0', '>=');
        if (is_ssl() && $checkPHP) {
            $result = file_get_contents($url, false, stream_context_create(array('ssl' => 
                array('verify_peer' => false, 'verify_peer_name' => false)))); 
        }else{
            $result = file_get_contents($url);    
        }             
    }else{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSLVERSION, 3); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);                        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);          
        if(false === $result){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $result = curl_exec($ch);
            curl_close($ch);          
        }
    }
    return $result;
}
function hex_code_to_rgb($code){        
    list($r, $g, $b) = sscanf($code, "#%02x%02x%02x");
    $rgb = array($r, $g, $b);
    return $rgb;
}
function is_nbdesigner_product($id){
    $id = get_wpml_original_id($id);    
    $check = get_post_meta($id, '_nbdesigner_enable', true);
    if($check) return true;
    return false;
}
function is_nbd_product_builder($id){
    $id = get_wpml_original_id($id);    
    $check_nbo = get_post_meta($id, '_nbo_enable', true);
    if(!$check_nbo) return false;
    $check = get_post_meta($id, '_nbdpb_enable', true);
    if($check) return true;
    return false;
}
function nbd_check_availaible_option( $layout_depend = 'c' ){
    $layout = nbdesigner_get_option('nbdesigner_design_layout');
    if($layout_depend == $layout) return true;
    return false;
};
function nbdesigner_get_option($key){
    $option = get_option($key, false);
    if(false === $option) return nbdesigner_get_default_setting($key);
    return $option;
}
function nbdesigner_get_all_setting(){
    $default = nbdesigner_get_default_setting();
    foreach ($default as $key => $val){
        $default[$key] = nbdesigner_get_option($key);
    }
    return $default;
}
function nbdesigner_get_all_frontend_setting(){
    $default = default_frontend_setting();
    foreach ($default as $key => $val){
        $default[$key] = nbdesigner_get_option($key);
    }
    return $default;
}
function nbdesigner_get_default_setting($key = false){
    $frontend = default_frontend_setting();
    $nbd_setting = apply_filters('nbdesigner_default_settings', array_merge(array(
        'nbdesigner_design_layout'  =>  'm',
        'nbdesigner_position_button_in_catalog' => 1,
        'nbdesigner_position_button_product_detail' => 1,
        'nbdesigner_position_pricing_in_detail_page' => 1,
        'nbdesigner_quantity_pricing_description' => '',
        'nbdesigner_thumbnail_width' => 300,
        'nbdesigner_template_width' => 500,
        'nbdesigner_default_dpi' => 96,
        'nbdesigner_show_in_cart' => 'yes',
        'nbdesigner_auto_add_cart_in_detail_page' => 'no',
        'nbdesigner_class_design_button_catalog' => '',
        'nbdesigner_class_design_button_detail' => '',
        'nbdesigner_show_in_order' => 'yes',  
        'nbdesigner_disable_on_smartphones' => 'no',        
        'nbdesigner_notifications' => 'yes',
        'nbdesigner_enable_send_mail_when_approve' => 'no',
        'nbdesigner_attachment_admin_email' => 'no',
        'nbdesigner_notifications_recurrence' => 'hourly',
        'nbdesigner_notifications_emails' => '',
        'nbdesigner_admin_emails' => '',
        'allow_customer_redesign_after_order' => 'yes',
        'nbd_force_upload_svg' => 'no',
        'nbdesigner_mindpi_upload' => 0,
        'nbdesigner_hide_button_cart_in_detail_page'    =>  'no',
        'nbdesigner_printful_key' => '',   
        'nbdesigner_google_api_key' => '',   
        'nbdesigner_google_client_id' => '',   
        'nbdesigner_enable_log' => 'no',
        'nbdesigner_cron_job_clear_w3_cache' => 'no',
        'nbdesigner_page_design_tool' => 1,
        'nbdesigner_upload_term' => __('Your term', 'web-to-print-online-designer'),
        'nbdesigner_create_your_own_page_id'	=>	nbd_get_page_id( 'create_your_own' ),
        'nbdesigner_designer_page_id'	=>	nbd_get_page_id( 'designer' ),
        'nbdesigner_gallery_page_id'	=>	nbd_get_page_id( 'gallery' ),
        
        'nbdesigner_mindpi_upload_file' => 0,  
        'nbdesigner_allow_upload_file_type' => '',
        'nbdesigner_disallow_upload_file_type' => '',
        'nbdesigner_number_file_upload' => 1,
        'nbdesigner_maxsize_upload_file' => nbd_get_max_upload_default(),
        'nbdesigner_minsize_upload_file' => 0,        
        'nbdesigner_max_res_upload_file' => '',
        'nbdesigner_min_res_upload_file' => '',            
        'nbdesigner_allow_download_file_upload' => 'no',       
        'nbdesigner_create_preview_image_file_upload' => 'no',
        'nbdesigner_file_upload_preview_width' => 200,
        'nbdesigner_long_time_retain_upload_fies' => '',
        
        'nbdesigner_enable_download_pdf_before' => 'no',
        'nbdesigner_enable_download_pdf_after' => 'no',   
        'nbdesigner_enable_pdf_watermark' => 'yes',   
        'nbdesigner_pdf_watermark_type' => 1,
        'nbdesigner_pdf_watermark_image' => '',
        'nbdesigner_editor_logo' => '',
        'nbdesigner_truetype_fonts' => '',
        'nbdesigner_default_icc_profile' => 1,
        'nbdesigner_pdf_watermark_text' => get_bloginfo('name'),
        'nbdesigner_bleed_stack' => 1,
        
        'nbdesigner_enable_perfect_scrollbar_js'    =>  'yes',
        'nbdesigner_enable_angular_js'    =>  'yes',
        'nbdesigner_enable_perfect_scrollbar_css'    =>  'yes',
        
        'nbdesigner_turn_off_persistent_cart' => 'no',
        'nbdesigner_option_display' => '1',
        'nbdesigner_hide_add_cart_until_form_filled' => 'no',
        'nbdesigner_enable_clear_cart_button' => 'no',
        'nbdesigner_force_select_options' => 'no',
        'nbdesigner_hide_table_pricing' => 'no',
        'nbdesigner_hide_option_swatch_label' => 'yes',
        'nbdesigner_change_base_price_html' => 'no',
        'nbdesigner_hide_zero_price' => 'no',
        'nbdesigner_tooltip_position' => 'top',
        'nbdesigner_hide_summary_options' => 'no',
        'nbdesigner_hide_options_in_cart' => 'no',
        'nbdesigner_hide_option_price_in_cart' => 'no',
        'nbdesigner_selector_increase_qty_btn' => '',
        'nbdesigner_site_force_login' => 'no',
        'nbdesigner_redefine_K_PATH_FONTS' => 'yes',
        'nbdesigner_disable_nonce' => 'no'
    ), $frontend));
    if(!$key) return $nbd_setting;
    return $nbd_setting[$key];
}
function default_frontend_setting(){
    return apply_filters('nbdesigner_default_frontend_settings', array(
        'nbdesigner_fix_domain_changed' => 'no',
        'nbdesigner_enable_text' => 'yes',
        'nbdesigner_text_change_font' => 1,
        'nbdesigner_text_italic' => 1,
        'nbdesigner_text_bold' => 1,
        'nbdesigner_text_underline' => 0,
        'nbdesigner_text_through' => 0,
        'nbdesigner_text_overline' => 0,
        'nbdesigner_text_case' => 1,
        'nbdesigner_text_align_left' => 1,
        'nbdesigner_text_align_right' => 1,
        'nbdesigner_text_align_center' => 1,
        'nbdesigner_text_color' => 1,
        'nbdesigner_text_background' => 1,
        'nbdesigner_text_shadow' => 0,
        'nbdesigner_text_line_height' => 1,
        'nbdesigner_text_spacing' => 1,
        'nbdesigner_text_font_size' => 1,
        'nbdesigner_text_opacity' => 1,
        'nbdesigner_text_outline' => 1,
        'nbdesigner_text_proportion' => 1,
        'nbdesigner_text_rotate' => 1,
        'nbdesigner_default_text' => __('Text here', 'web-to-print-online-designer'),
        'nbdesigner_default_font_subset' => 'all',
        'nbdesigner_enable_curvedtext' => 'yes',
        'nbdesigner_enable_text_free_transform' => 'no',
        'nbdesigner_enable_textpattern' => 'no',
        
        'nbdesigner_enable_clipart' => 'yes',
        'nbdesigner_clipart_change_path_color' => 1,           
        'nbdesigner_clipart_rotate' => 1,           
        'nbdesigner_clipart_opacity' => 1,   

        'nbdesigner_enable_image' => 'yes',
        'nbdesigner_image_unlock_proportion' => 1,           
        'nbdesigner_image_shadow' => 0,           
        'nbdesigner_image_opacity' => 1,           
        'nbdesigner_image_grayscale' => 1,           
        'nbdesigner_image_invert' => 1,           
        'nbdesigner_image_sepia' => 1,           
        'nbdesigner_image_sepia2' => 1,           
        'nbdesigner_image_remove_white' => 1,      
        'nbdesigner_image_transparency' => 1,           
        'nbdesigner_image_tint' => 1,           
        'nbdesigner_image_blend' => 1,           
        'nbdesigner_image_brightness' => 1,           
        'nbdesigner_image_noise' => 1,         
        'nbdesigner_image_pixelate' => 1,         
        'nbdesigner_image_multiply' => 1,     
        'nbdesigner_image_blur' => 1,           
        'nbdesigner_image_sharpen' => 1,         
        'nbdesigner_image_emboss' => 1,         
        'nbdesigner_image_edge_enhance' => 1,          
        'nbdesigner_image_rotate' => 1,          
        'nbdesigner_image_crop' => 1,          
        'nbdesigner_image_shapecrop' => 1,  
        'nbdesigner_facebook_app_id' => '',
        'nbdesigner_instagram_app_id' => '',
        'nbdesigner_dropbox_app_id' => '', 
        'nbdesigner_enable_upload_image' => 'yes',
        'nbdesigner_upload_designs_php_logged_in' => 'no',
        'nbdesigner_upload_multiple_images' => 'no',
        'nbdesigner_maxsize_upload' => nbd_get_max_upload_default(),
        'nbdesigner_minsize_upload' => 0,           
        'nbdesigner_enable_image_url' => 'yes',
        'nbdesigner_enable_low_resolution_image' => 'no',
        'nbdesigner_enable_image_webcam' => 'yes',
        'nbdesigner_enable_facebook_photo' => 'yes',
        'nbdesigner_enable_instagram_photo' => 'yes',
        'nbdesigner_enable_dropbox_photo' => 'yes',
        'nbdesigner_enable_google_drive' => 'yes',
        'nbdesigner_enable_svg_code' => 'no',
        'nbdesigner_upload_show_term' => 'no',                
        'nbdesigner_max_upload_files_at_once' => 5,
        
        'nbdesigner_enable_draw' => 'yes',
        'nbdesigner_draw_brush' => 1,          
        'nbdesigner_draw_brush_pencil' => 1,          
        'nbdesigner_draw_brush_circle' => 1,          
        'nbdesigner_draw_brush_spray' => 1,          
        'nbdesigner_draw_brush_pattern' => 0,          
        'nbdesigner_draw_brush_hline' => 0,          
        'nbdesigner_draw_brush_vline' => 0,          
        'nbdesigner_draw_brush_square' => 0,          
        'nbdesigner_draw_brush_diamond' => 0,          
        'nbdesigner_draw_brush_texture' => 0,          
        'nbdesigner_draw_shape' => 1, 
        'nbdesigner_draw_shape_rectangle' => 1, 
        'nbdesigner_draw_shape_circle' => 1, 
        'nbdesigner_draw_shape_triangle' => 1, 
        'nbdesigner_draw_shape_line' => 1, 
        'nbdesigner_draw_shape_polygon' => 1, 
        'nbdesigner_draw_shape_hexagon' => 1, 
        
        'nbdesigner_enable_qrcode' => 'yes',
        'nbdesigner_hide_element_tab' => 'no',
        'nbdesigner_hide_typo_section' => 'no',
        'nbdesigner_display_product_option' => '1',
        'nbdesigner_hide_layer_tab' => 'no',
        'nbdesigner_show_all_template_sides' => 'no',
        'nbdesigner_default_qrcode' => __('example.com', 'web-to-print-online-designer'),
        
        'nbdesigner_dimensions_unit' => 'cm',
        'nbdesigner_show_all_color' => 'yes',
        'nbdesigner_default_color' => '#cc324b',
        'nbdesigner_hex_names' => '',        
        'nbdesigner_save_latest_design'  => 'yes',
        'nbdesigner_save_for_later'  => 'yes',
        'nbdesigner_share_design'  => 'yes',
        'nbdesigner_cache_uploaded_image'  => 'yes',
        
        'nbdesigner_upload_file_php_logged_in' => 'no',
        
        'nbdesigner_auto_add_cart_in_detail_page' => 'no',
        
        'allow_customer_download_after_complete_order' => 'no',
        'nbdesigner_download_design_png' => 0,
        'nbdesigner_download_design_pdf' => 0,
        'nbdesigner_download_design_svg' => 0,
        'nbdesigner_download_design_jpg' => 0,
        'nbdesigner_download_design_jpg_cmyk' => 0,
        'nbdesigner_download_design_upload_file' => 0,
        'nbdesigner_attach_design_png' => 1,
        'nbdesigner_attach_design_svg' => 0,
        
        'allow_customer_download_design_in_editor' => 'no',
        'nbdesigner_download_design_in_editor_png' => 0,
        'nbdesigner_download_design_in_editor_pdf' => 0,
        'nbdesigner_download_design_in_editor_jpg' => 0,
        'nbdesigner_download_design_in_editor_svg' => 0,
        
        'nbdesigner_pixabay_api_key' => '27347-23fd1708b1c4f768195a5093b',
        'nbdesigner_unsplash_api_key' => '5746b12f75e91c251bddf6f83bd2ad0d658122676e9bd2444e110951f9a04af8',
        'nbdesigner_enable_pixabay' => 'yes',
        'nbdesigner_enable_unsplash' => 'yes',
        
        'nbdesigner_show_grid' => 'no',
        'nbdesigner_show_ruler' => 'no',
        'nbdesigner_show_product_dimensions' => 'no',
        'nbdesigner_show_bleed' => 'no',
        'nbdesigner_show_layer_size' => 'no',
        'nbdesigner_show_warning_oos' => 'no',
        'nbdesigner_show_warning_ilr' => 'no',
        'nbdesigner_show_design_border' => 'no',
        
        'nbdesigner_hide_print_option_in_editor'    =>  'no',
        'nbdesigner_object_center_scaling'    =>  'no',
        'nbdesigner_disable_auto_load_template'    =>  'no',
        'nbdesigner_lazy_load_template'    =>  'no'
    ));
}
function nbd_get_value_from_serialize_data( $str, $key ){
    $arr = array();
    $value = 0;
    parse_str($str, $arr);   
    if( isset($arr[$key]) ) $value = $arr[$key];
    return $value;
}
function nbd_not_empty($value) {
    return $value == '0' || !empty($value);
}
function nbd_default_product_setting(){
    return apply_filters('nbdesigner_default_product_setting', array(
        'orientation_name' => 'Side 1',
        'img_src' => get_option('nbdesigner_default_background'),
        'img_overlay' => get_option('nbdesigner_default_overlay'),
        'real_width' => 8,
        'real_height' => 6,
        'real_left' => 1,
        'real_top' => 1,
        'area_design_top' => 100,
        'area_design_left' => 50,
        'area_design_width' => 400,
        'area_design_height' => 300,
        'img_src_top' => 50,
        'img_src_left' => 0,
        'img_src_width' => 500,
        'img_src_height' => 400,
        'product_width' => 10,    
        'product_height' => 8,
        'show_bleed' => 0,
        'bleed_top_bottom' => 0.3,
        'bleed_left_right' => 0.3,
        'show_safe_zone' => 0,
        'margin_top_bottom' => 0.3,
        'margin_left_right' => 0.3,
        'bg_type'   => 'image',
        'bg_color_value' => "#ffffff",
        'show_overlay' => 0,
        'include_overlay' => 1,
        'area_design_type' => 1,
        'version' => NBDESIGNER_NUMBER_VERSION
    )); 
}
function nbd_get_default_product_option(){
    return apply_filters('nbdesigner_default_product_option', array(
        'admindesign'   => 0,
        'global_template'   => 0,
        'global_template_cat'   => 0,
        'dpi'   => nbdesigner_get_option('nbdesigner_default_dpi'),
        'request_quote' =>  0,
        'allow_specify_dimension'   =>  0,
        'min_width'   =>  0,
        'max_width'   =>  0,
        'min_height'   =>  0,
        'max_height'   =>  0,
        'extra_price'   => 0,
        'type_price'   => 1,
        'type_dimension'   => 1,
        'dynamic_side'   => 0,
        'defined_dimension' => array(array('width' => 10, 'height' => 8, 'price' => 0))
    ));
}
function nbd_get_default_upload_setting(){
    return apply_filters('nbdesigner_default_product_upload', array(
        'number'   => nbdesigner_get_option('nbdesigner_number_file_upload'),
        'allow_type'   => nbdesigner_get_option('nbdesigner_allow_upload_file_type'),
        'disallow_type'   => nbdesigner_get_option('nbdesigner_disallow_upload_file_type'),
        'maxsize'   => nbdesigner_get_option('nbdesigner_maxsize_upload_file'),
        'minsize'   => nbdesigner_get_option('nbdesigner_minsize_upload_file'),
        'mindpi'   => nbdesigner_get_option('nbdesigner_mindpi_upload_file')
    ));    
}
function nbd_get_global_template_cat(){
    $cats = get_transient( 'nbd_global_template_cat' );
    if( false === $cats ){
        $response = wp_remote_post( 'https://studio.cmsmart.net/v1/template',
            array(
                'timeout'     => 120,
                'body' => array(
                    'type'  =>  'get_template_cat'
                )
            )
        );
        $cats = array();
        if ( !is_wp_error( $response ) ) {
            $cats = json_decode($response['body'])->data;
        }
        set_transient( 'nbd_global_template_cat' , $cats, DAY_IN_SECONDS );
    }
    return $cats;
}
function nbd_update_config_default($designer_setting) {
    $default =  nbd_default_product_setting();    
    foreach ($designer_setting as $key => $setting){
        $designer_setting[$key] = array_merge($default, $setting);
    }
    return $designer_setting;
}
function getUrlPageNBD($page){
    global $wpdb;
    switch ($page) {
        case 'studio':
            $post = nbd_get_page_id( 'studio' );
            break;       
        case 'create':
            $post = nbd_get_page_id( 'create_your_own' );
            break;   
        case 'redirect':
            $post = nbd_get_page_id( 'logged' );
            break;   
        case 'designer':
            $post = nbd_get_page_id( 'designer' );
            break;
        case 'gallery':
            $post = nbd_get_page_id( 'gallery' );
            break;   
        case 'product_builder':
            $post = nbd_get_page_id( 'product_builder' );
            break;
    }
    return ($post) ? get_page_link($post) : '#';    
}
function nbd_update_hit_template( $template_id = false, $folder = '' ){
    global $wpdb;
    $table_name = $wpdb->prefix . 'nbdesigner_templates';
    if( $template_id ){
        $tem = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE id = {$template_id}" );
    }else if($folder != '') {
        $tem = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE folder = '{$folder}'" );
        if( $tem ){
            $template_id = $tem->id;
        }
    }
    if( $template_id ){
        $hit = $tem->hit ? $tem->hit + 1 : 1;
        $re = $wpdb->update($table_name, array(
            'hit' => $hit
        ), array( 'id' => $template_id));         
    }
}
function nbd_count_total_template( $product_id, $variation_id ){
    global $wpdb;
    $table_name = $wpdb->prefix . 'nbdesigner_templates';
    $sql = "SELECT count(*) as total FROM $table_name WHERE product_id = '$product_id' AND ( variation_id = '$variation_id' || variation_id = 0 ) ORDER BY created_date DESC";
    $results = $wpdb->get_results($sql, 'ARRAY_A');
    return $results[0]["total"];
}
function nbd_get_templates( $product_id, $variation_id, $template_id = '', $priority = false, $limit = false, $start = false ){
    global $wpdb;
    $table_name = $wpdb->prefix . 'nbdesigner_templates';
    if( $template_id != '' ){
        $sql = "SELECT * FROM $table_name WHERE id = $template_id";
    }else {
        if($priority) {
            $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id' AND ( variation_id = '$variation_id' || variation_id = 0 ) AND priority = 1";
        }else {
            if( $limit ){
                $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id' AND ( variation_id = '$variation_id' || variation_id = 0 ) AND publish = 1 ORDER BY created_date DESC LIMIT $limit";
            }else{
                $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id' AND ( variation_id = '$variation_id' || variation_id = 0 ) AND publish = 1 ORDER BY created_date DESC";                
            }           
        }
    }
    if( $start ){
        $sql .= ' OFFSET ' . $start;
    }
    $results = $wpdb->get_results($sql, 'ARRAY_A');
    if( $priority && count( $results ) == 0 ) {
        $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id' AND ( variation_id = '$variation_id' || variation_id = 0 ) AND publish = 1 ORDER BY created_date DESC LIMIT 1";
        $results = $wpdb->get_results($sql, ARRAY_A);
    }
    /* Case variation no template */
//    if( $variation_id != 0 && count( $results ) == 0 ) {
//        $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id'  ORDER BY created_date DESC";
//        $results = $wpdb->get_results($sql, ARRAY_A);        
//    }
    return $results;
}
function nbd_get_resource_templates($product_id, $variation_id, $limit = 20, $start = 0){
    $data = array();
    $templates = nbd_get_templates($product_id, $variation_id, '', false, $limit, $start);
    foreach ($templates as $tem){
        $path_preview = NBDESIGNER_CUSTOMER_DIR .'/'.$tem['folder']. '/preview';
        $listThumb = Nbdesigner_IO::get_list_images($path_preview);
        asort( $listThumb );
        if(count($listThumb)){
            $_temp = array();
            $_temp['id'] =  $tem['folder'];
            foreach($listThumb as $img){
                $_temp['src'][] = Nbdesigner_IO::wp_convert_path_to_url($img);
            }
            if( isset($tem['thumbnail']) ){
                $_temp['thumbnail'] = wp_get_attachment_url( $tem['thumbnail'] );
            }else{
                //$_temp['thumbnail'] = end($_temp['src']);
                $_temp['thumbnail'] = $_temp['src'][0];
            }
            $data[] = $_temp;
        }                
    }
    return $data;
}
function nbd_get_template_by_folder( $folder ){
    $data = array();
    $path = NBDESIGNER_CUSTOMER_DIR .'/'.$folder;
    $data['fonts'] = nbd_get_data_from_json($path . '/used_font.json');
    $data['design'] = nbd_get_data_from_json($path . '/design.json'); 
    $data['config'] = nbd_get_data_from_json($path . '/config.json');
    return $data;
}
function nbd_get_product_info( $product_id, $variation_id, $nbd_item_key = '', $task, $task2 = '', $reference = '', $need_templates = false, $cart_item_key = '' ){
    $data = array();
    if($variation_id > 0){
        $variation_id = get_wpml_original_id($variation_id);      
    }
    $nbd_item_cart_key = ($variation_id > 0) ? $product_id . '_' . $variation_id : $product_id; 
    $_nbd_item_key = WC()->session->get('nbd_item_key_'.$nbd_item_cart_key);  
    if( $_nbd_item_key && $task2 == '' && $nbd_item_key == '' ) $nbd_item_key = $_nbd_item_key;
//    if( $cart_item_key != '' && WC()->session->get($cart_item_key . '_nbd') ) {
//        $nbd_item_key = WC()->session->get($cart_item_key . '_nbd');
//    }
    $lazy_load_default_template = nbdesigner_get_option('nbdesigner_lazy_load_template');
    $path = NBDESIGNER_CUSTOMER_DIR . '/' . $nbd_item_key;
    /* Path not exist in case add to cart before design, session has been init */  
    if( $nbd_item_key == '' || !file_exists($path) ){
        $data['upload'] = unserialize(get_post_meta($product_id, '_nbdesigner_upload', true));
        $data['option'] = unserialize(get_post_meta($product_id, '_nbdesigner_option', true));  
        if($variation_id > 0){         
            $enable_variation = get_post_meta($variation_id, '_nbdesigner_variation_enable', true);
            $data['product'] = unserialize(get_post_meta($variation_id, '_designer_variation_setting', true)); 
            if ( !($enable_variation && isset($data['product'][0]))){
                $data['product'] = unserialize(get_post_meta($product_id, '_designer_setting', true)); 
            }    
        }else {
            $data['product'] = unserialize(get_post_meta($product_id, '_designer_setting', true)); 
        }
    }else {
        $data['product'] = unserialize(file_get_contents($path . '/product.json'));
        $data['option'] = unserialize(file_get_contents($path . '/option.json'));
        if( file_exists($path . '/upload.json') ){
            $data['upload'] = unserialize(file_get_contents($path . '/upload.json'));
        }else{
            $data['upload'] = unserialize(get_post_meta($product_id, '_nbdesigner_upload', true));
        }
        $data['fonts'] = nbd_get_data_from_json($path . '/used_font.json');
        $data['config'] = nbd_get_data_from_json($path . '/config.json');
        if(isset($data['config']->product)){
            $data['product'] = $data['config']->product;
        }
        if($lazy_load_default_template == 'yes'){
            $data['lazy_load_design_folder'] = $nbd_item_key;
        }else{
            $data['design'] = nbd_get_data_from_json($path . '/design.json');
        }
    }
    if( $data['option']['admindesign'] && $task == 'new' ) {
        /* Get primary template or latest template for new design */
        $template = nbd_get_templates( $product_id, $variation_id, '', 1 );
        if( isset($template[0]) ){
            $template_path = NBDESIGNER_CUSTOMER_DIR . '/' . $template[0]['folder'];
            $data['fonts'] = nbd_get_data_from_json($template_path . '/used_font.json');
            $data['config'] = nbd_get_data_from_json($template_path . '/config.json');
            if($lazy_load_default_template == 'yes'){
                $data['lazy_load_design_folder'] = $template[0]['folder'];
            }else{
                $data['design'] = nbd_get_data_from_json($template_path . '/design.json'); 
            }
        }
        $data['is_template'] = 1;
    }
    if(  $reference != '' ){
        /* Get reference design, font and reference product setting */
        $ref_path = NBDESIGNER_CUSTOMER_DIR . '/' . $reference;
        if($lazy_load_default_template == 'yes'){
            $data['lazy_load_design_folder'] = $reference;
        }else{
            $data['design'] = nbd_get_data_from_json($ref_path . '/design.json');
        }
        $data['fonts'] = nbd_get_data_from_json($ref_path . '/used_font.json');
        $data['ref'] = unserialize(file_get_contents($ref_path . '/product.json'));
        $data['config_ref'] = nbd_get_data_from_json($ref_path . '/config.json');
        $data['is_reference'] = 1;
        nbd_update_hit_template( false, $reference );
    }
    if( $data['upload']['allow_type'] == '' ) $data['upload']['allow_type'] = nbdesigner_get_option('nbdesigner_allow_upload_file_type');
    if( $data['upload']['disallow_type'] == '' ) $data['upload']['disallow_type'] = nbdesigner_get_option('nbdesigner_disallow_upload_file_type');
    $data['upload']['allow_type'] = preg_replace('/\s+/', '', strtolower( $data['upload']['allow_type']) );
    $data['upload']['disallow_type'] = preg_replace('/\s+/', '', strtolower( $data['upload']['disallow_type']) );
    $data['product'] = nbd_get_media_for_data_product( $data['product'] );
    if( $need_templates ){
        $templates = nbd_get_resource_templates( $product_id, $variation_id );
        if( count($templates) ){
            $data['templates'] = $templates;
        }
    }
    $data = apply_filters('nbd_product_info', $data);
    return $data;        
}
function nbd_get_product_pre_builder($option_id, $nbo_cart_item_key){
    $data = array();
    if( $nbo_cart_item_key != '' ){
        $cart_item = WC()->cart->get_cart_item( $nbo_cart_item_key );
        if( isset($cart_item['nbo_meta']) ){
            $builder_folder = $cart_item['nbo_meta']['nbdpb'];
            $path = NBDESIGNER_CUSTOMER_DIR . '/' . $builder_folder;
            $data['config'] = nbd_get_data_from_json($path . '/config.json');
            $data['design'] = nbd_get_data_from_json($path . '/design.json');
        }
    }else{
        global $wpdb;
        $sql = "SELECT builder FROM {$wpdb->prefix}nbdesigner_options WHERE id = {$option_id}";
        $options = $wpdb->get_results($sql, 'ARRAY_A');
        if( isset($options[0]) ){
            $builder_folder = $options[0]['builder'];
            if( $builder_folder ){
                $path = NBDESIGNER_CUSTOMER_DIR . '/' . $builder_folder;
                $data['config'] = nbd_get_data_from_json($path . '/config.json');
                $data['design'] = nbd_get_data_from_json($path . '/design.json');
            }
        }
    }
    return $data;
}
function nbd_get_media_for_data_product( $data_product ){
    foreach ( $data_product as $key => $data ){
        $data_product[$key] = $_data = (array) $data;
        $data_product[$key]['img_src'] = is_numeric( $_data['img_src'] ) ? wp_get_attachment_url( $_data['img_src'] ) : $_data['img_src'];
        $data_product[$key]['img_overlay'] = is_numeric( $_data['img_overlay'] ) ? wp_get_attachment_url( $_data['img_overlay'] ) : $_data['img_overlay'];
    }
    return $data_product;
}
function nbd_add_attachment( $file ){
    $filename = basename($file);
    $upload_file = wp_upload_bits($filename, null, file_get_contents($file));
    if (!$upload_file['error']) {
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attachment_id = wp_insert_attachment($attachment, $upload_file['file']);
        if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
            return $attachment_id;
        }
    }
}
function nbd_get_upload_files_from_session( $nbu_item_key ){
    $path = NBDESIGNER_UPLOAD_DIR . '/' . $nbu_item_key;
    $list_files = Nbdesigner_IO::get_list_files($path);
    $list_files = nbd_get_array_name_from_arry_path($list_files); 
    $files = array();
    foreach ($list_files as $file ){
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $files[] = array(
            'src' => Nbdesigner_IO::get_thumb_file($ext, '' ),
            'name'  =>  $file
        );
    }
    return $files;
}
function nbd_get_array_name_from_arry_path( $arr_path ){
    $arr_names = array();
    foreach ($arr_path as $path ){
        $arr_names[] = basename($path);
    }
    return $arr_names;
}
function nbd_get_data_from_json($path = ''){
    $content = file_exists($path) ? file_get_contents($path) : '';
    return json_decode($content) ;
}
function nbd_update_config_product_160($settings){
    return $settings;
}
function nbd_get_i18n_javascript(){
    $lang = array(
        'error' => __('Oops! Try again later!', 'web-to-print-online-designer'),
        'complete' => __('Complete!', 'web-to-print-online-designer'),
        'are_you_sure' => __('Are you sure?', 'web-to-print-online-designer'),
        'warning_mes_delete_file' => __('You will not be able to recover this file!', 'web-to-print-online-designer'),
        'warning_mes_delete_category' => __('You will not be able to recover this category!', 'web-to-print-online-designer'),
        'warning_mes_fill_category_name' => __('Please fill category name!', 'web-to-print-online-designer'),
        'warning_mes_backup_data' => __('Restore your last data!', 'web-to-print-online-designer'),
        'warning_mes_delete_lang' => __('You will not be able to recover this language!', 'web-to-print-online-designer')
    );
    return $lang;    
}
if ( ! function_exists( 'is_nbd_product_builder_page' ) ) {
    function is_nbd_product_builder_page(){
        return is_page( nbd_get_page_id( 'product_builder' ) );
    }
}
if ( ! function_exists( 'is_nbd_studio' ) ) {
    function is_nbd_studio(){
        return is_page( nbd_get_page_id( 'studio' ) );
    }    
}
if ( ! function_exists( 'is_nbd_design_page' ) ) {
    function is_nbd_design_page(){
        return is_page( nbd_get_page_id( 'create_your_own' ) );
    }    
}
if ( ! function_exists( 'is_nbd_gallery_page' ) ) {
    function is_nbd_gallery_page(){
        return is_page( nbd_get_page_id( 'gallery' ) );
    }    
}
if ( ! function_exists( 'is_nbd_designer_page' ) ) {
    function is_nbd_designer_page(){
        return is_page( nbd_get_page_id( 'designer' ) );
    }    
}
if( !function_exists('nbd_get_page_id')){
    function nbd_get_page_id($page){
        $page = apply_filters( 'nbdesigner_' . $page . '_page_id', get_option('nbdesigner_' . $page . '_page_id' ) );
        if ( class_exists('SitePress') ) {
            $page = icl_object_id($page,'page',false);
        }  
        return $page ? absint( $page ) : -1;
    }
}
function nbd_get_woo_version(){
    if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . 'woocommerce' );
    $plugin_file = 'woocommerce.php';     
    if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
    } else {
            return 0;
    }   
}
function is_woo_v3(){
    $woo_ver = nbd_get_woo_version(); 
    if( version_compare( $woo_ver, "3.0", "<" )) return false;
    return true;
}
function is_woo_v305(){
    $woo_ver = nbd_get_woo_version(); 
    if( version_compare( $woo_ver, "3.0.5", "<" )) return false;
    return true;
}
function is_dokan(){
    if(class_exists('WeDevs_Dokan') ){
        return true;
    }
    return false;
}
function nbd_get_dpi($filename){
    if( class_exists('Imagick') ){
        $image = new Imagick($filename);
        $resolutions = $image->getImageResolution();
        $units = $image->getImageUnits();
        if( $units == 2 ){
            if (!empty($resolutions['y'])) {
                $resolutions['y'] = round($resolutions['y'] * 2.54, 2);
            }
            if (!empty($resolutions['x'])) {
                $resolutions['x'] = round($resolutions['x'] * 2.54, 2);
            }
        }
    }else{
        $a = fopen($filename,'r');
        $string = fread($a,20);
        fclose($a);

        $data = bin2hex(substr($string,14,4));
        $x = substr($data,0,4);
        $y = substr($data,4,4);  
        $resolutions = array('x' => hexdec($x), 'y' => hexdec($y));
    }
    $resolutions['x'] = $resolutions['x'] != 0 ? $resolutions['x'] : 72;
    $resolutions['y'] = $resolutions['y'] != 0 ? $resolutions['y'] : 72;
    return $resolutions;
}
/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/web-to-print-online-designer/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/web-to-print-online-designer/templates/$template_name.
 *
 * @since 1.3.1
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	        Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 					Path to the template file.
 */    
function nbdesigner_locate_template($template_name, $template_path = '', $default_path = '') {
    // Set variable to search in web-to-print-online-designer folder of theme.
    if (!$template_path) :
        $template_path = 'web-to-print-online-designer/';
    endif;
    // Set default plugin templates path.
    if (!$default_path) :
        $default_path = NBDESIGNER_PLUGIN_DIR . 'templates/'; // Path to the template folder
    endif;
    // Search template file in theme folder.
    $template = locate_template(array(
        $template_path . $template_name,
        $template_name
    ));
    // Get plugins template file.
    if (!$template) :
        $template = $default_path . $template_name;
    endif;
    return apply_filters('nbdesigner_locate_template', $template, $template_name, $template_path, $default_path);
}
/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.3.1
 *
 * @see wcpt_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args				Args passed for the template file.
 * @param string 	$string $template_path	        Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function nbdesigner_get_template($template_name, $args = array(), $tempate_path = '', $default_path = '') {
    if (is_array($args) && isset($args)) :
        extract($args);
    endif;
    $template_file = nbdesigner_locate_template($template_name, $tempate_path, $default_path);
    if (!file_exists($template_file)) :
        _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.3.1');
        return;
    endif;
    include $template_file;
}
function nbd_get_language($code){
    $data = array();
    $data['mes'] = 'success';    
    $path = NBDESIGNER_PLUGIN_DIR . 'data/language.json';
    $path_data = NBDESIGNER_DATA_DIR . '/data/language.json';
    if(file_exists($path_data)) $path = $path_data;
    $list = json_decode(file_get_contents($path)); 
    $path_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/'.$code.'.json';
    $path_data_lang = NBDESIGNER_DATA_DIR . '/data/language/'.$code.'.json';
    if(file_exists($path_data_lang)) $path_lang = $path_data_lang;
    $path_original_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/en_US.json';
    if(!file_exists($path_lang)) $path_lang = $path_original_lang;
    $lang_original = json_decode(file_get_contents($path_original_lang)); 
    $lang = json_decode(file_get_contents($path_lang));
    if(is_array($lang)){
        $data_langs = (array)$lang[0];
        if(is_array($lang_original)){
            $data_langs_origin = (array)$lang_original[0];
            $data_langs = array_merge($data_langs_origin, $data_langs);
        }
        $data['langs'] = $data_langs;
        if(is_array( $data['langs'] )){
            asort($data['langs']);
        }
        $data['code'] = $code;
    }else{
        $data['mes'] = 'error';
    }
    if(is_array($list)){
        $data['cat'] = $list;
    }else{
        $data['mes'] = 'error';
    }    
    return $data;
}
function nbd_get_license_key(){
    $license = array(
        'key'   =>  ''
    );
    $path = NBDESIGNER_DATA_CONFIG_DIR . '/license.json';
    if( file_exists($path) ){
        $license = (array) json_decode(file_get_contents($path));
    }else{
        $_license = get_option('nbdesigner_license');
        if( $_license ){
            $license = (array) json_decode( $_license );
        }        
    }
    return $license;
}
function nbd_check_license(){
    $license = nbd_get_license_key();
    $result = false;
    if( $license['key'] != '' ){
        $code = (isset($license["code"])) ? $license["code"] : 10;
        if(($code == 5) || ($code == 6)){
            $now = strtotime("now");
            $expiry_date = (isset($license["expiry-date"])) ? $license["expiry-date"] : 0;         
            if($expiry_date > $now){
                $salt = (isset($license['salt'])) ? $license['salt'] : 'somethingiswrong';
                $new_salt = md5($license['key'].'pro');
                if($salt == $new_salt) $result = true;
            }
        }
    }
    return $result;
}
function nbd_active_domain($license_key){
    $url = 'https://cmsmart.net/activedomain/netbase/WPP1074/'.$license_key.'/'.base64_encode(rtrim(get_bloginfo('wpurl'), '/'));
    $result = nbd_file_get_contents($url);
    if($result){
        $path_data = NBDESIGNER_DATA_CONFIG_DIR . '/license.json';
        if (!file_exists(NBDESIGNER_DATA_CONFIG_DIR)) {
            wp_mkdir_p(NBDESIGNER_DATA_CONFIG_DIR);
        }     
        $data = (array) json_decode($result);
        $data['key'] = $license_key;
        if($data['type'] == 'free') $data['number_domain'] = "5";
        $data['salt'] = md5($license_key.$data['type']);  
        $license = json_encode($data);
        //file_put_contents($path_data, $license);
        update_option('nbdesigner_license', $license);  
    }
}
function nbd_is_product( $id ){
    $product = wc_get_product($id);
    if( $product ) return true;
    return false;
}
function is_nbd_product( $pid ){
    if( !nbd_is_product($pid) ) return false;
    $pid = get_wpml_original_id($pid);
    $is_nbdesign = get_post_meta($pid, '_nbdesigner_enable', true); 
    if ($is_nbdesign) {
        return true;
    }
    return false;
}
function nbd_get_default_variation_id( $product_id ){
    $variation_id = 0;
    if( !$product_id ) return $variation_id;
    $product = wc_get_product($product_id);
    if($product->is_type( 'variable' )){
        $available_variations = $product->get_available_variations();   
        if(is_woo_v3()){
            $default_attributes = $product->get_default_attributes();  
        }else{
            $default_attributes = $product->get_variation_default_attributes();  
        } 
        foreach ($default_attributes as $key => $value) {
            if (strpos($key, 'attribute_') === 0) {
                continue;
            }
            unset($default_attributes[$key]);
            $default_attributes[sprintf('attribute_%s', $key)] = $value;
        }
        if (class_exists('WC_Data_Store')) {
            $data_store = WC_Data_Store::load('product');
            return $data_store->find_matching_product_variation($product, $default_attributes);
        } else {          
            return $product->get_matching_variation($default_attributes);
        }
    }
    return $variation_id;
}
function nbd_get_default_variation_id2( $product_id ){
    $variation_id = 0;
    $variations     = wc_get_products( array(
        'status'         => array( 'private', 'publish' ),
        'type'           => 'variation',
        'parent'         => $product_id,
        'limit'          => $per_page,
        'page'           => -1,
        'orderby'        => array(
                'menu_order' => 'ASC',
                'ID'         => 'DESC',
        ),
        'return'         => 'objects',
    ) ); 
    if ( $variations ) {
        foreach ( $variations as $variation_object ) {
            $vid   = $variation_object->get_id();
            $variation_data = array_merge( array_map( 'maybe_unserialize', get_post_custom( $vid ) ), wc_get_product_variation_attributes( $vid ) );
        }
    }
}
function nbd_check_permission(){
    if( isset($_GET['cik']) ){ return true;
        if( (isset($_GET['task']) && $_GET['task'] == 'new') || (isset($_GET['task2']) && $_GET['task2'] == 'update') ) return true;
        if( !(isset($_GET['task2']) && $_GET['task2'] != '') && !WC()->session->get($_GET['cik'] . '_nbd') && !WC()->session->get($_GET['cik'] . '_nbu')) return false;
        if( !(isset($_GET['task']) && $_GET['task'] == 'edit') && !WC()->session->get($_GET['cik'] . '_nbd') && !WC()->session->get($_GET['cik'] . '_nbu')) return false;
    }
    if( isset($_GET['oid']) ){
        $order = wc_get_order(absint($_GET['oid']) ); 
        $uid = get_current_user_id();
        if ($order->get_user_id() != $uid) return false;
    }
    if( isset($_GET['task']) && $_GET['task'] == "create" ){
        if( !can_edit_nbd_template() ) return false;
    }
    return true;
}
function can_edit_nbd_template(){
    if( current_user_can('edit_nbd_template') ) return true;
    $is_nbdesigner = get_user_meta( get_current_user_id(), 'nbd_create_design', true );
    if( $is_nbdesigner == 'on' ) return true;
    return false;
}
function nbd_check_order_permission( $order_id ){
    $order = wc_get_order(absint( $order_id ) ); 
    $uid = get_current_user_id();
    if ($order->get_user_id() != $uid) return false;
    return true;    
}
function get_nbd_variations( $product_id, $include_price = false ){
    $product = wc_get_product( $product_id );
    $variations = array();
    if( $product->is_type( 'variable' ) ) {
        $available_variations = $product->get_available_variations();  
        foreach ($available_variations as $variation){
            $enable = get_post_meta($variation['variation_id'], '_nbdesigner_variation_enable', true);
            if($enable){
                if( is_array( $variation['attributes'] ) ){
                    $new_name = '';
                    $count_empty = 0;
                    foreach ( $variation['attributes'] AS $name => $value ) {
                        //if ( !empty( $value ) ) $new_name .= ucfirst($value).', ';
                        if ( !empty( $value ) ){
                            $taxonomy = esc_attr( str_replace( 'attribute_', '', $name ) );
                            if( taxonomy_exists( $taxonomy ) ){
                                $terms = wc_get_product_terms( $product_id, $taxonomy, array( 'fields' => 'all' ) );
                                foreach( $terms as $term ){
                                    if( $term->slug == $value ) {
                                        $value = $term->name;
                                    }
                                }
                            }
                            $new_name .= ucfirst($value).', ';
                        }else{
                            $count_empty++;
                        }
                    }                    
                    $new_name = substr($new_name, 0, -2);
                    if( $count_empty ==  count($variation['attributes']) ) $new_name = __( 'Any option', 'web-to-print-online-designer' );
                }   
                $var = array(
                    'id'    =>  $variation['variation_id'],
                    'name'  =>  $new_name
                );            
                if( $include_price ){
                    $product = wc_get_product( $variation['variation_id'] );
                    $var['price'] = $product->get_price();
                }
                $variations[] = $var;     
            }
        }   
    }
    return $variations;
}
function nbd_get_max_upload_default(){
    if( function_exists ( 'wp_max_upload_size' ) ){
        return round(wp_max_upload_size() / 1024 / 1024);
    }else{
        return abs( intval( ini_get( 'post_max_size' ) ) );
    }
}
function nbd_check_cart_item_exist( $cart_item_key ){
    global $woocommerce;
    $check = false;
    foreach($woocommerce->cart->get_cart() as $key => $val ) {
        if( $cart_item_key ==  $key) return true;
    }
    return $check;
}
function nbd_die( $result ){
    echo json_encode($result);
    wp_die();
}
function nbd_exec($cmd) {
    $output = array();
    exec("$cmd 2>&1", $output);
    return $output;
}
function get_wpml_original_id( $id, $type = 'post' ){
    if (class_exists('SitePress')) {
        global $sitepress;
        $langcode = $sitepress->get_default_language();
        $id = icl_object_id($id, $type, true, $langcode);
    }
    return $id;
}
function get_wpml_current_id( $id, $type = 'post' ){
    if ( class_exists('SitePress') ) {
        return icl_object_id($id,'post',false);
    } 
    return $id;
}
function nbd_get_artist_info( $user_id ){
    $infos = array();
    $infos['nbd_artist_name'] = get_the_author_meta( 'nbd_artist_name', $user_id );
    $infos['nbd_artist_phone'] = get_the_author_meta( 'nbd_artist_phone', $user_id );
    $infos['nbd_sell_design'] = get_the_author_meta( 'nbd_sell_design', $user_id );
    $infos['nbd_create_design'] = get_the_author_meta( 'nbd_create_design', $user_id );
    $infos['nbd_artist_banner'] = get_the_author_meta( 'nbd_artist_banner', $user_id );
    $infos['nbd_artist_address'] = get_the_author_meta( 'nbd_artist_address', $user_id );
    $infos['nbd_artist_facebook'] = get_the_author_meta( 'nbd_artist_facebook', $user_id );
    $infos['nbd_artist_google'] = get_the_author_meta( 'nbd_artist_google', $user_id );
    $infos['nbd_artist_twitter'] = get_the_author_meta( 'nbd_artist_twitter', $user_id );
    $infos['nbd_artist_linkedin'] = get_the_author_meta( 'nbd_artist_linkedin', $user_id );
    $infos['nbd_artist_youtube'] = get_the_author_meta( 'nbd_artist_youtube', $user_id );
    $infos['nbd_artist_instagram'] = get_the_author_meta( 'nbd_artist_instagram', $user_id );
    $infos['nbd_artist_flickr'] = get_the_author_meta( 'nbd_artist_flickr', $user_id );
    $infos['nbd_artist_commission'] = get_the_author_meta( 'nbd_artist_commission', $user_id );
    $infos['nbd_artist_description'] = get_the_author_meta( 'nbd_artist_description', $user_id );
    return $infos;
}
function nbd_user_logged_in(){
    return is_user_logged_in() ? 1 : 0; 
}
function nbd_get_pages(){
    $pages = get_pages();
    $_pages = array(
        '0' =>  'Default'
    );
    foreach($pages as $page) { 
        $id = $page->ID;
        $_pages[$id] = $page->post_title;
    }
    return $_pages;
}
function is_nbd_designer( $user_id ){
    $can_edit_template = get_user_meta( $user_id, 'nbd_create_design');
    return ( $can_edit_template[0] == 'on' ) ? true : false;
}
function user_can_edit_template( $user_id, $template_id = 0 ){
    $current_user_id = get_current_user_id();
    return ( ($user_id == $current_user_id) && is_nbd_designer($user_id) ) ? true : false;
}
function nbd_get_font_by_alias( $alias ){
    $fonts = array();
    if(file_exists( NBDESIGNER_DATA_DIR . '/fonts.json') ){
        $fonts = (array)json_decode( file_get_contents( NBDESIGNER_DATA_DIR . '/fonts.json' ) );        
    }    
    foreach ($fonts as $font) {
        if ($font->alias == $alias) {
            return $font;
        }
    }
    return false;
}
function nbd_get_products_has_design(){
    if( !is_dokan() ){
        $nbd_products = get_transient('nbd_frontend_products');
        if( false === $nbd_products ){
            $products = nbd_get_all_product_has_design();
            foreach ($products as $pro){
                $product = wc_get_product($pro->ID);
                $type = $product->get_type();
                $image = get_the_post_thumbnail_url($pro->ID, 'post-thumbnail');
                if( !$image ) $image = wc_placeholder_img_src();            
                $result[] = array(
                    'product_id'    =>  $pro->ID,
                    'name'  => $pro->post_title,
                    'src'   =>  $image,
                    'type'  =>  $type,
                    'url'   => get_permalink($pro->ID)
                );
            }
            set_transient( 'nbd_frontend_products' , $result ); 
        }else{
            $result = $nbd_products;
        }
    }else{
        $result = array();
        $products = nbd_get_all_product_of_vendor_has_design();
        foreach ($products as $pro){
            $product = wc_get_product($pro->ID);
            $type = $product->get_type();
            $image = get_the_post_thumbnail_url($pro->ID, 'post-thumbnail');
            if( !$image ) $image = wc_placeholder_img_src();            
            $result[] = array(
                'product_id'    =>  $pro->ID,
                'name'  => $pro->post_title,
                'src'   =>  $image,
                'type'  =>  $type,
                'url'   => get_permalink($pro->ID)
            );
        }
    }
    return $result;
}
function nbd_get_all_product_of_vendor_has_design(){
    $args_query = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'meta_key' => '_nbdesigner_enable',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page'=> -1,
        'author'         => get_current_user_id(),
        'meta_query' => array(
            array(
                'key' => '_nbdesigner_enable',
                'value' => 1,
            )
        )
    ); 
    $posts = get_posts($args_query);  
    return $posts;    
}
function nbd_get_all_product_has_design(){
    $args_query = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'meta_key' => '_nbdesigner_enable',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page'=> -1,
        'meta_query' => array(
            array(
                'key' => '_nbdesigner_enable',
                'value' => 1,
            )
        )
    ); 
    $posts = get_posts($args_query);  
    return $posts;    
}
function nbd_bulk_variations_add_to_cart_message( $count ) {
    if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) :
        $return_to = ( wp_get_referer() ) ? wp_get_referer() : home_url();
        $message   = sprintf( '<a href="%s" class="button">%s</a> %s', $return_to, __( 'Continue Shopping &rarr;', 'web-to-print-online-designer' ), sprintf( __( '%s products successfully added to your cart.', 'web-to-print-online-designer' ), $count ) );
    else :
        $message = sprintf( '<a href="%s" class="button">%s</a> %s', get_permalink( wc_get_page_id( 'cart' ) ), __( 'View Cart &rarr;', 'woocommerce' ), sprintf( __( '%s products successfully added to your cart.', 'web-to-print-online-designer' ), $count ) );
    endif;
    wc_add_notice( $message );
}
function nbd_zip_files_and_download($file_names, $archive_file_name, $nameZip, $option_name = array(), $download = true){
    if(file_exists($archive_file_name)){
        unlink($archive_file_name);
    }        
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
          exit("cannot open <$archive_file_name>\n");
        }
        foreach( $file_names as $file ) {
//            $path_arr = explode('/', $file);
//            $name = $path_arr[count($path_arr) - 2].'_'.$path_arr[count($path_arr) - 1]; 
            
            if( count( $option_name ) ){
                $file_name = pathinfo($file, PATHINFO_FILENAME);
                $file_ext = pathinfo($file, PATHINFO_EXTENSION);  
                foreach ( $option_name as $key => $val ){
                    if( is_int( strpos( $file_name, $key ) ) ) $file_name = $val;
                }
                $name = $file_name .'.'. $file_ext;
            }else{
				$path_arr = explode('/', $file);
                $name = $path_arr[count($path_arr) - 2] . '_' . basename($file);
            }
            $zip->addFile($file, $name);
        }
        $zip->close();
    }else{         
        require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
        $archive = new PclZip($archive_file_name);
        foreach($file_names as $file){
            $path_arr = explode('/', $file);
            $dir = dirname($file).'/';                
            $archive->add($file, PCLZIP_OPT_REMOVE_PATH, $dir, PCLZIP_OPT_ADD_PATH, $path_arr[count($path_arr) - 2]);               
        }            
    }
    if($download){
        if ( !is_file( $archive_file_name ) ){
            header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
            exit;
        } elseif ( !is_readable( $archive_file_name ) ){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            exit;
        } else {
            /*header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
            header("Pragma: public");
            header("Expires: 0");
            header("Accept-Ranges: bytes");
            header("Connection: keep-alive");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-type: application/zip");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=\"".$nameZip."\"");
            header('Content-Length: '.filesize($archive_file_name));
            header("Content-Transfer-Encoding: binary");
            ob_clean();
            @readfile($archive_file_name);*/
			NBD_Download::download_file($archive_file_name, $nameZip);
            exit;    
        }    
    }
}
function nbd_convert_svg_embed( $path ){
    $svgs = Nbdesigner_IO::get_list_files_by_type($path, 1, 'svg');
    $svg_path = $path . '/svg';
    if( !file_exists($svg_path) ) wp_mkdir_p($svg_path);
    foreach ( $svgs as $svg ){
        $svg_name = pathinfo($svg, PATHINFO_BASENAME);
        $new_svg_path = $svg_path.'/'.$svg_name;
        $xdoc = new DomDocument;
        $xdoc->Load($svg);
        /* Embed images */
        $images = $xdoc->getElementsByTagName('image');
        for ($i = 0; $i < $images->length; $i++) {
            $tagName = $xdoc->getElementsByTagName('image')->item($i);
            $attribNode = $tagName->getAttributeNode('xlink:href');
            $img_src = $attribNode->value;
            if(strpos($img_src, "data:image")!==FALSE)
            continue;
            $type = pathinfo($img_src, PATHINFO_EXTENSION);
            $type = ($type =='svg' ) ? 'svg+xml' : $type;
            $data = nbd_file_get_contents($img_src);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $tagName->setAttribute('xlink:href', $base64);
        }
        /* Embed fonts */
        $text_elements = $xdoc->getElementsByTagName('text');
        for ($i = 0; $i < $text_elements->length; $i++) {
            $tagName = $xdoc->getElementsByTagName('text')->item($i);
            $attribNode = $tagName->getAttributeNode('font-family');
            $font_family = $attribNode->value;
            $font = nbd_get_font_by_alias($font_family);
            if( $font ){
                $tagName->setAttribute('font-family', $font->name);
            }
        }
        $new_svg = $xdoc->saveXML();
        file_put_contents($new_svg_path, $new_svg);            
    }
}
function nbd_export_pdfs( $nbd_item_key, $watermark = true, $force = false, $showBleed = 'no' ){
    $path = NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key;
    $_watermark = $watermark ? '-watermark' : '';
    $_force = $force ? '-force' : '';
    $_showBleed = $showBleed == 'yes' ? '-bleed' : '';
    $folder = $path. '/customer-pdfs' .$_watermark.$_force.$_showBleed;
    $output_file = $folder .'/'. $nbd_item_key .'.pdf';
    $result = array();
    if( !file_exists($folder) ) {
        wp_mkdir_p($folder);
    }
    if( 1 /*!file_exists($folder) */){
        require_once(NBDESIGNER_PLUGIN_DIR.'includes/tcpdf/tcpdf.php');
        $datas = unserialize(file_get_contents($path .'/product.json'));
        $option = unserialize(file_get_contents($path .'/option.json'));
        $layout = isset( $option['layout'] ) ? $option['layout'] : 'm';
        $used_font_path = $path. '/used_font.json';
        $used_font = json_decode( file_get_contents($used_font_path) );			
        $path_font = array();
        foreach( $used_font as $font ){
            $font_name = $font->name;
            if( $font->type == 'google' ){
                $path_font = nbd_download_google_font($font_name);;
            }else{
                $has_custom_font = true;
                $_font = nbd_get_font_by_alias($font->alias);
                foreach( $_font->file as $key => $font_file ){
                    $path_font[$key] = NBDESIGNER_FONT_DIR . '/' . $font_file;                       
                }
            }
            $true_type = nbd_get_truetype_fonts();
            if (in_array($font_name, $true_type)) {
                foreach($path_font as $pfont){
                    $fontname = TCPDF_FONTS::addTTFfont($pfont, 'TrueType', '', 32);
                }          
            }else{
                foreach($path_font as $pfont){
                    $fontname = TCPDF_FONTS::addTTFfont($pfont, '', '', 32);
                }       
            }
        } 
        $pdfs = array();
        $unit = get_option('nbdesigner_dimensions_unit');
        if(!$unit) $unit = "cm";   
        $unitRatio = 10;
        $cm2Px = 37.795275591;
        $mm2Px = $cm2Px / 10;
        switch ($unit) {
            case 'mm':
                $unitRatio = 1;
                break;
            case 'in':
                $unitRatio = 25.4;
                break;
            default:
                $unitRatio = 10;
                break;
        }   
        $list_images = Nbdesigner_IO::get_list_images($path, 1);
        foreach ($list_images as $img){
            $name = basename($img);
            $arr = explode('.', $name);
            $_frame = explode('_', $arr[0]);
            $frame = $_frame[1];
            $list_design[$frame] = $img;
        }        
        foreach($datas as $key => $data){
            $contentImage = '';
            if(isset($list_design[$key])) $contentImage = $list_design[$key];                
            $proWidth = $data['product_width'];
            $proHeight = $data['product_height'];
            $bgTop = 0;
            $bgLeft = 0;
            if($proWidth > $proHeight){
                $bgRatio = 500 / $proWidth;
                $bgWidth = 500;
                $bgHeight = round($proHeight * $bgRatio);
                $offsetLeft = 0;
                $offsetTop = round((500 - $bgHeight) / 2);  
                $scale = round(500 / ($unitRatio * $proWidth * $mm2Px), 2);
            }else{
                $bgRatio = 500 / $proHeight;
                $bgHeight = 500;
                $bgWidth = round($proWidth * $bgRatio);
                $offsetTop = 0;
                $scale = round(500 / ($unitRatio * $proHeight * $mm2Px), 2);    
            }

            $pdfs[$key]['background'] = $data['img_src'];
            $pdfs[$key]['bg_type'] = $data['bg_type'];
            $pdfs[$key]['bg_color_value'] = $data['bg_color_value'];
            $pdfs[$key]['bg-top'] = $bgTop;
            $pdfs[$key]['bg-left'] = $bgLeft;
            $pdfs[$key]['bg-height'] = $bgHeight;
            $pdfs[$key]['bg-width'] = $bgWidth;
            $pdfs[$key]['cd-top'] = $data['real_top'] * $unitRatio;
            $pdfs[$key]['cd-left'] = $data['real_left'] * $unitRatio;
            $pdfs[$key]['cd-width'] = $data['real_width'] * $unitRatio;
            $pdfs[$key]['cd-height'] = $data['real_height'] * $unitRatio;
            $pdfs[$key]['customer-design'] = $contentImage;
            $pdfs[$key]['product-width'] = round($proWidth * $unitRatio, 2);
            $pdfs[$key]['product-height'] = round($proHeight * $unitRatio, 2);
            $pdfs[$key]['margin-top'] = $pdfs[$key]['margin-right'] = $pdfs[$key]['margin-bottom'] = $pdfs[$key]['margin-left'] = 0;
            $pdfs[$key]['bleed-top'] = $pdfs[$key]['bleed-bottom'] = $unitRatio * $data['bleed_top_bottom'];      
            $pdfs[$key]['bleed-left'] = $pdfs[$key]['bleed-right'] = $unitRatio * $data['bleed_left_right'];  
            $includeBg = isset($data['include_background']) ? $data['include_background'] : 1;
            $pdfs[$key]['include_background'] = $includeBg;
            if( $includeBg == 0 && $data['bg_type'] == 'image' ){
                $pdfs[$key]['product-width'] = $pdfs[$key]['cd-width'];
                $pdfs[$key]['product-height'] = $pdfs[$key]['cd-height'];
                $pdfs[$key]['cd-left'] = $pdfs[$key]['cd-top'] = 0;
                $pdfs[$key]['include_background'] = 0;
            }
        }
        $mTop = $pdfs[0]["margin-top"];
        $mBottom = $pdfs[0]["margin-bottom"];
        $mLeft = $pdfs[0]["margin-left"];
        $mRight = $pdfs[0]["margin-right"];  
        $bgWidth = $pdfs[0]['product-width'];        
        $bgHeight = $pdfs[0]['product-height'];     
        
        $pWidth = $bgWidth + $mLeft + $mRight;
        $pHeight = $bgHeight + $mTop + $mBottom;
        $pdf_format = array($pWidth, $pHeight);
        if($pWidth > $pHeight){
            $orientation = "L";
        }else {
            $orientation = "P";
        }
        $pdf = new TCPDF($orientation, 'mm', $pdf_format, true, 'UTF-8', false);
        $pdf->SetMargins($mLeft, $mTop, $mRight, true);     
        $pdf->SetCreator( get_site_url() );
        $pdf->SetTitle(get_bloginfo( 'name' ));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);       
        $pdf->SetAutoPageBreak(TRUE, 0);  
        //$pdf->SetFont('roboto', '', 14, '', false);
        
        foreach($pdfs as $key => $_pdf){         
            $customer_design = $_pdf['customer-design'];    
            $bTop = (float)$_pdf['bleed-top'];
            $bLeft = (float)$_pdf['bleed-left'];
            $bRight = (float)$_pdf['bleed-right'];
            $bBottom = (float)$_pdf['bleed-bottom']; 
            $cdTop = (float)$_pdf["cd-top"];
            $cdLeft = (float)$_pdf["cd-left"];
            $cdWidth = (float)$_pdf["cd-width"];
            $cdHeight = (float)$_pdf["cd-height"];               
            $background = $_pdf['background'];     
            $bg_type = $_pdf['bg_type'];       
            $bg_color_value = $_pdf['bg_color_value'];  
            if($bg_type == 'image'){
                $path_bg = (absint($background) > 0 ) ? get_attached_file($background) : Nbdesigner_IO::convert_url_to_path( $background );
            }   
            if(!$force){
                $pdf = new TCPDF($orientation, 'mm', $pdf_format, true, 'UTF-8', false);
                $pdf->SetMargins($mLeft, $mTop, $mRight, true);     
                $pdf->SetCreator( get_site_url() );
                $pdf->SetTitle(get_bloginfo( 'name' ));
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->SetAutoPageBreak(TRUE, 0); 
                //$pdf->SetFont('roboto', '', 14, '', false);
            }
            $pdf->AddPage();             
            if($bg_type == 'image' && $path_bg && $_pdf['include_background']){
                $img_ext = array('jpg','jpeg','png');
                $svg_ext = array('svg');
                $eps_ext = array('eps','ai');
                $check_img = Nbdesigner_IO::checkFileType(basename($path_bg), $img_ext);
                $check_svg = Nbdesigner_IO::checkFileType(basename($path_bg), $svg_ext);
                $check_eps = Nbdesigner_IO::checkFileType(basename($path_bg), $eps_ext);
                $ext = pathinfo($path_bg);
                if($check_img){
                    $pdf->Image($path_bg,$mLeft, $mTop, $bgWidth, $bgHeight, '', '', '', false, '');
                }
                if($check_svg){
                    $pdf->ImageSVG($path_bg, $mLeft,$mTop, $bgWidth, $bgHeight, '', '', '', 0, true);
                }     
                if($check_eps){
                   $pdf->ImageEps($path_bg, $mLeft,$mTop, $bgWidth, $bgHeight, '', true, '', '', 0, true);
                }                 
            }elseif($bg_type == 'color') {
                $pdf->Rect($mLeft, $mTop,  $bgWidth, $bgHeight, 'F', '', hex_code_to_rgb($bg_color_value));
            }
            if($showBleed == 'yes' && nbdesigner_get_option('nbdesigner_bleed_stack') == 1){
                $pdf->Line(0, $mTop + $bTop, $mLeft + $bLeft, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line(0, $mTop + $bgHeight - $bBottom, $mLeft + $bLeft, $mTop + $bgHeight - $bBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($bgWidth + $mLeft - $bRight, $mTop + $bTop, $bgWidth + $mLeft + $mRight, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($bgWidth + $mLeft - $bRight, $mTop + $bgHeight - $bBottom, $bgWidth + $mLeft + $mRight, $mTop + $bgHeight - $bBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bLeft, 0, $mLeft + $bLeft, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bLeft, $mTop + $bgHeight - $bBottom, $mLeft + $bLeft, $mTop + $bgHeight + $mBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bgWidth - $bRight, 0, $mLeft + $bgWidth - $bRight, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bgWidth - $bRight, $mTop + $bgHeight - $bBottom, $mLeft + $bgWidth - $bRight, $mTop + $bgHeight + $mBottom, array('color' => array(0,0,0), 'width' => 0.05));
            }
            if($customer_design != ''){
                $svg = NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key. '/frame_'. $key .'_svg.svg';
                $pdf->ImageSVG($svg, $mLeft + $cdLeft, $mTop + $cdTop, $cdWidth,$cdHeight, '', '', '', 0, true);                     
            }   
            if($showBleed == 'yes' && nbdesigner_get_option('nbdesigner_bleed_stack') == 2){
                $pdf->Line(0, $mTop + $bTop, $mLeft, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line(0, $mTop + $bgHeight - $bBottom, $mLeft, $mTop + $bgHeight - $bBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($bgWidth + $mLeft, $mTop + $bTop, $bgWidth + $mLeft + $mRight, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($bgWidth + $mLeft, $mTop + $bgHeight - $bBottom, $bgWidth + $mLeft + $mRight, $mTop + $bgHeight - $bBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bLeft, 0, $mLeft + $bLeft, $mTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bLeft, $mTop + $bgHeight, $mLeft + $bLeft, $mTop + $bgHeight + $mBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bgWidth - $bRight, 0, $mLeft + $bgWidth - $bRight, $mTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bgWidth - $bRight, $mTop + $bgHeight, $mLeft + $bgWidth - $bRight, $mTop + $bgHeight + $mBottom, array('color' => array(0,0,0), 'width' => 0.05));
            }
            if( $datas[$key]['show_overlay'] == 1 && $datas[$key]['include_overlay'] == 1 && $layout != 'c' ){
                $overlay = Nbdesigner_IO::convert_url_to_path( $datas[$key]['img_overlay'] );
                $check_over_img = Nbdesigner_IO::checkFileType(basename($overlay), array('jpg','jpeg','png'));
                if( $check_over_img ){
                    $pdf->Image($overlay, $mLeft + $cdLeft, $mTop + $cdTop, $cdWidth,$cdHeight, '', '', '', false, '');
                }
            }
            if( $watermark ){
                $watermark_type = nbdesigner_get_option('nbdesigner_pdf_watermark_type');
                if($watermark_type == 1){
                    $watermark_image = nbdesigner_get_option('nbdesigner_pdf_watermark_image');
                    $watermark_file = get_attached_file($watermark_image);
                    if( $watermark_file ){
                        $myPageWidth = $pdf->getPageWidth();
                        $myPageHeight = $pdf->getPageHeight();                        
                        list($watermark_width, $watermark_height) = getimagesize($watermark_file);
                        $watermark_width = $watermark_width/72*25.4;
                        $watermark_height = $watermark_height/72*25.4;
                        $myX = ( $myPageWidth - $watermark_width )/2 > 0 ? ( $myPageWidth - $watermark_width )/2 : 0;
                        $myY = ( $myPageHeight - $watermark_height )/2 > 0 ? ( $myPageHeight - $watermark_height )/2 : 0;
                        $pdf->SetAlpha(0.2);
                        $pdf->StartTransform();
                        $pdf->Rotate(45, $myPageWidth / 2, $myPageHeight / 2);
                        $pdf->Image($watermark_file, $myX, $myY, '', '', '', '', '', false);
                        $pdf->StopTransform();
                        $pdf->SetAlpha(1);
                    }
                }else{
                    $watermark_text = nbdesigner_get_option('nbdesigner_pdf_watermark_text');
                    $vfont = "helvetica";
                    $vfontsize = 20;
                    $vfontbold = "B";         

                    $widthtext = $pdf->GetStringWidth(trim($watermark_text), $vfont, $vfontbold, $vfontsize, false );
                    $widthtextcenter = round(($widthtext * sin(deg2rad(45))) / 2 ,0);
                    $myPageWidth = $pdf->getPageWidth();
                    $myPageHeight = $pdf->getPageHeight();
                    $myX = ( $myPageWidth - $widthtext )/2 > 0 ? ( $myPageWidth - $widthtext )/2 : 0;
                    $myY = $myPageHeight / 2;                
                    $pdf->SetAlpha(0.2);
                    $pdf->StartTransform();
                    $pdf->Rotate(45, $myPageWidth / 2, $myPageHeight / 2);
                    $pdf->SetFont($vfont, $vfontbold, $vfontsize);
                    $pdf->Text($myX, $myY ,trim($watermark_text));
                    $pdf->StopTransform();
                    $pdf->SetAlpha(1);
                }
            }
            if(!$force){
                $output_file = $folder .'/'. $nbd_item_key .'_'.$key.'.pdf';
                $pdf->Output($output_file, 'F');    
                $result[] = $output_file;                 
            }
        }       
        if($force){
            $pdf->Output($output_file, 'F');  
            $result[] = $output_file;    
        }
    }else{
        $result = Nbdesigner_IO::get_list_files( $folder );
    } 
    return $result;
}
function nbd_convert_files( $nbd_item, $type = 'jpg', $dpi = 300 ){
    $path = NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item;
    $path_jpg = $path . '/jpg/';
    if( $type == 'jpg'){
        $path_png = $path . '/png/';
        if( !file_exists($path_png) ){
            Nbdesigner_IO::mkdir($path_png);            
        }else{
            Nbdesigner_IO::delete_folder($path_png);  
            Nbdesigner_IO::mkdir($path_png);     
        }
        if( !file_exists($path_jpg) ){
            Nbdesigner_IO::mkdir($path_jpg);            
        }else{
            Nbdesigner_IO::delete_folder($path_jpg);   
            Nbdesigner_IO::mkdir($path_jpg);  
        }     
        $list =  Nbdesigner_IO::get_list_images($path, 1);
        foreach ($list as $image){
            $image_name = pathinfo($image, PATHINFO_FILENAME);
            $png_with_white_bg = $path_png . $image_name .'.png';  
            $jpg = $path_jpg . $image_name .'.jpg';
            NBD_Image::imagick_add_white_bg($image, $png_with_white_bg);
            NBD_Image::imagick_convert_png2jpg_without_bg($png_with_white_bg, $jpg);
            NBD_Image::imagick_resample($jpg, $jpg, $dpi);                  
        }  
    }else if( $type == 'cmyk'){
        $path_cmyk = $path . '/cmyk/';
        if( !file_exists($path_cmyk) ){
            Nbdesigner_IO::mkdir($path_cmyk);            
        }else{
            Nbdesigner_IO::delete_folder($path_cmyk);   
            Nbdesigner_IO::mkdir($path_cmyk);  
        }             
        $icc_index = $_POST['icc'];
        $list_icc = nbd_get_icc_cmyk_list_file();
        $icc = $list_icc[$icc_index];
        $icc_file = NBDESIGNER_PLUGIN_DIR . 'data/icc/CMYK/' . $icc;
        $list =  Nbdesigner_IO::get_list_images($path_jpg, 1);
        foreach ($list as $image){
            $image_name = pathinfo($image, PATHINFO_FILENAME);
            $cmyk = $path_cmyk . $image_name .'.jpg';
            NBD_Image::imagick_convert_rgb_to_cymk($image, $cmyk);
            if($icc_index){
                NBD_Image::imagick_change_icc_profile($cmyk, $cmyk, $icc_file);
            }                
        }
    }
}
function nbd_add_to_cart( $product_id, $variation_id, $quantity ){
    if( $variation_id > 0) {
        $adding_to_cart = wc_get_product( $product_id );
        $variation_data = wc_get_product_variation_attributes( $variation_id );
        $attributes     = $adding_to_cart->get_attributes();	
        foreach ( $attributes as $attribute ) {
            if ( ! $attribute['is_variation'] ) {
                continue;
            }    
            $taxonomy = 'attribute_' . sanitize_title( $attribute['name'] );
            $valid_value = isset( $variation_data[ $taxonomy ] ) ? $variation_data[ $taxonomy ] : '';
            $variations[ $taxonomy ] = $valid_value;                    
        }
        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id,$quantity, $variation_id, $variations );
        if ( $passed_validation ) {
            $added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );
        }	
    }else{
        $added = WC()->cart->add_to_cart( $product_id, $quantity );
    }
    return $added;
}
function nbd_download_product_designs( $order_id, $order_item_id, $nbd_item_key, $nbu_item_key, $type, $force = false, $showBleed = 'no'  ){
    $option_name = array();
    if( $type != 'files' ){
        $product_data = unserialize(file_get_contents(NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key .'/product.json'));
        foreach ($product_data as $key => $side){
            $option_name['frame_'.$key] = ($key + 1) .'_'. $side['orientation_name'];
        }
    }
    switch ($type) {
        case 'jpg':
            $path_jpg = NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key . '/jpg';
            if( !file_exists($path_jpg) ){
                $option = json_decode( file_get_contents(NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key . '/option.json') );
                nbd_convert_files($nbd_item_key, 'jpg', $option['dpi']);                    
            }
            $files = Nbdesigner_IO::get_list_images( $path_jpg, 1 ); 
            break;
        case 'jpg_cmyk':
            $path_jpg = NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key . '/jpg';
            if( !file_exists($path_jpg) ){
                $option = json_decode( file_get_contents(NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key . '/option.json') );
                nbd_convert_files($nbd_item_key, 'jpg', $option['dpi']);                    
            }
            $path_cmyk = NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key . '/cmyk';
            if( !file_exists($path_cmyk) ){
                nbd_convert_files($nbd_item_key, 'cmyk');                    
            }                
            $files = Nbdesigner_IO::get_list_images( $path_cmyk, 1 ); 
            break;                
        case 'svg':
            $path = NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key;
            $svg_path = $path . '/svg';               
            if( !file_exists($svg_path) ){
                nbd_convert_svg_embed($path);
            }
            $files = Nbdesigner_IO::get_list_files_by_type($svg_path, 1, 'svg');                  
            break;            
        case 'pdf':
            $enable_watermark = nbdesigner_get_option('nbdesigner_enable_pdf_watermark');
            $watermark = $enable_watermark == 'yes' ? true : false;
            if( $enable_watermark == 'before' ){
                $order = wc_get_order($order_id);
                if( $order->get_status() != 'completed' ) $watermark = true;                
            }
            $files = nbd_export_pdfs( $nbd_item_key, $watermark, $force, $showBleed ); 
            break;
        case 'files':
            if( $nbu_item_key != '' ){
                $files = Nbdesigner_IO::get_list_files( NBDESIGNER_UPLOAD_DIR .'/'. $nbu_item_key );  
            }                
            break;            
        default:
            if( $nbd_item_key != '' ){
                $files = Nbdesigner_IO::get_list_images(NBDESIGNER_CUSTOMER_DIR .'/'. $nbd_item_key, 1);          
            }     
    };
    if(count($files) > 0){
        foreach($files as $key => $file){
            $zip_files[] = $file;
        }
        $pathZip = NBDESIGNER_DATA_DIR.'/download/customer-design-'.$order_id.'-'.$order_item_id.'.zip';
        nbd_zip_files_and_download($zip_files, $pathZip, 'customer-design.zip', $option_name);  	
        exit();
    }else{
        $message = ' <a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="wc-forward">' . esc_html__( 'Go to shop', 'woocommerce' ) . '</a>';
        wp_die( $message, __('No file defined', 'web-to-print-online-designer'), array( 'response' => 404 ) );            
    }
}
function nbd_download_google_font( $font_name = ''){
    $path_dst = array(
        'r' =>  NBDESIGNER_FONT_DIR . '/' . $font_name . '.ttf'
    );
    if( !file_exists($path_dst['r']) ){
        $google_font_path = NBDESIGNER_PLUGIN_DIR . '/data/google-fonts-ttf.json';
        $fonts = json_decode( file_get_contents($google_font_path) );         
        $items = $fonts->items;
        foreach ( $items as $item ){
            if( $item->family == $font_name ){
                $font = $item->files;
                break;
            }
        }
        $path_src = isset($font->regular) ? $font->regular : reset($font);
        copy($path_src, $path_dst['r']);
        if( isset($font->italic) ){
            $path_dst['i'] = NBDESIGNER_FONT_DIR . '/' . $font_name . 'i.ttf';
            copy($font->italic, $path_dst['i']);
        }
        if( isset($font->{"700"}) ){
            $path_dst['b'] = NBDESIGNER_FONT_DIR . '/' . $font_name . 'b.ttf';
            copy($font->{"700"}, $path_dst['b']);
        }    
        if( isset($font->{"700italic"}) ){
            $path_dst['bi'] = NBDESIGNER_FONT_DIR . '/' . $font_name . 'bi.ttf';
            copy($font->{"700italic"}, $path_dst['bi']);
        }          
    }    
    return $path_dst;
}
function nbd_custom_notices($command, $mes) {
    switch ($command) {
        case 'success':
            if (!isset($mes))
                $mes = __('Your settings have been saved.', 'web-to-print-online-designer');
            $notice = '<div class="updated notice notice-success is-dismissible">
                            <p>' . $mes . '</p>
                            <button type="button" class="notice-dismiss">
                                <span class="screen-reader-text">Dismiss this notice.</span>
                            </button>				  
                        </div>';
            break;
        case 'error':
            if (!isset($mes))
                $mes = __('Irks! An error has occurred.', 'web-to-print-online-designer');
            $notice = '<div class="notice notice-error is-dismissible">
                            <p>' . $mes . '</p>
                            <button type="button" class="notice-dismiss">
                                <span class="screen-reader-text">Dismiss this notice.</span>
                            </button>				  
                        </div>';
            break;
        case 'notices':
            if (!isset($mes))
                $mes = __('Irks! An error has occurred.', 'web-to-print-online-designer');
            $notice = '<div class="notice notice-warning">
                            <p>' . $mes . '</p>				  
                        </div>';
            break;             
        case 'warning':
            if (!isset($mes))
                $mes = __('Warning.', 'web-to-print-online-designer');
            $notice = '<div class="notice notice-warning is-dismissible">
                            <p>' . $mes . '</p>
                            <button type="button" class="notice-dismiss">
                                <span class="screen-reader-text">Dismiss this notice.</span>
                            </button>				  
                        </div>';
            break;
        default:
            $notice = '';
    }
    return $notice;
}
function nbd_font_subsets(){
    return array(
        'all'   =>  array(
            'name'  =>  'All language',
            'preview_text'  =>  'Abc Xyz',
            'default_font'  =>  'Roboto'
        ),	
        'arabic'   =>  array(
            'name'  =>  'Arabic',
            'preview_text'  =>  'ءيوهن',
            'default_font'  =>  'Cairo'
        ),
        'bengali'   =>  array(
            'name'  =>  'Bengali',
            'preview_text'  =>  'অআইঈউ',
            'default_font'  =>  'Hind Siliguri'
        ),
        'cyrillic'   =>  array(
            'name'  =>  'Cyrillic',
            'preview_text'  =>  'БВГҐД',
            'default_font'  =>  'Roboto'
        ),   
        'cyrillic-ext'   =>  array(
            'name'  =>  'Cyrillic Extended',
            'preview_text'  =>  'БВГҐД',
            'default_font'  =>  'Roboto'
        ),
        'devanagari'   =>  array(
            'name'  =>  'Devanagari',
            'preview_text'  =>  'आईऊऋॠ',
            'default_font'  =>  'Noto Sans'
        ),
        'greek'   =>  array(
            'name'  =>  'Greek',
            'preview_text'  =>  'αβγδε',
            'default_font'  =>  'Roboto'
        ),
        'greek-ext'   =>  array(
            'name'  =>  'Greek Extended',
            'preview_text'  =>  'αβγδε',
            'default_font'  =>  'Roboto'
        ), 
        'gujarati'   =>  array(
            'name'  =>  'Gujarati',
            'preview_text'  =>  'આઇઈઉઊ',
            'default_font'  =>  'Shrikhand'
        ),
        'gurmukhi'   =>  array(
            'name'  =>  'Gurmukhi',
            'preview_text'  =>  'ਆਈਊਏਐ',
            'default_font'  =>  'Baloo Paaji'
        ),
        'hebrew'   =>  array(
            'name'  =>  'Hebrew',
            'preview_text'  =>  'אבגדה',
            'default_font'  =>  'Arimo'
        ),
        'kannada'   =>  array(
            'name'  =>  'Kannada',
            'preview_text'  =>  'ಅಆಇಈಉ',
            'default_font'  =>  'Baloo Tamma'
        ),   
        'khmer'   =>  array(
            'name'  =>  'Khmer',
            'preview_text'  =>  'កខគឃង',
            'default_font'  =>  'Hanuman'
        ),
        'korean'   =>  array(
            'name'  =>  'Korean',
            'preview_text'  =>  '가개갸거게',
            'default_font'  =>  'Nanum Gothic'
        ),
        'latin'   =>  array(
            'name'  =>  'Latin',
            'preview_text'  =>  'Abc Xyz',
            'default_font'  =>  'Roboto'
        ),
        'latin-ext'   =>  array(
            'name'  =>  'Latin Extended',
            'preview_text'  =>  'Abc Xyz',
            'default_font'  =>  'Roboto'
        ), 
        'malayalam'   =>  array(
            'name'  =>  'Malayalam',
            'preview_text'  =>  'അആഇഈഉ',
            'default_font'  =>  'Baloo Chettan'
        ),
        'myanmar'   =>  array(
            'name'  =>  'Myanmar',
            'preview_text'  =>  'ကခဂဃင',
            'default_font'  =>  'Padauk'
        ),
        'oriya'   =>  array(
            'name'  =>  'Oriya',
            'preview_text'  =>  'ଅଆଇଈଉ',
            'default_font'  =>  'Baloo Bhaina'
        ),
        'sinhala'   =>  array(
            'name'  =>  'Sinhala',
            'preview_text'  =>  'අආඇඈඉ',
            'default_font'  =>  'Abhaya Libre'
        ),
        'tamil'   =>  array(
            'name'  =>  'Tamil',
            'preview_text'  =>  'க்ங்ச்ஞ்ட்',
            'default_font'  =>  'Catamaran'
        ), 
        'telugu'   =>  array(
            'name'  =>  'Telugu',
            'preview_text'  =>  'అఆఇఈఉ',
            'default_font'  =>  'Gurajada'
        ),
        'thai'   =>  array(
            'name'  =>  'Thai',
            'preview_text'  =>  'กขคฆง',
            'default_font'  =>  'Kanit'
        ),
        'vietnamese'   =>  array(
            'name'  =>  'Vietnamese',
            'preview_text'  =>  'Abc Xyz',
            'default_font'  =>  'Roboto'
        )       
    );
}
function _nbd_font_subsets(){
    $subsets = array();
    foreach(nbd_font_subsets() as $key => $subset){
        $subsets[$key] = $subset['name'];
    }
    return $subsets;
}
function nbd_get_default_font(){
    $default_fonts =  json_decode( file_get_contents(NBDESIGNER_PLUGIN_DIR . '/data/default-font.json'));
    $subset = nbdesigner_get_option('nbdesigner_default_font_subset');
    $subsets = nbd_font_subsets();
    $font = 'Roboto';   
    foreach($subsets as $key => $sub){
        if( $key == $subset ) $font = $sub['default_font'];
    }
    foreach( $default_fonts as $f ){
        if( $f->name == $font ) return json_encode($f);
    }
}
function nbd_get_fonts( $return = false ){
    $gg_fonts = array();
    $custom_fonts = array();
    if(file_exists(NBDESIGNER_DATA_DIR . '/googlefonts.json')) {
        $_gg_fonts = file_get_contents(NBDESIGNER_DATA_DIR . '/googlefonts.json');
        $_gg_fonts = $_gg_fonts != '' ? $_gg_fonts : '[]';
        $gg_fonts = json_decode( $_gg_fonts );
    }
    if(file_exists(NBDESIGNER_DATA_DIR . '/fonts.json')) {
        $_custom_fonts = file_get_contents(NBDESIGNER_DATA_DIR . '/fonts.json');
        $_custom_fonts = $_custom_fonts != '' ? $_custom_fonts : '[]';
        $custom_fonts = json_decode( $_custom_fonts );
    }
    $fonts = array_merge($gg_fonts,$custom_fonts);
    if( $return ) return $fonts;
    echo json_encode($fonts);
}
function nbd_get_order_object() {
    global $thepostid, $theorder;
    if (!is_object($theorder)) {
        $theorder = wc_get_order($thepostid);
    }
    if (!$theorder && isset($_POST['order_id'])) {
        $order_id = absint($_POST['order_id']);
        $order = wc_get_order($order_id);
        return $order;
    } elseif (!$theorder && isset($_POST['post_ID'])) {
        $order_id = absint($_POST['post_ID']);
        $order = wc_get_order($order_id);
        return $order;
    }
    if (!$theorder) {
        global $post;
        if ($post) {
            $theorder = wc_get_order($post->ID);
        }
    }
    return $theorder;
}
function nbd_get_image_thumbnail( $id ){
    if( absint($id) != 0 ){
        $image = wp_get_attachment_image_src( $id, 'thumbnail' );
        if(!$image){
            $image_url = wp_get_attachment_url($id);
        }else{
            $image_url = $image[0];
        }
    }else{
        $image_url = NBDESIGNER_ASSETS_URL . 'images/placeholder.png';
    }
    return $image_url;
}
function nbd_get_truetype_fonts(){
    $true_type = array('Bruum FY', 'CitadelScriptStd', 'DIN Next LT Pro Light', 'DIN Next LT Pro Medium', 'DIN Next LT Pro Regular', 'Gudea', 'Abel', 'Abril Fatface', 'Acme', 'Advent Pro', 'Aguafina Script', 'Aladin', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amiri', 'Antic', 'Antic Didone', 'Anonymous Pro', 'Antic Slab', 'Arbutus', 'Architects Daughter', 'Aref Ruqaa', 'Arizonia', 'Asset', 'Asul', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Belgrano', 'Bilbo', 'Bilbo Swash', 'Boogaloo', 'Bowlby One', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buenard', 'Butcherman', 'Cagliostro', 'Cambo', 'Cantarell', 'Cardo', 'Caudex', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Cherry Swash', 'Chicle', 'Cinzel', 'Cinzel Decorative', 'Coiny', 'Condiment', 'Contrail One', 'Convergence', 'Cookie', 'Corben', 'Covered By Your Grace', 'Creepster', 'Crete Round', 'Croissant One', 'Damion', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Devonshire', 'Diplomata', 'Diplomata SC', 'Dorsa', 'Dr Sugiyama', 'Economica', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Fanwood Text', 'Farsan', 'Faster One', 'Fauna One', 'Fenix', 'Felipa', 'Fjord One', 'Flamenco', 'Fredericka the Great', 'Fredoka One', 'Fresca', 'Fugaz One', 'Gafata', 'Galdeano', 'Geostar', 'Geostar Fill', 'Germania One', 'Glass Antiqua', 'Goblin One', 'Graduate', 'Gravitas One', 'Great Vibes', 'Handlee', 'Harmattan', 'Herr Von Muellerhoff', 'Holtwood One SC', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Imprima', 'Inika', 'Italiana', 'Italianno', 'Jockey One', 'omhuria', 'Joti One', 'Jomhuria', 'Julee', 'Just Me Again Down Here', 'Katibeh', 'Kavivanar', 'Keania One', 'Kelly Slab', 'Kite One', 'Knewave', 'Kotta One', 'Kreon', 'Krona One', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lilita One', 'Lily Script One', 'Linden Hill', 'Love Ya Like A Sister ', 'Lovers Quarrel', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Marck Script', 'Marko One', 'Marvel', 'Mate', 'Mate SC', 'Medula One', 'Meera Inimai', 'Merienda', 'Merienda One', 'Mina', 'Mirza', 'Miss Fajardose', 'Modern Antiqua', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montserrat', 'Montserrat Subrayada', 'Mountains of Christmas', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Niconne', 'Nixie One', 'Nobile', 'Norican', 'Nosifer', 'Offside', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Orbitron', 'Overlock', 'Overlock SC', 'Ovo', 'Paprika', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Piedra', 'Pinyon Script', 'Pirata One', 'Playball', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Poppins', 'Port Lligat Sans', 'Port Lligat Slab', 'Preahvihear', 'Qwigley', 'Rambla', 'Ranga', 'Reem Kufi', 'Rammetto One', 'Ribeye Marrow', 'Righteous', 'Rochester', 'Rosarivo', 'Rouge Script', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Ruslan Display', 'Russo One', 'Ruthie', 'Sail A', 'Salsa', 'Sanchez', 'Sancreek', 'Sarina', 'Shadows Into Light Two', 'Short Stack', 'Signika Negative', 'Sintony', 'Smokum', 'Snippet', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Spirax', 'Squada One', 'Strait', 'Sunflower', 'Swanky and Moo Moo', 'Text Me One', 'Tinyhust', 'The Girl Next Door', 'Titan One', 'Trochut', 'Trykker', 'Tulpen One', 'Unica One', 'Unlock', 'Vast Shadow', 'Viga', 'Voltaire', 'Wellfleet', 'Wendy One', 'Zeyada', 'Yellowtail');
    $true_type_setting = nbdesigner_get_option('nbdesigner_truetype_fonts');
    if( $true_type_setting != '' ){
        $true_type_setting = preg_split('/\r\n|[\r\n]/', $true_type_setting);
    }else{
        $true_type_setting = array();
    }
    return array_merge($true_type, $true_type_setting);
}
function nbd_admin_pages(){
    return array(
        'toplevel_page_nbdesigner', 
        'nbdesigner_page_nbdesigner_manager_product', 
        'toplevel_page_nbdesigner_shoper',
        'nbdesigner_page_nbdesigner_frontend_translate',
        'nbdesigner_page_nbdesigner_tools',
        'nbdesigner_page_nbdesigner_manager_arts',
        'nbdesigner_page_nbdesigner_manager_fonts',
        'admin_page_nbdesigner_detail_order',
        'nbdesigner_page_nbd_support',
        'nbdesigner_page_nbd_printing_options'
    );
}
function nbd_convert_svg_url($path, $file){
    $svg_path = $path . '/svgpath';
    if( !file_exists($svg_path) ) wp_mkdir_p($svg_path);
    $new_svg_path = $svg_path.'/'.$file;
    $xdoc = new DomDocument;
    $xdoc->Load($path.$file);
    $images = $xdoc->getElementsByTagName('image');
    for ($i = 0; $i < $images->length; $i++) {
        $tagName = $xdoc->getElementsByTagName('image')->item($i);
        $attribNode = $tagName->getAttributeNode('xlink:href');
        $img_src = $attribNode->value;
        if(strpos($img_src, "data:image")!==FALSE)
        continue;
        $type = pathinfo($img_src, PATHINFO_EXTENSION);
        $type = ($type =='svg' ) ? 'svg+xml' : $type;
        $path_image = Nbdesigner_IO::convert_url_to_path($img_src);
        $tagName->setAttribute('xlink:href', $path_image);
    }
    $new_svg = $xdoc->saveXML();
    file_put_contents($new_svg_path, $new_svg); 
}
function nbd_get_product_layout($pid){
    $layout = nbdesigner_get_option('nbdesigner_design_layout');
    $option = unserialize(get_post_meta($pid, '_nbdesigner_option', true));
    $layout =  isset($option['layout']) ? $option['layout'] : $layout;
    return $layout;
}
function is_nbd_product_with_vista_layout( $pid ){
    $is_vista = false;
    if (is_nbdesigner_product($pid)) {
        $option = unserialize(get_post_meta($pid, '_nbdesigner_option', true));
        if( isset($option['layout']) && $option['layout'] == 'v' ){
            $is_vista = true;
        }
    }
    return $is_vista;
}
function nbd_get_user_information( $id = false ){
    $infos = array();
    $current_user = wp_get_current_user();
    $id = $id ? $id : $current_user->ID;
    if( 0 != $id ){
        $customer = new WC_Customer( $id );
        $customer_data = array(
            'email'            => $customer->get_email(),
            'first_name'       => $customer->get_first_name(),
            'last_name'        => $customer->get_last_name(),
            'username'         => $customer->get_username(),
            'avatar_url'       => $customer->get_avatar_url(),
            'website'          => get_the_author_meta( 'url', $id ),
            'billing_address'  => array(
                'first_name' => $customer->get_billing_first_name(),
                'last_name'  => $customer->get_billing_last_name(),
                'company'    => $customer->get_billing_company(),
                'address_1'  => $customer->get_billing_address_1(),
                'address_2'  => $customer->get_billing_address_2(),
                'city'       => $customer->get_billing_city(),
                'state'      => $customer->get_billing_state(),
                'postcode'   => $customer->get_billing_postcode(),
                'country'    => $customer->get_billing_country(),
                'email'      => $customer->get_billing_email(),
                'phone'      => $customer->get_billing_phone(),
            ),
            'shipping_address' => array(
                'first_name' => $customer->get_shipping_first_name(),
                'last_name'  => $customer->get_shipping_last_name(),
                'company'    => $customer->get_shipping_company(),
                'address_1'  => $customer->get_shipping_address_1(),
                'address_2'  => $customer->get_shipping_address_2(),
                'city'       => $customer->get_shipping_city(),
                'state'      => $customer->get_shipping_state(),
                'postcode'   => $customer->get_shipping_postcode(),
                'country'    => $customer->get_shipping_country(),
            ),
            'title' =>  get_the_author_meta( 'acf[field_5b98eb8deeedc]', $id ),
            'mobile' =>  get_the_author_meta( 'acf[field_5b98eceeac859]', $id )
        );
        $customer_data = apply_filters('nbd_customer_data', $customer_data);
        /* Validate data */
        $infos['full_name'] = array(
            'title' =>  __('Full name', 'web-to-print-online-designer'),
            'value' => ($customer_data['shipping_address']['first_name'] == '' ? ($customer_data['billing_address']['first_name'] != '' ? $customer_data['billing_address']['first_name'] : $customer_data['first_name']) : $customer_data['shipping_address']['first_name']) . ' ' . ($customer_data['shipping_address']['last_name'] == '' ? ($customer_data['billing_address']['last_name'] != '' ? $customer_data['billing_address']['last_name'] : $customer_data['last_name']) : $customer_data['shipping_address']['last_name'])
        );        
        $infos['first_name'] = array(
            'title' =>  __('First name', 'web-to-print-online-designer'),
            'value' => $customer_data['shipping_address']['first_name'] == '' ? ($customer_data['billing_address']['first_name'] != '' ? $customer_data['billing_address']['first_name'] : $customer_data['first_name']) : $customer_data['shipping_address']['first_name']
        );
        $infos['last_name'] = array(
            'title' =>  __('Last name', 'web-to-print-online-designer'),
            'value' => $customer_data['shipping_address']['last_name'] == '' ? ($customer_data['billing_address']['last_name'] != '' ? $customer_data['billing_address']['last_name'] : $customer_data['last_name']) : $customer_data['shipping_address']['last_name']
        );
        $infos['company'] = array(
            'title' =>  __('Company', 'web-to-print-online-designer'),
            'value' => $customer_data['shipping_address']['company'] == '' ? ($customer_data['billing_address']['company'] != '' ? $customer_data['billing_address']['company'] : __('Company', 'web-to-print-online-designer')) : $customer_data['shipping_address']['company']
        );
        $infos['address'] = array(
            'title' =>  __('Address', 'web-to-print-online-designer'),
            'value' => $customer_data['shipping_address']['address_1'] == '' ? ($customer_data['billing_address']['address_1'] != '' ? $customer_data['billing_address']['address_1'] : __('Address', 'web-to-print-online-designer')) : $customer_data['shipping_address']['address_1']
        );
        $infos['postcode'] = array(
            'title' =>  __('Postcode', 'web-to-print-online-designer'),
            'value' => $customer_data['shipping_address']['postcode'] == '' ? ($customer_data['billing_address']['postcode'] != '' ? $customer_data['billing_address']['postcode'] : __('12345', 'web-to-print-online-designer')) : $customer_data['shipping_address']['postcode']
        );
        $infos['city'] = array(
            'title' =>  __('City', 'web-to-print-online-designer'),
            'value' => $customer_data['shipping_address']['city'] == '' ? ($customer_data['billing_address']['city'] != '' ? $customer_data['billing_address']['city'] : __('City Name', 'web-to-print-online-designer')) : $customer_data['shipping_address']['city']
        );
        $infos['phone'] = array(
            'title' =>  __('Phone', 'web-to-print-online-designer'),
            'value' => $customer_data['billing_address']['phone'] != '' ? $customer_data['billing_address']['phone'] : __('+012 346 6789', 'web-to-print-online-designer')
        );
        $infos['email'] = array(
            'title' =>  __('Email', 'web-to-print-online-designer'),
            'value' => $customer_data['billing_address']['email'] != '' ? $customer_data['billing_address']['email'] : __('example@website.com', 'web-to-print-online-designer')
        );
        $infos['mobile'] = array(
            'title' =>  __('Mobile', 'web-to-print-online-designer'),
            'value' => $customer_data['mobile'] != '' ? $customer_data['mobile'] : __('+012 346 6789', 'web-to-print-online-designer')
        );
        $infos['website'] = array(
            'title' =>  __('Website', 'web-to-print-online-designer'),
            'value' => $customer_data['website'] != '' ? $customer_data['website'] : __('website.com', 'web-to-print-online-designer')
        );
        $infos['title'] = array(
            'title' =>  __('Title', 'web-to-print-online-designer'),
            'value' => $customer_data['title'] != '' ? $customer_data['title'] : __('Title', 'web-to-print-online-designer')
        ); 
        $infos['country'] = array(
            'title' =>  __('Country', 'web-to-print-online-designer'),
            'value' => $customer_data['shipping_address']['country'] == '' ? ($customer_data['billing_address']['country'] != '' ? $customer_data['billing_address']['country'] : __('Country', 'web-to-print-online-designer')) : $customer_data['shipping_address']['country']
        );
        $infos = apply_filters('nbd_customer_infos', $infos);        
    }
    return $infos;
}
function nbd_get_users_by_country_code( $country_code ){
    $all_users = get_users(array( 'fields' => array( 'ID' ) ));
    $users = array();
    foreach($all_users as $user){
        $cid = $user->ID;
        //$customer = new WC_Customer( $cid );
        //$cc = $customer->get_billing_country();
        $acf_fields = get_field_objects('user_' . $cid);
        $countries = isset( $acf_fields['country'] ) ? $acf_fields['country']['value'] : array(0 => 'se');
        foreach($countries as $con){
            if(strtolower($con) == strtolower($country_code) ){
                $contact_infos = nbd_get_user_information($cid);
                $contact_acf_fields = get_field_objects('user_' . $cid);
                $users[] = array(
                    'c_full_name' =>  $contact_infos['full_name']['value'],
                    'c_title' =>  (isset( $contact_acf_fields['user_title'] ) && $contact_acf_fields['user_title']['value'] != '') ? $contact_acf_fields['user_title']['value'] : __('Title', 'web-to-print-online-designer'),
    //                    'c_mobile' =>  isset( $contact_acf_fields['user_mobile'] ) ? 'Mob ' . $contact_acf_fields['user_mobile']['value'] : 'Mob ' . __('0123456789', 'web-to-print-online-designer'),
                    'c_phone' => 'Tel ' . $contact_infos['phone']['value'],
                    'c_email' =>  $contact_infos['email']['value'],
                    'c_avatar'    =>  nbd_convert_tif_to_png($contact_infos['email']['value'] . '.tif', 'small')
                );
                break;
            }
        }
    }
    return $users;
}
function nbd_get_user_contact_sheet( $id = false ){
    $current_user = wp_get_current_user();
    $id = $id ? $id : $current_user->ID;
    $acf_fields = get_field_objects('user_' . $id);
    $user_infos = nbd_get_user_information($id);
    $infos = array(
        'full_name' =>  $user_infos['full_name']['value'],
        'title' =>  isset( $acf_fields['user_title'] ) ? $acf_fields['user_title']['value'] : __('Title', 'web-to-print-online-designer'),
        'mobile' =>  isset( $acf_fields['user_mobile'] ) ? $acf_fields['user_mobile']['value'] : __('0123456789', 'web-to-print-online-designer'),
        'phone' =>  $user_infos['phone']['value'],
        'email' =>  $user_infos['email']['value'],
        'date'  =>  date("Y-m-d"),
        'contact'   =>  array(),
        'avatar'    =>  nbd_convert_tif_to_png($user_infos['email']['value'] . '.tif')
    );
    $contactsheet = isset( $acf_fields['contactsheet'] ) ? $acf_fields['contactsheet']['value'] : array();
    $infos['first_con'] = 'se';
    $infos['contactsheet'] = $contactsheet;
    foreach($contactsheet as $con){
        $infos['contact'][$con] = array();
        if( isset( $acf_fields[$con . '_contact'] ) && count($acf_fields[$con . '_contact']['value']) ){
            foreach ($acf_fields[$con . '_contact']['value'] as $key => $cid){
                $contact_infos = nbd_get_user_information($cid);
                $contact_acf_fields = get_field_objects('user_' . $cid);
                $infos['contact'][$con][$key] = array(
                    'c_full_name' =>  $contact_infos['full_name']['value'],
                    'c_title' =>  (isset( $contact_acf_fields['user_title'] ) && $contact_acf_fields['user_title']['value'] != '') ? $contact_acf_fields['user_title']['value'] : __('Title', 'web-to-print-online-designer'),
                    'c_mobile' =>  isset( $contact_acf_fields['user_mobile'] ) ? 'Mob ' . $contact_acf_fields['user_mobile']['value'] : 'Mob ' . __('0123456789', 'web-to-print-online-designer'),
//                    'c_phone' => 'Tel ' . $contact_infos['phone']['value'],
                    'c_email' =>  $contact_infos['email']['value'],
                    'c_avatar'    =>  nbd_convert_tif_to_png($contact_infos['email']['value'] . '.tif', 'small')
                );
            }
        }
    }
    if( count($contactsheet) ){
        $infos['first_con'] = $contactsheet[0];
    }else{
        $infos['contact'][$infos['first_con']] = array();
    }
    $infos['avatar'] = nbd_convert_tif_to_png($user_infos['email']['value'] . '.tif');
    return $infos;
}
function nbd_convert_tif_to_png( $file, $type = 'big' ){
    $mini = $type == 'small' ? 'minibilder/' : '';
    $path = WP_CONTENT_DIR . '/images/personalbilder/' .$mini;
    $url = WP_CONTENT_URL . '/images/personalbilder/' .$mini;
    $origin_path = $path . $file;	
    if( file_exists($origin_path) ){
        $filename = pathinfo($file,  PATHINFO_FILENAME );
        $png_path = $path . $filename . '.jpg';
        $png_url = $url . $filename . '.jpg';
        if( !file_exists($png_path) ){
            $image = new Imagick($origin_path. '[0]');
            $identify = $image->identifyImage();
            if( $identify['type'] != 'TrueColor' ){
                    $image->setImageBackgroundColor('white');
                    $image->setImageAlphaChannel(11);
                    $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            }			
            $image->setImageFormat('jpg');
            $image->writeImage($png_path);
            $image->destroy();
        }
        return $png_url;
    }
    return WP_CONTENT_URL . '/images/personalbilder/dummy.jpg';
}
function nbd_get_redirect_url(){
    $rd                 = $_GET['rd'];
    switch( $rd ){
        case 'cart':
            $cart_item_key      = $_GET['cik'];
            $id                 = 'nbd' . $cart_item_key;
            $redirect_url       = wc_get_cart_url().'#'.$id;
            break;
        case 'checkout':
            $cart_item_key      = $_GET['cik'];
            $id                 = 'nbd' . $cart_item_key;
            $redirect_url       = wc_get_checkout_url().'#'.$id;
            break;
        case 'order':
            $order_id           = $_GET['oid'];
            $item_id            = $_GET['item_id'];
            $redirect_url       = wc_get_endpoint_url( 'view-order', $order_id, wc_get_page_permalink( 'myaccount' ) ) . '#' . $item_id;
            break;
        case 'designer':
            $aid                = $_GET['aid'];
            $redirect_url       = add_query_arg(array('id' => $aid), getUrlPageNBD('designer'));
            break;
        case 'designer_template':
            $aid                = $_GET['aid'];
            $template_id        = $_GET['template_id'];
            $redirect_url       = add_query_arg(array('id' => $aid, 'template_id' => $template_id), getUrlPageNBD('designer'));
            break;
        case 'admin_templates':
            $product_id         = $_GET['product_id'];
            $redirect_url       = add_query_arg(array('pid' => $product_id, 'view' => 'templates'), admin_url('admin.php?page=nbdesigner_manager_product'));
            break;
        case 'admin_order':
            $arr                = array('nbd_item_key' => $_GET['nbd_item_key'], 'order_id'    =>  $_GET['order_id'], 'product_id'  =>  $_GET['product_id'], 'variation_id'  =>  $_GET['variation_id']);
            $redirect_url       = add_query_arg($arr, admin_url('admin.php?page=nbdesigner_detail_order'));
            break;
        case 'my_design':
            $current_page       = isset( $_GET['current_page'] ) ? $_GET['current_page'] : 1;
            $redirect_url       = wc_get_endpoint_url( 'my-designs', $current_page, wc_get_page_permalink( 'myaccount' ) );
            break;
        case 'my_design_detail':
            $design_id          = $_GET['design_id'];
            $redirect_url       = urlencode(wc_get_endpoint_url( 'view-design', $design_id, wc_get_page_permalink( 'myaccount' ) ));
            break;
        default:
            $redirect_url       = $rd;
            break;
    }
    return $redirect_url;
}