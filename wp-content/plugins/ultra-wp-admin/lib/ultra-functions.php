<?php
/**
 * @Package: WordPress Plugin
 * @Subpackage: Ultra WordPress Admin Theme
 * @Since: Ultra 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Ultra WordPress Admin Theme Plugin.
 */
?>
<?php

/* 
 * Function to select the CSS theme file based on option panel settings
 * Also it can regenerate custom CSS file and enqueue 
 *  
 */


function ultra_core(){

    global $ultra_css_ver;
    global $ultraadmin;

    $login_screen = "custom"; 
    if(isset($ultraadmin['enable-login']) && $ultraadmin['enable-login'] != "1" && $ultraadmin['enable-login'] == "0" && !$ultraadmin['enable-login']){ 
        $login_screen = "default"; 
    }


        if($ultra_css_ver != ""){

            add_action('admin_enqueue_scripts', 'ultra_scripts', 1);

            add_action('admin_enqueue_scripts', 'ultra_logo', 99);
            add_action('admin_enqueue_scripts', 'ultra_admintopbar', 1);
            add_action('admin_enqueue_scripts', 'ultra_admintopbar_links', 1);
            add_action('wp_enqueue_scripts', 'ultra_wptopbar', 1);
            add_action('admin_enqueue_scripts', 'ultra_page_loader', 1);
            add_action('admin_enqueue_scripts', 'ultra_fonts', 99);
            add_action('admin_enqueue_scripts', 'ultra_admin_css', 99);
            add_action('admin_enqueue_scripts', 'ultra_favicon', 99);
            // add_action('admin_enqueue_scripts', 'ultraadmin_access', 99);

            remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
            if($login_screen == "custom")
            {
                add_action('login_enqueue_scripts', 'ultra_custom_login',99);
            }

            ultra_dynamic_css_settings();

        } else {
            echo "<script type='text/javascript'>console.log('Ultra WP Admin: WordPress Version Not Supported Yet!');</script>";
        }
}



function ultra_dynamic_css_settings() {

    global $ultra_css_ver;

    	global $ultraadmin;
    	//echo "<pre>"; print_r($ultraadmin); echo "</pre>"; 

        $csstype = ultra_dynamic_css_type();
        
        if (isset($csstype) && $csstype != "custom") {
    	    // enqueue default/ inbuilt CSS styles
    		add_action('admin_enqueue_scripts', 'ultra_default_css_colors', 99);

        } else {
        	
        	// load custom CSS style generated dynamically

    		$css_dir = trailingslashit(plugin_dir_path(__FILE__).'../'.$ultra_css_ver);

            if (is_writable($css_dir)) {
                //write the file if isn't there
                if (!file_exists($css_dir . '/ultra-colors.css')) {
                    ultra_regenerate_dynamic_css_file();
                }
    			add_action('admin_enqueue_scripts', 'ultra_dynamic_enqueue_style', 99);
            } else {
    			add_action('admin_head', 'ultra_wp_head_css');
            }
        }
   
}





function ultra_scripts(){
    global $ultraadmin;
        $url = plugins_url('/', __FILE__).'../js/ultra-scripts.js';
        wp_deregister_script('ultra-scripts-js');
        wp_register_script('ultra-scripts-js', $url);
        wp_enqueue_script('ultra-scripts-js');

        $url = plugins_url('/', __FILE__).'../js/ultra-smoothscroll.min.js';
        wp_deregister_script('ultra-smoothscroll-js');
        wp_register_script('ultra-smoothscroll-js', $url);
        wp_enqueue_script('ultra-smoothscroll-js');


    if (file_exists(plugin_dir_path(__FILE__) . '../demo-settings/ultra-settings-panel-css.css')) {
        wp_deregister_style('ultra-settings-panel-css');
        wp_register_style('ultra-settings-panel-css', plugins_url('/', __FILE__) . "../demo-settings/ultra-settings-panel-css.css");
        wp_enqueue_style('ultra-settings-panel-css');
    }
    
    if (file_exists(plugin_dir_path(__FILE__) . '../demo-settings/ultra-settings-panel-js.js')) {
        wp_deregister_script('ultra-settings-panel-js');
        wp_register_script('ultra-settings-panel-js', plugins_url('/', __FILE__) . "../demo-settings/ultra-settings-panel-js.js");
        wp_enqueue_script('ultra-settings-panel-js');
    }


}



