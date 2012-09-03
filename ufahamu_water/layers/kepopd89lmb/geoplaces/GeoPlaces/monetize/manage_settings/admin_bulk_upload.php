<script>
function check_frm()
{
	if(document.getElementById('bulk_upload_csv').value == '')
	{
		alert("<?php _e('Please select csv file to upload','templatic');?>");
		return false;
	}
	return true;
}

function update_posttype(ptype){
	if (ptype=="")  {
		ocument.getElementById("ptype").innerHTML="";
		return;
	}else{
		document.getElementById("ptype").innerHTML= ptype;
	}
	if (window.XMLHttpRequest)  {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
		xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
		document.getElementById("ptype").innerHTML=xmlhttp.responseText;
		}
	} 
	url = "<?php echo get_template_directory_uri(); ?>/monetize/manage_settings/ajax_manage_settings.php?ptype="+ptype
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
</script>

<?php
$sample_csv = apply_filters('templ_bulk_sample_csv_link_filter', get_template_directory_uri().'/post_sample.csv');
global $wpdb,$current_user;
$dirinfo = wp_upload_dir();
$path = $dirinfo['path'];
$url = $dirinfo['url'];
$subdir = $dirinfo['subdir'];
$basedir = $dirinfo['basedir'];
$baseurl = $dirinfo['baseurl'];
$tmppath = "/csv/";
$multycity_table_name = $wpdb->prefix."multicity";
if(isset($_REQUEST['dropcities']) && $_REQUEST['dropcities'] != ""){
$wpdb->query("DROP table $multycity_table_name");
update_option('update_cities','1');
}
if(isset($_POST['submit_csv']))
{
	if($_FILES['bulk_upload_csv']['name']!='' && $_FILES['bulk_upload_csv']['error']=='0')
	{
		$filename = $_FILES['bulk_upload_csv']['name'];
		$filenamearr = explode('.',$filename);
		$extensionarr = array('csv','CSV');
		
		if(in_array($filenamearr[count($filenamearr)-1],$extensionarr))
		{
			$destination_path = $basedir . $tmppath;
			if (!file_exists($destination_path))
			{
				mkdir($destination_path, 0777);				
			}
			$target_path = $destination_path . $filename;
			$csv_target_path = $target_path;
			if(move_uploaded_file($_FILES['bulk_upload_csv']['tmp_name'], $target_path)) 
			{
				$fd = fopen($target_path, "rt");
				$rowcount = 0;
				$customKeyarray = array();
				while (!feof ($fd))
				{
					$buffer = fgetcsv($fd, 4096);
					if($rowcount == 0)
					{
						for($k=0;$k<count($buffer);$k++)
						{
							$customKeyarray[$k] = $buffer[$k];
						}
						if($customKeyarray[0]=='')
						{
							$url = site_url('/wp-admin/admin.php');
							echo '<form action="'.$url.'#option_bulk_upload" method="get" id="frm_bulk_upload" name="frm_bulk_upload">
							<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="bulkupload" name="mod"><input type="hidden" value="wrong" name="emsg">
							</form>
							<script>document.frm_bulk_upload.submit();</script>';exit;	
						}
					}else
					{ 
						$userid = trim($buffer[0]);
						$post_date = trim($buffer[1]);
						$post_date_gmt = trim($buffer[2]);
						$post_title = addslashes($buffer[3]);
						$post_cat = array();
						$catids_arr = array();
						$post_cat = trim($buffer[4]);
						$post_desc = addslashes($buffer[7]);					
						$post_excerpt = addslashes($buffer[8]);						
						$post_status = addslashes($buffer[9]);						
						$comment_status = addslashes($buffer[10]);						
						$ping_status = addslashes($buffer[11]);						
						$post_password = addslashes($buffer[12]);						
						$post_name = addslashes($buffer[13]);						
						$to_ping = addslashes($buffer[14]);						
						$pinged = addslashes($buffer[15]);						
						$post_modified = addslashes($buffer[16]);						
						$post_modified_gmt = addslashes($buffer[17]);						
						$post_content_filter = addslashes($buffer[18]);						
						$post_parent = addslashes($buffer[19]);						
						$guide = addslashes($buffer[20]);						
						$menu_oreder = addslashes($buffer[21]);	
						
						$my_post_type = $_POST['my_post_type'];
						$post_mime_type = addslashes($buffer[23]);
						$comment_count = addslashes($buffer[24]);
						$geo_address = 	addslashes($buffer[25]);			
						$geo_latitude = 	addslashes($buffer[26]);			
						$geo_longitude = 	addslashes($buffer[27]);			
						$map_view = 	addslashes($buffer[28]);			
						$add_feature = 	addslashes($buffer[29]);			
						$timing = 	addslashes($buffer[30]);			
						$content = 	addslashes($buffer[31]);			
						$email = 	addslashes($buffer[32]);			
						$teitter = 	addslashes($buffer[33]);			
						$facebook = 	addslashes($buffer[34]);			
						$property_feature = 	addslashes($buffer[35]);			
						$post_city_id = 	addslashes($buffer[36]);			
						$video = 	addslashes($buffer[37]);			
						$is_featured = 	addslashes($buffer[38]);			
						$paid_amount = 	addslashes($buffer[39]);			
						$alive_days = 	addslashes($buffer[40]);			
						$payment_method = 	addslashes($buffer[41]);			
						$remote_ip = 	addslashes($buffer[42]);			
						$ip_status = 	addslashes($buffer[43]);			
						$pkg_id = 	addslashes($buffer[44]);			
						$featured_type = addslashes($buffer[45]);
						$total_amount = addslashes($buffer[46]);
						$website = addslashes($buffer[47]);
						/*--- Variable for post type2---*/
						$reg_desc = addslashes($buffer[48]);
						$reg_fees = addslashes($buffer[49]);
						$st_date = addslashes($buffer[50]);
						$st_time = addslashes($buffer[51]);
						$end_date = addslashes($buffer[52]);
						$end_time = addslashes($buffer[53]);
						/*--- Variable for post type2 EOF---*/
						if($post_cat)
						{
							$post_cat_arr = explode('&',$post_cat);
							for($c=0;$c<count($post_cat_arr);$c++)
							{
								$catid = trim($post_cat_arr[$c]);
								if(get_cat_ID($catid))
								{
									$catids_arr[] = get_cat_ID($catid);
								}
							}
						}
						if(!$catids_arr)
						{
							$catids_arr[] = 1;	
						}
						$post_tags = trim($buffer[6]); // comma seperated tags
						if($post_tags)
						{
							$tag_arr = explode('&',$post_tags);	
						}
						
						if($post_title!='')
						{
							$my_post['post_title'] = $post_title;
							$my_post['post_content'] = $post_desc;
							if($userid)
							{
								$my_post['post_author'] = $userid;
							}else
							{
								$my_post['post_author'] = $current_user->ID;
							}
							
							$my_post['post_status'] = $post_status;
							$my_post['comment_status'] = $comment_status;
							$my_post['ping_status'] = $ping_status;
							$my_post['post_password'] = $post_password;
							$my_post['post_name'] = $post_name;
							$my_post['to_ping'] = $to_ping;
							$my_post['pinged'] = $pinged;
							$my_post['post_modified'] = $post_modified;
							$my_post['post_modified_gmt'] = $post_modified_gmt;
							$my_post['post_content_filtered'] = $post_content_filtered;
							$my_post['post_parent'] = $post_parent;
							$my_post['guide'] = $guide;
							$my_post['menu_order'] = $menu_order;
							$my_post['post_date'] = $post_date;
							$my_post['post_date_gmt'] = $post_date_gmt;
							$my_post['post_excerpt'] = $post_excerpt;
							$my_post['post_type'] = $my_post_type;
							$my_post['post_mime_type'] = $post_mime_type;
							$my_post['comment_count'] = $comment_count;
							$my_post['post_category'] = $catids_arr;
							$my_post['tags_input'] = $tag_arr;
							$last_postid = wp_insert_post( $my_post );
							if($my_post_type!='post'){
								if($my_post_type == trim(CUSTOM_POST_TYPE1)){
									wp_set_object_terms($last_postid, $post_cat_arr, CUSTOM_CATEGORY_TYPE1); //custom category
									wp_set_object_terms($last_postid, $tag_arr, CUSTOM_TAG_TYPE1); //custom tags
								}
								if($my_post_type == trim(CUSTOM_POST_TYPE2)){
									wp_set_object_terms($last_postid, $post_cat_arr, CUSTOM_CATEGORY_TYPE2); //custom category
									wp_set_object_terms($last_postid, $tag_arr, CUSTOM_TAG_TYPE2); //custom tags
								}
								
							}
							update_post_meta($last_postid,'geo_address', $geo_address);
							update_post_meta($last_postid,'geo_latitude', $geo_latitude);
							update_post_meta($last_postid,'geo_longitude', $geo_longitude);
							update_post_meta($last_postid,'map_view', $map_view);
							update_post_meta($last_postid,'add_feature', $add_feature);
							update_post_meta($last_postid,'timing', $timing);
							update_post_meta($last_postid,'contact', $contact);
							update_post_meta($last_postid,'email', $email);
							update_post_meta($last_postid,'twitter', $twitter);
							update_post_meta($last_postid,'facebook', $facebook);
							update_post_meta($last_postid,'property_feature', $property_feature);
							update_post_meta($last_postid,'post_city_id', $post_city_id);
							update_post_meta($last_postid,'video', $video);
							update_post_meta($last_postid,'is_featured', $is_featured);
							update_post_meta($last_postid,'paid_amount', $paid_amount);
							update_post_meta($last_postid,'alive_days', $alive_days);
							update_post_meta($last_postid,'paymentmethod', $paymentmethod);
							update_post_meta($last_postid,'remote_ip', $remote_ip);
							update_post_meta($last_postid,'ip_status', $ip_status);
							update_post_meta($last_postid,'pkg_id', $pkg_id);
							update_post_meta($last_postid,'featured_type', $featured_type);
							update_post_meta($last_postid,'total_amount', $total_amount);
							update_post_meta($last_postid,'website', $website);
							
							/*---- If post type is event then enter in below condition ----*/
							if($my_post_type ==  trim(CUSTOM_POST_TYPE2))
							{
								update_post_meta($last_postid,'reg_desc', $reg_desc);
								update_post_meta($last_postid,'reg_fees', $reg_fees);
								update_post_meta($last_postid,'st_date', $st_date);
								update_post_meta($last_postid,'st_time', $st_time);
								update_post_meta($last_postid,'end_date', $end_date);
								update_post_meta($last_postid,'end_time', $end_time);
						
							}
							/*---- End of condition ----*/
							$menu_order = 0;
							$image_folder_name = 'bulk/';
							for($c=5;$c<count($customKeyarray);$c++)
							{
								update_post_meta($last_postid, $customKeyarray[$c], addslashes($buffer[$c]));
								if($customKeyarray[$c]=='IMAGE')
								{
									$image_name = $buffer[$c];
									$menu_order = $c+1;
									$image_name_arr = explode(';',$image_name);
									foreach($image_name_arr as $_image_name_arr)
									{
									$img_name = $_image_name_arr;
									$img_name_arr = explode('.',$img_name);
									$post_img = array();
									$post_img['post_title'] = $_image_name_arr;
									$post_img['post_status'] = 'attachment';
									$post_img['post_parent'] = $last_postid;
									$post_img['post_type'] = 'attachment';
									$post_img['post_mime_type'] = 'image/jpeg';
									$post_img['menu_order'] = $menu_order;
									$last_postimage_id = wp_insert_post( $post_img );
									update_post_meta($last_postimage_id, '_wp_attached_file', $image_folder_name.$img_name);
									$post_attach_arr = array(
														"width"	=>	580,
														"height" =>	480,
														"hwstring_small"=> "height='150' width='150'",
														"file"	=> $image_folder_name.$image_name,
														);
									wp_update_attachment_metadata( $last_postimage_id, $post_attach_arr );
									}
								}else if($customKeyarray[$c] == 'comments_data'){ 
								$time = current_time('mysql');
								$comments = $buffer[$c];
								$comeents_explode = explode('##',$comments);
								foreach($comeents_explode as $comeents_explode_obj){
								$comment_data = explode("~",$comeents_explode_obj);
								$data = array(
										'comment_post_ID' => $last_postid,
										'comment_author' => $comment_data[2],
										'comment_author_email' =>  $comment_data[3],
										'comment_author_url' =>  $comment_data[4],
										'comment_content' =>  $comment_data[8],
										'comment_type' =>  $comment_data[12],
										'comment_parent' =>  $comment_data[13],
										'user_id' =>  $comment_data[14],
										'comment_author_IP' => $comment_data[5],
										'comment_agent' =>  $comment_data[11],
										'comment_date' =>  $comment_data[6],
										'comment_approved' =>  $comment_data[10],
									);

									remove_action('wp_insert_comment', 'save_comment_rating' );
									wp_insert_comment($data);
									$lastid = $wpdb->insert_id;

									$rating = $buffer[$c+1];
									if($rating ){
									$rating_explode = explode('##',$rating);
									foreach($rating_explode as $rating_explode_obj){
									$rating_data = explode("~",$rating_explode_obj);
									
									$rating_postid = $last_postid;
									$rating_posttitle = $rating_data[2];
									if($rating_posttitle  == 'null'){
									$rating_posttitle = $post_title;
									}
									$rating_rating =  $rating_data[3];
									$rating_timestamp =  $rating_data[4];
									if($rating_timestamp == 'null'){
									$rating_timestamp = '';
									}
									$rating_ip =  $rating_data[5];
									if($rating_ip == 'null'){
									$rating_ip = $_SERVER['REMOTE_ADDR'];
									}
									$rating_host =  $rating_data[6];
									if($rating_host == 'null'){
									$rating_host = '';
									}
									$rating_username = $rating_data[7];
									if($rating_username == 'null'){
									$rating_username = '';
									}
									$rating_userid = $rating_data[8];
									if(!$rating_userid || $rating_userid == 'null'){
									$rating_userid = 0;
									}
									$comment_id =$lastid;
									$rating_table = $wpdb->prefix."ratings";
									
									$is_tmpl_comment = $wpdb->get_row("select * from $rating_table where comment_id = '".$comment_id."'");
									if(!$is_tmpl_comment){
									$is_tmpl_samecomment = $wpdb->get_row("select * from $rating_table where rating_rating = '".$rating_rating."' && rating_postid = '".$rating_postid."' && rating_userid = '".$rating_userid."'");
									if(!$is_tmpl_samecomment){
									if($rating_rating !=0 && ($comment_data[0] == $rating_data[9] || isset($rating_data[9]))){ 
									$wpdb->query("INSERT INTO $rating_table (`rating_id`, `rating_postid`, `rating_posttitle`, `rating_rating`, `rating_timestamp`, `rating_ip`, `rating_host`, `rating_username`, `rating_userid`, `comment_id`) VALUES ('', $last_postid, '".$rating_posttitle."', $rating_rating , '".$rating_timestamp."', '".$rating_ip."', '".$rating_host."', '".$rating_username."','".$comment_data[2]."','".$comment_id."')"); }
									}
									}
									}
									}
								}
								
								}
								
							}
						}
					}				
				$rowcount++;
				}
				@unlink($csv_target_path);
				$url = site_url().'/wp-admin/admin.php';
				echo '<form action="'.$url.'#option_bulk_upload" method="get" id="frm_bulk_upload" name="frm_bulk_upload">
				<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="bulkupload" name="mod"><input type="hidden" value="success" name="upload_msg"><input type="hidden" value="'.$rowcount.'" name="rowcount">
				</form>
				<script>document.frm_bulk_upload.submit();</script>
				';exit;
			}
			else
			{
				$url = site_url().'/wp-admin/admin.php';
				echo '<form action="'.$url.'#option_bulk_upload" method="get" id="frm_bulk_upload" name="frm_bulk_upload">
				<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="bulkupload" name="mod"><input type="hidden" value="tmpfile" name="emsg">
				</form>
				<script>document.frm_bulk_upload.submit();</script>
				';exit;
			}
		}else
		{
			$url = site_url().'/wp-admin/admin.php';
			echo '<form action="'.$url.'#option_bulk_upload" method="get" id="frm_bulk_upload" name="frm_bulk_upload">
			<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="bulkupload" name="mod"><input type="hidden" value="csvonly" name="emsg">
			</form>
			<script>document.frm_bulk_upload.submit();</script>
			';exit;
		}
	}else
	{
		$url = site_url().'/wp-admin/admin.php';
		echo '<form action="'.$url.'#option_bulk_upload" method="get" id="frm_bulk_upload" name="frm_bulk_upload">
		<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="bulkupload" name="mod"><input type="hidden" value="invalid_file" name="emsg">
		</form>
		<script>document.frm_bulk_upload.submit();</script>
		';exit;
	}
}

