<?php
ob_start();
    /*
	Plugin Name: Woocomerce PDF Catalogue
	Plugin URI: http://www.builtapp.com
	Description: This plugin will enable wordpress users to download a pdf catalogue from the woocommerce store.
	Author: Builtapp
	Version: 1.1.0
	Author URI: http://builtapp.com
	*/

global $wpdb;
global $pdf;
global $wcc_settings;

define('WCC_FILE_PATH', dirname(__FILE__));
define('WCC_TABLE_PREFIX', 'wcc_');


require(WCC_FILE_PATH."/libraries/mpdf.php" );
require(WCC_FILE_PATH."/wc_settings.php");
$pdf=new mPDF();
add_action( 'init', 'run_the_script' );

register_activation_hook(__FILE__,'wcc_install');
register_deactivation_hook(__FILE__ , 'wcc_uninstall' );

$wcc_settings = new wcc_settings;
function fl_stylesheet() {
    wp_enqueue_style( 'prettyPhoto', plugins_url('/css/prettyPhoto.css', __FILE__) );
	wp_enqueue_style( 'tabs', plugins_url('/css/style.css', __FILE__) );
}
function fl_script() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'prettyPhoto', plugins_url('/js/jquery.prettyPhoto.js', __FILE__), array('jquery'), '3.1.3' );
	
}
function pp_print_footer_script() {
	wp_enqueue_script('main', plugins_url('/js/main.js', __FILE__), array('jquery'));
}
add_action( 'wp_enqueue_scripts', 'fl_script' );
add_action( 'wp_print_styles', 'fl_stylesheet' );
add_action('wp_head', 'pp_print_footer_script');


function wcc_install()
{
	global $wcc_settings;
	$wcc_settings->create_table();
}

function wcc_uninstall()
{
	global $wcc_settings;
	$wcc_settings->delete_table();
}

add_action('admin_menu', 'wcc_plugin_menu');


function wcc_plugin_menu() {
	add_options_page('PDF Catalogue Settings', 'PDF Catalogue Settings', 'manage_options', 'woo_cat_settings.php', 'wcc_edit_settings');
}

function wcc_edit_settings(){
	global $wcc_settings;
	$wcc_edit_results = $wcc_settings->fetch_data();
		foreach($wcc_edit_results as $wcc_edit_result) 
			require(WCC_FILE_PATH."/woo_cat_settings.php");
}



function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function get_product_category_by_id( $category_id ) {
    $term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
    return $term['name'];
}

