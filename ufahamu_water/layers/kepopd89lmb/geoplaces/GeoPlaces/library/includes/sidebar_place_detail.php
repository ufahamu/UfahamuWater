<?php
/**
 * The Sidebar containing the Sidebar 1 and Sidebar 2 widget areas.
 */
?>
<?php templ_before_sidebar(); // before sidebar hooks?>
<?php
if(templ_is_layout('full_width'))  ////Sidebar Full width page
{
	
}else
if(templ_is_layout('2_col_right'))  ////Sidebar 2 column right
{
	
	
	echo '<div class="sidebar right right_col">';
	$address = stripslashes(get_post_meta($post->ID,'geo_address',true));
	$geo_latitude = get_post_meta($post->ID,'geo_latitude',true);
	$geo_longitude = get_post_meta($post->ID,'geo_longitude',true);
	$timing = get_post_meta($post->ID,'timing',true);
	$contact = stripslashes(get_post_meta($post->ID,'contact',true));
	$email = get_post_meta($post->ID,'email',true);
	$website = get_post_meta($post->ID,'website',true);
	$twitter = get_post_meta($post->ID,'twitter',true);
	$facebook = get_post_meta($post->ID,'facebook',true);
	
	
	?>
	<div class="company_info">
	<?php global $post;
	$custom_user = wp_get_current_user(); 
	$cuid = $custom_user->ID;
	$paid = $post->post_author;
	if($cuid == $paid)
	{
	?>
	<p class="edit-link"><a href="<?php echo site_url();?>/?ptype=post_listing&pid=<?php echo $post->ID;?>" class="post-edit-link"><?php _e ('EDIT THIS','templatic');?></a></p>
	<?php } ?>

 <!-- claim to ownership -->
          <?php global $post,$wpdb,$claim_db_table_name ;
							$claimreq = $wpdb->get_results("select * from $claim_db_table_name where post_id= '".$post->ID."' and status = '1'");
								if(mysql_affected_rows() >0 || get_post_meta($post->ID,'is_claimed',true) == 1)
								{
								_e('<p class="i_verfied">Owner Verified Listing</p>','templatic');
								}else{
						
						?>	
							<a href="javascript:void(0);" title="Claim this listing" class="i_claim c_sendtofriend"><?php echo CLAIM_OWNERSHIP;?></a>
						<?php include_once (TEMPLATEPATH .'/monetize/email_notification/popup_owner_frm.php'); ?>
						<?php } ?>


<?php if($address) {     ?>
<p> <span class="i_location"><?php echo ADDRESS." :"; ?></span> <?php echo get_post_meta($post->ID,'geo_address',true);?>   </p> <?php } ?>
<?php if($website){
		$website = $website;
        if(!strstr($website,'http')) {
             $website = 'http://'.$website;
        } ?>
<?php 	if($website && get_post_meta($post->ID,'web_show',true) != 'No'){?>
		<p>  <span class="i_website"><a href="<?php echo $website;?>" target="blank"><strong><?php  echo WEBSITE_TEXT; ?></strong></a>  </span> </p>
<?php 	}?>
<?php } if($timing){?>
<p> <span class="i_time"> <?php echo TIME." :" ; ?> </span>  <?php echo $timing; ?>  </p> <?php } 
if($contact && get_option('ptthemes_contact_on_detailpage') == 'Yes') { ?>
<p> <span class="i_contact"><?php echo PHONE." :"; ?> </span>  <?php echo $contact;?>  </p> <?php } ?>
<p><?php favourite_html($post->post_author,$post->ID); ?> </p>
</div>  <!-- company info -->
                
        <div class="company_info2">
		<?php 	    if(get_option('ptthemes_disable_rating') == 'no') {  ?>
       <p> <span class="i_rating"><?php echo RATING." :"; ?></span> 
       <span class="single_rating"> 
       <?php  echo get_post_rating_star($post->ID); ?>
        	</span> 
        </p><?php } ?>
       <div class="share clarfix"> 
       <div class="addthis_toolbox addthis_default_style">
        <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c873bb26489d97f" class="addthis_button_compact sharethis"><?php echo SHARE_TEXT; ?></a>
        </div>
       <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c873bb26489d97f"></script>
	
    	</div>
      
      <div class="links">
	  <?php if($twitter) {  ?>
       <a class="i_twitter" href="<?php echo $twitter ;?>"  target="blank"> <?php echo TWITTER; ?></a>      <?php } 
	   if($facebook) { ?>
        <a class="i_facebook" href="<?php echo $facebook;?>"  target="blank"><?php echo FACEBOOK; ?></a>  <?php } ?>
         </div>
             <?php 
		 if(get_option('ptthemes_email_on_detailpage') == 'Yes') { ?>
         <a href="javascript:void(0);"  title="Mail to a friend" class="b_sendtofriend i_email2"><?php echo MAIL_TO_FRIEND;?></a> 
		<?php include_once (TEMPLATEPATH . '/monetize/email_notification/popup_frms.php'); } ?>					
    <!-- post Inquiry -->
	<?php if(get_option('ptthemes_email_on_detailpage') == 'Yes') { ?>
        <a href="javascript:void(0);" title="I"  class="i_email2 i_sendtofriend"><?php echo SEND_INQUIRY;?></a> 
        <?php include_once (TEMPLATEPATH .'/monetize/email_notification/popup_inquiry_frm.php'); } ?>
		<?php	global $custom_post_meta_db_table_name;
				$sql = "select * from $custom_post_meta_db_table_name where is_active=1 and show_on_detail=1 and (post_type='".CUSTOM_POST_TYPE1."' or post_type='both') ";
				if($fields_name)
				{
					$fields_name = '"'.str_replace(',','","',$fields_name).'"';
					$sql .= " and htmlvar_name in ($fields_name) ";
				}
				$sql .=  " order by sort_order asc,cid asc";
				$post_meta_info = $wpdb->get_results($sql);
				foreach($post_meta_info as $post_meta_info_obj){ 
					if($post_meta_info_obj->ctype =='text' || $post_meta_info_obj->ctype =='texteditor' || $post_meta_info_obj->ctype =='textarea' || $post_meta_info_obj->ctype =='date' || $post_meta_info_obj->ctype =='upload'){
					if(get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true) != "" ){ echo "";
						if($post_meta_info_obj->htmlvar_name != "gallery" && $post_meta_info_obj->htmlvar_name != "twitter"  && $post_meta_info_obj->htmlvar_name != "facebook" && $post_meta_info_obj->htmlvar_name != "contact" && $post_meta_info_obj->htmlvar_name != "listing_image" && $post_meta_info_obj->htmlvar_name != "available" && $post_meta_info_obj->htmlvar_name != "geo_address" && $post_meta_info_obj->htmlvar_name != "website" && $post_meta_info_obj->htmlvar_name != "timing" && $post_meta_info_obj->htmlvar_name != "video")
						{
								if($post_meta_info_obj->ctype =='texteditor' || $post_meta_info_obj->ctype =='textarea') {
									echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)."</div>";
								} else {
									echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)."</div>";
								}
						}
					 }
					}else{
						if($post_meta_info_obj->ctype == 'multicheckbox'){
							$multiVal = get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true);
							$arrVal="";
							if($multiVal):
								foreach($multiVal as $_multiVal):
										$arrVal .= $_multiVal.",";
								endforeach;
							endif;	
							echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".substr($arrVal,0,-1)."</div>";					
							}else if($post_meta_info_obj->ctype == 'select'){
							$Val = get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true);
							if($Val != $post_meta_info_obj->is_default){
							echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)."</div>";
							}
							}else{
							echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)."</div>";
							
						}
					}
					
					 
		} ?>
         
        </div>
	
	<?php
	if (function_exists('dynamic_sidebar') && dynamic_sidebar('place_detail_sidebar')){?><?php } else {?>  <?php }
	if (function_exists('dynamic_sidebar') && dynamic_sidebar('custome_sidebar')){ } else { }
	echo '</div>';
	
	
}
else  ////Sidebar 2 column left as default setting
{
	echo '<div class="sidebar left left_col">';
	
	$address = stripslashes(get_post_meta($post->ID,'geo_address',true));
	$geo_latitude = get_post_meta($post->ID,'geo_latitude',true);
	$geo_longitude = get_post_meta($post->ID,'geo_longitude',true);
	$timing = get_post_meta($post->ID,'timing',true);
	$contact = stripslashes(get_post_meta($post->ID,'contact',true));
	$email = get_post_meta($post->ID,'email',true);
	$website = get_post_meta($post->ID,'website',true);
	$twitter = get_post_meta($post->ID,'twitter',true);
	$facebook = get_post_meta($post->ID,'facebook',true);
	
	
	?>
	<div class="company_info">
	<?php global $post;
	$custom_user = wp_get_current_user(); 
	$cuid = $custom_user->ID;
	$paid = $post->post_author;
	if($cuid == $paid)
	{
	?>
	<p class="edit-link"><a href="<?php echo site_url();?>/?ptype=post_listing&pid=<?php echo $post->ID;?>" class="post-edit-link"><?php _e ('EDIT THIS','templatic');?></a></p>
	<?php } ?>

 <!-- claim to ownership -->
          <?php global $post,$wpdb,$claim_db_table_name ;
							$claimreq = $wpdb->get_results("select * from $claim_db_table_name where post_id= '".$post->ID."' and status = '1'");
								if(mysql_affected_rows() >0 || get_post_meta($post->ID,'is_claimed',true) == 1)
								{
								_e('<p class="i_verfied">Owner Verified Listing</p>','templatic');
								}else{
						
						?>	
							<a href="javascript:void(0);" title="Claim this listing" class="i_claim c_sendtofriend"><?php echo CLAIM_OWNERSHIP;?></a>
						<?php include_once (TEMPLATEPATH .'/monetize/email_notification/popup_owner_frm.php'); ?>
						<?php } ?>


<?php if($address) {     ?>
<p> <span class="i_location"><?php echo ADDRESS." :"; ?></span> <?php echo get_post_meta($post->ID,'geo_address',true);?>   </p> <?php } ?>
<?php if($website){
		$website = $website;
        if(!strstr($website,'http')) {
             $website = 'http://'.$website;
        } ?>
<?php 	if($website && get_post_meta($post->ID,'web_show',true) != 'No'){?>
		<p>  <span class="i_website"><a href="<?php echo $website;?>" target="blank"><strong><?php echo WEBSITE_TEXT; ?></strong></a>  </span> </p>
<?php 	}?>
<?php } if($timing){?>
<p> <span class="i_time"> <?php echo TIME." :" ; ?> </span>  <?php echo $timing; ?>  </p> <?php } 
if($contact && get_option('ptthemes_contact_on_detailpage') == 'Yes') { ?>
<p> <span class="i_contact"><?php echo PHONE." :"; ?> </span>  <?php echo $contact;?>  </p> <?php } ?>
<p><?php favourite_html($post->post_author,$post->ID); ?> </p>
</div>  <!-- company info -->
                
        <div class="company_info2">
		<?php 	    if(get_option('ptthemes_disable_rating') == 'no') {  ?>
       <p> <span class="i_rating"><?php echo RATING." :"; ?></span> 
       <span class="single_rating"> 
       <?php  echo get_post_rating_star($post->ID); ?>
        	</span> 
        </p><?php } ?>
       <div class="share clarfix"> 
       <div class="addthis_toolbox addthis_default_style">
        <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c873bb26489d97f" class="addthis_button_compact sharethis"><?php echo SHARE_TEXT; ?></a>
        </div>
       <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c873bb26489d97f"></script>
	
    	</div>
      
      <div class="links">
	  <?php if($twitter) {  ?>
       <a class="i_twitter" href="<?php echo $twitter ;?>"  target="blank"> <?php echo TWITTER; ?></a>      <?php } 
	   if($facebook) { ?>
        <a class="i_facebook" href="<?php echo $facebook;?>"  target="blank"><?php echo FACEBOOK; ?></a>  <?php } ?>
         </div>
             <?php 
		 if(get_option('ptthemes_email_on_detailpage') == 'Yes') { ?>
         <a href="javascript:void(0);"  title="Mail to a friend" class="b_sendtofriend i_email2"><?php echo MAIL_TO_FRIEND;?></a> 
		<?php include_once (TEMPLATEPATH . '/monetize/email_notification/popup_frms.php'); } ?>					
    <!-- post Inquiry -->
	<?php if(get_option('ptthemes_email_on_detailpage') == 'Yes') { ?>
        <a href="javascript:void(0);" title="I"  class="i_email2 i_sendtofriend"><?php echo SEND_INQUIRY;?></a> 
        <?php include_once (TEMPLATEPATH .'/monetize/email_notification/popup_inquiry_frm.php'); } ?>
		<?php	global $custom_post_meta_db_table_name;
				$sql = "select * from $custom_post_meta_db_table_name where is_active=1 and show_on_detail=1 and (post_type='".CUSTOM_POST_TYPE1."' or post_type='both') ";
				if($fields_name)
				{
					$fields_name = '"'.str_replace(',','","',$fields_name).'"';
					$sql .= " and htmlvar_name in ($fields_name) ";
				}
				$sql .=  " order by sort_order asc,cid asc";
				$post_meta_info = $wpdb->get_results($sql);
				foreach($post_meta_info as $post_meta_info_obj){ 
					if($post_meta_info_obj->ctype =='text' || $post_meta_info_obj->ctype =='texteditor' || $post_meta_info_obj->ctype =='textarea' || $post_meta_info_obj->ctype =='date' || $post_meta_info_obj->ctype =='upload'){
					if(get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true) != "" ){
						if($post_meta_info_obj->htmlvar_name != "gallery" && $post_meta_info_obj->htmlvar_name != "twitter"  && $post_meta_info_obj->htmlvar_name != "facebook" && $post_meta_info_obj->htmlvar_name != "contact" && $post_meta_info_obj->htmlvar_name != "listing_image" && $post_meta_info_obj->htmlvar_name != "available" && $post_meta_info_obj->htmlvar_name != "geo_address" && $post_meta_info_obj->htmlvar_name != "website" && $post_meta_info_obj->htmlvar_name != "timing" && $post_meta_info_obj->htmlvar_name != "video")
						{
								if($post_meta_info_obj->ctype =='texteditor' || $post_meta_info_obj->ctype =='textarea') {
									echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)."</div>";
								} else {
									echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)."</div>";
								}
						}
					 }
					}else{
						if($post_meta_info_obj->ctype == 'multicheckbox'):
							$multiVal = get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true);
							$arrVal="";
							if($multiVal):
								foreach($multiVal as $_multiVal):
										$arrVal .= $_multiVal.",";
								endforeach;
							endif;	
							echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".substr($arrVal,0,-1)."</div>";																														   		        		else:
							echo "<div class='i_customlable'><span>".$post_meta_info_obj->site_title." :"."</span>".get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)."</div>";
						endif;
					}
					
					 
		} ?>
         
        </div>
	
	<?php

	if (function_exists('dynamic_sidebar') && dynamic_sidebar('custome_sidebar')){ } else { }

	if (function_exists('dynamic_sidebar') && dynamic_sidebar('place_detail_sidebar')){?><?php } else {?>  <?php }
	echo '</div>';

}
?>

<?php templ_after_sidebar(); // after sidebar hooks?>