if(isset($_POST['submit_city']) && $_POST['submit_city']!=""){
	if($_FILES['bulk_upload_city']['name']!='' && $_FILES['bulk_upload_city']['error']=='0')
	{
		$filename = $_FILES['bulk_upload_city']['name'];
		$filenamearr = explode('.',$filename);
		
		$extensionarr = array('csv','CSV');
		
		if(in_array($filenamearr[count($filenamearr)-1],$extensionarr))
		{
			$destination_path = $basedir . $tmppath;
		
			if (!file_exists($destination_path))
			{
				mkdir($destination_path, 0777);				
			}
			$target_path = $destination_path . $filename;
			$csv_target_path = $target_path;
			
			if(move_uploaded_file($_FILES['bulk_upload_city']['tmp_name'], $target_path)) 
			{
				$fd = fopen($target_path, "rt");
				
				$rowcount1 = 0;
				$customKeyarray = array();
				while (!feof ($fd))
				{
					$buffer = fgetcsv($fd, 4096);

					if($rowcount1 == 0)
					{
						for($k=0;$k<count($buffer);$k++)
						{
							$customKeyarray[$k] = $buffer[$k];
						}
						
						if($customKeyarray[0]=='')
						{
							$url = site_url('/wp-admin/admin.php');
							echo '<form action="'.$url.'#option_bulk_upload" method="get" id="frm_city_upload" name="frm_city_upload">
							<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="bulkupload" name="mod"><input type="hidden" value="wrong" name="emsg1">
							</form>
							<script>document.frm_city_upload.submit();</script>';exit;	
						}
					}else{
				
					
								$city_id = trim($buffer[0]);
								$country_id = trim($buffer[1]);
								$cityname = trim($buffer[4]);
								$lat = trim($buffer[5]);
								$lng = addslashes($buffer[6]);
								$scall_factor = trim($buffer[7]);
								$sortorder = addslashes($buffer[8]);					
								$is_zoom_home = addslashes($buffer[9]);						
								$is_default = addslashes($buffer[10]);	
								$multicity_table = $wpdb->prefix."multicity";
								
								if($city_id !=""){
								$wpdb->query("INSERT INTO $multicity_table (`city_id`,`country_id`,`ptype`, `cityname`, `lat`, `lng`, `scall_factor`, `sortorder`, `is_zoom_home`, `categories`, `is_default`, `geo_address`) VALUES ('', '".$city_id."','', '".$cityname."', '".$lat."', '".$lng."', '".$scall_factor."', '".$sortorder."', '".$is_zoom_home."','' ,'".$is_default."','');");
								}
						
						}
						$rowcount1 ++;	
					}
				@unlink($csv_target_path);
				$url = site_url().'/wp-admin/admin.php';
				echo '<form action="'.$url.'#option_bulk_upload" method="get" id="frm_bulk_upload" name="frm_bulk_upload">
				<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="bulkupload" name="mod"><input type="hidden" value="success" name="uploadcity_msg"><input type="hidden" value="'.$rowcount1.'" name="rowcount1">
				</form>
				<script>document.frm_bulk_upload.submit();</script>
				';
				exit;
				
			}
		
		}
	}
}