function woo_cat_data_table($args,$total_products){
	global $wcc_settings;
	global  $woocommerce;
	global  $pdf;

	$no_of_columns =$wcc_settings->get_value('columns') ;
	$remainder = 0;
	$current = 0;
	$cat = "";
	
	$loop = new WP_Query( $args );
	
	    
	
		///show description

	if($wcc_settings->get_value('format') == 2){
		
			$cat =  $cat . '<table class ="woo_cat_table"><tr><th colspan=2><h2 class="woo_cat_title">'.$args['product_cat'].' </h2> 
		</th></tr>';
			while ( $loop->have_posts() ) : $loop->the_post(); 
			global $product; 
					$cat =  $cat . '<tr>';
					$current = $current + 1;
			 $cat =  $cat . '<td>'.get_the_post_thumbnail($loop->post->ID, 'thumbnail').'<br />'.get_the_title( $loop->post->ID ).'<br  />';
			 if(intval(get_post_meta($loop->post->ID, '_regular_price', true)) > intval(get_post_meta($loop->post->ID, '_price', true))){
			 
			 $cat =  $cat . 'Regular Price: <span style="text-decoration: line-through;">'.get_woocommerce_currency_symbol().get_post_meta($loop->post->ID, '_regular_price', true).'</span><br  />';
			 $cat =  $cat . '<b>Sale Price: '.get_woocommerce_currency_symbol().get_post_meta($loop->post->ID, '_price', true).'</b>';
			 }else{
			 $cat =  $cat . 'Price: '.get_woocommerce_currency_symbol().get_post_meta($loop->post->ID, '_price', true);
			 }
			 if(strlen(get_post_meta($loop->post->ID, '_sku', true)) > 0 && $wcc_settings->get_value('sku') == 1)
				 $cat =  $cat.'<br> SKU: '.get_post_meta($loop->post->ID, '_sku', true);
			 $cat =  $cat .' </td><td>'.get_post($loop->post->ID)->post_content.'</td>';
				if($current >= $no_of_columns){
					$cat =  $cat . '</tr>';
				}
		
			endwhile; 
		
			$cat =  $cat .'</table>';
	///show grid
		
	}else{
	
	
			$cat =  $cat . '<table class ="woo_cat_table"><tr><th colspan='.$no_of_columns.'><h2 class="woo_cat_title">'.$args['product_cat'].' </h2> 
		</th></tr>';
			while ( $loop->have_posts() ) : $loop->the_post(); 
			global $product; 
				if( $current== 0){
					$cat =  $cat . '<tr>';
				}
					$current = $current + 1;
			 $cat =  $cat . '<td>'.get_the_post_thumbnail($loop->post->ID, 'thumbnail').'<br />'.get_the_title( $loop->post->ID ).'<br  />';
			 
			 if(get_post_meta($loop->post->ID, '_regular_price', true) > get_post_meta($loop->post->ID, '_price', true)){
			 
			 $cat =  $cat . 'Regular Price: <span style="text-decoration: line-through;">'.get_woocommerce_currency_symbol().get_post_meta($loop->post->ID, '_regular_price', true).'</span><br  />';
			 $cat =  $cat . '<b>Sale Price: '.get_woocommerce_currency_symbol().get_post_meta($loop->post->ID, '_price', true).'</b>';
			 }else{
			 $cat =  $cat . 'Price: '.get_woocommerce_currency_symbol().get_post_meta($loop->post->ID, '_price', true);
			 }
			 if(strlen(get_post_meta($loop->post->ID, '_sku', true)) > 0 && $wcc_settings->get_value('sku') == 1)
				 $cat =  $cat.'<br> SKU: '.get_post_meta($loop->post->ID, '_sku', true);
			 $cat =  $cat.' </td>';
				if($current >= $no_of_columns){
					$cat =  $cat . '</tr>';
					$current= 0;
				}
		
			endwhile; 
			$remainder = $no_of_columns - ($total_products % $no_of_columns);
			
			if($remainder > 0){
				while ($remainder > 0){
					$cat =  $cat ."<td></td>";
					$remainder = $remainder - 1;
				}
			}
			
		
			$cat =  $cat .'</table>';
	
	}
	
	return ($cat);
	//exit();
	//return $cat;
}

