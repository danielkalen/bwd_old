<!-- Bootstrap CSS -->
<link href="<?php echo plugins_url( "/css/bootstrap.css" , __FILE__ )?>" rel="stylesheet">
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div><h2>Woocommerce PDF Catalogue Settings</h2>
	<?php
    
    //Display notices
    if(isset($_SESSION["wc_notice"])){
        if($_SESSION["wc_notice"][0]){
            ?>
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert">×</a>  
                    <strong>Success!</strong> <?php echo $_SESSION["wc_notice"][1];?>
                </div>
            <?php
        }else{
            ?>
                <div class="alert alert-error">
                    <a class="close" data-dismiss="alert">×</a>  
                    <strong>Failure!</strong> <?php echo $_SESSION["wc_notice"][1];?>    
                </div>
            <?php
            
        }
        
        unset($_SESSION["wc_notice"]);
    }
    foreach($wcc_edit_results as $wc_result) {
        ?>
        <form action="" method="post" id="edit_activity"  enctype="multipart/form-data">
            <table class="form-table">
                
                <tr valign="top">
                    <th scope="row"><label for="font">Font</label></th>
                    <td><select name="font">
                            <option value='Arial'<?php if($wc_result->font === 'Arial'){?> selected="selected" <?php }?>>Arial</option>
                            <option value='Helvetica'<?php if($wc_result->font === 'Helvetica'){?> selected="selected" <?php }?>>Helvetica</option>
                            <option value='sans-serif'<?php if($wc_result->font === 'sans-serif'){?> selected="selected" <?php }?>>sans-serif</option>
                            <option value='Lucida Sans Unicode'<?php if($wc_result->font === 'Lucida Sans Unicode'){?> selected="selected" <?php }?>>Lucida Sans Unicode</option>
                            <option value='Lucida Grande'<?php if($wc_result->font === 'Lucida Grande'){?> selected="selected" <?php }?>>Lucida Grande</option>
                            <option value='Tahoma'<?php if($wc_result->font === 'Tahoma'){?> selected="selected" <?php }?>>Tahoma</option>
                            <option value='Geneva'<?php if($wc_result->font === 'Geneva'){?> selected="selected" <?php }?>>Geneva</option>
                            <option value='Verdana'<?php if($wc_result->font === 'Verdana'){?> selected="selected" <?php }?>>Verdana</option>
                            <option value='Comic Sans MS'<?php if($wc_result->font === 'Comic Sans MS'){?> selected="selected" <?php }?>>Comic Sans MS</option>
                        </select>

                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><label for="text_color">Text Color (HEX)</label></th>
                    <td><input name="text_color" type="text" id="text_color" value="<?php echo $wc_result->text_color; ?>" class="regular-text" /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><label for="cat_title">Catalogue Title</label></th>
                    <td><input name="cat_title" type="text" id="cat_title" value="<?php echo $wc_result->cat_title; ?>" class="regular-text" /></td>
                </tr>
                
<!--                <tr valign="top">
                    <th scope="row"><label for="pinterest">Catalogue Image</label></th>
                    <td><input name="cat_image" type="text" id="cat_image" value="<?php echo $wc_result->cat_image; ?>" class="regular-text" /></td>
                </tr>
-->                
				<tr valign="top">
                    <th scope="row"><label for="format">Format</label></th>
                    <td><select name="format">
                            <option value="1"<?php if($wc_result->format == 1){?> selected="selected" <?php }?>>Grid</option>
                            <option value="2"<?php if($wc_result->format == 2){?> selected="selected" <?php }?>>With Description</option>
                        </select></td>
                </tr>
                
				<tr valign="top">
                    <th scope="row"><label for="sku">Show SKU</label></th>
                    <td><select name="sku">
                            <option value="1"<?php if($wc_result->sku == 1){?> selected="selected" <?php }?>>Yes</option>
                            <option value="0"<?php if($wc_result->sku == 0){?> selected="selected" <?php }?>>No</option>
                        </select></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><label for="orderby">Order By</label></th>
                    <td><select name="orderby">
                            <option value="title"<?php if($wc_result->order_parameter === 'title'){?> selected="selected" <?php }?>>Title</option>
                            <option value="date"<?php if($wc_result->order_parameter === 'date'){?> selected="selected" <?php }?>>Date</option>
                        </select></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><label for="order">Order</label></th>
                    <td><select name="order">
                            <option value="ASC"<?php if($wc_result->order_dir === 'ASC'){?> selected="selected" <?php }?>>Ascending</option>
                            <option value="DESC"<?php if($wc_result->order_dir === 'DESC'){?> selected="selected" <?php }?>>Descending</option>
                        </select></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><label for="columns">Number of Columns</label></th>
                    <td><input name="columns" type="text" id="columns" value="<?php echo $wc_result->columns; ?>" class="regular-text" /></td>
                </tr>
                
                
                <tr valign="top">
                    <th scope="row"><label for="logo_url">Logo</label></th>
                    <td><input name="logo_url" type="text" id="logo_url" value="<?php echo $wc_result->logo_url; ?>" class="regular-text" /></td>
                </tr>
                
                
                <tr valign="top">
                    <th scope="row"><label for="logo_width">Logo Width</label></th>
                    <td><input name="logo_width" type="text" id="logo_width" value="<?php echo $wc_result->logo_width; ?>" class="regular-text" /></td>
                </tr>
                
                
                <tr valign="top">
                    <th scope="row"><label for="logo_height">Logo Height</label></th>
                    <td><input name="logo_height" type="text" id="logo_height" value="<?php echo $wc_result->logo_height; ?>" class="regular-text" /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><label for="copyright">Copyright Text</label></th>
                    <td><textarea rows="4" cols="50" name="copyright"><?php echo $wc_result->copyright; ?></textarea> </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="company">Company Name</label></th>
                    <td><input name="company" type="text" id="logo_height" value="<?php echo $wc_result->company; ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="telephone">Telephone</label></th>
                    <td><input name="telephone" type="text" id="logo_height" value="<?php echo $wc_result->telephone; ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="address">Address</label></th>
                    <td><input name="address" type="text" id="logo_height" value="<?php echo $wc_result->address; ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="email">Email</label></th>
                    <td><input name="email" type="text" id="logo_height" value="<?php echo $wc_result->email; ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="website">Website</label></th>
                    <td><input name="website" type="text" id="logo_height" value="<?php echo $wc_result->website; ?>" class="regular-text" /></td>
                </tr>
                
            </table>
        

    	<p class="submit"><input type="submit" name="wc_edit_submit" id="wc_edit_submit" class="button button-primary" value="Save Changes"  /></p></form>
</form>

<?php }?>

</div>