// BOF Upload Function

$wp_upload_dir = wp_upload_dir();
$basedir = $wp_upload_dir['basedir'];
$baseurl = $wp_upload_dir['baseurl'];
$folderpath = $basedir."/bulk/";
full_copy( TEMPLATEPATH."/images/bulk/", $folderpath );
function full_copy( $source, $target ) 
{
	$imagepatharr = explode('/',str_replace(TEMPLATEPATH,'',$target));
	for($i=0;$i<count($imagepatharr);$i++)
	{
	  if($imagepatharr[$i])
	  {
		  $year_path = ABSPATH.$imagepatharr[$i]."/";
		  if (!file_exists($year_path)){
			 @mkdir($year_path, 0777);
		  }     
		}
	}
	if ( is_dir( $source ) ) {
		@mkdir( $target );
		$d = dir( $source );
		while ( FALSE !== ( $entry = $d->read() ) ) {
			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}
			$Entry = $source . '/' . $entry; 
			if ( is_dir( $Entry ) ) {
				full_copy( $Entry, $target . '/' . $entry );
				continue;
			}
			@copy( $Entry, $target . '/' . $entry );
		}
	
		$d->close();
	}else {
		@copy( $source, $target );
	}
}
// EOF Upload Function

?>
<?php
if(isset($_REQUEST['dropcities']) && $_REQUEST['dropcities'] != ""){
$success_msg .=  "<div id=message1 class=updated>".__('<p style="padding-bottom:5px;">Cities has been droped.','templatic');
	echo $success_msg .= "</div>";
}
if($_REQUEST['upload_msg']=='success'){ 
$rowcount = $_REQUEST['rowcount'];
$success_msg = '';
$rowcount = $rowcount-2;
if($rowcount > 0)
  {
	$success_msg .=  "<div id=message1 class=updated>".__('<p style="padding-bottom:5px;">CSV uploaded successfully.','templatic');
	$success_msg .= __(sprintf('<br /><b>Total of %s records inserted.</b></p>',$rowcount),'templatic');
	echo $success_msg .= "</div>";
  }
else
  {
 	$success_msg = __(sprintf('<b style="color:red;">No record available .</b>'),'templatic');
	echo $success_msg;
  }
}
?>
<?php
$sample_csv = apply_filters('templ_bulk_sample_csv_link_filter', get_template_directory_uri().'/post_sample.csv');
?> 
<h4><?php _e('Bulk upload','templatic');?></h4>	
<form action="<?php echo site_url('/wp-admin/admin.php')?>?page=manage_settings&mod=bulkupload#option_bulk_upload" method="post" name="bukl_upload_frm" enctype="multipart/form-data">

