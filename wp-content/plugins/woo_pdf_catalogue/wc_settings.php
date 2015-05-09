<?php

class wcc_settings{
	var $success_message = "";
	var $failure_message = "";
	
	public function __construct()
	{
	}
	
	function create_table (){
		global $wpdb;
		$table = WCC_TABLE_PREFIX."settings";
		$this->delete_table();
		$structure = "
			CREATE TABLE `$table` (
			  `setting_id` int(11) NOT NULL,
			  `font` text,
			  `columns` int,
			  `text_color` text,
			  `cat_title` text,
			  `cat_image` text,
			  `format` text,
			  `order_parameter` text,
			  `order_dir` text,
			  `logo_url` text,
			  `logo_width` int,
			  `logo_height` int,
			  `copyright` text,
			  `company` text,
			  `address` text,
			  `telephone` text,
			  `email` text,
			  `website` text,
			  `sku` int,
			  PRIMARY KEY (`setting_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;";
		$wpdb->query($structure);
		$insert = "INSERT INTO ".$table." (setting_id,font,columns,text_color,cat_title,cat_image,format,logo_url,logo_width,logo_height,copyright,order_parameter,order_dir,company, website, address, email, telephone, sku)
		VALUES(1,'Verdana',3,'','Catalogue','','2','http://woothemes.wpengine.netdna-cdn.com/wp-content/uploads/2011/09/Woo-Ninja_standing-small.png','130','130','','title','DESC','','','','','',0)";
				
		
		$wpdb->query($insert);
	}
		
		
	function edit_wcc_settings($font,$columns,$text_color,$cat_title,$cat_image,$format,$logo_url,$logo_width,$logo_height,$copyright,$orderby,$order, $company, $website, $address, $email, $telephone, $sku){
	
		global $wpdb;
		$table = WCC_TABLE_PREFIX."settings";
			
			//prevent sql injection
			$font = htmlentities(mysql_real_escape_string($font));
			$columns = htmlentities(mysql_real_escape_string($columns));
			$text_color = htmlentities(mysql_real_escape_string($text_color));
			$cat_title = htmlentities(mysql_real_escape_string($cat_title));
			$cat_image = htmlentities(mysql_real_escape_string($cat_image));
			$logo_url = htmlentities(mysql_real_escape_string($logo_url));
			$logo_width = htmlentities(mysql_real_escape_string($logo_width));
			$logo_height = htmlentities(mysql_real_escape_string($logo_height));
			$copyright = htmlentities(mysql_real_escape_string($copyright));
			$orderby = htmlentities(mysql_real_escape_string($orderby));
			$order = htmlentities(mysql_real_escape_string($order));
			$company = htmlentities(mysql_real_escape_string($company));
			$website = htmlentities(mysql_real_escape_string($website));
			$address = htmlentities(mysql_real_escape_string($address));
			$email = htmlentities(mysql_real_escape_string($email));
			$telephone = htmlentities(mysql_real_escape_string($telephone));
			$sku = htmlentities(mysql_real_escape_string($sku));
		
			$sql = "UPDATE $table SET font = '".$font."',columns=".$columns.",text_color ='".$text_color."',cat_title ='".$cat_title."',cat_image ='".$cat_image."',format ='".$format."',logo_url ='".$logo_url."',logo_width ='".$logo_width."',logo_height =".$logo_height.",copyright ='".$copyright."',order_parameter ='".$orderby."',order_dir ='".$order."',company ='".$company."',website ='".$website."',address ='".$address."',email ='".$email."',telephone ='".$telephone."' ,sku =".$sku." WHERE setting_id = 1";
			
			//die($sql);
			
			$wpdb->query($sql);
			
			
			$this->success_message = "Settings saved successfully.";
			
			return array(true,$this->success_message);
		
	}
	
		
	function delete_table(){
		global $wpdb;
		
		//delete settings table		
		$table = WCC_TABLE_PREFIX."settings";
		$structure = "drop table if exists $table";
		$wpdb->query($structure);  
		
	}
	
	function fetch_data(){
		global $wpdb;
		$table = WCC_TABLE_PREFIX."settings";
		$sql = "SELECT * FROM ".$table;
		//echo $sql;
		return $wpdb->get_results($sql);
	}
	
	function get_value($setting){
		
			$results = $this->fetch_data();
	
			foreach($results as $result)
				return $result->$setting;

	}
	
		
		
}

?>