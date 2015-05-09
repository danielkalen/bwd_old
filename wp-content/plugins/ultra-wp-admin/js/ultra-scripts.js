/**
 * @Package: WordPress Plugin
 * @Subpackage: Ultra WordPress Admin Theme
 * @Since: Ultra 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Ultra WordPress Admin Theme Plugin.
 */


jQuery(function($) {

    'use strict';

    var ULTRA_SETTINGS = window.ULTRA_SETTINGS || {};

    
    /******************************
     Menu resizer
     *****************************/
    ULTRA_SETTINGS.menuResizer = function() {
        var menuWidth = $("#adminmenuwrap").width();
        if($("#adminmenuwrap").is(":hidden")){
          $("body").addClass("menu-hidden");
          $("body").removeClass("menu-expanded");
          $("body").removeClass("menu-collapsed");
        }
        else if(menuWidth > 46){
          $("body").addClass("menu-expanded");
          $("body").removeClass("menu-hidden");
          $("body").removeClass("menu-collapsed");
        } else {
          $("body").addClass("menu-collapsed");
          $("body").removeClass("menu-expanded");
          $("body").removeClass("menu-hidden");
        }
    };

    ULTRA_SETTINGS.menuClickResize = function() {
      $('#collapse-menu, #wp-admin-bar-menu-toggle').click(function(e) {
        var menuWidth = $("#adminmenuwrap").width();
        if($("#adminmenuwrap").is(":hidden")){
          $("body").addClass("menu-hidden");
          $("body").removeClass("menu-expanded");
          $("body").removeClass("menu-collapsed");
        }
        else if(menuWidth > 46){
          $("body").addClass("menu-expanded");
          $("body").removeClass("menu-hidden");
          $("body").removeClass("menu-collapsed");
        } else {
          $("body").addClass("menu-collapsed");
          $("body").removeClass("menu-expanded");
          $("body").removeClass("menu-hidden");
        }
      });
    };






    /******************************
     initialize respective scripts 
     *****************************/
    $(document).ready(function() {
        ULTRA_SETTINGS.menuResizer();
        ULTRA_SETTINGS.menuClickResize();
    });

    $(window).resize(function() {
        ULTRA_SETTINGS.menuResizer();
        ULTRA_SETTINGS.menuClickResize();
    });

    $(window).load(function() {
        ULTRA_SETTINGS.menuResizer();
        ULTRA_SETTINGS.menuClickResize();
    });

});