<input type="hidden" name="ptype" id="ptype" value="post"/>
  <p><?php
  echo _e('You can import listings from another GeoPlaces site in CSV format to this site. To export listings from an older version of this theme, i.e. GeoPlaces v3 or older, you will have to <a href="http://templatic.com/theme-support/export-listings-geoplaces3-to-geoplaces4">follow this tutorial</a> to enable the export feature.','templatic');
    ?></p>
   <div class="updated settings-error">
   <p style="padding:7px 0px;"><?php echo _e('<b>READ THIS FIRST:</b> Bulk upload feature is currently in <font color="#ff0000">beta</font>. We recommend you setup a dummy site to test out this feature. Using this feature with live data is not recommended as yet.','templatic')?></p> 
  </div>
 <?php if($_REQUEST['emsg']=='csvonly'){?>
 <div class="updated fade below-h2" id="message" style="padding:5px; font-size:11px;" >
 <?php _e('Please upload CSV file only.','templatic');?>
 </div>
 <br />
 <?php }?>
 <?php if($_REQUEST['emsg']=='invalid_file'){?>
 <div class="updated fade below-h2" id="message" style="padding:5px; font-size:11px;" >
 <?php _e('Please select valid CSV file only for listing bulk upload.','templatic');?>
 </div>
 <br />
 <?php }?>
  <?php if($_REQUEST['emsg']=='tmpfile'){?>
 <div class="updated fade below-h2" id="message" style="padding:5px; font-size:11px;" >
 <?php echo $target_path;  echo sprintf(__('Cannot move the bulk upload file to Temporary system folder','templatic').' <b>"%s"</b>. '.__('Please check folder permission should be 0777.','templatic'),$destination_path);?>
 </div>
 <br />
 <?php }?>
  <?php if($_REQUEST['emsg']=='wrong'){?>
 <div class="updated fade below-h2" id="message" style="padding:5px; font-size:11px;" >
 <?php echo $target_path;  _e('File you are uploading is not valid. First column should be "Post Title".','templatic');?>
 </div>
 <br />
 <?php }?>

