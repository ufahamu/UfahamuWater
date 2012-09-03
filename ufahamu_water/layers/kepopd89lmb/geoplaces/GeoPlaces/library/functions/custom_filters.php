<?php
/********************************************************************
You can add your filetes in this file and it will affected.
This is the common filter functions file where you can add you filtes.
********************************************************************/

add_filter('templ_page_title_filter','templ_page_title_fun');
function templ_page_title_fun($title)
{
	return '<h1>'.$title.'</h1>';
}

add_filter('templ_theme_guide_link_filter','templ_theme_guide_link_fun');
function templ_theme_guide_link_fun($guidelink)
{
	$guidelink .= "/theme-documentation/geoplaces-v4-theme-guide/"; // templatic.com site theme guide url here
	return $guidelink;
}

add_filter('templ_theme_forum_link_filter','templ_theme_forum_link_fun');
function templ_theme_forum_link_fun($forumlink)
{
	$forumlink .= "/test123form_link"; // templatic.com site Forum url here
	return $forumlink;
}

add_filter('templ_admin_menu_title_filter','templ_admin_menu_title_fun');
function templ_admin_menu_title_fun($content)
{
	return $content=__('GeoPlaces','templatic');
}




/*add_filter('templ_admin_post_custom_fields_filter','templ_admin_post_custom_fields_fun');
function templ_admin_post_custom_fields_fun($array)
{
	$pt_metaboxes = $array;
	$pt_metaboxes["custofiled"] = array (
				"name"		=> "custofiled",
				"default" 	=> "",
				"label" 	=> __('Custom Title','templatic'),
				"type" 		=> "text",
				"desc"      => __('Enter Custom Title. eg. : code from youtibe, vimeo, etc','templatic')
			);
	return $pt_metaboxes;
}*/


add_filter('templ_breadcrumbs_navigation_filter','templ_breadcrumbs_navigation_fun');
function templ_breadcrumbs_navigation_fun($bc){
	global $post;
	if($post->post_type == CUSTOM_POST_TYPE1){
		if(strstr($bc,CUSTOM_MENU_TAG_TITLE)) {
			$templ = substr($bc, strrpos($bc,'. &raquo; '.CUSTOM_MENU_TAG_TITLE.':') , strlen($bc));
			$arr = explode('&raquo;',$templ);
			$bc = str_replace($arr[1],'',$bc);
		}	
		$bread = str_replace('. &raquo;',' &raquo;',$bc);
		$bread = str_replace(CUSTOM_MENU_CAT_TITLE.':','',$bread);
		$bread = str_replace(', and',',',$bread);
		$bread = str_replace(' and ',', ',$bread);
		$bread = str_replace(' &raquo;&raquo; ',' &raquo; ',$bread);
		$bread = str_replace(' &raquo;  &raquo; ',' &raquo; ',$bread);
	} else if($post->post_type == CUSTOM_POST_TYPE2){
		if(strstr($bc,CUSTOM_MENU_TAG_TITLE2)) {
			$templ = substr($bc, strrpos($bc,'. &raquo; '.CUSTOM_MENU_TAG_TITLE2.':') , strlen($bc));
			$arr = explode('&raquo;',$templ);
			$bc = str_replace($arr[1],'',$bc);
		}	
		$bread = str_replace('. &raquo;',' &raquo;',$bc);
		$bread = str_replace(CUSTOM_MENU_CAT_TITLE2.':','',$bread);
		$bread = str_replace(', and',',',$bread);
		$bread = str_replace(' and ',', ',$bread);
		$bread = str_replace(' &raquo;&raquo; ',' &raquo; ',$bread);
		$bread = str_replace(' &raquo;  &raquo; ',' &raquo; ',$bread);
	}
	return __($bread,'templatic');	
}

add_action('templ_page_title_above','templ_page_title_above_fun'); //page title above action hook
//add_action('templ_page_title_below','templ_page_title_below_fun');  //page title below action hook
function templ_page_title_above_fun()
{
	templ_set_breadcrumbs_navigation();
}

