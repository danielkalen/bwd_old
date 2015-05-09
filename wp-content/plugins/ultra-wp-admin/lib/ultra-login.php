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

function ultra_custom_login() {
    global $ultraadmin;

    global $ultra_css_ver;

    $url = plugins_url('/', __FILE__).'../'.$ultra_css_ver.'/ultra-login.css';
    wp_deregister_style('ultra-login');
    wp_register_style('ultra-login', $url);
    wp_enqueue_style('ultra-login');



    echo "\n<style type='text/css'>";

    /*text, backgrounds, link color*/
    echo ultra_css_background("body, #wp-auth-check-wrap #wp-auth-check", "login-background") . "\n";
    echo ultra_css_background(".login form", "login-form-background") . "\n";
    echo ultra_link_color(".login #backtoblog a, .login #nav a, .login a", "login-link-color") . "\n";
    echo ultra_css_color(".login, .login form label, .login form, .login .message", "login-text-color") . "\n";

    /*login button*/
    echo ultra_css_bgcolor(".login.wp-core-ui .button-primary", "login-button-bg") . "\n";
    echo ultra_css_bgcolor(".login.wp-core-ui .button-primary:hover, .login.wp-core-ui .button-primary:focus", "login-button-hover-bg") . "\n";
    echo ultra_css_color(".login.wp-core-ui .button-primary", "login-button-text-color") . "\n";


    /*form input fields - text and checkbox*/
    echo ultra_css_bgcolor(".login form .input, .login form input[type=checkbox], .login input[type=text]", "login-input-bg-color", ($data['login-input-bg-opacity']) == "" ? "0.5" : $data['login-input-bg-opacity']) . "\n";
    echo ultra_css_bgcolor(".login form .input:hover, .login form input[type=checkbox]:hover, .login input[type=text]:hover, .login form .input:focus, .login form input[type=checkbox]:focus, .login input[type=text]:focus", "login-input-bg-color", ($data['login-input-bg-hover-opacity']) == "" ? "0.8" : $data['login-input-bg-hover-opacity']) . "\n";
    echo ultra_css_color(".login form .input, .login form input[type=checkbox], .login input[type=text]", "login-input-text-color") . "\n";
    echo ultra_css_color(".login.wp-core-ui input[type=checkbox]:checked:before", "login-input-text-color") . "\n";


    /*form input fields - other fields - for future use*/
    echo ultra_css_bgcolor("input[type=checkbox], input[type=color], input[type=date], input[type=datetime-local], input[type=datetime], input[type=email], input[type=month], input[type=number], input[type=password], input[type=radio], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week], select, textarea", "login-input-bg-color", ($data['login-input-bg-opacity']) == "" ? "0.5" : $data['login-input-bg-opacity']) . "\n";
    echo ultra_css_color("input[type=checkbox], input[type=color], input[type=date], input[type=datetime-local], input[type=datetime], input[type=email], input[type=month], input[type=number], input[type=password], input[type=radio], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week], select, textarea", "login-input-text-color") . "\n";


    /*login error message*/
    echo ultra_css_bgcolor(".login #login_error, .login .message", "login-input-bg-color", ($data['login-input-bg-opacity']) == "" ? "0.5" : $data['login-input-bg-opacity']) . "\n";
    echo ultra_css_color(" .login .message,  .login .message a, .login #login_error, .login #login_error a", "login-input-text-color") . "\n";


    /*login logo*/
	$logo_url = "";
    if (isset($ultraadmin['login-logo']['url']) && $ultraadmin['login-logo']['url'] != "") {
        $logo_url = $ultraadmin['login-logo']['url'];
    } else {
        $logo_url = $ultraadmin['logo']['url'];
    }

    echo '.login h1 a { background-image: url("' . $logo_url . '");}';


echo "</style>\n"; 


/*


    echo '.login{color: '.$ultraadmin['login-link-color']['regular'].'}';
    echo '.login.wp-core-ui .button-primary{ 
    height: 34px;
    line-height: 28px;
    border-radius : 0px;
    padding: 0 12px 2px;
    border-color: transparent;
    -webkit-box-shadow: none;
    box-shadow: none;
    -ms-box-shadow: none;
    -moz-box-shadow: none;
    -o-shadow: none;
}';

    echo ".login.wp-core-ui input[type=checkbox]{
    box-shadow: none;
    -ms-box-shadow: none;
    -moz-box-shadow: none;
    -o-shadow: none; 
    border-color: " . $ultraadmin['separator-color'] . "
    }";

    if (isset($ultraadmin['login-logo']['url']) && $ultraadmin['login-logo']['url'] != "") {
        $logo_url = $ultraadmin['login-logo']['url'];
    } else {
        $logo_url = $ultraadmin['logo']['url'];
    }

    echo '.login h1 a { background-image: url("' . $logo_url . '");'
    . 'background-size: contain;min-height: 88px;width:auto;}';

    */



}


?>