<p style="background: #f4f4f4; padding:10px; margin-bottom:20px;"><b><?php _e('Import','templatic');?></b></p>
<div class="option option-select"  >
<p style="font-size:13px;"><b><?php _e('Import place, events and blog posts','templatic');?></p>	
    <h3 style="width:163px;"><?php _e('Select post type','templatic');?> : </h3>
    <div class="section">
		<div class="element" style="padding:8px 0px;">
			<input type="radio" value="post" name="my_post_type" checked="checked" /> <?php _e('Post','templatic');?> &nbsp;&nbsp;
			<input type="radio" value="<?php echo CUSTOM_POST_TYPE1; ?>" name="my_post_type" /> <?php echo CUSTOM_MENU_TITLE; ?> &nbsp;&nbsp;
			<input type="radio" value="<?php echo CUSTOM_POST_TYPE2; ?>" name="my_post_type" /> <?php echo CUSTOM_MENU_TITLE2; ?>
   		</div>
	</div>
	<h3 style="width:163px; clear:both;"><?php _e('Select CSV file to upload','templatic');?> : </h3>
    <div class="section">
		<div class="element">
			<input type="file" name="bulk_upload_csv" id="bulk_upload_csv">
   		</div>
		<div class="description"><input type="submit" name="submit_csv" value="<?php _e('Submit','templatic');?>" class="button-framework-imp" onClick="return check_frm();"></div>    
    </div> 
	<div class="section">
		<div class="element">
		<p><?php _e('You can download');?> <a href="<?php echo site_url()?>/?ptype=csvdl"><?php _e('sample CSV file');?></a></p>
     
   		</div>
		  
    </div>
	
