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

function ultra_admintopbar(){
    global $ultraadmin;

    if(isset($ultraadmin['enable-topbar']) && $ultraadmin['enable-topbar'] != "1" && $ultraadmin['enable-topbar'] == "0" && !$ultraadmin['enable-topbar']){
        echo "<style type='text/css'>#wpadminbar{display: none !important;} html.wp-toolbar{padding-top:0px !important;} </style>";
    }
}



function ultra_wptopbar(){
    global $ultraadmin;

    if(isset($ultraadmin['enable-topbar-wp']) && $ultraadmin['enable-topbar-wp'] != "1" && $ultraadmin['enable-topbar-wp'] == "0" && !$ultraadmin['enable-topbar-wp']){
        remove_action('wp_footer', 'wp_admin_bar_render', 9);
        add_filter('show_admin_bar', '__return_false');
    }

}



function ultra_admintopbar_style(){
    global $ultraadmin;

    if(isset($ultraadmin['topbar-style']) && $ultraadmin['topbar-style'] == "style2"){
        return "#adminmenuback{z-index: 99998 !important;} 
        #adminmenuwrap{margin-top: -40px !important;z-index: 99999 !important;} 
        .folded #wpadminbar{padding-left: 46px !important;} 
        #wpadminbar{padding-left: 230px !important;z-index: 9999 !important;}
        .menu-hidden #wpadminbar{padding-left: 0px !important;}         
        .menu-expanded #wpadminbar{padding-left: 230px !important;}         
        .menu-collapsed #wpadminbar{padding-left: 46px !important;} 
        
        .rtl #adminmenuback{z-index: 99998 !important;} 
        .rtl #adminmenuwrap{margin-top: -40px !important;z-index: 99999 !important;} 
        .rtl.folded #wpadminbar{padding-right: 46px !important;padding-left: 0px!important;} 
        .rtl #wpadminbar{padding-right: 230px !important;padding-left: 0px !important;z-index: 9999 !important;}
        .rtl.menu-hidden #wpadminbar{padding-left: 0px !important;padding-right: 0px !important;}         
        .rtl.menu-expanded #wpadminbar{padding-right: 230px !important;padding-left: 0px !important;}         
        .rtl.menu-collapsed #wpadminbar{padding-right: 46px !important;padding-left: 0px !important;}
        ";
    }
}



function ultra_admintopbar_links(){
        global $ultraadmin;
        $str = "";

        $element = 'enable-topbar-links-wp';
        if(isset($ultraadmin[$element]) && $ultraadmin[$element] != "1" && $ultraadmin[$element] == "0" && !$ultraadmin[$element]){
            $str .= "#wp-admin-bar-wp-logo{display:none;}";
        }
        
        $element = 'enable-topbar-links-site';
        if(isset($ultraadmin[$element]) && $ultraadmin[$element] != "1" && $ultraadmin[$element] == "0" && !$ultraadmin[$element]){
            $str .= "#wp-admin-bar-site-name{display:none;}";
        }

        $element = 'enable-topbar-links-comments';
        if(isset($ultraadmin[$element]) && $ultraadmin[$element] != "1" && $ultraadmin[$element] == "0" && !$ultraadmin[$element]){
            $str .= "#wp-admin-bar-comments{display:none;}";
        }

        $element = 'enable-topbar-links-new';
        if(isset($ultraadmin[$element]) && $ultraadmin[$element] != "1" && $ultraadmin[$element] == "0" && !$ultraadmin[$element]){
            $str .= "#wp-admin-bar-new-content{display:none;}";
        }

        $element = 'enable-topbar-links-ultraadmin';
        if(isset($ultraadmin[$element]) && $ultraadmin[$element] != "1" && $ultraadmin[$element] == "0" && !$ultraadmin[$element]){
            $str .= "#wp-admin-bar-_ultraoptions{display:none;}";
        }





       echo "<style type='text/css'>".$str."</style>";
}

?>