function ultra_admin_css()
{
    global $ultra_css_ver;

    $url = plugins_url('/', __FILE__).'../'.$ultra_css_ver.'/ultra-admin.css';
    wp_deregister_style('ultra-admin', $url);
    wp_register_style('ultra-admin', $url);
    wp_enqueue_style('ultra-admin');
}

function ultra_color()
{
    global $ultra_css_ver;
    global $ultraadmin;
    global $ultra_color;

    $csstype = ultra_dynamic_css_type();
    
    if (isset($csstype) && $csstype != "custom" && trim($csstype) != "")
    {
        $dyn_data = $ultra_color[$csstype];
        $ultraadmin = ultra_newdata($dyn_data);
    }
    return $ultraadmin;
}

function ultra_dynamic_css_type(){
    global $ultra_css_ver;
    global $ultraadmin;
    $csstype = "custom";
    if(isset($ultraadmin['dynamic-css-type'])){
        $csstype = $ultraadmin['dynamic-css-type'];
    } 


    /* --------------- Ultra Settings Panel ---------------- */
   if(!has_action('plugins_loaded', 'ultra_regenerate_all_dynamic_css_file') && has_action('admin_footer', 'ultra_admin_footer_function')){
        if (file_exists(plugin_dir_path(__FILE__) . '../demo-settings/ultra-settings-panel-session.php')) {
            include( trailingslashit(dirname( __FILE__ )) . '../demo-settings/ultra-settings-panel-session.php' );
        }
    }
    return $csstype;
}


function ultra_default_css_colors() {
    global $ultra_css_ver;
    global $ultraadmin;
    $csstype = ultra_dynamic_css_type();

    $css_path = trailingslashit(plugins_url('/', __FILE__).'../'.$ultra_css_ver.'/colors');
	$css_dir = trailingslashit(plugin_dir_path(__FILE__).'../'.$ultra_css_ver.'/colors');

    if (isset($csstype) && $csstype != "custom" && trim($csstype) != "") {
        
        $style_color = trim($csstype);
        
        if(file_exists($css_dir . 'ultra-colors-' . $style_color . '.css'))
        {
            // check if file exists or not

            // deregister default wp admin color skins
//            wp_deregister_style('colors');
            wp_deregister_style('ultra-colors');
            wp_register_style('ultra-colors', $css_path . 'ultra-colors-' . $style_color . '.css');
            wp_enqueue_style('ultra-colors');
        } else {
            // enqueue the default ultra-colors.css file   
            ultra_dynamic_enqueue_style();   
        }
    }
}


function ultra_dynamic_enqueue_style()
{
    global $ultra_css_ver;

        // deregister default wp admin color skins
//    wp_deregister_style('colors');

	$url = plugins_url('/', __FILE__).'../'.$ultra_css_ver.'/ultra-colors.css';
    wp_deregister_style('ultra-colors');
    wp_register_style('ultra-colors', $url);
    wp_enqueue_style('ultra-colors');

    $style_type = 'custom';

}


function ultra_wp_head_css() {

    global $ultra_css_ver;
    global $ultraadmin;

    echo '<style type="text/css">';

    $dynamic_css_file = trailingslashit(plugin_dir_path(__FILE__).'../'.$ultra_css_ver) . 'dynamic_css.php';

    // buffer css 
    ob_start();
    require($dynamic_css_file); // Generate CSS
    $dynamic_css = ob_get_contents();
    ob_get_clean();

    // compress css
    $dynamic_css = ultra_compress_css($dynamic_css);

    echo $dynamic_css;
    echo '</style>';

    $style_type = 'custom';
}