</div> <!-- #end -->
  
  	
  
</form>


<form action="<?php echo site_url('/wp-admin/admin.php')?>?page=manage_settings&mod=bulkupload#option_bulk_upload" method="post" name="frm_city_upload" enctype="multipart/form-data">
<p style="font-size:13px;"><b><?php _e('Import city data from GeoPlaces (older versions)','templatic');?></p>	
<?php
if($_REQUEST['uploadcity_msg']=='success'){
$rowcount1 = $_REQUEST['rowcount1'];
$success_msg1 = '';
$rowcount1 = $rowcount1-2;
if($rowcount1 > 0)
  {
	$success_msg1 .=  "<div id=message class=updated>".__('<p style="padding-bottom:5px;">CSV uploaded successfully.','templatic');
	$success_msg1 .= __(sprintf('<br /><b>Total of %s records inserted.</b></p>',$rowcount1),'templatic');
	echo $success_msg1 .= "</div>";
  }
else
  {
	$success_msg1 = __(sprintf('<b style="color:red;">No record available .</b>'),'templatic');
	echo $success_msg1;
  }
}
?>
<?php if($_REQUEST['emsg1']=='wrong'){?>
 <div class="updated fade below-h2" id="message" style="padding:5px; font-size:11px;" >
 <?php echo $target_path;  _e('File you are uploading is not valid.','templatic');?>
 </div>
 <br />
 <?php }?>
