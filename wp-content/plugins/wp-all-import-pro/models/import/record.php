<?php

class PMXI_Import_Record extends PMXI_Model_Record {

	public static $cdata = array();

	protected $errors;	
	
	/**
	 * Some pre-processing logic, such as removing control characters from xml to prevent parsing errors
	 * @param string $xml
	 */
	public static function preprocessXml( & $xml) {		
		
		if ( empty(PMXI_Plugin::$session->is_csv) and empty(PMXI_Plugin::$is_csv)){ 
		
			self::$cdata = array();			

			$xml = preg_replace_callback('/<!\[CDATA\[[^\]\]>]*\]\]>/s', 'wp_all_import_cdata_filter', $xml );

			$xml = str_replace("&", "&amp;", str_replace("&amp;","&", $xml));			
			
			if ( ! empty(self::$cdata) ){
			    foreach (self::$cdata as $key => $val) {
			        $xml = str_replace('{{CPLACE_' . ($key + 1) . '}}', $val, $xml);
			    }
			}
		}		
	}

	/**
	 * Validate XML to be valid for import
	 * @param string $xml
	 * @param WP_Error[optional] $errors
	 * @return bool Validation status
	 */
	public static function validateXml( & $xml, $errors = NULL) {
		if (FALSE === $xml or '' == $xml) {
			$errors and $errors->add('form-validation', __('WP All Import can\'t read your file.<br/><br/>Probably, you are trying to import an invalid XML feed. Try opening the XML feed in a web browser (Google Chrome is recommended for opening XML files) to see if there is an error message.<br/>Alternatively, run the feed through a validator: http://validator.w3.org/<br/>99% of the time, the reason for this error is because your XML feed isn\'t valid.<br/>If you are 100% sure you are importing a valid XML feed, please contact WP All Import support.', 'wp_all_import_plugin'));
		} else {
						
			PMXI_Import_Record::preprocessXml($xml);																						

			if ( function_exists('simplexml_load_string')){
				libxml_use_internal_errors(true);
				libxml_clear_errors();
				$_x = @simplexml_load_string($xml);
				$xml_errors = libxml_get_errors();			
				libxml_clear_errors();
				if ($xml_errors) {								
					$error_msg = '<strong>' . __('Invalid XML', 'wp_all_import_plugin') . '</strong><ul>';
					foreach($xml_errors as $error) {
						$error_msg .= '<li>';
						$error_msg .= __('Line', 'wp_all_import_plugin') . ' ' . $error->line . ', ';
						$error_msg .= __('Column', 'wp_all_import_plugin') . ' ' . $error->column . ', ';
						$error_msg .= __('Code', 'wp_all_import_plugin') . ' ' . $error->code . ': ';
						$error_msg .= '<em>' . trim(esc_html($error->message)) . '</em>';
						$error_msg .= '</li>';
					}
					$error_msg .= '</ul>';
					$errors and $errors->add('form-validation', $error_msg);				
				} else {
					return true;
				}
			}
			else{
				$errors and $errors->add('form-validation', __('Required PHP components are missing.', 'wp_all_import_plugin'));				
				$errors and $errors->add('form-validation', __('WP All Import requires the SimpleXML PHP module to be installed. This is a standard feature of PHP, and is necessary for WP All Import to read the files you are trying to import.<br/>Please contact your web hosting provider and ask them to install and activate the SimpleXML PHP module.', 'wp_all_import_plugin'));				
			}
		}
		return false;
	}

	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMXI_Plugin::getInstance()->getTablePrefix() . 'imports');
		$this->errors = new WP_Error();
	}

	/**
	 * Import all files matched by path
	 * @param callback[optional] $logger Method where progress messages are submmitted
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function execute($logger = NULL, $cron = true, $history_log_id = false) {
				
		$uploads = wp_upload_dir();	

		if ($this->path) {
			
			$files = array($this->path);					
			
			foreach ($files as $ind => $path) {				

				$filePath = '';

				if ( $this->queue_chunk_number == 0 and $this->processing == 0 ) {

					$this->set(array('processing' => 1))->update(); // lock cron requests	

					if ($this->type == 'ftp'){
						$logger and call_user_func($logger, sprintf(__('This import appears to be using FTP. Unfortunately WP All Import no longer supports the FTP protocol. Please contact <a href="mailto:support@wpallimport.com">%s</a> if you have any questions.', 'wp_all_import_plugin'), 'support@wpallimport.com'));
						die();						
					}
					elseif ($this->type == 'url'){

						$filesXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<data><node></node></data>";

						$filePaths = XmlImportParser::factory($filesXML, '/data/node', $this->path, $file)->parse(); $tmp_files[] = $file;	

						foreach ($tmp_files as $tmp_file) { // remove all temporary files created
							@unlink($tmp_file);
						}

						$file_to_import = $this->path;

						if ( ! empty($filePaths) and is_array($filePaths) )
						{
							$file_to_import = array_shift($filePaths);
						}
						
						$uploader = new PMXI_Upload(trim($file_to_import), $this->errors);			
						$upload_result = $uploader->url($this->feed_type, $this->path);						
						if ($upload_result instanceof WP_Error){
							$this->errors = $upload_result;		
						}			
						else {
							$filePath  = $upload_result['filePath'];
						}						
					}
					elseif ( $this->type == 'file'){
						
						$uploader = new PMXI_Upload(trim(basename($this->path)), $this->errors);			
						$upload_result = $uploader->file();					
						if ($upload_result instanceof WP_Error){
							$this->errors = $upload_result;		
						}			
						else{
							$filePath  = $upload_result['filePath'];
						}
					}
					elseif ( ! in_array($this->type, array('ftp'))){ // retrieve already uploaded file

						$uploader = new PMXI_Upload(trim($this->path), $this->errors, rtrim(str_replace(basename($this->path), '', $this->path), '/'));			
						$upload_result = $uploader->upload();					
						if ($upload_result instanceof WP_Error){
							$this->errors = $upload_result;		
						}			
						else{
							$filePath  = $upload_result['filePath'];						
						}
					}

					if ( ! $this->errors->get_error_codes() and "" != $filePath ) {

						$this->set(array('queue_chunk_number' => 1))->update();					
						
					}
					elseif ( $this->errors->get_error_codes() ){

						$msgs = $this->errors->get_error_messages();

						if ( ! is_array($msgs)) {
							$msgs = array($msgs);
						}

						foreach ($msgs as $msg){
							$logger and call_user_func($logger, sprintf(__('ERROR: %s', 'wp_all_import_plugin'), $msg));
						}						
												
						$this->set(array('processing' => 0))->update();

						die;
					}
					
					$this->set(array('processing' => 0))->update(); // unlock cron requests

				}
						
				// if empty file path, than it's mean feed in cron process. Take feed path from history.
				if (empty($filePath)){
					$history = new PMXI_File_List();
					$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $this->id), 'id DESC');				
					if ($history->count()){
						$history_file = new PMXI_File_Record();
						$history_file->getBy('id', $history[0]['id']);
						$filePath =	$history_file->path;						
					}
				}			
				
				// if feed path founded
				if ( ! empty($filePath) and @file_exists($filePath) ) {					
					
					if ( $this->queue_chunk_number === 1 and $this->processing == 0 ){ // import first cron request

						$this->set(array('processing' => 1))->update(); // lock cron requests																		
						
						if (empty($this->options['encoding'])){
							$currentOptions = $this->options;
							$currentOptions['encoding'] = 'UTF-8';
							$this->set(array(
								'options' => $currentOptions
							))->update();
						}
						
						set_time_limit(0);													

						$file = new PMXI_Chunk($filePath, array('element' => $this->root_element, 'encoding' => $this->options['encoding']));
					    
					    // chunks counting		    
					    $chunks = 0; $history_xml = '';
					    while ($xml = $file->read()) { 
					    	if (!empty($xml)) {									    		
					    		PMXI_Import_Record::preprocessXml($xml);
					    		$xml = "<?xml version=\"1.0\" encoding=\"". $this->options['encoding'] ."\"?>" . "\n" . $xml;					    		
					      		
						      	$dom = new DOMDocument('1.0', ( ! empty($this->options['encoding']) ) ? $this->options['encoding'] : 'UTF-8');
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($xml);
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);
								if (($elements = @$xpath->query($this->xpath)) and $elements->length){ 
									$chunks += $elements->length;
									if ("" == $history_xml) $history_xml = $xml;
								}
								unset($dom, $xpath, $elements);
						    }
						}
						unset($file);			
						
						if ( ! $chunks ){
							$logger and call_user_func($logger, sprintf(__('#%s No matching elements found for Root element and XPath expression specified', 'wp_all_import_plugin'), $this->id));
							$this->set(array(
								'queue_chunk_number' => 0,
								'processing' => 0,
								'imported' => 0,
								'created' => 0,
								'updated' => 0,
								'skipped' => 0,
								'deleted' => 0,
								'triggered' => 0	
							))->update();
							die;
						}

						// unlick previous files
						$history = new PMXI_File_List();
						$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $this->id), 'id DESC');				
						if ($history->count()){
							foreach ($history as $file){						
								if (@file_exists($file['path']) and $file['path'] != $filePath){ 
									if (in_array($this->type, array('upload')))
										wp_all_import_remove_source($file['path'], false);									
									else
										wp_all_import_remove_source($file['path']);
								}
								$history_file = new PMXI_File_Record();
								$history_file->getBy('id', $file['id']);
								if ( ! $history_file->isEmpty()) $history_file->delete();
							}
						}

						// update history
						$history_file = new PMXI_File_Record();
						$history_file->set(array(
							'name' => $this->name,
							'import_id' => $this->id,
							'path' => $filePath,
							//'contents' => (isset($history_xml)) ? $history_xml : '',
							'registered_on' => date('Y-m-d H:i:s')
						))->insert();

						do_action( 'pmxi_before_xml_import', $this->id );	

						$this->set(array('count' => $chunks, 'processing' => 0))->update(); // set pointer to the first chunk, updates feed elements count and unlock cron process
						
					}					
					
					// compose data to look like result of wizard steps									
					if( $this->queue_chunk_number and $this->processing == 0 ) {	

						$this->set(array('processing' => 1))->update(); // lock cron requests						

						@set_time_limit(0);							

						$file = new PMXI_Chunk($filePath, array('element' => $this->root_element, 'encoding' => $this->options['encoding'], 'pointer' => $this->queue_chunk_number));					
					    
						$feed = "<?xml version=\"1.0\" encoding=\"". $this->options['encoding'] ."\"?>" . "\n" . "<pmxi_records>";

					    $loop = 0;
					    $xml = '';

					    $processing_time_limit = (PMXI_Plugin::getInstance()->getOption('cron_processing_time_limit')) ? PMXI_Plugin::getInstance()->getOption('cron_processing_time_limit') : 120;
					    $start_rocessing_time = time();

					    $chunk_number = $this->queue_chunk_number;

					    while ($xml = $file->read() and $this->processing == 1 and (time() - $start_rocessing_time) <= $processing_time_limit ) {

					    	if ( ! empty($xml) ) {

					    		$chunk_number++;

					    		PMXI_Import_Record::preprocessXml($xml);	
					    		$xml_chunk = "<?xml version=\"1.0\" encoding=\"". $this->options['encoding'] ."\"?>" . "\n" . $xml;     						      		    

						      	$dom = new DOMDocument('1.0', ( ! empty($this->options['encoding']) ) ? $this->options['encoding'] : 'UTF-8');															
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($xml_chunk);								
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);

								if (($elements = @$xpath->query($this->xpath)) and $elements->length){									
									$feed .= $xml;																		
									$loop += $elements->length;
								}

								unset($dom, $xpath, $elements);							

						    }		

						    if ($loop == $this->options['records_per_request'] or $this->count == $this->imported + $this->skipped or $this->count == $loop + $this->imported + $this->skipped ){ // skipping scheduled imports if any for the next hit
						    	$feed .= "</pmxi_records>";		

						    	$this->process($feed, $logger, $chunk_number, $cron, '/pmxi_records', $loop);
						    	
						    	// set last update
						    	$this->set(array(
									'registered_on' => date('Y-m-d H:i:s'),
									'queue_chunk_number' => $chunk_number 
								))->update();	

						    	$loop = 0;
						    	$feed = "<?xml version=\"1.0\" encoding=\"". $this->options['encoding'] ."\"?>" . "\n" . "<pmxi_records>";
						    }				
						    
						}
						
						unset($file);								
						
						// delect, if cron process if finished
						if ( (int) $this->count <= (int) $this->imported + (int) $this->skipped ){
							
							// Delete posts that are no longer present in your file
							if (! empty($this->options['is_delete_missing']) and $this->options['duplicate_matching'] == 'auto'){

								$postList = new PMXI_Post_List();												
								$missing_ids = array();
								$missingPosts = $postList->getBy(array('import_id' => $this->id, 'iteration !=' => $this->iteration));

								if ( ! $missingPosts->isEmpty() ):
								
									foreach ($missingPosts as $missingPost) {
										
										$missing_ids[] = $missingPost['post_id'];																				
																		
									}	

								endif;							

								// Delete posts from database
								if ( ! empty($missing_ids) && is_array($missing_ids) ){																											
									
									$missing_ids_arr = array_chunk($missing_ids, 100);

									foreach ($missing_ids_arr as $key => $ids) {

										if ( ! empty($ids) ){ 

											foreach ( $ids as $k => $id ) {

												$to_delete = true;

												// Instead of deletion, set Custom Field
												if ($this->options['is_update_missing_cf']){
													update_post_meta( $id, $this->options['update_missing_cf_name'], $this->options['update_missing_cf_value'] );
													$to_delete = false;
												}

												// Instead of deletion, change post status to Draft
												if ($this->options['set_missing_to_draft']){ 
													$this->wpdb->update( $this->wpdb->posts, array('post_status' => 'draft'), array('ID' => $id) );										
													$to_delete = false;
												}

												// Delete posts that are no longer present in your file
												if ( $to_delete ){

													// Remove attachments
													empty($this->options['is_keep_attachments']) and wp_delete_attachments($id, true, 'files');
													// Remove images
													empty($this->options['is_keep_imgs']) and wp_delete_attachments($id, true, 'images');													
																							
													// Clear post's relationships
													if ( $this->options['custom_type'] != "import_users" ) wp_delete_object_term_relationships($id, get_object_taxonomies('' != $this->options['custom_type'] ? $this->options['custom_type'] : 'post'));
												
												}
												else{
													unset($ids[$k]);
												}
											}

											if ( ! empty( $ids ) ){

												do_action('pmxi_delete_post', $ids);

												if ( $this->options['custom_type'] == "import_users" ){
													$sql = "delete a,b
													FROM ".$this->wpdb->users." a
													LEFT JOIN ".$this->wpdb->usermeta." b ON ( a.ID = b.user_id )										
													WHERE a.ID IN (" . implode(',', $ids) . ");";
												}
												else {
													$sql = "delete a,b,c
													FROM ".$this->wpdb->posts." a
													LEFT JOIN ".$this->wpdb->term_relationships." b ON ( a.ID = b.object_id )
													LEFT JOIN ".$this->wpdb->postmeta." c ON ( a.ID = c.post_id )				
													WHERE a.ID IN (" . implode(',', $ids) . ");";
												}

												$this->wpdb->query( $sql );														

												// Delete record form pmxi_posts
												$sql = "DELETE FROM " . PMXI_Plugin::getInstance()->getTablePrefix() . "posts WHERE post_id IN (".implode(',', $ids).") AND import_id = %d";
												$this->wpdb->query( 
													$this->wpdb->prepare($sql, $this->id)
												);

												$this->set(array('deleted' => $this->deleted + count($ids)))->update();
											}
										}										
									}									
								}
							}

							// Set out of stock status for missing records [Woocommerce add-on option]
							if (empty($this->options['is_delete_missing']) and $this->options['custom_type'] == "product" and class_exists('PMWI_Plugin') and !empty($this->options['missing_records_stock_status'])) {
								
								$postList = new PMXI_Post_List();																			
								$missingPosts = $postList->getBy(array('import_id' => $this->id, 'iteration !=' => $this->iteration));
								if ( ! $missingPosts->isEmpty() ){
									foreach ($missingPosts as $missingPost) {
										update_post_meta( $missingPost['post_id'], '_stock_status', 'outofstock' );
										update_post_meta( $missingPost['post_id'], '_stock', 0 );
										$postRecord = new PMXI_Post_Record();
										$postRecord->getBy('id', $missingPost['id']);
										if ( ! $postRecord->isEmpty())
											$postRecord->set(array('iteration' => $this->iteration))->update();
										unset($postRecord);
									}
								}
							}							

							$this->set(array(
								'processing' => 0,
								'triggered' => 0,
								'queue_chunk_number' => 0,
								'registered_on' => date('Y-m-d H:i:s'), // update registered_on to indicated that job has been exectured even if no files are going to be imported by the rest of the method
								'iteration' => ++$this->iteration
							))->update();							

							wp_cache_flush();
							foreach ( get_taxonomies() as $tax ) {				
								delete_option( "{$tax}_children" );
								_get_term_hierarchy( $tax );
							}								

							if ( $history_log_id ){															
								$history_log = new PMXI_History_Record();						
								$history_log->getById( $history_log_id );
								if ( ! $history_log->isEmpty() ){
									$custom_type = get_post_type_object( $this->options['custom_type'] );		
									$history_log->set(array(
										'time_run' => time() - strtotime($history_log->date),
										'summary' => sprintf(__("import finished & cron un-triggered<br>%s %s created %s updated %s deleted %s skipped", "pmxi_plugin"), $this->created, $custom_type->labels->name, $this->updated, $this->deleted, $this->skipped)
									))->save();																	
								}
							}

							do_action( 'pmxi_after_xml_import', $this->id );

							$logger and call_user_func($logger, sprintf(__('Import #%s complete', 'wp_all_import_plugin'), $this->id));							

						}
						else{	

							$this->set(array(
								'processing' => 0								
							))->update();			

							if ( $history_log_id ){
								$history_log = new PMXI_History_Record();						
								$history_log->getById( $history_log_id );
								if ( ! $history_log->isEmpty() ){
									$custom_type = get_post_type_object( $this->options['custom_type'] );		
									$history_log->set(array(
										'time_run' => time() - strtotime($history_log->date),
										'summary' => sprintf(__("%d %s created %d updated %d deleted %d skipped", "pmxi_plugin"), $this->created, $custom_type->labels->name, $this->updated, $this->deleted, $this->skipped)
									))->save();																	
								}
							}
							
							$logger and call_user_func($logger, sprintf(__('Records Count %s', 'wp_all_import_plugin'), (int) $this->count));
							$logger and call_user_func($logger, sprintf(__('Records Processed %s', 'wp_all_import_plugin'), (int) $this->imported + (int) $this->skipped));
							
						}
					}					
				}
				else {
					$this->set(array(
						'processing' => 0,
						'triggered' => 0,
						'queue_chunk_number' => 0,
						'imported' => 0,
						'created' => 0,
						'updated' => 0,
						'skipped' => 0,
						'deleted' => 0										
					))->update();
					$logger and call_user_func($logger, sprintf(__('#%s source file not found', 'wp_all_import_plugin'), $this->id));
					die;
				}																				
			}			
		}
		return $this;
	}
	
	public $post_meta_to_insert = array();

	/**
	 * Perform import operation
	 * @param string $xml XML string to import
	 * @param callback[optional] $logger Method where progress messages are submmitted
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function process($xml, $logger = NULL, $chunk = false, $is_cron = false, $xpath_prefix = '', $loop = 0) {
		add_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // do not perform special filtering for imported content
		
		kses_remove_filters();

		$cxpath = $xpath_prefix . $this->xpath;		

		$this->options += PMXI_Plugin::get_default_import_options(); // make sure all options are defined
		
		$avoid_pingbacks = PMXI_Plugin::getInstance()->getOption('pingbacks');

		$cron_sleep = (int) PMXI_Plugin::getInstance()->getOption('cron_sleep');
		
		if ( $avoid_pingbacks and ! defined( 'WP_IMPORTING' ) ) define( 'WP_IMPORTING', true );

		$postRecord = new PMXI_Post_Record();		
		
		$tmp_files = array();
		// compose records to import
		$records = array();

		$is_import_complete = false;
		
		try { 						
			
			$chunk == 1 and $logger and call_user_func($logger, __('Composing titles...', 'wp_all_import_plugin'));
			if ( ! empty($this->options['title'])){
				$titles = XmlImportParser::factory($xml, $cxpath, $this->options['title'], $file)->parse($records); $tmp_files[] = $file;							
			}
			else{
				$loop and $titles = array_fill(0, $loop, '');
			}

			$chunk == 1 and $logger and call_user_func($logger, __('Composing excerpts...', 'wp_all_import_plugin'));			
			$post_excerpt = array();
			if ( ! empty($this->options['post_excerpt']) ){
				$post_excerpt = XmlImportParser::factory($xml, $cxpath, $this->options['post_excerpt'], $file)->parse($records); $tmp_files[] = $file;
			}
			else{
				count($titles) and $post_excerpt = array_fill(0, count($titles), '');
			}			

			if ( "xpath" == $this->options['status'] ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing statuses...', 'wp_all_import_plugin'));			
				$post_status = array();
				if (!empty($this->options['status_xpath'])){
					$post_status = XmlImportParser::factory($xml, $cxpath, $this->options['status_xpath'], $file)->parse($records); $tmp_files[] = $file;					
				}
				else{
					count($titles) and $post_status = array_fill(0, count($titles), '');
				}
			}

			if ( "xpath" == $this->options['comment_status'] ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing comment statuses...', 'wp_all_import_plugin'));			
				$comment_status = array();
				if (!empty($this->options['comment_status_xpath'])){
					$comment_status = XmlImportParser::factory($xml, $cxpath, $this->options['comment_status_xpath'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{
					count($titles) and $comment_status = array_fill(0, count($titles), 'open');
				}
			}

			if ( "xpath" == $this->options['ping_status'] ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing ping statuses...', 'wp_all_import_plugin'));			
				$ping_status = array();
				if (!empty($this->options['ping_status_xpath'])){
					$ping_status = XmlImportParser::factory($xml, $cxpath, $this->options['ping_status_xpath'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{
					count($titles) and $ping_status = array_fill(0, count($titles), 'open');
				}
			}

			if ( "xpath" == $this->options['post_format'] ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing post formats...', 'wp_all_import_plugin'));			
				$post_format = array();
				if (!empty($this->options['post_format_xpath'])){
					$post_format = XmlImportParser::factory($xml, $cxpath, $this->options['post_format_xpath'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{
					count($titles) and $post_format = array_fill(0, count($titles), 'open');
				}
			}

			if ( "no" == $this->options['is_multiple_page_template'] ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing page templates...', 'wp_all_import_plugin'));			
				$page_template = array();
				if (!empty($this->options['single_page_template'])){
					$page_template = XmlImportParser::factory($xml, $cxpath, $this->options['single_page_template'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{
					count($titles) and $page_template = array_fill(0, count($titles), 'default');
				}
			}			

			if ( $this->options['is_override_post_type'] and ! empty($this->options['post_type_xpath']) ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing post types...', 'wp_all_import_plugin'));			
				$post_type = array();
				$post_type = XmlImportParser::factory($xml, $cxpath, $this->options['post_type_xpath'], $file)->parse($records); $tmp_files[] = $file;				
			}
			else{
				if ('post' == $this->options['type'] and '' != $this->options['custom_type']) {
					$pType = $this->options['custom_type'];
				} else {
					$pType = $this->options['type'];
				}
				count($titles) and $post_type = array_fill(0, count($titles), $pType);
			}

			if ( "no" == $this->options['is_multiple_page_parent'] ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing page parent...', 'wp_all_import_plugin'));			
				$page_parent = array();
				if (!empty($this->options['single_page_parent'])){
					$page_parent = XmlImportParser::factory($xml, $cxpath, $this->options['single_page_parent'], $file)->parse($records); $tmp_files[] = $file;
					foreach ($page_parent as $key => $identity) {
						
						$page = 0;
						switch ($this->options['type']) {
							
							case 'post':
								
								if ( ! empty($identity) ){

									if (ctype_digit($identity)){
										$page = get_post($identity);
									}
									else
									{
										$page = get_page_by_title($identity, OBJECT, $post_type[$key]);

										if ( empty($page) ){
											$args = array(
											  'name' => $identity,
											  'post_type' => $post_type[$key],
											  'post_status' => 'any',
											  'numberposts' => 1
											);
											$my_posts = get_posts($args);								
											if ( $my_posts ) {
											  	$page = $my_posts[0];
											}
										}

									}									
								
								}		
															
								break;

							case 'page':

								$page = get_page_by_title($identity) or $page = get_page_by_path($identity) or ctype_digit($identity) and $page = get_post($identity);
							
								break;
							
							default:
								# code...
								break;
						}						
						
						$page_parent[$key] = (!empty($page)) ? $page->ID : 0;


					}
				}
				else{
					count($titles) and $page_parent = array_fill(0, count($titles), 0);
				}
			}

			$chunk == 1 and $logger and call_user_func($logger, __('Composing authors...', 'wp_all_import_plugin'));			
			$post_author = array();
			$current_user = wp_get_current_user();

			if (!empty($this->options['author'])){
				$post_author = XmlImportParser::factory($xml, $cxpath, $this->options['author'], $file)->parse($records); $tmp_files[] = $file;
				foreach ($post_author as $key => $author) {
					$user = get_user_by('login', $author) or $user = get_user_by('slug', $author) or $user = get_user_by('email', $author) or ctype_digit($author) and $user = get_user_by('id', $author);					
					$post_author[$key] = (!empty($user)) ? $user->ID : $current_user->ID;
				}
			}
			else{								
				count($titles) and $post_author = array_fill(0, count($titles), $current_user->ID);
			}			

			$chunk == 1 and $logger and call_user_func($logger, __('Composing slugs...', 'wp_all_import_plugin'));			
			$post_slug = array();
			if (!empty($this->options['post_slug'])){
				$post_slug = XmlImportParser::factory($xml, $cxpath, $this->options['post_slug'], $file)->parse($records); $tmp_files[] = $file;
			}
			else{
				count($titles) and $post_slug = array_fill(0, count($titles), '');
			}

			$chunk == 1 and $logger and call_user_func($logger, __('Composing menu order...', 'wp_all_import_plugin'));			
			$menu_order = array();
			if (!empty($this->options['order'])){
				$menu_order = XmlImportParser::factory($xml, $cxpath, $this->options['order'], $file)->parse($records); $tmp_files[] = $file;
			}
			else{
				count($titles) and $menu_order = array_fill(0, count($titles), '');
			}

			$chunk == 1 and $logger and call_user_func($logger, __('Composing contents...', 'wp_all_import_plugin'));			 						
			if (!empty($this->options['content'])){
				$contents = XmlImportParser::factory(
					((!empty($this->options['is_keep_linebreaks']) and intval($this->options['is_keep_linebreaks'])) ? $xml : preg_replace('%\r\n?|\n%', ' ', $xml)),
					$cxpath,
					$this->options['content'],
					$file)->parse($records
				); $tmp_files[] = $file;						
			}
			else{
				count($titles) and $contents = array_fill(0, count($titles), '');
			}
										
			$chunk == 1 and $logger and call_user_func($logger, __('Composing dates...', 'wp_all_import_plugin'));
			if ('specific' == $this->options['date_type']) {
				$dates = XmlImportParser::factory($xml, $cxpath, $this->options['date'], $file)->parse($records); $tmp_files[] = $file;
				$warned = array(); // used to prevent the same notice displaying several times
				foreach ($dates as $i => $d) {
					if ($d == 'now') $d = current_time('mysql'); // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
					$time = strtotime($d);
					if ( strlen($d) == 10 ) $time = $d;
					if (FALSE === $time) {
						in_array($d, $warned) or $logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: unrecognized date format `%s`, assigning current date', 'wp_all_import_plugin'), $warned[] = $d));
						$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
						$time = time();
					}
					$dates[$i] = date('Y-m-d H:i:s', $time);
				}
			} else {
				$dates_start = XmlImportParser::factory($xml, $cxpath, $this->options['date_start'], $file)->parse($records); $tmp_files[] = $file;
				$dates_end = XmlImportParser::factory($xml, $cxpath, $this->options['date_end'], $file)->parse($records); $tmp_files[] = $file;
				$warned = array(); // used to prevent the same notice displaying several times
				foreach ($dates_start as $i => $d) {
					$time_start = strtotime($dates_start[$i]);
					if (FALSE === $time_start) {
						in_array($dates_start[$i], $warned) or $logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: unrecognized date format `%s`, assigning current date', 'wp_all_import_plugin'), $warned[] = $dates_start[$i]));
						$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
						$time_start = time();
					}
					$time_end = strtotime($dates_end[$i]);
					if (FALSE === $time_end) {
						in_array($dates_end[$i], $warned) or $logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: unrecognized date format `%s`, assigning current date', 'wp_all_import_plugin'), $warned[] = $dates_end[$i]));
						$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
						$time_end = time();
					}					
					$dates[$i] = date('Y-m-d H:i:s', mt_rand($time_start, $time_end));
				}
			}
						
			// [custom taxonomies]
			require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

			$taxonomies = array();						
			$exclude_taxonomies = apply_filters('pmxi_exclude_taxonomies', (class_exists('PMWI_Plugin')) ? array('post_format', 'product_type', 'product_shipping_class') : array('post_format'));	
			$post_taxonomies = array_diff_key(get_taxonomies_by_object_type(array($this->options['custom_type']), 'object'), array_flip($exclude_taxonomies));
			if ( ! empty($post_taxonomies) ):
				foreach ($post_taxonomies as $ctx): if ("" == $ctx->labels->name or (class_exists('PMWI_Plugin') and strpos($ctx->name, "pa_") === 0 and $this->options['custom_type'] == "product")) continue;
					$chunk == 1 and $logger and call_user_func($logger, sprintf(__('Composing terms for `%s` taxonomy...', 'wp_all_import_plugin'), $ctx->labels->name));
					$tx_name = $ctx->name;
					$mapping_rules = ( ! empty($this->options['tax_mapping'][$tx_name])) ? json_decode($this->options['tax_mapping'][$tx_name], true) : false;
					$taxonomies[$tx_name] = array();
					if ( ! empty($this->options['tax_logic'][$tx_name]) and ! empty($this->options['tax_assing'][$tx_name]) ){
						switch ($this->options['tax_logic'][$tx_name]){
							case 'single':
								if ( ! empty($this->options['tax_single_xpath'][$tx_name]) ){
									$txes = XmlImportParser::factory($xml, $cxpath, $this->options['tax_single_xpath'][$tx_name], $file)->parse($records); $tmp_files[] = $file;		
									foreach ($txes as $i => $tx) {
										$taxonomies[$tx_name][$i][] = wp_all_import_ctx_mapping(array(
											'name' => $tx,
											'parent' => false,
											'assign' => (isset($this->options['term_assing'][$tx_name])) ? $this->options['term_assing'][$tx_name] : true,
											'is_mapping' => (!empty($this->options['tax_enable_mapping'][$tx_name])),
											'hierarchy_level' => 1,
											'max_hierarchy_level' => 1
										), $mapping_rules, $tx_name);
									}									
								}
								break;
							case 'multiple':
								if ( ! empty($this->options['tax_multiple_xpath'][$tx_name]) ){
									$txes = XmlImportParser::factory($xml, $cxpath, $this->options['tax_multiple_xpath'][$tx_name], $file)->parse($records); $tmp_files[] = $file;		
									foreach ($txes as $i => $tx) {
										$_tx = $tx;
										// apply mapping rules before splitting via separator symbol
										if ( ! empty($this->options['tax_enable_mapping'][$tx_name]) and ! empty($this->options['tax_logic_mapping'][$tx_name]) ){											
											if ( ! empty( $mapping_rules) ){			
												foreach ($mapping_rules as $rule) {
													if ( ! empty($rule[trim($_tx)])){ 
														$_tx = trim($rule[trim($_tx)]);
														break;
													}
												}
											}
										}
										$delimeted_taxonomies = explode( ! empty($this->options['tax_multiple_delim'][$tx_name]) ? $this->options['tax_multiple_delim'][$tx_name] : ',', $_tx);
										if ( ! empty($delimeted_taxonomies) ){
											foreach ($delimeted_taxonomies as $cc) {												
												$taxonomies[$tx_name][$i][] = wp_all_import_ctx_mapping(array(
													'name' => $cc,
													'parent' => false,
													'assign' => (isset($this->options['multiple_term_assing'][$tx_name])) ? $this->options['multiple_term_assing'][$tx_name] : true,
													'is_mapping' => (!empty($this->options['tax_enable_mapping'][$tx_name]) and empty($this->options['tax_logic_mapping'][$tx_name])),
													'hierarchy_level' => 1,
													'max_hierarchy_level' => 1
												), $mapping_rules, $tx_name);
											}
										}										
									}
								}
								break;
							case 'hierarchical':
								if ( ! empty($this->options['tax_hierarchical_logic_entire'][$tx_name])){									
									if (! empty($this->options['tax_hierarchical_xpath'][$tx_name]) and is_array($this->options['tax_hierarchical_xpath'][$tx_name])){
										count($titles) and $iterator = array_fill(0, count($titles), 0);
										$taxonomies_hierarchy_groups = array_fill(0, count($titles), array());
										
										// separate hierarchy groups via symbol
										if ( ! empty($this->options['is_tax_hierarchical_group_delim'][$tx_name]) and ! empty($this->options['tax_hierarchical_group_delim'][$tx_name])){
											foreach ($this->options['tax_hierarchical_xpath'][$tx_name] as $k => $tx_xpath) { 
												if (empty($tx_xpath)) continue;
												$txes = XmlImportParser::factory($xml, $cxpath, $tx_xpath, $file)->parse($records); $tmp_files[] = $file;					
												foreach ($txes as $i => $tx) {
													$_tx = $tx;
													// apply mapping rules before splitting via separator symbol
													if ( ! empty($this->options['tax_enable_mapping'][$tx_name]) and ! empty($this->options['tax_logic_mapping'][$tx_name]) ){											
														if ( ! empty( $mapping_rules) ){			
															foreach ($mapping_rules as $rule) {
																if ( ! empty($rule[trim($_tx)])){ 
																	$_tx = trim($rule[trim($_tx)]);
																	break;
																}
															}
														}
													}
													$delimeted_groups = explode($this->options['tax_hierarchical_group_delim'][$tx_name], $_tx);
													if ( ! empty($delimeted_groups) and is_array($delimeted_groups)){
														foreach ($delimeted_groups as $group) {
															if ( ! empty($group) ) array_push($taxonomies_hierarchy_groups[$i], $group);
														}
													}
												}												
											}
										}
										else{
											foreach ($this->options['tax_hierarchical_xpath'][$tx_name] as $k => $tx_xpath) {
												if (empty($tx_xpath)) continue;
												$txes = XmlImportParser::factory($xml, $cxpath, $tx_xpath, $file)->parse($records); $tmp_files[] = $file;					
												foreach ($txes as $i => $tx) {													
													array_push($taxonomies_hierarchy_groups[$i], $tx);
												}
											}
										}
										
										foreach ($taxonomies_hierarchy_groups as $i => $groups) { if (empty($groups)) continue;
											foreach ($groups as $kk => $tx) {																																			
												$_tx = $tx;
												// apply mapping rules before splitting via separator symbol
												if ( ! empty($this->options['tax_enable_mapping'][$tx_name]) and ! empty($this->options['tax_logic_mapping'][$tx_name]) ){											
													if ( ! empty( $mapping_rules) ){			
														foreach ($mapping_rules as $rule) {
															if ( ! empty($rule[trim($_tx)])){ 
																$_tx = trim($rule[trim($_tx)]);
																break;
															}
														}
													}
												}
												$delimeted_taxonomies = explode( ! empty($this->options['tax_hierarchical_delim'][$tx_name]) ? $this->options['tax_hierarchical_delim'][$tx_name] : ',', $_tx);
												if ( ! empty($delimeted_taxonomies) ){															
													foreach ($delimeted_taxonomies as $j => $cc) {																												
														$is_assign_term = (isset($this->options['tax_hierarchical_assing'][$tx_name][$k])) ? $this->options['tax_hierarchical_assing'][$tx_name][$k] : true;
														if ( ! empty($this->options['tax_hierarchical_last_level_assign'][$tx_name]) ){
															$is_assign_term = (count($delimeted_taxonomies) == $j + 1) ? 1 : 0;
														}
														$taxonomies[$tx_name][$i][] = wp_all_import_ctx_mapping(array(
															'name' => $cc,
															'parent' => (!empty($taxonomies[$tx_name][$i][$iterator[$i] - 1]) and $j) ? $taxonomies[$tx_name][$i][$iterator[$i] - 1] : false,
															'assign' => $is_assign_term,
															'is_mapping' => (!empty($this->options['tax_enable_mapping'][$tx_name]) and empty($this->options['tax_logic_mapping'][$tx_name])),
															'hierarchy_level' => $j + 1, 
															'max_hierarchy_level' => count($delimeted_taxonomies)
														), $mapping_rules, $tx_name);															
														$iterator[$i]++;	
													}
												}													
											}
										}										
									}
								}
								if ( ! empty($this->options['tax_hierarchical_logic_manual'][$tx_name])){									
									if ( ! empty($this->options['post_taxonomies'][$tx_name]) ){
										$taxonomies_hierarchy = json_decode($this->options['post_taxonomies'][$tx_name], true);
										
										foreach ($taxonomies_hierarchy as $k => $taxonomy){	if ("" == $taxonomy['xpath']) continue;								
											$txes_raw =  XmlImportParser::factory($xml, $cxpath, $taxonomy['xpath'], $file)->parse($records); $tmp_files[] = $file;						
											$warned = array();
											
											foreach ($txes_raw as $i => $cc) {

												$_tx = $cc;
												// apply mapping rules before splitting via separator symbol
												if ( ! empty($this->options['tax_enable_mapping'][$tx_name]) and ! empty($this->options['tax_logic_mapping'][$tx_name]) ){											
													if ( ! empty( $mapping_rules) ){			
														foreach ($mapping_rules as $rule) {
															if ( ! empty($rule[trim($_tx)])){ 
																$_tx = trim($rule[trim($_tx)]);
																break;
															}
														}
													}
												}
												
												if ( ! empty($this->options['tax_manualhierarchy_delim'][$tx_name])){
													$delimeted_taxonomies = explode($this->options['tax_manualhierarchy_delim'][$tx_name], $_tx);
												}
												
												if ( empty($delimeted_taxonomies) ) continue;

												if (empty($taxonomies_hierarchy[$k]['txn_names'][$i])) $taxonomies_hierarchy[$k]['txn_names'][$i] = array();
												if (empty($taxonomies[$tx_name][$i])) $taxonomies[$tx_name][$i] = array();
												$count_cats = count($taxonomies[$tx_name][$i]);																											
												
												foreach ($delimeted_taxonomies as $j => $dc) {
													
													if (!empty($taxonomy['parent_id'])) {																			
														foreach ($taxonomies_hierarchy as $key => $value){
															if ($value['item_id'] == $taxonomy['parent_id'] and !empty($value['txn_names'][$i])){													
																foreach ($value['txn_names'][$i] as $parent) {																			
																	$taxonomies[$tx_name][$i][] = wp_all_import_ctx_mapping(array(
																		'name' => trim($dc),
																		'parent' => $parent,
																		'assign' => (isset($taxonomy['assign'])) ? $taxonomy['assign'] : true,
																		'is_mapping' => (!empty($this->options['tax_enable_mapping'][$tx_name]) and empty($this->options['tax_logic_mapping'][$tx_name])),
																		'hierarchy_level' => 1,
																		'max_hierarchy_level' => 1
																	), $mapping_rules, $tx_name);																		
																}																												
															}
														}															
													}
													else {																
														$taxonomies[$tx_name][$i][] = wp_all_import_ctx_mapping(array(
															'name' => trim($dc),
															'parent' => false,
															'assign' => (isset($taxonomy['assign'])) ? $taxonomy['assign'] : true,
															'is_mapping' => (!empty($this->options['tax_enable_mapping'][$tx_name]) and empty($this->options['tax_logic_mapping'][$tx_name])),
															'hierarchy_level' => 1,
															'max_hierarchy_level' => 1
														), $mapping_rules, $tx_name);
													}		

													if ($count_cats < count($taxonomies[$tx_name][$i])) $taxonomies_hierarchy[$k]['txn_names'][$i][] = $taxonomies[$tx_name][$i][count($taxonomies[$tx_name][$i]) - 1];
												}																														
											}
										}
									}
								}																											
								break;

							default:
											
								break;
						}
					}
				endforeach;
			endif;			
			// [/custom taxonomies]							

			// [custom fields]
			$chunk == 1 and $logger and call_user_func($logger, __('Composing custom parameters...', 'wp_all_import_plugin'));
			$meta_keys = array(); $meta_values = array();			
			
			foreach ($this->options['custom_name'] as $j => $custom_name) {
				$meta_keys[$j]   = XmlImportParser::factory($xml, $cxpath, $custom_name, $file)->parse($records); $tmp_files[] = $file;
				if (is_serialized($this->options['custom_value'][$j])){
					$meta_values[$j] = array_fill(0, count($titles), $this->options['custom_value'][$j]);
				}
				else{
					if ('' != $this->options['custom_value'][$j] and ! is_serialized($this->options['custom_value'][$j])){
						$meta_values[$j] = XmlImportParser::factory($xml, $cxpath, $this->options['custom_value'][$j], $file)->parse($records); $tmp_files[] = $file;
						// mapping custom fields
						
						if ( ! empty($this->options['custom_mapping_rules'][$j])){
							$mapping_rules = (!empty($this->options['custom_mapping_rules'][$j])) ? json_decode($this->options['custom_mapping_rules'][$j], true) : false;	
							if ( ! empty($mapping_rules) and is_array($mapping_rules)){
								foreach ($meta_values[$j] as $key => $val) {
									foreach ($mapping_rules as $rule_number => $rule) {
										if ( ! empty($rule[trim($val)])){ 
											$meta_values[$j][$key] = trim($rule[trim($val)]);										
											break;
										}
									}									
								}
							}
						}						
					}					
					else{
						$meta_values[$j] = array_fill(0, count($titles), '');
					}
				}
			}		

			// serialized custom post fields
			$serialized_meta = array();

			if ( ! empty($meta_keys) ){

				foreach ($meta_keys as $j => $custom_name) {													
					
					//if ( ! in_array($custom_name[0], array_keys($serialized_meta)) ){

						// custom field in serialized format
						if (!empty($this->options['custom_format']) and $this->options['custom_format'][$j])
						{
							$meta_values[$j] = array_fill(0, count($titles), array());

							$serialized_values = (!empty($this->options['serialized_values'][$j])) ? json_decode($this->options['serialized_values'][$j], true) : false;														
							
							if (!empty($serialized_values) and is_array($serialized_values)) {
								
								$serialized_meta_keys = array(); $serialized_meta_values = array();

								foreach ( array_filter($serialized_values) as $key => $value) {
									$k = $key;
									if (is_array($value)){
										$keys = array_keys($value);
										$k = $keys[0];
									}
									if (empty($k) or is_numeric($k)){
										array_push($serialized_meta_keys, array_fill(0, count($titles), $k));
									}
									else{
										array_push($serialized_meta_keys, XmlImportParser::factory($xml, $cxpath, $k, $file)->parse($records)); $tmp_files[] = $file;
									}
									$v = (is_array($value)) ? $value[$k] : $value;
									if ( "" == $v){
										array_push($serialized_meta_values, array_fill(0, count($titles), ""));
									}
									elseif ( is_serialized($v) ){
										array_push($serialized_meta_values, array_fill(0, count($titles), $v));
									}
									else{
										array_push($serialized_meta_values, XmlImportParser::factory($xml, $cxpath, $v, $file)->parse($records)); $tmp_files[] = $file;
									}
								}								

								if (!empty($serialized_meta_keys)){
									foreach ($serialized_meta_keys as $skey => $sval) {
										foreach ($sval as $ipost => $ival) {
											$v = (is_serialized($serialized_meta_values[$skey][$ipost])) ? unserialize($serialized_meta_values[$skey][$ipost]) : $serialized_meta_values[$skey][$ipost];
											( "" == $ival or is_numeric($ival) ) ? array_push($meta_values[$j][$ipost], $v) : $meta_values[$j][$ipost][$ival] = $v;											
										}																				
									}
								}

							}

						}
						
						$serialized_meta[] = array(stripcslashes($custom_name[0]) => $meta_values[$j]);
						
					//}					
				}
			}	
			// [/custom fields]						

			// Composing featured images			
			$image_sections = apply_filters('wp_all_import_image_sections', array( 
				array(
					'slug'  => '',
					'title' => __('Images', 'wp_all_import_plugin'),
					'type'  => 'images'
				)
			));	

			if ( ! (($uploads = wp_upload_dir()) && false === $uploads['error'])) {
				$logger and call_user_func($logger, __('<b>WARNING</b>', 'wp_all_import_plugin') . ': ' . $uploads['error']);
				$logger and call_user_func($logger, __('<b>WARNING</b>: No featured images will be created. Uploads folder is not found.', 'wp_all_import_plugin'));				
				$logger and !$is_cron and PMXI_Plugin::$session->warnings++;				
			} else {
				$images_bundle = array();
				foreach ($image_sections as $section) {					
					$chunk == 1 and $logger and call_user_func($logger, __('Composing URLs for ' . strtolower($section['title']) . '...', 'wp_all_import_plugin'));
					$featured_images = array();				
					if ( "no" == $this->options[$section['slug'] . 'download_images']){
						if ($this->options[$section['slug'] . 'featured_image']) {					
							$featured_images = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'featured_image'], $file)->parse($records); $tmp_files[] = $file;																				
						} else {
							count($titles) and $featured_images = array_fill(0, count($titles), '');
						}					
					}
					else{
						if ($this->options[$section['slug'] . 'download_featured_image']) {					
							$featured_images = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'download_featured_image'], $file)->parse($records); $tmp_files[] = $file;																				
						} else {
							count($titles) and $featured_images = array_fill(0, count($titles), '');
						}
					}					
					
					$images_bundle[ empty($section['slug']) ? 'pmxi_gallery_image' : $section['slug']] = array(
						'type'  => $section['type'],
						'files' => $featured_images
					);
										
					// Composing images meta titles
					$image_meta_titles_bundle = array();
					if ( $this->options[$section['slug'] . 'set_image_meta_title'] ){																	
						$chunk == 1 and $logger and call_user_func($logger, __('Composing ' . strtolower($section['title']) . ' meta data (titles)...', 'wp_all_import_plugin'));
						$image_meta_titles = array();				
						if ($this->options[$section['slug'] . 'image_meta_title']) {					
							$image_meta_titles = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'image_meta_title'], $file)->parse($records); $tmp_files[] = $file;						
						} else {
							count($titles) and $image_meta_titles = array_fill(0, count($titles), '');
						}
						$image_meta_titles_bundle[ empty($section['slug']) ? 'pmxi_gallery_image' : $section['slug']] = $image_meta_titles;
					}

					// Composing images meta captions
					$image_meta_captions_bundle = array();
					if ( $this->options[$section['slug'] . 'set_image_meta_caption'] ){	
						$chunk == 1 and $logger and call_user_func($logger, __('Composing ' . strtolower($section['title']) . ' meta data (captions)...', 'wp_all_import_plugin'));
						$image_meta_captions = array();				
						if ($this->options[$section['slug'] . 'image_meta_caption']) {					
							$image_meta_captions = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'image_meta_caption'], $file)->parse($records); $tmp_files[] = $file;								
						} else {
							count($titles) and $image_meta_captions = array_fill(0, count($titles), '');
						}
						$image_meta_captions_bundle[ empty($section['slug']) ? 'pmxi_gallery_image' : $section['slug']] = $image_meta_captions;
					}

					// Composing images meta alt text
					$image_meta_alts_bundle = array();
					if ( $this->options[$section['slug'] . 'set_image_meta_alt'] ){	
						$chunk == 1 and $logger and call_user_func($logger, __('Composing ' . strtolower($section['title']) . ' meta data (alt text)...', 'wp_all_import_plugin'));
						$image_meta_alts = array();				
						if ($this->options[$section['slug'] . 'image_meta_alt']) {					
							$image_meta_alts = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'image_meta_alt'], $file)->parse($records); $tmp_files[] = $file;						
						} else {
							count($titles) and $image_meta_alts = array_fill(0, count($titles), '');
						}
						$image_meta_alts_bundle[ empty($section['slug']) ? 'pmxi_gallery_image' : $section['slug']] = $image_meta_alts;
					}

					// Composing images meta description
					$image_meta_descriptions_bundle = array();
					if ( $this->options[$section['slug'] . 'set_image_meta_description'] ){											
						$chunk == 1 and $logger and call_user_func($logger, __('Composing ' . strtolower($section['title']) . ' meta data (description)...', 'wp_all_import_plugin'));
						$image_meta_descriptions = array();				
						if ($this->options[$section['slug'] . 'image_meta_description']) {					
							$image_meta_descriptions = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'image_meta_description'], $file)->parse($records); $tmp_files[] = $file;						
						} else {
							count($titles) and $image_meta_descriptions = array_fill(0, count($titles), '');
						}	
						$image_meta_descriptions_bundle[ empty($section['slug']) ? 'pmxi_gallery_image' : $section['slug']] = $image_meta_descriptions;							
					}

					$auto_rename_images_bundle = array();
					$auto_extensions_bundle = array();
					if ( "yes" == $this->options[$section['slug'] . 'download_images'] ){
						// Composing images suffix
						$chunk == 1 and $this->options[$section['slug'] . 'auto_rename_images'] and $logger and call_user_func($logger, __('Composing ' . strtolower($section['title']) . ' suffix...', 'wp_all_import_plugin'));			
						$auto_rename_images = array();
						if ( $this->options[$section['slug'] . 'auto_rename_images'] and ! empty($this->options[$section['slug'] . 'auto_rename_images_suffix'])){
							$auto_rename_images = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'auto_rename_images_suffix'], $file)->parse($records); $tmp_files[] = $file;
						}
						else{
							count($titles) and $auto_rename_images = array_fill(0, count($titles), '');
						}
						$auto_rename_images_bundle[ empty($section['slug']) ? 'pmxi_gallery_image' : $section['slug']] = $auto_rename_images;	

						// Composing images extensions
						$chunk == 1 and $this->options[$section['slug'] . 'auto_set_extension'] and $logger and call_user_func($logger, __('Composing ' . strtolower($section['title']) . ' extensions...', 'wp_all_import_plugin'));			
						$auto_extensions = array();
						if ( $this->options[$section['slug'] . 'auto_set_extension'] and ! empty($this->options[$section['slug'] . 'new_extension'])){
							$auto_extensions = XmlImportParser::factory($xml, $cxpath, $this->options[$section['slug'] . 'new_extension'], $file)->parse($records); $tmp_files[] = $file;
						}
						else{
							count($titles) and $auto_extensions = array_fill(0, count($titles), '');
						}
						$auto_extensions_bundle[ empty($section['slug']) ? 'pmxi_gallery_image' : $section['slug']] = $auto_extensions;	
					}

				}				
			}

			// Composing attachments
			if ( ! (($uploads = wp_upload_dir()) && false === $uploads['error'])) {
				$logger and call_user_func($logger, __('<b>WARNING</b>', 'wp_all_import_plugin') . ': ' . $uploads['error']);				
				$logger and call_user_func($logger, __('<b>WARNING</b>: No attachments will be created', 'wp_all_import_plugin')); 				
				$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
			} else {
				$chunk == 1 and $logger and call_user_func($logger, __('Composing URLs for attachments files...', 'wp_all_import_plugin'));
				$attachments = array();

				if ($this->options['attachments']) {
					// Detect if attachments is separated by comma
					$atchs = explode(',', $this->options['attachments']);					
					if (!empty($atchs)){
						$parse_multiple = true;
						foreach($atchs as $atch) if (!preg_match("/{.*}/", trim($atch))) $parse_multiple = false;			

						if ($parse_multiple)
						{							
							foreach($atchs as $atch) 
							{								
								$posts_attachments = XmlImportParser::factory($xml, $cxpath, trim($atch), $file)->parse($records); $tmp_files[] = $file;																
								foreach($posts_attachments as $i => $val) $attachments[$i][] = $val;								
							}
						}
						else
						{
							$attachments = XmlImportParser::factory($xml, $cxpath, $this->options['attachments'], $file)->parse($records); $tmp_files[] = $file;								
						}
					}
					
				} else {
					count($titles) and $attachments = array_fill(0, count($titles), '');
				}
			}				

			$chunk == 1 and $logger and call_user_func($logger, __('Composing unique keys...', 'wp_all_import_plugin'));
			if (!empty($this->options['unique_key'])){
				$unique_keys = XmlImportParser::factory($xml, $cxpath, $this->options['unique_key'], $file)->parse($records); $tmp_files[] = $file;
			}
			else{
				count($titles) and $unique_keys = array_fill(0, count($titles), '');
			}
			
			$chunk == 1 and $logger and call_user_func($logger, __('Processing posts...', 'wp_all_import_plugin'));															

			$addons = array();
			$addons_data = array();

			// data parsing for WP All Import add-ons
			$chunk == 1 and $logger and call_user_func($logger, __('Data parsing via add-ons...', 'wp_all_import_plugin'));
			$parsingData = array(
				'import' => $this,
				'count'  => count($titles),
				'xml'    => $xml,
				'logger' => $logger,
				'chunk'  => $chunk,
				'xpath_prefix' => $xpath_prefix				
			);			

			$parse_functions = apply_filters('wp_all_import_addon_parse', array());

			foreach (PMXI_Admin_Addons::get_active_addons() as $class) {							
				$model_class = str_replace("_Plugin", "_Import_Record", $class);	
				if (class_exists($model_class)){						
					$addons[$class] = new $model_class();
					$addons_data[$class] = ( method_exists($addons[$class], 'parse') ) ? $addons[$class]->parse($parsingData) : false;				
				}
				else
				{					
					if ( ! empty($parse_functions[$class]) ){ 

						if ( is_array($parse_functions[$class]) and is_callable($parse_functions[$class]) or ! is_array($parse_functions[$class]) and function_exists($parse_functions[$class])  ){

							$addons_data[$class] = call_user_func($parse_functions[$class], $parsingData);					
						}
						
					}
				}
			}

			// save current import state to variables before import			
			$created = $this->created;
			$updated = $this->updated;
			$skipped = $this->skipped;			
			
			$specified_records = array();

			if ($this->options['is_import_specified']) {
				$chunk == 1 and $logger and call_user_func($logger, __('Calculate specified records to import...', 'wp_all_import_plugin'));
				foreach (preg_split('% *, *%', $this->options['import_specified'], -1, PREG_SPLIT_NO_EMPTY) as $chank) {
					if (preg_match('%^(\d+)-(\d+)$%', $chank, $mtch)) {
						$specified_records = array_merge($specified_records, range(intval($mtch[1]), intval($mtch[2])));
					} else {
						$specified_records = array_merge($specified_records, array(intval($chank)));
					}
				}

			}	

			$simpleXml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    
    		$rootNodes = $simpleXml->xpath($cxpath);				

			foreach ($titles as $i => $void) {			

				$custom_type_details = get_post_type_object( $post_type[$i] );

				if ($is_cron and $cron_sleep) sleep($cron_sleep);		

				$logger and call_user_func($logger, __('---', 'wp_all_import_plugin'));
				$logger and call_user_func($logger, sprintf(__('Record #%s', 'wp_all_import_plugin'), $this->imported + $this->skipped + $i + 1));

				wp_cache_flush();

				$logger and call_user_func($logger, __('<b>ACTION</b>: pmxi_before_post_import ...', 'wp_all_import_plugin'));
				do_action('pmxi_before_post_import', $this->id);															

				if ( empty($titles[$i]) ) {
					if ( ! empty($addons_data['PMWI_Plugin']) and !empty($addons_data['PMWI_Plugin']['single_product_parent_ID'][$i]) ){
						$titles[$i] = $addons_data['PMWI_Plugin']['single_product_parent_ID'][$i] . ' Product Variation';
					}					
					else{
						$logger and call_user_func($logger, __('<b>WARNING</b>: title is empty.', 'wp_all_import_plugin'));
						$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
					}
				}				
				
				if ( $this->options['custom_type'] == 'import_users' ){					
					$articleData = array(			
						'user_pass' => $addons_data['PMUI_Plugin']['pmui_pass'][$i],
						'user_login' => $addons_data['PMUI_Plugin']['pmui_logins'][$i],
						'user_nicename' => $addons_data['PMUI_Plugin']['pmui_nicename'][$i],
						'user_url' =>  $addons_data['PMUI_Plugin']['pmui_url'][$i],
						'user_email' =>  $addons_data['PMUI_Plugin']['pmui_email'][$i],
						'display_name' =>  $addons_data['PMUI_Plugin']['pmui_display_name'][$i],
						'user_registered' =>  $addons_data['PMUI_Plugin']['pmui_registered'][$i],
						'first_name' =>  $addons_data['PMUI_Plugin']['pmui_first_name'][$i],
						'last_name' =>  $addons_data['PMUI_Plugin']['pmui_last_name'][$i],
						'description' =>  $addons_data['PMUI_Plugin']['pmui_description'][$i],
						'nickname' => $addons_data['PMUI_Plugin']['pmui_nickname'][$i],
						'role' => ('' == $addons_data['PMUI_Plugin']['pmui_role'][$i]) ? 'subscriber' : strtolower($addons_data['PMUI_Plugin']['pmui_role'][$i]),
					);		
					$logger and call_user_func($logger, sprintf(__('Combine all data for user %s...', 'wp_all_import_plugin'), $articleData['user_login']));
				} 
				else {					
					$articleData = array(
						'post_type' => $post_type[$i],
						'post_status' => ("xpath" == $this->options['status']) ? $post_status[$i] : $this->options['status'],
						'comment_status' => ("xpath" == $this->options['comment_status']) ? $comment_status[$i] : $this->options['comment_status'],
						'ping_status' => ("xpath" == $this->options['ping_status']) ? $ping_status[$i] : $this->options['ping_status'], 
						'post_title' => (!empty($this->options['is_leave_html'])) ? html_entity_decode($titles[$i]) : $titles[$i], 
						'post_excerpt' => apply_filters('pmxi_the_excerpt', ((!empty($this->options['is_leave_html'])) ? html_entity_decode($post_excerpt[$i]) : $post_excerpt[$i]), $this->id),
						'post_name' => $post_slug[$i],
						'post_content' => apply_filters('pmxi_the_content', ((!empty($this->options['is_leave_html'])) ? html_entity_decode($contents[$i]) : $contents[$i]), $this->id),
						'post_date' => $dates[$i],
						'post_date_gmt' => get_gmt_from_date($dates[$i]),
						'post_author' => $post_author[$i],						
						'menu_order' => (int) $menu_order[$i],
						'post_parent' => ("no" == $this->options['is_multiple_page_parent']) ? (int) $page_parent[$i] : (int) $this->options['parent']
					);
					$logger and call_user_func($logger, sprintf(__('Combine all data for post `%s`...', 'wp_all_import_plugin'), $articleData['post_title']));		
				}						
				
				// Re-import Records Matching
				$post_to_update = false; $post_to_update_id = false;
				
				// if Auto Matching re-import option selected
				if ( "manual" != $this->options['duplicate_matching'] ){
					
					// find corresponding article among previously imported				
					$logger and call_user_func($logger, sprintf(__('Find corresponding article among previously imported for post `%s`...', 'wp_all_import_plugin'), $articleData['post_title']));
					$postRecord->clear();
					$postRecord->getBy(array(
						'unique_key' => $unique_keys[$i],
						'import_id' => $this->id,
					));

					if ( ! $postRecord->isEmpty() ) {
						$logger and call_user_func($logger, sprintf(__('Duplicate post was founded for post %s with unique key `%s`...', 'wp_all_import_plugin'), $articleData['post_title'], $unique_keys[$i]));
						if ( $this->options['custom_type'] == 'import_users'){
							$post_to_update = get_user_by('id', $post_to_update_id = $postRecord->post_id);							
						}
						else{
							$post_to_update = get_post($post_to_update_id = $postRecord->post_id);
						}
					}
					else{
						$logger and call_user_func($logger, sprintf(__('Duplicate post wasn\'t founded with unique key `%s`...', 'wp_all_import_plugin'), $unique_keys[$i]));
					}
																
				// if Manual Matching re-import option seleted
				} else {
										
					if ('custom field' == $this->options['duplicate_indicator']) {
						$custom_duplicate_value = XmlImportParser::factory($xml, $cxpath, $this->options['custom_duplicate_value'], $file)->parse($records); $tmp_files[] = $file;
						$custom_duplicate_name = XmlImportParser::factory($xml, $cxpath, $this->options['custom_duplicate_name'], $file)->parse($records); $tmp_files[] = $file;
					}
					else{
						count($titles) and $custom_duplicate_name = $custom_duplicate_value = array_fill(0, count($titles), '');
					}
					
					$logger and call_user_func($logger, sprintf(__('Find corresponding article among database for post `%s`...', 'wp_all_import_plugin'), $articleData['post_title']));
					// handle duplicates according to import settings
					if ($duplicates = pmxi_findDuplicates($articleData, $custom_duplicate_name[$i], $custom_duplicate_value[$i], $this->options['duplicate_indicator'])) {															
						$duplicate_id = array_shift($duplicates);						
						if ($duplicate_id) {	
							$logger and call_user_func($logger, sprintf(__('Duplicate post was founded for post `%s`...', 'wp_all_import_plugin'), $articleData['post_title']));
							if ( $this->options['custom_type'] == 'import_users'){													
								$post_to_update = get_user_by('id', $post_to_update_id = $duplicate_id);
							}
							else{
								$post_to_update = get_post($post_to_update_id = $duplicate_id);
							}
						}	
						else{
							$logger and call_user_func($logger, sprintf(__('Duplicate post wasn\'n founded for post `%s`...', 'wp_all_import_plugin'), $articleData['post_title']));
						}					
					}					
				}

				if ( ! empty($specified_records) ) {

					if ( ! in_array($created + $updated + $skipped + 1, $specified_records) ){

						if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $this->iteration))->update();

						$skipped++;											
						$logger and call_user_func($logger, __('<b>SKIPPED</b>: by specified records option', 'wp_all_import_plugin'));
						$logger and !$is_cron and PMXI_Plugin::$session->warnings++;					
						$logger and !$is_cron and PMXI_Plugin::$session->chunk_number++;
						$logger and !$is_cron and PMXI_Plugin::$session->save_data();						
						continue;
					}										
				}				

				// Duplicate record is founded
				if ($post_to_update){

					$continue_import = true;

					$continue_import = apply_filters('wp_all_import_is_post_to_update', $post_to_update_id, wp_all_import_xml2array($rootNodes[$i]));

					if ( ! $continue_import ){

						if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $this->iteration))->update();	

						$skipped++;
						$logger and call_user_func($logger, sprintf(__('<b>SKIPPED</b>: By filter wp_all_import_is_post_to_update `%s`', 'wp_all_import_plugin'), $articleData['post_title']));
						$logger and !$is_cron and PMXI_Plugin::$session->warnings++;							
						$logger and !$is_cron and PMXI_Plugin::$session->chunk_number++;	
						$logger and !$is_cron and PMXI_Plugin::$session->save_data();	
						continue;
					}

					//$logger and call_user_func($logger, sprintf(__('Duplicate record is founded for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));

					// Do not update already existing records option selected
					if ("yes" == $this->options['is_keep_former_posts']) {	

						if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $this->iteration))->update();	

						do_action('pmxi_do_not_update_existing', $post_to_update_id, $this->id, $this->iteration);																																											

						$skipped++;
						$logger and call_user_func($logger, sprintf(__('<b>SKIPPED</b>: Previously imported record found for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));
						$logger and !$is_cron and PMXI_Plugin::$session->warnings++;							
						$logger and !$is_cron and PMXI_Plugin::$session->chunk_number++;	
						$logger and !$is_cron and PMXI_Plugin::$session->save_data();	
						continue;
					}					

					$articleData['ID'] = $post_to_update_id;					
					// Choose which data to update
					if ( $this->options['update_all_data'] == 'no' ){

						if ( ! in_array($this->options['custom_type'], array('import_users'))){

							// preserve date of already existing article when duplicate is found					
							if ( ( ! $this->options['is_update_categories'] and ( is_object_in_taxonomy( $post_type[$i], 'category' ) or is_object_in_taxonomy( $post_type[$i], 'post_tag' ) ) ) or ($this->options['is_update_categories'] and $this->options['update_categories_logic'] != "full_update")) { 							
								$logger and call_user_func($logger, sprintf(__('Preserve taxonomies of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));	
								$existing_taxonomies = array();
								foreach (array_keys($taxonomies) as $tx_name) {
									$txes_list = get_the_terms($articleData['ID'], $tx_name);
									if (is_wp_error($txes_list)) {
										$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to get current taxonomies for article #%d, updating with those read from XML file', 'wp_all_import_plugin'), $articleData['ID']));
										$logger and !$is_cron and PMXI_Plugin::$session->warnings++;		
									} else {
										$txes_new = array();
										if (!empty($txes_list)):
											foreach ($txes_list as $t) {
												$txes_new[] = $t->term_taxonomy_id;
											}
										endif;
										$existing_taxonomies[$tx_name][$i] = $txes_new;								
									}
								}							
							}	
										
							if ( ! $this->options['is_update_dates']) { // preserve date of already existing article when duplicate is found
								$articleData['post_date'] = $post_to_update->post_date;
								$articleData['post_date_gmt'] = $post_to_update->post_date_gmt;
								$logger and call_user_func($logger, sprintf(__('Preserve date of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));								
							}
							if ( ! $this->options['is_update_status']) { // preserve status and trashed flag
								$articleData['post_status'] = $post_to_update->post_status;
								$logger and call_user_func($logger, sprintf(__('Preserve status of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));								
							}
							if ( ! $this->options['is_update_content']){ 
								$articleData['post_content'] = $post_to_update->post_content;
								$logger and call_user_func($logger, sprintf(__('Preserve content of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));								
							}
							if ( ! $this->options['is_update_title']){ 
								$articleData['post_title'] = $post_to_update->post_title;		
								$logger and call_user_func($logger, sprintf(__('Preserve title of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));																		
							}
							if ( ! $this->options['is_update_slug']){ 
								$articleData['post_name'] = $post_to_update->post_name;			
								$logger and call_user_func($logger, sprintf(__('Preserve slug of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));																	
							}
							if ( ! $this->options['is_update_excerpt']){ 
								$articleData['post_excerpt'] = $post_to_update->post_excerpt;
								$logger and call_user_func($logger, sprintf(__('Preserve excerpt of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));																				
							}										
							if ( ! $this->options['is_update_menu_order']){ 
								$articleData['menu_order'] = $post_to_update->menu_order;
								$logger and call_user_func($logger, sprintf(__('Preserve menu order of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));								
							}
							if ( ! $this->options['is_update_parent']){ 
								$articleData['post_parent'] = $post_to_update->post_parent;
								$logger and call_user_func($logger, sprintf(__('Preserve post parent of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));								
							}
							if ( ! $this->options['is_update_author']){ 
								$articleData['post_author'] = $post_to_update->post_author;
								$logger and call_user_func($logger, sprintf(__('Preserve post author of already existing article for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));
							}
						}
						else {
							if ( ! $this->options['is_update_first_name'] ) $articleData['first_name'] = $post_to_update->first_name;
							if ( ! $this->options['is_update_last_name'] ) $articleData['last_name'] = $post_to_update->last_name;
							if ( ! $this->options['is_update_role'] ) unset($articleData['role']);
							if ( ! $this->options['is_update_nickname'] ) $articleData['nickname'] = get_user_meta($post_to_update->ID, 'nickname', true);
							if ( ! $this->options['is_update_description'] ) $articleData['description'] = get_user_meta($post_to_update->ID, 'description', true);
							if ( ! $this->options['is_update_login'] ) $articleData['user_login'] = $post_to_update->user_login; 
							if ( ! $this->options['is_update_password'] ) unset($articleData['user_pass']);
							if ( ! $this->options['is_update_nicename'] ) $articleData['user_nicename'] = $post_to_update->user_nicename;
							if ( ! $this->options['is_update_email'] ) $articleData['user_email'] = $post_to_update->user_email;
							if ( ! $this->options['is_update_registered'] ) $articleData['user_registered'] = $post_to_update->user_registered;
							if ( ! $this->options['is_update_display_name'] ) $articleData['display_name'] = $post_to_update->display_name;
							if ( ! $this->options['is_update_url'] ) $articleData['user_url'] = $post_to_update->user_url;
						}

						$logger and call_user_func($logger, sprintf(__('Applying filter `pmxi_article_data` for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));	
						$articleData = apply_filters('pmxi_article_data', $articleData, $this, $post_to_update);
																
					}

					if ( ! in_array($this->options['custom_type'], array('import_users'))){

						if ( $this->options['update_all_data'] == 'yes' or ( $this->options['update_all_data'] == 'no' and $this->options['is_update_attachments'])) {
							$logger and call_user_func($logger, sprintf(__('Deleting attachments for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));								
							wp_delete_attachments($articleData['ID'], true, 'files');
						}
						// handle obsolete attachments (i.e. delete or keep) according to import settings
						if ( $this->options['update_all_data'] == 'yes' or ( $this->options['update_all_data'] == 'no' and $this->options['is_update_images'] and $this->options['update_images_logic'] == "full_update")){
							$logger and call_user_func($logger, sprintf(__('Deleting images for `%s`', 'wp_all_import_plugin'), $articleData['post_title']));								
							wp_delete_attachments($articleData['ID'], ! $this->options['do_not_remove_images'], 'images');
						}

					}
				}
				elseif ( ! $postRecord->isEmpty() ){
					
					// existing post not found though it's track was found... clear the leftover, plugin will continue to treat record as new
					$postRecord->clear();
					
				}									
				
				// no new records are created. it will only update posts it finds matching duplicates for
				if (  ! $this->options['create_new_records'] and empty($articleData['ID']) ){ 
					
					if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $this->iteration))->update();

					$logger and call_user_func($logger, __('<b>SKIPPED</b>: by do not create new posts option.', 'wp_all_import_plugin'));
					$logger and !$is_cron and PMXI_Plugin::$session->warnings++;								
					$logger and !$is_cron and PMXI_Plugin::$session->chunk_number++;
					$skipped++;		
					$logger and !$is_cron and PMXI_Plugin::$session->save_data();	
					continue;
				}
				
				// cloak urls with `WP Wizard Cloak` if corresponding option is set
				if ( ! empty($this->options['is_cloak']) and class_exists('PMLC_Plugin')) {
					if (preg_match_all('%<a\s[^>]*href=(?(?=")"([^"]*)"|(?(?=\')\'([^\']*)\'|([^\s>]*)))%is', $articleData['post_content'], $matches, PREG_PATTERN_ORDER)) {
						$hrefs = array_unique(array_merge(array_filter($matches[1]), array_filter($matches[2]), array_filter($matches[3])));
						foreach ($hrefs as $url) {
							if (preg_match('%^\w+://%i', $url)) { // mask only links having protocol
								// try to find matching cloaked link among already registered ones
								$list = new PMLC_Link_List(); $linkTable = $list->getTable();
								$rule = new PMLC_Rule_Record(); $ruleTable = $rule->getTable();
								$dest = new PMLC_Destination_Record(); $destTable = $dest->getTable();
								$list->join($ruleTable, "$ruleTable.link_id = $linkTable.id")
									->join($destTable, "$destTable.rule_id = $ruleTable.id")
									->setColumns("$linkTable.*")
									->getBy(array(
										"$linkTable.destination_type =" => 'ONE_SET',
										"$linkTable.is_trashed =" => 0,
										"$linkTable.preset =" => '',
										"$linkTable.expire_on =" => '0000-00-00',
										"$ruleTable.type =" => 'ONE_SET',
										"$destTable.weight =" => 100,
										"$destTable.url LIKE" => $url,
									), NULL, 1, 1)->convertRecords();
								if ($list->count()) { // matching link found
									$link = $list[0];
								} else { // register new cloaked link
									global $wpdb;
									$slug = max(
										intval($wpdb->get_var("SELECT MAX(CONVERT(name, SIGNED)) FROM $linkTable")),
										intval($wpdb->get_var("SELECT MAX(CONVERT(slug, SIGNED)) FROM $linkTable")),
										0
									);
									$i = 0; do {
										is_int(++$slug) and $slug > 0 or $slug = 1;
										$is_slug_found = ! intval($wpdb->get_var("SELECT COUNT(*) FROM $linkTable WHERE name = '$slug' OR slug = '$slug'"));
									} while( ! $is_slug_found and $i++ < 100000);
									if ($is_slug_found) {
										$link = new PMLC_Link_Record(array(
											'name' => strval($slug),
											'slug' => strval($slug),
											'header_tracking_code' => '',
											'footer_tracking_code' => '',
											'redirect_type' => '301',
											'destination_type' => 'ONE_SET',
											'preset' => '',
											'forward_url_params' => 1,
											'no_global_tracking_code' => 0,
											'expire_on' => '0000-00-00',
											'created_on' => date('Y-m-d H:i:s'),
											'is_trashed' => 0,
										));
										$link->insert();
										$rule = new PMLC_Rule_Record(array(
											'link_id' => $link->id,
											'type' => 'ONE_SET',
											'rule' => '',
										));
										$rule->insert();
										$dest = new PMLC_Destination_Record(array(
											'rule_id' => $rule->id,
											'url' => $url,
											'weight' => 100,
										));
										$dest->insert();
									} else {
										$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to create cloaked link for %s', 'wp_all_import_plugin'), $url));
										$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
										$link = NULL;
									}
								}
								if ($link) { // cloaked link is found or created for url
									$articleData['post_content'] = preg_replace('%' . preg_quote($url, '%') . '(?=([\s\'"]|$))%i', $link->getUrl(), $articleData['post_content']);
								}
							}
						}
					}
				}		

				// insert article being imported						
				if ($this->options['is_fast_mode']){
					foreach (array('transition_post_status', 'save_post', 'pre_post_update', 'add_attachment', 'edit_attachment', 'edit_post', 'post_updated', 'wp_insert_post', 'save_post_' . $post_type[$i]) as $act) {
						remove_all_actions($act);
					}						
				}
					
				if ( ! in_array($this->options['custom_type'], array('import_users'))){						
					if (empty($articleData['ID'])){
						$logger and call_user_func($logger, sprintf(__('<b>CREATING</b> `%s` `%s`', 'wp_all_import_plugin'), $articleData['post_title'], $custom_type_details->labels->singular_name));
					}
					else{
						$logger and call_user_func($logger, sprintf(__('<b>UPDATING</b> `%s` `%s`', 'wp_all_import_plugin'), $articleData['post_title'], $custom_type_details->labels->singular_name));
					}					
					$pid = wp_insert_post($articleData, true);
				}
				else{
					$pid = (empty($articleData['ID'])) ? wp_insert_user( $articleData ) : wp_update_user( $articleData );
					$articleData['post_title'] = $articleData['user_login'];
				}
				
				if (is_wp_error($pid)) {
					$logger and call_user_func($logger, __('<b>ERROR</b>', 'wp_all_import_plugin') . ': ' . $pid->get_error_message());
					$logger and !$is_cron and PMXI_Plugin::$session->errors++;
					$skipped++;
				} else {										
															
					if ("manual" != $this->options['duplicate_matching'] or empty($articleData['ID'])){						
						// associate post with import												
						$postRecord->isEmpty() and $postRecord->set(array(
							'post_id' => $pid,
							'import_id' => $this->id,
							'unique_key' => $unique_keys[$i],
							'product_key' => (($post_type[$i] == "product" and PMXI_Admin_Addons::get_addon('PMWI_Plugin')) ? $addons_data['PMWI_Plugin']['single_product_ID'][$i] : '')
						))->insert();

						$postRecord->set(array('iteration' => $this->iteration))->update();						

						$logger and call_user_func($logger, sprintf(__('Associate post `%s` with current import ...', 'wp_all_import_plugin'), $articleData['post_title']));
					}

					// [post format]
					if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post_type[$i], 'post-formats' ) ){						
						set_post_format($pid, ("xpath" == $this->options['post_format']) ? $post_format[$i] : $this->options['post_format'] ); 						
						$logger and call_user_func($logger, sprintf(__('Associate post `%s` with post format %s ...', 'wp_all_import_plugin'), $articleData['post_title'], ("xpath" == $this->options['post_format']) ? $post_format[$i] : $this->options['post_format']));
					}
					// [/post format]
					
					// [custom fields]					
					if (empty($articleData['ID']) or $this->options['update_all_data'] == 'yes' or ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields']) or ($this->options['update_all_data'] == 'no' and $this->options['is_update_attributes'] and $post_type[$i] == "product" and class_exists('PMWI_Plugin'))) {																			

						$show_log = ( ! empty($serialized_meta) );

						$show_log and $logger and call_user_func($logger, __('<b>CUSTOM FIELDS:</b>', 'wp_all_import_plugin'));

						// Delete all meta keys 
						if ( ! empty($articleData['ID']) ) {

							// Get all existing meta keys						
							$existing_meta_keys = array(); 

							if ( $this->options['custom_type'] == 'import_users'){
								foreach (get_user_meta($pid, '') as $cur_meta_key => $cur_meta_val) $existing_meta_keys[$cur_meta_key] = $cur_meta_val;
							}
							else{
								foreach (get_post_meta($pid, '') as $cur_meta_key => $cur_meta_val) $existing_meta_keys[$cur_meta_key] = $cur_meta_val;
							}
							/*$import_entry = ( $this->options['custom_type'] == 'import_users') ? 'user' : 'post';
							$meta_table = _get_meta_table( $import_entry );*/
							// delete keys which are no longer correspond to import settings																														
							foreach ($existing_meta_keys as $cur_meta_key => $cur_meta_val) { 
								
								// Do not delete post meta for features image 
								if ( in_array($cur_meta_key, array('_thumbnail_id','_product_image_gallery')) ) continue;																

								if ( in_array($cur_meta_key, array('first_name', PMXI_Plugin::getInstance()->getWPPrefix() . 'capabilities', 'nickname', 'last_name', 'description')) and $this->options['custom_type'] == 'import_users') continue;

								$field_to_delete = true;								

								// apply addons filters
								if ( ! apply_filters('pmxi_custom_field_to_delete', $field_to_delete, $pid, $post_type[$i], $this->options, $cur_meta_key) ) continue;

								// Update all Custom Fields is defined
								if ($this->options['update_all_data'] == 'yes' or ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "full_update")) {
									
									//$this->wpdb->query($this->wpdb->prepare("DELETE FROM $meta_table WHERE `" . $import_entry . "_id` = $pid AND `meta_key` = %s", $cur_meta_key));
									if ( $this->options['custom_type'] == 'import_users'){
										delete_user_meta($pid, $cur_meta_key);
									}
									else{
										delete_post_meta($pid, $cur_meta_key);
									}
									
									$show_log and $logger and call_user_func($logger, sprintf(__('- Custom field %s has been deleted for `%s` attempted to `update all custom fields` setting ...', 'wp_all_import_plugin'), $cur_meta_key, $articleData['post_title']));
								}
								// Update only these Custom Fields, leave the rest alone
								elseif ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "only"){
									if (!empty($this->options['custom_fields_list']) and is_array($this->options['custom_fields_list']) and in_array($cur_meta_key, $this->options['custom_fields_list'])){ 
										
										//$this->wpdb->query($this->wpdb->prepare("DELETE FROM $meta_table WHERE `" . $import_entry . "_id` = $pid AND `meta_key` = %s", $cur_meta_key));
										if ( $this->options['custom_type'] == 'import_users'){
											delete_user_meta($pid, $cur_meta_key);
										}
										else{
											delete_post_meta($pid, $cur_meta_key);
										}
										$show_log and $logger and call_user_func($logger, sprintf(__('- Custom field %s has been deleted for `%s` attempted to `update only these custom fields: %s, leave rest alone` setting ...', 'wp_all_import_plugin'), $cur_meta_key, $articleData['post_title'], implode(',', $this->options['custom_fields_list'])));
									}									
								}
								// Leave these fields alone, update all other Custom Fields
								elseif ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "all_except"){
									if ( empty($this->options['custom_fields_list']) or ! in_array($cur_meta_key, $this->options['custom_fields_list'])){ 
										
										//$this->wpdb->query($this->wpdb->prepare("DELETE FROM $meta_table WHERE `" . $import_entry . "_id` = $pid AND `meta_key` = %s", $cur_meta_key));
										if ( $this->options['custom_type'] == 'import_users'){
											delete_user_meta($pid, $cur_meta_key);
										}
										else{
											delete_post_meta($pid, $cur_meta_key);
										}
										$show_log and $logger and call_user_func($logger, sprintf(__('- Custom field %s has been deleted for `%s` attempted to `leave these fields alone: %s, update all other Custom Fields` setting ...', 'wp_all_import_plugin'), $cur_meta_key, $articleData['post_title'], implode(',', $this->options['custom_fields_list'])));
									}									
								}
								
							}
						}						

						$encoded_meta = array();						
						
						if ( ! empty($serialized_meta)){

							foreach ($serialized_meta as $m_keys) { 

								foreach ($m_keys as $m_key => $values) {									
								
									if (!empty($articleData['ID'])){
																		
										if ($this->options['update_all_data'] != 'yes'){

											$field_to_update = false;

											if ($this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "only" and ! empty($this->options['custom_fields_list']) and is_array($this->options['custom_fields_list']) and in_array($m_key, $this->options['custom_fields_list']) ) $field_to_update = true;
											if ($this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "all_except" and ( empty($this->options['custom_fields_list']) or ! in_array($m_key, $this->options['custom_fields_list']) )) $field_to_update = true;
											
											if ( $this->options['update_custom_fields_logic'] == "full_update" ) $field_to_update = true;									

											// apply addons filters
											$field_to_update = apply_filters('pmxi_custom_field_to_update', $field_to_update, $post_type[$i], $this->options, $m_key);									

											if ( ! $field_to_update ) {
												$logger and call_user_func($logger, sprintf(__('- Custom field `%s` has been skipped attempted to record matching options ...', 'wp_all_import_plugin'), $m_key));
												continue;										
											}

										}

									}
									
									$cf_value = apply_filters('pmxi_custom_field', (is_serialized($values[$i])) ? unserialize($values[$i]) : $values[$i], $pid, $m_key, $existing_meta_keys, $this->id);
									
									//$this->pushmeta($pid, $m_key, $cf_value);																	
									if ( $this->options['custom_type'] == 'import_users'){
										add_user_meta($pid, $m_key, $cf_value);
									}
									else{
										add_post_meta($pid, $m_key, $cf_value);
									}

									$logger and call_user_func($logger, sprintf(__('- Custom field `%s` has been updated with value `%s` for post `%s` ...', 'wp_all_import_plugin'), $m_key, maybe_serialize($cf_value), $articleData['post_title']));
									$logger and call_user_func($logger, __('- <b>ACTION</b>: pmxi_update_post_meta', 'wp_all_import_plugin'));
									do_action( 'pmxi_update_post_meta', $pid, $m_key, (is_serialized($values[$i])) ? unserialize($values[$i]) : $values[$i]); // hook that was triggered after post meta data updated	
								}
							}	

							//$this->executeSQL();
						}							
					}
					// [/custom fields]

					// [addons import]

					// prepare data for import
					$importData = array(
						'pid' => $pid,
						'i' => $i,
						'import' => $this,
						'articleData' => $articleData,
						'xml' => $xml,
						'is_cron' => $is_cron,
						'logger' => $logger,
						'xpath_prefix' => $xpath_prefix,
						'post_type' => $post_type[$i]
					);

					$import_functions = apply_filters('wp_all_import_addon_import', array());

					// deligate operation to addons
					foreach (PMXI_Admin_Addons::get_active_addons() as $class){ 						
						if (class_exists($class)){
							if ( method_exists($addons[$class], 'import') ) $addons[$class]->import($importData);	
						}
						else
						{							
							if ( ! empty($import_functions[$class]) ){ 

								if ( is_array($import_functions[$class]) and is_callable($import_functions[$class]) or ! is_array($import_functions[$class]) and function_exists($import_functions[$class])  ){

									call_user_func($import_functions[$class], $importData, $addons_data[$class]);			
								}							
							}
						}
					}
					
					// [/addons import]

					// Page Template
					if ('page' == $articleData['post_type'] and wp_all_import_is_update_cf('_wp_page_template', $this->options) and ( !empty($this->options['page_template']) or "no" == $this->options['is_multiple_page_template']) ){
						update_post_meta($pid, '_wp_page_template', ("no" == $this->options['is_multiple_page_template']) ? $page_template[$i] : $this->options['page_template']);
					}
					
					// [featured image]
					if ( ! empty($uploads) and false === $uploads['error'] and (empty($articleData['ID']) or $this->options['update_all_data'] == "yes" or ( $this->options['update_all_data'] == "no" and $this->options['is_update_images']))) {
						
						if ( ! empty($images_bundle) ){

							$is_show_add_new_images = apply_filters('wp_all_import_is_show_add_new_images', true, $post_type[$i]); 

							foreach ($images_bundle as $slug => $bundle_data) {
								
								$featured_images = $bundle_data['files'];

								$option_slug = ($slug == 'pmxi_gallery_image') ? '' : $slug;

								if ( ! empty($featured_images[$i]) ){									

									$targetDir = $uploads['path'];
									$targetUrl = $uploads['url'];

									$logger and call_user_func($logger, __('<b>IMAGES:</b>', 'wp_all_import_plugin'));

									if ( ! @is_writable($targetDir) ){

										$logger and call_user_func($logger, sprintf(__('<b>ERROR</b>: Target directory %s is not writable', 'wp_all_import_plugin'), $targetDir));

									}
									else{

										require_once(ABSPATH . 'wp-admin/includes/image.php');						
										
										$success_images = false;	
										$gallery_attachment_ids = array();																			
										$imgs = array();

										$featured_delim = ( "yes" == $this->options[$option_slug . 'download_images'] ) ? $this->options[$option_slug . 'download_featured_delim'] : $this->options[$option_slug . 'featured_delim'];

										$line_imgs = explode("\n", $featured_images[$i]);
										if ( ! empty($line_imgs) )
											foreach ($line_imgs as $line_img)
												$imgs = array_merge($imgs, ( ! empty($featured_delim) ) ? str_getcsv($line_img, $featured_delim) : array($line_img) );								

										// keep existing and add newest images
										if ( ! empty($articleData['ID']) and $this->options['is_update_images'] and $this->options['update_images_logic'] == "add_new" and $this->options['update_all_data'] == "no" and $is_show_add_new_images){
											
											$logger and call_user_func($logger, __('- Keep existing and add newest images ...', 'wp_all_import_plugin'));

											$attachment_imgs = get_attached_media( 'image', $pid ); 

											if ( $post_type[$i] == "product" )
												$gallery_attachment_ids = array_filter(explode(",", get_post_meta($pid, '_product_image_gallery', true)));

											if ( $attachment_imgs ) {	
												foreach ( $attachment_imgs as $attachment_img ) {													
													$post_thumbnail_id = get_post_thumbnail_id( $pid );
													if ( empty($post_thumbnail_id) and $this->options[$option_slug . 'is_featured'] ) {
														set_post_thumbnail($pid, $attachment_img->ID);
													}
													elseif(!in_array($attachment_img->ID, $gallery_attachment_ids) and $post_thumbnail_id != $attachment_img->ID) {
														$gallery_attachment_ids[] = $attachment_img->ID;													
													}																																	
												}		
												$success_images = true;										
											}																

											if ( ! empty($gallery_attachment_ids) ){
												foreach ($gallery_attachment_ids as $aid){
													do_action( $slug, $pid, $aid, wp_get_attachment_url($aid), 'update_images');
												}
											}
										}
																
										if ( ! empty($imgs) ) {

											if ( $this->options[$option_slug . 'set_image_meta_title'] and !empty($image_meta_titles_bundle[$slug])){		
												$img_titles = array();									
												$line_img_titles = explode("\n", $image_meta_titles_bundle[$slug][$i]);
												if ( ! empty($line_img_titles) )
													foreach ($line_img_titles as $line_img_title)
														$img_titles = array_merge($img_titles, ( ! empty($this->options[$option_slug . 'image_meta_title_delim']) ) ? str_getcsv($line_img_title, $this->options[$option_slug . 'image_meta_title_delim']) : array($line_img_title) );
					
											}
											if ( $this->options[$option_slug . 'set_image_meta_caption'] and !empty($image_meta_captions_bundle[$slug])){								
												$img_captions = array();									
												$line_img_captions = explode("\n", $image_meta_captions_bundle[$slug][$i]);
												if ( ! empty($line_img_captions) )
													foreach ($line_img_captions as $line_img_caption)
														$img_captions = array_merge($img_captions, ( ! empty($this->options[$option_slug . 'image_meta_caption_delim']) ) ? str_getcsv($line_img_caption, $this->options[$option_slug . 'image_meta_caption_delim']) : array($line_img_caption) );

											}
											if ( $this->options[$option_slug . 'set_image_meta_alt'] and !empty($image_meta_alts_bundle[$slug])){								
												$img_alts = array();									
												$line_img_alts = explode("\n", $image_meta_alts_bundle[$slug][$i]);
												if ( ! empty($line_img_alts) )
													foreach ($line_img_alts as $line_img_alt)
														$img_alts = array_merge($img_alts, ( ! empty($this->options[$option_slug . 'image_meta_alt_delim']) ) ? str_getcsv($line_img_alt, $this->options[$option_slug . 'image_meta_alt_delim']) : array($line_img_alt) );

											}
											if ( $this->options[$option_slug . 'set_image_meta_description'] and !empty($image_meta_descriptions_bundle[$slug])){								
												$img_descriptions = array();									
												$line_img_descriptions = explode("\n", $image_meta_descriptions_bundle[$slug][$i]);
												if ( ! empty($line_img_descriptions) )
													foreach ($line_img_descriptions as $line_img_description)
														$img_descriptions = array_merge($img_descriptions, ( ! empty($this->options[$option_slug . 'image_meta_description_delim']) ) ? str_getcsv($line_img_description, $this->options[$option_slug . 'image_meta_description_delim']) : array($line_img_description) );

											}				

											$is_keep_existing_images = ( ! empty($articleData['ID']) and $this->options['is_update_images'] and $this->options['update_images_logic'] == "add_new" and $this->options['update_all_data'] == "no" and $is_show_add_new_images);						

											foreach ($imgs as $k => $img_url) { if (empty($img_url)) continue;										

												$attid = false;		

												$attch = null;																										

												$url = str_replace(" ", "%20", trim($img_url));
												$bn  = basename($url);
												
												if ( "yes" == $this->options[$option_slug . 'download_images'] and ! empty($auto_extensions_bundle[$slug][$i]) and preg_match('%^(jpg|jpeg|png|gif)$%i', $auto_extensions_bundle[$slug][$i])){
													$img_ext = $auto_extensions_bundle[$slug][$i];
												}
												else {
													$img_ext = pmxi_getExtensionFromStr($url);									
													$default_extension = pmxi_getExtension($bn);																									

													if ($img_ext == "") $img_ext = pmxi_get_remote_image_ext($url);		
												}

												$logger and call_user_func($logger, sprintf(__('- Importing image `%s` for `%s` ...', 'wp_all_import_plugin'), $img_url, $articleData['post_title']));

												// generate local file name
												$image_name = urldecode(($this->options[$option_slug . 'auto_rename_images'] and !empty($auto_rename_images_bundle[$slug][$i])) ? sanitize_file_name(($img_ext) ? str_replace("." . $default_extension, "", $auto_rename_images_bundle[$slug][$i]) : $auto_rename_images_bundle[$slug][$i]) : sanitize_file_name((($img_ext) ? str_replace("." . $default_extension, "", $bn) : $bn))) . (("" != $img_ext) ? '.' . $img_ext : '');
													
												// if wizard store image data to custom field									
												$create_image   = false;
												$download_image = true;
												$wp_filetype = false;

												if ($bundle_data['type'] == 'images' and base64_decode($url, true) !== false){
													$img = @imagecreatefromstring(base64_decode($url));									    
												    if($img)
												    {	
												    	$logger and call_user_func($logger, __('- Founded base64_encoded image', 'wp_all_import_plugin'));

												    	$image_filename = md5(time()) . '.jpg';
												    	$image_filepath = $targetDir . '/' . $image_filename;
												    	imagejpeg($img, $image_filepath);
												    	if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
															$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'wp_all_import_plugin'), $image_filepath));
															$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
														} else {
															$create_image = true;											
														}
												    } 
												} 
												else {										
													
													$image_filename = wp_unique_filename($targetDir, $image_name);
													$image_filepath = $targetDir . '/' . $image_filename;																						

													// keep existing and add newest images
													if ( $is_keep_existing_images ){ 	

														$attch = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->posts . " WHERE (post_title = %s OR post_title = %s OR post_name = %s) AND post_type = %s AND post_parent = %d;", $image_name, preg_replace('/\\.[^.\\s]{3,4}$/', '', $image_name), sanitize_title($image_name), "attachment", $pid ) );												
														
														if ( $attch != null ){
															$post_thumbnail_id = get_post_thumbnail_id( $pid );
															if ( $post_thumbnail_id == $attch->ID or in_array($attch->ID, $gallery_attachment_ids) ) continue;															
														} 
														elseif (file_exists($targetDir . '/' . $image_name)){
															if ($bundle_data['type'] == 'images' and ($img_meta = wp_read_image_metadata($targetDir . '/' . $image_name))) {
																if (trim($img_meta['title']) && ! is_numeric(sanitize_title($img_meta['title']))){
																	$img_title = $img_meta['title'];
																	$attch = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->posts . " WHERE post_title = %s AND post_type = %s AND post_parent = %d;", $img_title, "attachment", $pid ) );												
																	if ( $attch != null ){
																		$post_thumbnail_id = get_post_thumbnail_id( $pid );
																		if ( $post_thumbnail_id == $attch->ID or in_array($attch->ID, $gallery_attachment_ids) ) continue;															
																	} 
																}
															}
														}

													}

													// search existing attachment
													if ($this->options[$option_slug . 'search_existing_images']){
														
														$image_filename = $image_name;												

														$attch = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->posts . " WHERE (post_title = %s OR post_title = %s OR post_name = %s) AND post_type = %s;", $image_name, preg_replace('/\\.[^.\\s]{3,4}$/', '', $image_name), sanitize_title($image_name), "attachment" ) );

														if ( $attch != null ){			
															$download_image = false;
															$attid = $attch->ID;
														}			
														elseif (@file_exists($targetDir . '/' . $image_name)){
															if ($bundle_data['type'] == 'images' and ($img_meta = wp_read_image_metadata($targetDir . '/' . $image_name))) {
																if (trim($img_meta['title']) && ! is_numeric(sanitize_title($img_meta['title']))){
																	$img_title = $img_meta['title'];
																	$attch = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->posts . " WHERE post_title = %s AND post_type = %s AND post_parent = %d;", $img_title, "attachment", $pid ) );												
																	if ( $attch != null ){			
																		$download_image = false;
																		$attid = $attch->ID;
																	}			
																}
															}
														}			
													}

													if ($download_image){		

														// do not download images
														if ( "yes" != $this->options[$option_slug . 'download_images'] ){													

															$image_filename = $image_name;
															$image_filepath = $targetDir . '/' . $image_filename;		
																
															$wpai_uploads = $uploads['basedir'] . DIRECTORY_SEPARATOR . PMXI_Plugin::FILES_DIRECTORY . DIRECTORY_SEPARATOR;
															$wpai_image_path = $wpai_uploads . str_replace('%20', ' ', $url);

															$logger and call_user_func($logger, sprintf(__('- Searching for existing image `%s` in `%s` folder', 'wp_all_import_plugin'), $wpai_image_path, $wpai_uploads));

															if ( @file_exists($wpai_image_path) and @copy( $wpai_image_path, $image_filepath )){
																$download_image = false;		
																// valdate import attachments
																if ($bundle_data['type'] == 'files'){
																	if( ! $wp_filetype = wp_check_filetype(basename($image_filepath), null )) {
																		$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Can\'t detect attachment file type %s', 'wp_all_import_plugin'), trim($image_filepath)));
																		$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
																		@unlink($image_filepath);
																	}
																	else {
																		$create_image = true;											
																		$logger and call_user_func($logger, sprintf(__('- File `%s` has been successfully founded', 'wp_all_import_plugin'), $wpai_image_path));
																	}
																}
																// validate import images
																elseif($bundle_data['type'] == 'images'){
																	if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
																		$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'wp_all_import_plugin'), $image_filepath));
																		$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
																		@unlink($image_filepath);
																	} else {
																		$create_image = true;											
																		$logger and call_user_func($logger, sprintf(__('- Image `%s` has been successfully founded', 'wp_all_import_plugin'), $wpai_image_path));
																	}
																}
															}													
														}	
														else {												
															
															$logger and call_user_func($logger, sprintf(__('- Downloading image from `%s`', 'wp_all_import_plugin'), $url));

															$request = get_file_curl($url, $image_filepath);

															if ( (is_wp_error($request) or $request === false) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
																@unlink($image_filepath); // delete file since failed upload may result in empty file created
															} else{

																if($bundle_data['type'] == 'images'){
																	if( ($image_info = @getimagesize($image_filepath)) and in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
																		$create_image = true;		
																		$logger and call_user_func($logger, sprintf(__('- Image `%s` has been successfully downloaded', 'wp_all_import_plugin'), $url));									
																	}
																}
																elseif($bundle_data['type'] == 'files'){
																	if( $wp_filetype = wp_check_filetype(basename($image_filepath), null )) {
																		$create_image = true;		
																		$logger and call_user_func($logger, sprintf(__('- File `%s` has been successfully downloaded', 'wp_all_import_plugin'), $url));
																	}
																}

															}												
															
															if ( ! $create_image ){

																$url = str_replace(" ", "%20", trim(pmxi_convert_encoding($img_url)));
																
																$request = get_file_curl($url, $image_filepath);

																if ( (is_wp_error($request) or $request === false) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
																	$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s cannot be saved locally as %s', 'wp_all_import_plugin'), $url, $image_filepath));
																	$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
																	@unlink($image_filepath); // delete file since failed upload may result in empty file created										
																} 
																else{
																	if($bundle_data['type'] == 'images'){
																		if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
																			$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'wp_all_import_plugin'), $url));
																			$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
																			@unlink($image_filepath);
																		} else {
																			$create_image = true;	
																			$logger and call_user_func($logger, sprintf(__('- Image `%s` has been successfully downloaded', 'wp_all_import_plugin'), $url));												
																		}
																	}
																	elseif($bundle_data['type'] == 'files'){
																		if( ! $wp_filetype = wp_check_filetype(basename($image_filepath), null )) {
																			$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Can\'t detect attachment file type %s', 'wp_all_import_plugin'), trim($url)));
																			$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
																			@unlink($image_filepath);
																		}
																		else {
																			$create_image = true;											
																			$logger and call_user_func($logger, sprintf(__('- File `%s` has been successfully founded', 'wp_all_import_plugin'), $url));
																		}
																	}
																}
															}
														}												
													}
												}

												if ($create_image){
													
													$logger and call_user_func($logger, sprintf(__('- Creating an attachment for image `%s`', 'wp_all_import_plugin'), $targetUrl . '/' . $image_filename));	

													$attachment = array(
														'post_mime_type' => ($bundle_data['type'] == 'images') ? image_type_to_mime_type($image_info[2]) : $wp_filetype['type'],
														'guid' => $targetUrl . '/' . $image_filename,
														'post_title' => $image_name,
														'post_content' => '',
														'post_author' => $post_author[$i],
													);
													if ($bundle_data['type'] == 'images' and ($image_meta = wp_read_image_metadata($image_filepath))) {
														if (trim($image_meta['title']) && ! is_numeric(sanitize_title($image_meta['title'])))
															$attachment['post_title'] = $image_meta['title'];
														if (trim($image_meta['caption']))
															$attachment['post_content'] = $image_meta['caption'];
													}											

													$attid = wp_insert_attachment($attachment, $image_filepath, $pid);										

													if (is_wp_error($attid)) {
														$logger and call_user_func($logger, __('- <b>WARNING</b>', 'wp_all_import_plugin') . ': ' . $attid->get_error_message());
														$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
													} else {
														// you must first include the image.php file
														// for the function wp_generate_attachment_metadata() to work
														require_once(ABSPATH . 'wp-admin/includes/image.php');
														wp_update_attachment_metadata($attid, wp_generate_attachment_metadata($attid, $image_filepath));																							
																									
														$update_attachment_meta = array();
														if ( $this->options[$option_slug . 'set_image_meta_title'] and ! empty($img_titles[$k]) ) $update_attachment_meta['post_title'] = $img_titles[$k];
														if ( $this->options[$option_slug . 'set_image_meta_caption'] and ! empty($img_captions[$k]) ) $update_attachment_meta['post_excerpt'] =  $img_captions[$k];								
														if ( $this->options[$option_slug . 'set_image_meta_description'] and ! empty($img_descriptions[$k]) ) $update_attachment_meta['post_content'] =  $img_descriptions[$k];
														if ( $this->options[$option_slug . 'set_image_meta_alt'] and ! empty($img_alts[$k]) ) update_post_meta($attid, '_wp_attachment_image_alt', $img_alts[$k]);
														
														if ( ! empty($update_attachment_meta)) $this->wpdb->update( $this->wpdb->posts, $update_attachment_meta, array('ID' => $attid) );																
													}

												}

												if ($attid){

													if ($attch != null and empty($attch->post_parent)){
														wp_update_post(
														    array(
														        'ID' => $attch->ID, 
														        'post_parent' => $pid
														    )
														);
													}

													$logger and call_user_func($logger, __('- <b>ACTION</b>: ' . $slug, 'wp_all_import_plugin'));																							
													do_action( $slug, $pid, $attid, $image_filepath, $is_keep_existing_images ? 'add_images' : 'update_images'); 

													$success_images = true;												

													$post_thumbnail_id = get_post_thumbnail_id( $pid );
													if ($bundle_data['type'] == 'images' and empty($post_thumbnail_id) and $this->options[$option_slug . 'is_featured'] ) {
														set_post_thumbnail($pid, $attid);
													}
													elseif(!in_array($attid, $gallery_attachment_ids) and $post_thumbnail_id != $attid){
														$gallery_attachment_ids[] = $attid;	
													}

													$logger and call_user_func($logger, sprintf(__('- Attachment has been successfully created for image `%s`', 'wp_all_import_plugin'), $targetUrl . '/' . $image_filename));											
													
												}																		
											}									
										}															
										// Set product gallery images
										if ( $post_type[$i] == "product" )
											update_post_meta($pid, '_product_image_gallery', (!empty($gallery_attachment_ids)) ? implode(',', $gallery_attachment_ids) : '');
										// Create entry as Draft if no images are downloaded successfully
										if ( ! $success_images and "yes" == $this->options[$option_slug . 'create_draft'] ) {								
											$this->wpdb->update( $this->wpdb->posts, array('post_status' => 'draft'), array('ID' => $pid) );
											$logger and call_user_func($logger, sprintf(__('- Post `%s` saved as Draft, because no images are downloaded successfully', 'wp_all_import_plugin'), $articleData['post_title']));
										}
									}
								}
								else{							
									// Create entry as Draft if no images are downloaded successfully
									if ( "yes" == $this->options[$option_slug . 'create_draft'] ){ 
										$this->wpdb->update( $this->wpdb->posts, array('post_status' => 'draft'), array('ID' => $pid) );
										$logger and call_user_func($logger, sprintf(__('Post `%s` saved as Draft, because no images are downloaded successfully', 'wp_all_import_plugin'), $articleData['post_title']));
									}
								}
							}
						}

					}
					else
					{
						if ( ! empty($images_bundle) ){

							foreach ($images_bundle as $slug => $bundle_data) {

								if ( ! empty($bundle_data['images'][$i]) ){

									$imgs = array();

									$featured_delim = ( "yes" == $this->options[$option_slug . 'download_images'] ) ? $this->options[$option_slug . 'download_featured_delim'] : $this->options[$option_slug . 'featured_delim'];

									$line_imgs = explode("\n", $bundle_data['images'][$i]);
									if ( ! empty($line_imgs) )
										foreach ($line_imgs as $line_img)
											$imgs = array_merge($imgs, ( ! empty($featured_delim) ) ? str_getcsv($line_img, $featured_delim) : array($line_img) );								

									foreach ($imgs as $img) {
										do_action( $slug, $pid, false, $img, false);
									}									

								}

							}
						}
					}
					// [/featured image]

					// [attachments]
					if ( ! empty($uploads) and false === $uploads['error'] and !empty($attachments[$i]) and (empty($articleData['ID']) or $this->options['update_all_data'] == "yes" or ($this->options['update_all_data'] == "no" and $this->options['is_update_attachments']))) {

						$targetDir = $uploads['path'];
						$targetUrl = $uploads['url'];

						$logger and call_user_func($logger, __('<b>ATTACHMENTS:</b>', 'wp_all_import_plugin'));

						if ( ! @is_writable($targetDir) ){
							$logger and call_user_func($logger, sprintf(__('- <b>ERROR</b>: Target directory %s is not writable', 'wp_all_import_plugin'), trim($targetDir)));
						}
						else{
							// you must first include the image.php file
							// for the function wp_generate_attachment_metadata() to work
							require_once(ABSPATH . 'wp-admin/includes/image.php');

							if ( ! is_array($attachments[$i]) ) $attachments[$i] = array($attachments[$i]);

							$logger and call_user_func($logger, sprintf(__('- Importing attachments for `%s` ...', 'wp_all_import_plugin'), $articleData['post_title']));

							foreach ($attachments[$i] as $attachment) { if ("" == $attachment) continue;
								
								$atchs = str_getcsv($attachment, $this->options['atch_delim']);

								if ( ! empty($atchs) ) {

									foreach ($atchs as $atch_url) {	if (empty($atch_url)) continue;	

										$download_file = true;	

										$atch_url = str_replace(" ", "%20", trim($atch_url));							

										$attachment_filename = urldecode(basename(parse_url(trim($atch_url), PHP_URL_PATH)));	
										$attachment_filepath = $targetDir . '/' . sanitize_file_name($attachment_filename);											

										if ($this->options['is_search_existing_attach']){
											// search existing attachment																																									
											$attch = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->posts . " WHERE (post_title = %s OR post_title = %s OR post_name = %s OR post_name = %s) AND post_type = %s;", $attachment_filename, preg_replace('/\\.[^.\\s]{3,4}$/', '', $attachment_filename), sanitize_title($attachment_filename), sanitize_title(preg_replace('/\\.[^.\\s]{3,4}$/', '', $attachment_filename)), "attachment" ) );
											
											if ( $attch != null ){			
												$download_file = false;
												$attach_id = $attch->ID;
											}
										}			
										
										if ($download_file){			

											$attachment_filename = wp_unique_filename($targetDir, $attachment_filename);										
											$attachment_filepath = $targetDir . '/' . sanitize_file_name($attachment_filename);									

											$logger and call_user_func($logger, sprintf(__('- Filename for attachment was generated as %s', 'wp_all_import_plugin'), $attachment_filename));
											
											$request = get_file_curl(trim($atch_url), $attachment_filepath);
																	
											if ( (is_wp_error($request) or $request === false)  and ! @file_put_contents($attachment_filepath, @file_get_contents(trim($atch_url)))) {												
												$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Attachment file %s cannot be saved locally as %s', 'wp_all_import_plugin'), trim($atch_url), $attachment_filepath));
												is_wp_error($request) and $logger and call_user_func($logger, sprintf(__('- <b>WP Error</b>: %s', 'wp_all_import_plugin'), $request->get_error_message()));
												$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
												unlink($attachment_filepath); // delete file since failed upload may result in empty file created												
											} elseif( ! $wp_filetype = wp_check_filetype(basename($attachment_filename), null )) {
												$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: Can\'t detect attachment file type %s', 'wp_all_import_plugin'), trim($atch_url)));
												$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
											} else {
												$logger and call_user_func($logger, sprintf(__('- File %s has been successfully downloaded', 'wp_all_import_plugin'), $atch_url));
												$attachment_data = array(
												    'guid' => $targetUrl . '/' . basename($attachment_filepath), 
												    'post_mime_type' => $wp_filetype['type'],
												    'post_title' => preg_replace('/\.[^.]+$/', '', basename($attachment_filepath)),
												    'post_content' => '',
												    'post_status' => 'inherit',
												    'post_author' => $post_author[$i],
												);
												$attach_id = wp_insert_attachment( $attachment_data, $attachment_filepath, $pid );

												if (is_wp_error($attach_id)) {
													$logger and call_user_func($logger, __('- <b>WARNING</b>', 'wp_all_import_plugin') . ': ' . $pid->get_error_message());
													$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
												} else {											
													wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $attachment_filepath));											
													$logger and call_user_func($logger, sprintf(__('- Attachment has been successfully created for post `%s`', 'wp_all_import_plugin'), $articleData['post_title']));
													$logger and call_user_func($logger, __('- <b>ACTION</b>: pmxi_attachment_uploaded', 'wp_all_import_plugin'));
													do_action( 'pmxi_attachment_uploaded', $pid, $attach_id, $attachment_filepath);
												}										
											}
										}
										else{
											$logger and call_user_func($logger, __('- <b>ACTION</b>: pmxi_attachment_uploaded', 'wp_all_import_plugin'));
											do_action( 'pmxi_attachment_uploaded', $pid, $attach_id, $attachment_filepath);
										}
									}
								}
							}
						}
					}
					// [/attachments]
					
					// [custom taxonomies]
					if ( ! empty($taxonomies) ){

						$logger and call_user_func($logger, __('<b>TAXONOMIES:</b>', 'wp_all_import_plugin'));							

						foreach ($taxonomies as $tx_name => $txes) {								

							// Skip updating product attributes
							if ( PMXI_Admin_Addons::get_addon('PMWI_Plugin') and strpos($tx_name, "pa_") === 0 ) continue;

							if ( empty($articleData['ID']) or $this->options['update_all_data'] == "yes" or ( $this->options['update_all_data'] == "no" and $this->options['is_update_categories'] )) {
								
								$logger and call_user_func($logger, sprintf(__('- Importing taxonomy `%s` ...', 'wp_all_import_plugin'), $tx_name));	

								if ( ! empty($this->options['tax_logic'][$tx_name]) and $this->options['tax_logic'][$tx_name] == 'hierarchical' and ! empty($this->options['tax_hierarchical_logic'][$tx_name]) and $this->options['tax_hierarchical_logic'][$tx_name] == 'entire'){
									$logger and call_user_func($logger, sprintf(__('- Auto-nest enabled with separator `%s` ...', 'wp_all_import_plugin'), ( ! empty($this->options['tax_hierarchical_delim'][$tx_name]) ? $this->options['tax_hierarchical_delim'][$tx_name] : ',')));
								}

								if (!empty($articleData['ID'])){
									if ($this->options['update_all_data'] == "no" and $this->options['update_categories_logic'] == "all_except" and !empty($this->options['taxonomies_list']) 
										and is_array($this->options['taxonomies_list']) and in_array($tx_name, $this->options['taxonomies_list'])){ 
											$logger and call_user_func($logger, sprintf(__('- %s %s `%s` has been skipped attempted to `Leave these taxonomies alone, update all others`...', 'wp_all_import_plugin'), $custom_type_details->labels->singular_name, $tx_name, $single_tax['name']));
											continue;
										}		
									if ($this->options['update_all_data'] == "no" and $this->options['update_categories_logic'] == "only" and ((!empty($this->options['taxonomies_list']) 
										and is_array($this->options['taxonomies_list']) and ! in_array($tx_name, $this->options['taxonomies_list'])) or empty($this->options['taxonomies_list']))){ 
											$logger and call_user_func($logger, sprintf(__('- %s %s `%s` has been skipped attempted to `Update only these taxonomies, leave the rest alone`...', 'wp_all_import_plugin'), $custom_type_details->labels->singular_name, $tx_name, $single_tax['name']));
											continue;
										}
								}								

								$assign_taxes = array();

								if ($this->options['update_categories_logic'] == "add_new" and !empty($existing_taxonomies[$tx_name][$i])){
									$assign_taxes = $existing_taxonomies[$tx_name][$i];	
									unset($existing_taxonomies[$tx_name][$i]);
								}
								elseif(!empty($existing_taxonomies[$tx_name][$i])){
									unset($existing_taxonomies[$tx_name][$i]);
								}

								// create term if not exists								
								if ( ! empty($txes[$i]) ):
									foreach ($txes[$i] as $key => $single_tax) {
										$is_created_term = false;
										if (is_array($single_tax) and ! empty($single_tax['name'])){																														

											$parent_id = ( ! empty($single_tax['parent'])) ? pmxi_recursion_taxes($single_tax['parent'], $tx_name, $txes[$i], $key) : '';
											
											$term = (empty($this->options['tax_is_full_search_' . $this->options['tax_logic'][$tx_name]][$tx_name])) ? term_exists($single_tax['name'], $tx_name, (int)$parent_id) : term_exists($single_tax['name'], $tx_name);																																			

											if ( empty($term) and !is_wp_error($term) ){
												$term = (empty($this->options['tax_is_full_search_' . $this->options['tax_logic'][$tx_name]][$tx_name])) ? term_exists(htmlspecialchars($single_tax['name']), $tx_name, (int)$parent_id) : term_exists(htmlspecialchars($single_tax['name']), $tx_name);		
												if ( empty($term) and !is_wp_error($term) ){
													$term_attr = array('parent'=> ( ! empty($parent_id) ) ? $parent_id : 0);
													$term = wp_insert_term(
														$single_tax['name'], // the term 
													  	$tx_name, // the taxonomy
													  	$term_attr
													);
													if ( ! is_wp_error($term) ){
														$is_created_term = true;
														if ( empty($parent_id) ){
															$logger and call_user_func($logger, sprintf(__('- Creating parent %s %s `%s` ...', 'wp_all_import_plugin'), $custom_type_details->labels->singular_name, $tx_name, $single_tax['name']));	
														}
														else{
															$logger and call_user_func($logger, sprintf(__('- Creating child %s %s for %s named `%s` ...', 'wp_all_import_plugin'), $custom_type_details->labels->singular_name, $tx_name, (is_array($single_tax['parent']) ? $single_tax['parent']['name'] : $single_tax['parent']), $single_tax['name']));		
														}
													}
												}
											}											
											
											if ( is_wp_error($term) ){									
												$logger and call_user_func($logger, sprintf(__('- <b>WARNING</b>: `%s`', 'wp_all_import_plugin'), $term->get_error_message()));
												$logger and !$is_cron and PMXI_Plugin::$session->warnings++;
											}
											elseif ( ! empty($term)) {													

												$cat_id = $term['term_id'];																								

												if ($cat_id and $single_tax['assign']) 
												{
													$term = get_term_by('id', $cat_id, $tx_name);
													if ( $term->parent != '0' and ! empty($this->options['tax_is_full_search_' . $this->options['tax_logic'][$tx_name]][$tx_name]) and empty($this->options['tax_assign_to_one_term_' . $this->options['tax_logic'][$tx_name]][$tx_name])){
														$parent_ids = wp_all_import_get_parent_terms($cat_id, $tx_name);
														if ( ! empty($parent_ids)){
															foreach ($parent_ids as $p) {
																if (!in_array($p, $assign_taxes)) $assign_taxes[] = $p;
															}
														}
													}													
													if (!in_array($term->term_taxonomy_id, $assign_taxes)) $assign_taxes[] = $term->term_taxonomy_id;		
													if (!$is_created_term){														
														if ( empty($parent_id) ){															
															$logger and call_user_func($logger, sprintf(__('- Attempted to create parent %s %s `%s`, duplicate detected. Importing %s to existing `%s` %s, ID %d, slug `%s` ...', 'wp_all_import_plugin'), $custom_type_details->labels->singular_name, $tx_name, $single_tax['name'], $custom_type_details->labels->singular_name, $term->name, $tx_name, $term->term_id, $term->slug));	
														}
														else{															
															$logger and call_user_func($logger, sprintf(__('- Attempted to create child %s %s `%s`, duplicate detected. Importing %s to existing `%s` %s, ID %d, slug `%s` ...', 'wp_all_import_plugin'), $custom_type_details->labels->singular_name, $tx_name, $single_tax['name'], $custom_type_details->labels->singular_name, $term->name, $tx_name, $term->term_id, $term->slug));	
														}	
													}
												}									
											}									
										}
									}				
								endif;										
								
								// associate taxes with post								
								$this->associate_terms($pid, ( empty($assign_taxes) ? false : $assign_taxes ), $tx_name, $logger, $is_cron);	
								
							}
							else
							{
								$logger and call_user_func($logger, sprintf(__('- %s %s `%s` has been skipped attempted to `Do not update Taxonomies (incl. Categories and Tags)`...', 'wp_all_import_plugin'), $custom_type_details->labels->singular_name, $tx_name, $single_tax['name']));
							}
						}
						if ( $this->options['update_all_data'] == "no" and ( ($this->options['is_update_categories'] and $this->options['update_categories_logic'] != 'full_update') or ( ! $this->options['is_update_categories'] and ( is_object_in_taxonomy( $post_type[$i], 'category' ) or is_object_in_taxonomy( $post_type[$i], 'post_tag' ) ) ) ) ) {
							
							if ( ! empty($existing_taxonomies) ){
								foreach ($existing_taxonomies as $tx_name => $txes) {
									// Skip updating product attributes
									if ( PMXI_Admin_Addons::get_addon('PMWI_Plugin') and strpos($tx_name, "pa_") === 0 ) continue;

									if (!empty($txes[$i]))									
										$this->associate_terms($pid, $txes[$i], $tx_name, $logger, $is_cron);									
								}
							}
						}
					}					
					// [/custom taxonomies]										

					if (empty($articleData['ID'])) {																												
						$logger and call_user_func($logger, sprintf(__('<b>CREATED</b> `%s` `%s` (ID: %s)', 'wp_all_import_plugin'), $articleData['post_title'], $custom_type_details->labels->singular_name, $pid));
					} else {						
						$logger and call_user_func($logger, sprintf(__('<b>UPDATED</b> `%s` `%s` (ID: %s)', 'wp_all_import_plugin'), $articleData['post_title'], $custom_type_details->labels->singular_name, $pid));
					}

					// fire important hooks after custom fields are added
					if ( ! $this->options['is_fast_mode'] and $this->options['custom_type'] != 'import_users'){
						$post_object = get_post( $pid );
						$is_update = ! empty($articleData['ID']);
						do_action( "save_post_" . $articleData['post_type'], $pid, $post_object, $is_update );
						do_action( 'save_post', $pid, $post_object, $is_update );
						do_action( 'wp_insert_post', $pid, $post_object, $is_update );
					}

					// [addons import]

					// prepare data for import
					$importData = array(
						'pid' => $pid,						
						'import' => $this,						
						'logger' => $logger											
					);

					$saved_functions = apply_filters('wp_all_import_addon_saved_post', array());

					// deligate operation to addons
					foreach (PMXI_Admin_Addons::get_active_addons() as $class){ 
						if (class_exists($class)){
							if ( method_exists($addons[$class], 'saved_post') ) $addons[$class]->saved_post($importData);	
						}
						else
						{							
							if ( ! empty($saved_functions[$class]) ){ 

								if ( is_array($saved_functions[$class]) and is_callable($saved_functions[$class]) or ! is_array($saved_functions[$class]) and function_exists($saved_functions[$class])  ){

									call_user_func($saved_functions[$class], $importData);		
								}								
							}														
						}
					}
					
					// [/addons import]										
					$logger and call_user_func($logger, __('<b>ACTION</b>: pmxi_saved_post', 'wp_all_import_plugin'));
					do_action( 'pmxi_saved_post', $pid, $rootNodes[$i]); // hook that was triggered immediately after post saved
					
					if (empty($articleData['ID'])) $created++; else $updated++;						

					if ( ! $is_cron and "default" == $this->options['import_processing'] ){
						$processed_records = $created + $updated + $skipped;
						$logger and call_user_func($logger, sprintf(__('<span class="processing_info"><span class="created_count">%s</span><span class="updated_count">%s</span><span class="percents_count">%s</span></span>', 'wp_all_import_plugin'), $created, $updated, ceil(($processed_records/$this->count) * 100)));
					}
																					
				}				
				$logger and call_user_func($logger, __('<b>ACTION</b>: pmxi_after_post_import', 'wp_all_import_plugin'));
				do_action('pmxi_after_post_import', $this->id);

				$logger and !$is_cron and PMXI_Plugin::$session->chunk_number++; 
			}			

			wp_cache_flush();						

			$this->set(array(		
				'imported' => $created + $updated,	
				'created'  => $created,
				'updated'  => $updated,
				'skipped'  => $skipped,
				'last_activity' => date('Y-m-d H:i:s')				
			))->update();
			
			if ( ! $is_cron ){

				PMXI_Plugin::$session->save_data();	

				$records_count = $this->created + $this->updated + $this->skipped;

				$is_import_complete = ($records_count == $this->count);						

				// Delete posts that are no longer present in your file
				if ( $is_import_complete and ! empty($this->options['is_delete_missing']) and $this->options['duplicate_matching'] == 'auto') { 

					$logger and call_user_func($logger, __('Removing previously imported posts which are no longer actual...', 'wp_all_import_plugin'));
					$postList = new PMXI_Post_List();									

					$missing_ids = array();
					$missingPosts = $postList->getBy(array('import_id' => $this->id, 'iteration !=' => $this->iteration));

					if ( ! $missingPosts->isEmpty() ): 
						
						foreach ($missingPosts as $missingPost) {
						
							$missing_ids[] = $missingPost['post_id'];
															
						}

					endif;							

					// Delete posts from database
					if ( ! empty($missing_ids) && is_array($missing_ids) ){																	
						
						$logger and call_user_func($logger, __('<b>ACTION</b>: pmxi_delete_post', 'wp_all_import_plugin'));													

						$logger and call_user_func($logger, __('Deleting posts from database', 'wp_all_import_plugin'));

						$missing_ids_arr = array_chunk($missing_ids, 100);
						
						foreach ($missing_ids_arr as $key => $ids) {

							if ( ! empty($ids) ) { 

								foreach ( $ids as $k => $id ) {
									
									$to_delete = true;
									
									// Instead of deletion, set Custom Field
									if ($this->options['is_update_missing_cf']){
										update_post_meta( $id, $this->options['update_missing_cf_name'], $this->options['update_missing_cf_value'] );
										$to_delete = false;
										$logger and call_user_func($logger, sprintf(__('Instead of deletion post with ID `%s`, set Custom Field `%s` to value `%s`', 'wp_all_import_plugin'), $id, $this->options['update_missing_cf_name'], $this->options['update_missing_cf_value']));
									}

									// Instead of deletion, change post status to Draft
									if ($this->options['set_missing_to_draft']){ 
										$this->wpdb->update( $this->wpdb->posts, array('post_status' => 'draft'), array('ID' => $id) );								
										$to_delete = false;
										$logger and call_user_func($logger, sprintf(__('Instead of deletion, change post with ID `%s` status to Draft', 'wp_all_import_plugin'), $id));
									}
									if ($to_delete){
										// Remove attachments										
										empty($this->options['is_keep_attachments']) and wp_delete_attachments($id, true, 'files');						
										// Remove images										
										empty($this->options['is_keep_imgs']) and wp_delete_attachments($id, true, 'images');																		

										// Clear post's relationships
										if ( $post_type[$i] != "import_users" ) wp_delete_object_term_relationships($id, get_object_taxonomies('' != $this->options['custom_type'] ? $this->options['custom_type'] : 'post'));

									}	
									else{ 
										unset($ids[$k]);							
									}
								}

								if ( ! empty($ids) ){

									do_action('pmxi_delete_post', $ids);

									if ( $this->options['custom_type'] == "import_users" ){
										$sql = "delete a,b
										FROM ".$this->wpdb->users." a
										LEFT JOIN ".$this->wpdb->usermeta." b ON ( a.ID = b.user_id )										
										WHERE a.ID IN (" . implode(',', $ids) . ");";
									}
									else {
										$sql = "delete a,b,c
										FROM ".$this->wpdb->posts." a
										LEFT JOIN ".$this->wpdb->term_relationships." b ON ( a.ID = b.object_id )
										LEFT JOIN ".$this->wpdb->postmeta." c ON ( a.ID = c.post_id )				
										WHERE a.ID IN (" . implode(',', $ids) . ");";
									}						
									
									$this->wpdb->query( $sql );
										
									// Delete record form pmxi_posts
									$sql = "DELETE FROM " . PMXI_Plugin::getInstance()->getTablePrefix() . "posts WHERE post_id IN (".implode(',', $ids).") AND import_id = %d";
									$this->wpdb->query( 
										$this->wpdb->prepare($sql, $this->id)
									);	

									$this->set(array('deleted' => $this->deleted + count($ids)))->update();	
								}
							}													
						}							
					}								

				}

				// Set out of stock status for missing records [Woocommerce add-on option]
				if ( $is_import_complete and empty($this->options['is_delete_missing']) and $post_type[$i] == "product" and class_exists('PMWI_Plugin') and !empty($this->options['missing_records_stock_status'])) {

					$logger and call_user_func($logger, __('Update stock status previously imported posts which are no longer actual...', 'wp_all_import_plugin'));
					$postList = new PMXI_Post_List();				
					$missingPosts = $postList->getBy(array('import_id' => $this->id, 'iteration !=' => $this->iteration));
					if ( ! $missingPosts->isEmpty() ){
						foreach ($missingPosts as $missingPost) {
							update_post_meta( $missingPost['post_id'], '_stock_status', 'outofstock' );
							update_post_meta( $missingPost['post_id'], '_stock', 0 );
							$missingPostRecord = new PMXI_Post_Record();
							$missingPostRecord->getBy('id', $missingPost['id']);
							if ( ! $missingPostRecord->isEmpty())
								$missingPostRecord->set(array('iteration' => $this->iteration))->update();
							unset($missingPostRecord);
						}
					}
				}	
			}		
			
		} catch (XmlImportException $e) {
			$logger and call_user_func($logger, __('<b>ERROR</b>', 'wp_all_import_plugin') . ': ' . $e->getMessage());
			$logger and !$is_cron and PMXI_Plugin::$session->errors++;	
		}				
		
		$logger and $is_import_complete and call_user_func($logger, __('Cleaning temporary data...', 'wp_all_import_plugin'));
		foreach ($tmp_files as $file) { // remove all temporary files created
			@unlink($file);
		}
		
		if (($is_cron or $is_import_complete) and $this->options['is_delete_source']) {
			$logger and call_user_func($logger, __('Deleting source XML file...', 'wp_all_import_plugin'));			

			// Delete chunks
			foreach (PMXI_Helper::safe_glob($uploads['basedir'] . DIRECTORY_SEPARATOR . PMXI_Plugin::TEMP_DIRECTORY . DIRECTORY_SEPARATOR . 'pmxi_chunk_*', PMXI_Helper::GLOB_RECURSE | PMXI_Helper::GLOB_PATH) as $filePath) {
				$logger and call_user_func($logger, __('Deleting chunks files...', 'wp_all_import_plugin'));
				@file_exists($filePath) and wp_all_import_remove_source($filePath, false);		
			}

			if ($this->type != "ftp"){
				if ( ! @unlink($this->path)) {
					$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to remove %s', 'wp_all_import_plugin'), $this->path));
				}
			}
			else{
				$file_path_array = PMXI_Helper::safe_glob($this->path, PMXI_Helper::GLOB_NODIR | PMXI_Helper::GLOB_PATH);
				if (!empty($file_path_array)){
					foreach ($file_path_array as $path) {
						if ( ! @unlink($path)) {
							$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to remove %s', 'wp_all_import_plugin'), $path));
						}
					}
				}
			}
		}

		if ( ! $is_cron and $is_import_complete ){

			$this->set(array(
				'processing' => 0, // unlock cron requests	
				'triggered' => 0,
				'queue_chunk_number' => 0,				
				'registered_on' => date('Y-m-d H:i:s'),
				'iteration' => ++$this->iteration
			))->update();

			$logger and call_user_func($logger, 'Done');			
		}
		
		remove_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // return any filtering rules back if they has been disabled for import procedure
		
		return $this;
	}	

	protected function pushmeta($pid, $meta_key, $meta_value){

		if (empty($meta_key)) return;		

		$this->post_meta_to_insert[] = array(
			'meta_key' => $meta_key,
			'meta_value' => $meta_value,
			'pid' => $pid
		);		

	}

	protected function executeSQL(){
		
		$import_entry = ( $this->options['custom_type'] == 'import_users') ? 'user' : 'post';

		// prepare bulk SQL query
		$meta_table = _get_meta_table( $import_entry );
		
		if ( $this->post_meta_to_insert ){			
			$values = array();
			$already_added = array();
			
			foreach (array_reverse($this->post_meta_to_insert) as $key => $value) {
				if ( ! empty($value['meta_key']) and ! in_array($value['pid'] . '-' . $value['meta_key'], $already_added) ){
					$already_added[] = $value['pid'] . '-' . $value['meta_key'];						
					$values[] = '(' . $value['pid'] . ',"' . $value['meta_key'] . '",\'' . maybe_serialize($value['meta_value']) .'\')';						
				}
			}
			
			$this->wpdb->query("INSERT INTO $meta_table (`" . $import_entry . "_id`, `meta_key`, `meta_value`) VALUES " . implode(',', $values));
			$this->post_meta_to_insert = array();
		}	
	}
	
	public function _filter_has_cap_unfiltered_html($caps)
	{
		$caps['unfiltered_html'] = true;
		return $caps;
	}		
	
	protected function associate_terms($pid, $assign_taxes, $tx_name, $logger, $is_cron = false){
		
		$terms = wp_get_object_terms( $pid, $tx_name );
		$term_ids = array();     

		$assign_taxes = (is_array($assign_taxes)) ? array_filter($assign_taxes) : false;   

		if ( ! empty($terms) ){
			if ( ! is_wp_error( $terms ) ) {				
				foreach ($terms as $term_info) {
					$term_ids[] = $term_info->term_taxonomy_id;
					$this->wpdb->query(  $this->wpdb->prepare("UPDATE {$this->wpdb->term_taxonomy} SET count = count - 1 WHERE term_taxonomy_id = %d", $term_info->term_taxonomy_id) );
				}				
				$in_tt_ids = "'" . implode( "', '", $term_ids ) . "'";
				$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$this->wpdb->term_relationships} WHERE object_id = %d AND term_taxonomy_id IN ($in_tt_ids)", $pid ) );
			}
		}

		if (empty($assign_taxes)) return;

		foreach ($assign_taxes as $tt) {
			$this->wpdb->insert( $this->wpdb->term_relationships, array( 'object_id' => $pid, 'term_taxonomy_id' => $tt ) );
			$this->wpdb->query( "UPDATE {$this->wpdb->term_taxonomy} SET count = count + 1 WHERE term_taxonomy_id = $tt" );
		}

		$values = array();
        $term_order = 0;
		foreach ( $assign_taxes as $tt )			                        	
    		$values[] = $this->wpdb->prepare( "(%d, %d, %d)", $pid, $tt, ++$term_order);
		                					

		if ( $values ){
			if ( false === $this->wpdb->query( "INSERT INTO {$this->wpdb->term_relationships} (object_id, term_taxonomy_id, term_order) VALUES " . join( ',', $values ) . " ON DUPLICATE KEY UPDATE term_order = VALUES(term_order)" ) ){
				$logger and call_user_func($logger, __('<b>ERROR</b> Could not insert term relationship into the database', 'wp_all_import_plugin') . ': '. $this->wpdb->last_error);				
			}
		}                        			

		wp_cache_delete( $pid, $tx_name . '_relationships' ); 
	}

	/**
	 * Clear associations with posts
	 * @param bool[optional] $keepPosts When set to false associated wordpress posts will be deleted as well
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function deletePosts($keepPosts = TRUE, $is_deleted_images = 'auto', $is_delete_attachments = 'auto') {
		$post = new PMXI_Post_List();		
		if ( ! $keepPosts) {								
			$ids = array();
			foreach ($post->getBy('import_id', $this->id)->convertRecords() as $p) {				
				// Remove attachments
				if ($is_delete_attachments == 'yes' or $is_delete_attachments == 'auto' and empty($this->options['is_keep_attachments']))
				{
					wp_delete_attachments($p->post_id, true, 'files');
				}
				else
				{
					wp_delete_attachments($p->post_id, false, 'files');
				}
				// Remove images
				if ($is_deleted_images == 'yes' or $is_deleted_images == 'auto' and empty($this->options['is_keep_imgs']))
				{
					wp_delete_attachments($p->post_id, true, 'images');
				}
				else
				{
					wp_delete_attachments($p->post_id, false, 'images');
				}
				$ids[] = $p->post_id;
			}

			if ( ! empty($ids) ){

				foreach ($ids as $id) {
					do_action('pmxi_delete_post', $id);
					if ( $this->options['custom_type'] != 'import_users' ) wp_delete_object_term_relationships($id, get_object_taxonomies('' != $this->options['custom_type'] ? $this->options['custom_type'] : 'post'));
				}

				if ( $this->options['custom_type'] == 'import_users' ){
					$sql = "delete a,b
					FROM ".$this->wpdb->users." a
					LEFT JOIN ".$this->wpdb->usermeta." b ON ( a.ID = b.user_id )					
					WHERE a.ID IN (".implode(',', $ids).");";
				}
				else {
					$sql = "delete a,b,c
					FROM ".$this->wpdb->posts." a
					LEFT JOIN ".$this->wpdb->term_relationships." b ON ( a.ID = b.object_id )
					LEFT JOIN ".$this->wpdb->postmeta." c ON ( a.ID = c.post_id )
					LEFT JOIN ".$this->wpdb->posts." d ON ( a.ID = d.post_parent )
					WHERE a.ID IN (".implode(',', $ids).");";
				}

				$this->wpdb->query( 
					$this->wpdb->prepare($sql, '')
				);				
				
			}			
		}
		
		$this->wpdb->query($this->wpdb->prepare('DELETE FROM ' . $post->getTable() . ' WHERE import_id = %s', $this->id));

		return $this;
	}
	/**
	 * Delete associated files
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function deleteFiles() {
		$fileList = new PMXI_File_List();
		foreach($fileList->getBy('import_id', $this->id)->convertRecords() as $f) {
			if ( @file_exists($f->path) ){ 
				wp_all_import_remove_source($f->path);				
			}
			$f->delete();
		}
		return $this;
	}
	/**
	 * Delete associated history logs
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function deleteHistories(){
		$historyList = new PMXI_History_List();
		foreach ($historyList->getBy('import_id', $this->id)->convertRecords() as $h) {
			$h->delete();
		}
		return $this;
	}
	/**
	 * Delete associated sub imports
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function deleteChildren($keepPosts = TRUE){
		$importList = new PMXI_Import_List();
		foreach ($importList->getBy('parent_import_id', $this->id)->convertRecords() as $i) {
			$i->delete($keepPosts);
		}
		return $this;
	}	
	/**
	 * @see parent::delete()
	 * @param bool[optional] $keepPosts When set to false associated wordpress posts will be deleted as well
	 */
	public function delete($keepPosts = TRUE, $is_deleted_images = 'auto', $is_delete_attachments = 'auto') {
		$this->deletePosts($keepPosts, $is_deleted_images, $is_delete_attachments)->deleteFiles()->deleteHistories()->deleteChildren($keepPosts);
		
		return parent::delete();
	}
	
}