function get_me_list_of($categories = array(), $content = null)
{   
	global $wcc_settings;
	global  $woocommerce;
	global  $pdf;
	$font = $wcc_settings->get_value('font');
	$first = 0;
	$style = "
	<style>


		/* Catalog Title*/
		
		.woo_cat_title {
			font-family: ".$font.";
			font-size: 40px;
			font-weight: normal;
			text-transform: capitalize;
			text-shadow: 0 1px 0 #FFFFFF;
			color: #818181;
		}
		
		.woo_cat_logo {
			float: right;
		}
		
		.woo_cat_header {
			border: 0 none;
			font: inherit;
			margin: 20px;
			padding: 0;
			vertical-align: baseline;
		}
		
		.woo_cat_header_table {
			font-family: ".html_entity_decode($font).";
		}
		
		.woo_cat_footer {
			font-family: ".html_entity_decode($font).";
			font-size:12px;
			background: #E8EBF1;
			background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
			background: -moz-linear-gradient(top,  #ededed,  #ebebeb);  
			border: 0 none;
			font: inherit;
			margin: 20px;
			padding: 0;
			vertical-align: baseline;
		}
		
		.woo_cat_header p{
			float: right;
		}
		
		
		.woo_cat_table a:link {
			color: #666;
			font-weight: bold;
			text-decoration:none;
		}
		.woo_cat_table a:visited {
			color: #999999;
			font-weight:bold;
			text-decoration:none;
		}
		.woo_cat_table a:active,
		.woo_cat_table a:hover {
			color: #bd5a35;
			text-decoration:underline;
		}
		.woo_cat_table {
			font-family:".html_entity_decode($font).";
			color:#666;
			font-size:12px;
			text-shadow: 1px 1px 0px #fff;
			background:#ffffff;
			margin:20px;
			border:#ccc 1px solid;
			border-radius:3px;
			width: 100%;
			padding:0px;
		}
		.woo_cat_table th {
			padding:21px 25px 22px 25px;
			border-top:1px solid #fafafa;
			border-bottom:1px solid #e0e0e0;
		
			background: #ededed;
			background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
			background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
		}
		
		.woo_cat_table th:first-child {
			text-align: left;
			padding-left:20px;
		}
		.woo_cat_table tr:first-child th:first-child {
			-moz-border-radius-topleft:3px;
			-webkit-border-top-left-radius:3px;
			border-top-left-radius:3px;
		}
		.woo_cat_table tr:first-child th:last-child {
			-moz-border-radius-topright:3px;
			-webkit-border-top-right-radius:3px;
			border-top-right-radius:3px;
		}
		.woo_cat_table tr {
			text-align: center;
			padding-left:20px;
		}
		.woo_cat_table td:first-child {
			text-align: left;
			padding-left:20px;
			border-left: 0;
		}
		.woo_cat_table td {
			padding:18px;
			border-bottom:1px solid #e0e0e0;
			background: #fafafa;
			margin: 0 px;
			border-left:none;
			background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
			background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
		}
		.woo_cat_table tr.even td {
			background: #f6f6f6;
			background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
			background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
		}
		.woo_cat_table tr:last-child td {
			border-bottom:0;
		}
		.woo_cat_table tr:last-child td:first-child {
			-moz-border-radius-bottomleft:3px;
			-webkit-border-bottom-left-radius:3px;
			border-bottom-left-radius:3px;
		}
		.woo_cat_table tr:last-child td:last-child {
			-moz-border-radius-bottomright:3px;
			-webkit-border-bottom-right-radius:3px;
			border-bottom-right-radius:3px;
		}
		.woo_cat_table tr:hover td {
			background: #f2f2f2;
			background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
			background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
		}
	
	";
	
	
	
	
	
		$cat =  $cat .' <div class="woo_cat_header"><table width="100%" class="woo_cat_header_table"><tr><td><p align="left"><img src="'.$wcc_settings->get_value('logo_url').'" /></p><td/><td align="right"><h2 class="woo_cat_title">'.$wcc_settings->get_value('company').' </h2> <br />'.$wcc_settings->get_value('address').'<br />'.$wcc_settings->get_value('telephone').'<br />'.$wcc_settings->get_value('email').'<br />'.$wcc_settings->get_value('website').'</td></table></div>';
	
	if(count($categories) > 0){
		foreach ($categories as $category){
			if($first >= 1)
				$cat =  $cat .'<pagebreak />';
				$args = array( 'post_type' => 'product', 'product_cat' => $category,'posts_per_page' => -1,  'meta_query' => array(
		array( 'key' => '_visibility', 'value' => array( 'catalog', 'visible' ), 'compare' => 'IN' )) ,'orderby' => $wcc_settings->get_value('order_parameter'), 'order' => $wcc_settings->get_value('order_dir') );
				$total_products = count( get_posts( array('post_type' => 'product',  'product_cat' => $category, 'meta_query' => array(
		array( 'key' => '_visibility', 'value' => array( 'catalog', 'visible' ), 'compare' => 'IN' )), 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) );
			$cat =  $cat .woo_cat_data_table($args,$total_products);
			$first = $first + 1;
		}
	}else{
		$all_categories = get_woo_categories_list();
		
		foreach ($all_categories as $all_category){
			if($first >= 1)
				$cat =  $cat .'<pagebreak />';
		$args = array( 'post_type' => 'product', 'product_cat' => $all_category->name, 'posts_per_page' => -1, 'meta_query' => array(
array( 'key' => '_visibility', 'value' => array( 'catalog', 'visible' ), 'compare' => 'IN' )), 'orderby' => $wcc_settings->get_value('order_parameter'), 'order' => $wcc_settings->get_value('order_dir') );
		$total_products = count( get_posts( array('post_type' => 'product', 'product_cat' => $all_category->name,'meta_query' => array(
array( 'key' => '_visibility', 'value' => array( 'catalog', 'visible' ), 'compare' => 'IN' )), 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) );
	
			$cat =  $cat .woo_cat_data_table($args,$total_products);
			$first = $first + 1;
		}
	}

    

	
	$cat =  $cat .'<div class="woo_cat_footer"><p align="center">
	'.$wcc_settings->get_value('copyright').' </p></div>
	';
	//die($cat);
    wp_reset_query(); 
	$pdf->setFooter('{PAGENO} / {nb}');
	$pdf->WriteHTML($style,1);
	$pdf->WriteHTML($cat,2);
	
	ob_end_clean();
	$pdf->Output('Catalog.pdf','I');
	exit;

}

// Creating the widget 
class woo_catalog_download extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'woo_catalog_download', 

// Widget name will appear in UI
__('Woo Catalog Download Widget', 'woo_catalog_download_domain'), 

// Widget description
array( 'description' => __( 'Woocommerce Catalog Download PDF', 'woo_catalog_download_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo __( '
<a href="#pdf_catalog" rel="prettyPhoto"  class="a_demo_four">Download Catalog</a>
<div id="pdf_catalog" style="display : none;">'.draw_tab(), 'woo_catalog_download_domain' );
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'woo_catalog_download_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class woo_catalog_download ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'woo_catalog_download' );
}
add_action( 'widgets_init', 'wpb_load_widget' );


function run_the_script() {
	if(isset($_POST['cat_download']) && $_POST['cat_download'] == true ) {
					get_me_list_of();
	}

	if(isset($_POST['category_download']) && $_POST['category_download'] == true ) {
					get_me_list_of($_POST['categories']);
	}

}

function get_woo_categories_options(){
	
	  $taxonomy     = 'product_cat';
	  $orderby      = 'name';  
	  $show_count   = 0;      // 1 for yes, 0 for no
	  $pad_counts   = 0;      // 1 for yes, 0 for no
	  $hierarchical = 1;      // 1 for yes, 0 for no  
	  $title        = '';  
	  $empty        = 0;
	$args = array(
	  'taxonomy'     => $taxonomy,
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
	  'title_li'     => $title,
	  'hide_empty'   => $empty
	);
	$all_categories = get_categories( $args );
	$html = "";
	if (count($all_categories) > 0) {
		foreach ($all_categories as $category) {
			$html .=  '<option value="'.$category->name.'">'.$category->name.'</option>';    
		}
	}
	
	return $html;
	
}

function get_woo_categories_list(){
	
	  $taxonomy     = 'product_cat';
	  $orderby      = 'name';  
	  $show_count   = 0;      // 1 for yes, 0 for no
	  $pad_counts   = 0;      // 1 for yes, 0 for no
	  $hierarchical = 1;      // 1 for yes, 0 for no  
	  $title        = '';  
	  $empty        = 0;
	$args = array(
	  'taxonomy'     => $taxonomy,
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
	  'title_li'     => $title,
	  'hide_empty'   => $empty
	);
	$all_categories = get_categories( $args );
	return $all_categories;
	
}

function draw_tab(){
	$tab ='

	
	<section class="woo_cat_tabs">
	            <input id="tab-1" type="radio" name="radio-set" class="tab-selector-1" checked="checked" />
		        <label for="tab-1" class="tab-label-1">All Products</label>
		
	            <input id="tab-2" type="radio" name="radio-set" class="tab-selector-2" />
		        <label for="tab-2" class="tab-label-2">By Category</label>
		
			    <div class="clear-shadow"></div>
				
		        <div class="woo_cat_content">
			        <div class="content-1">
						<h3>Download PDF Catalogue With all products</h3>
						<p> Click the button below to download a pdf catalogue with all the products in the shop</p>
                        <form method="post" action=""><input id="cat_download" type="hidden" value="Download Catalog" name="cat_download">
						<br /><br />
							<span style="padding-left: 150px;"><a href="#" onclick="jQuery(this).closest(\'form\').submit()" class="a_demo_four">Download Catalogue</a></form></span></form>
				    </div>
			        <div class="content-2">
						<h3>Download PDF Catalogue With Products According to Categories selected below</h3>
							<form method="post" action="">
									<span style="padding-left: 150px;"><ul class="options">
									</ul>
									<select multiple style="width: 300px; height: 200px;" name="categories[]">
									<option disabled="disabled">Select Category</option>
									'.get_woo_categories_options().'
									</select>
									<br />Hold crtl to select more categories
									<input id="category_download" type="hidden" value="Download Catalog" name="category_download"> <br /><br />
							<a href="#" onclick="jQuery(this).closest(\'form\').submit()" class="a_demo_four">Download Catalogue</a></span></form>
				    </div>
		        </div>
			</section>';

	return $tab;
	
}
if(isset($_POST['wc_edit_submit'])) {
	global $mwcs_settings;
	$_SESSION["wc_notice"]  = $wcc_settings->edit_wcc_settings($_POST['font'],$_POST['columns'],$_POST['text_color'],$_POST['cat_title'],$_POST['cat_image'],$_POST['format'],$_POST['logo_url'],$_POST['logo_width'],$_POST['logo_height'],$_POST['copyright'],$_POST['orderby'],$_POST['order'],$_POST['company'],$_POST['website'],$_POST['address'],$_POST['email'],$_POST['telephone'],$_POST['sku']);
}


?>