/* ------------ Generate / Update dynamic CSS file on saving / changing plugin settings ----------*/
function ultra_regenerate_dynamic_css_file($newultraadmin = array(),$filename = "",$basedir = "") {

    global $ultra_css_ver;
    global $ultraadmin;
    if (is_array($newultraadmin) && sizeof($newultraadmin) > 0) {
        $ultraadmin = $newultraadmin;
    }

    global $ultra_color;
    
    $newfilename = "ultra-colors";
    if(trim($filename) != ""){$newfilename = "ultra-colors-".$filename;}

    $dynamic_css = trailingslashit(plugin_dir_path(__FILE__).'../'.$ultra_css_ver) . 'dynamic_css.php';
    ob_start(); // Capture all output (output buffering)
    require($dynamic_css); // Generate CSS
    $css = ob_get_clean(); // Get generated CSS (output buffering)
    $css = ultra_compress_css($css);

	$css_dir = trailingslashit(plugin_dir_path(__FILE__).'../'.$ultra_css_ver);

    if(isset($basedir) && $basedir != ""){
        $css_dir = $basedir;
    }

    WP_Filesystem();
    global $wp_filesystem;
    if (!$wp_filesystem->put_contents($css_dir . '/'.$newfilename.'.css', $css, 0644)) {
        return true;
    }

}


function ultra_compress_css($css) {
    //return $css;
    /* remove comments */
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

    /* remove tabs, spaces, newlines, etc. */
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    return $css;
}



/*******************
* ultra_regenerate_all_dynamic_css_file();
* Generate all Colors CSS files Function
* Function called in main plugin file
*********************/

function ultra_regenerate_all_dynamic_css_file(){

    global $ultra_css_ver;
    global $ultraadmin;
    $ultraadmin_backup = $ultraadmin;
    global $ultra_color;

	$basedir = trailingslashit(plugin_dir_path(__FILE__).'../'.$ultra_css_ver.'/colors');
    // loop through each color
    foreach($ultra_color as $filename => $dyn_data)
    {
        $ultraadmin = ultra_newdata($dyn_data);
        //echo $filename."<pre>";
        //print_r($ultraadmin);
        //echo "</pre>";

        //regenerate new css file
        ultra_regenerate_dynamic_css_file($ultraadmin,$filename,$basedir);
        $ultraadmin = $ultraadmin_backup;
    }
    
    // V. Imp to restore the original $data in variable back.
    $ultraadmin = $ultraadmin_backup;
    //die;
}



function ultra_newdata($dyn_data)
{

    global $ultra_css_ver;
    global $ultraadmin;
    //print_r($dyn_data);
        // loop through dynamic values
        foreach($dyn_data as $type => $val)
        {
            // string type options
            if(!is_array($val) && trim($val) != "")
            {
                $ultraadmin[$type] = $val;
            }
            
            // array type options
            if(is_array($val) && sizeof($val) > 0)
            {
                foreach($val as $type2 => $val2)
                {
                    if(!is_array($val2) && trim($val2) != "")
                    {
                        $ultraadmin[$type][$type2] = $val2;
                    }
                }
            }
        }
        
        return $ultraadmin;
}


function ultraadmin_access(){

       global $ultraadmin;
       $str = "";

        $element = 'enable-allusers-ultraadmin';
        if(isset($ultraadmin[$element]) && $ultraadmin[$element] != "1" && $ultraadmin[$element] == "0" && !$ultraadmin[$element]){
            if(!is_admin()){
                $str .= ".toplevel_page__ultraoptions{display:none;}";
                $str .= "#wp-admin-bar-_ultraoptions{display:none;}";
            }
        }

        echo "<style type='text/css'>".$str."</style>";
}



//function ultra_adminmenu_rearrange() {
//    global $menu;
    //return $menu;
/*    $menu[6] = $menu[5];
    $menu[5] = $menu[20];
    unset($menu[20]);*/
//}
            



?>