add_filter('templ_anything_slider_widget_content_filter','templ_anything_slider_content_fun');
function templ_anything_slider_content_fun($post)
{
	ob_start(); // don't remove this code
/////////////////////////////////////////////////////
	if(get_the_post_thumbnail( $post->ID, array())){
	?>
	<a class="post_img" href="<?php echo get_permalink($post->ID);?>"><?php echo  get_the_post_thumbnail( $post->ID, array(220,220),array('class'	=> "",));?></a>
	<?php
    }else if($post_images = bdw_get_images($post->ID,'medium')){ 
	global $thumb_url;
	?>
	<a class="post_img" href="<?php echo get_permalink($post->ID);?>">
	 <img src="<?php echo get_bloginfo('template_url');?>/thumb.php?src=<?php echo $post_images[0];?>&amp;w=220&amp;h=220&amp;zc=1&amp;q=80<?php echo $thumb_url;?>" alt="<?php echo get_the_title($post->ID);?>" title="<?php echo get_the_title($post->ID);?>"  /></a>
	<?php } ?>
    <div class="tslider3_content">
    <h3> <a class="widget-title" href="<?php echo get_permalink($post->ID);?>"><?php echo get_the_title($post->ID);?></a></h3>
    <p>
	<?php echo bm_better_excerpt(605, ' ... ');?></p>
    <p><a href="<?php echo get_permalink($post->ID);?>" class="more"><?php echo READ_MORE_LABEL; ?></a></p>
   </div>


<?php
/////////////////////////////////////////////////////
	$return = ob_get_contents(); // don't remove this code
	ob_end_clean(); // don't remove this code
	return  $return;
}

add_filter('templ_sidebar_widget_box_filter','templ_sidebar_widget_box_fun');
function templ_sidebar_widget_box_fun($content)
{

	$content['home_slider']='';
	// End Remove Footer Widgets Area Page Layout option wise
	//$content['top_navigation']='';
	//$content['header_logo_right_side']='';
	//$content['main_navigation']='';
	$content['header_above']='';
	$content['slider_above']='';
	$content['slider_below']='';
	$content['sidebar_2col_merge']='';
	$content['sidebar2']='';
	
	//$content['header_logo_right_side']='';
	
	$array_key = array_keys($content);
	$position = array_keys($array_key,'single_post_below');
	$widget_pos = $position[0]+1;

$sidebar_widget_arr = array();

$sidebar_widget_arr['front_top_banner'] =array(1,array('name' => 'Front Top Banner Section','id' => 'front_top_banner','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));

$sidebar_widget_arr['front_content'] =array(1,array('name' => 'Front Content','id' => 'front_content','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['front_sidebar'] =array(1,array('name' => 'Front Sidebar','id' => 'front_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));

$sidebar_widget_arr['place_listing_sidebar'] =array(1,array('name' => 'Place Listing Sidebar','id' => 'place_listing_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['place_detail_sidebar'] =array(1,array('name' => 'Place Detail Sidebar','id' => 'place_detail_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['place_detail_content_banner'] =array(1,array('name' => 'Place Detail Content Banner','id' => 'place_detail_content_banner','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['add_place_sidebar'] =array(1,array('name' => 'Add Place Sidebar','id' => 'add_place_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));

$sidebar_widget_arr['event_listing_sidebar'] =array(1,array('name' => 'Event Listing Sidebar','id' => 'event_listing_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['event_detail_sidebar'] =array(1,array('name' => 'Event Detail Sidebar','id' => 'event_detail_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['event_detail_content_banner'] =array(1,array('name' => 'Event Detail Content Banner','id' => 'event_detail_content_banner','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['add_event_sidebar'] =array(1,array('name' => 'Add Event Sidebar','id' => 'add_event_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));


$sidebar_widget_arr['contact_googlemap'] =array(1,array('name' => 'Contact Page - Google Map','id' => 'contact_googlemap','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['blog_listing_sidebar'] =array(1,array('name' => 'Blog Listing - Sidebar','id' => 'blog_listing_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['blog_detail_sidebar'] =array(1,array('name' => 'Blog Details - Sidebar','id' => 'blog_detail_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['blog_detail_content_banner'] =array(1,array('name' => 'Blog Detail Content Banner','id' => 'blog_detail_content_banner','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['login_page'] =array(1,array('name' => 'Login Page','id' => 'login_page','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['custome_sidebar'] =array(1,array('name' => 'Custom Sidebar','id' => 'custome_sidebar','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
$sidebar_widget_arr['footer_nav'] =array(1,array('name' => 'Footer Navigation','id' => 'footer_nav','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));

	 
	array_splice($content, $widget_pos-1, 0, $sidebar_widget_arr);
	
	//print_r($content);
	
	return $content;
}

add_filter('templ_widgets_listing_filter','templ_widgets_listing_fun');
function templ_widgets_listing_fun($content)
{
	//print_r($content);
	$content['featured_video']='';
	$content['pika_choose_slider']='';
	$content['anything_slider']='';
	//$content['login']='';
	$content['anything_listing_slider']='';
	$content['nivo_slider']='';
	$content['my_bio']='';
	//$content['social_media']='';
	
	//print_r($content);
	//$content['flickr']='';
	return $content;
} ?>