<p><?php _e('If you want to import cities from GeoPlaces3 to GeoPlaces4 with same City ID then',''); ?><a href="<?php echo site_url()."/wp-admin/admin.php?page=manage_settings&mod=bulkupload&dropcities=true#option_bulk_upload".""; ?>"> Click Here </a><?php _e('to drop the table.','templatic'); ?></p>
<div class="option option-select"  >
	<h3 style="width:163px; clear:both;"><?php _e('Select CSV file to upload','templatic');?> : </h3>

    <div class="section">
		<div class="element">
			<input type="file" name="bulk_upload_city" id="bulk_upload_city">
   		</div>
		<div class="description"><input type="submit" name="submit_city" value="<?php _e('Submit','templatic');?>" class="button-framework-imp" ></div>    
    </div>
</div>  
</form>
<!-- #end --> 

<!-- It's section to export csv form BOF-->
<p style="background: #f4f4f4; padding:10px; margin-bottom:20px;"><b><?php _e('Export','templatic');?></b></p>	
<div class="option option-select"  >
    <h3 style="width:163px;"><?php _e('Select post type','templatic');?> : </h3>
    <div class="section">
		<div class="element" style="padding:8px 0px;">
			<input type="radio" value="post" name="post_type_export" checked="checked" onclick="update_posttype(this.value)"/> Post &nbsp;&nbsp;
			<input type="radio" value="<?php echo CUSTOM_POST_TYPE1; ?>" name="post_type_export" onclick="update_posttype(this.value)"/> <?php echo CUSTOM_MENU_TITLE; ?> &nbsp;&nbsp;
			<input type="radio" value="<?php echo CUSTOM_POST_TYPE2; ?>" name="post_type_export" onclick="update_posttype(this.value)"/> <?php echo CUSTOM_MENU_TITLE2; ?>
   		</div>
	</div>
	<h3 style="width:163px; clear:both;"></h3>
    <div class="section">
		<div class="description"><a href="<?php echo get_template_directory_uri().'/monetize/manage_settings/export_to_CSV.php';?>" title="Export To CSV" class="button-framework-csv"><?php echo "Export to CSV"; ?></a></div>    
    </div>
</div> 

<!-- #end -->

<div class="option option-select"  >
 
	<h3 style="width:163px; clear:both;">Export all cities to CSV</h3>
    <div class="section">
		<div class="description"><a href="<?php echo get_template_directory_uri().'/monetize/manage_settings/export_cities.php';?>" title="Export To CSV" class="button-framework-csv"><?php echo "Export to CSV"; ?></a></div>    
    </div>
</div> <!-- #end -->
  
  	