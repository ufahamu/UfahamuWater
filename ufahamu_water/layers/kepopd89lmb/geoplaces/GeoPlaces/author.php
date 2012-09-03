<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
<!--  CONTENT AREA START. -->
<?php
global $current_user;
if(isset($_GET['author_name'])) :
	$curauth = get_userdatabylogin($author_name);
else :
	$curauth = get_userdata(intval($author));
endif;

if($curauth->ID == $current_user->ID) {
	
	$user_displayname = $curauth->display_name ;
	$dashboard_display = '<a href="'.get_author_posts_url($current_user->ID).'" class="back_link" >'.BACK_TO_DASHBOARD.'</a>';
} elseif($curauth->ID != $current_user->ID ){
	
	$user_displayname = $curauth->display_name;
}
$user_link = get_author_posts_url($curauth->ID);
if(strstr($user_link,'?') ){$user_link = $user_link.'&list=favourite';}else{$user_link = $user_link.'?list=favourite';}
?>
<?php if ( get_option('ptthemes_breadcrumbs' )) {  ?>
		<div class="breadcrumb clearfix">
			<div class="breadcrumb_in"><a href="<?php echo site_url(); ?>"><?php echo HOME; ?></a> &raquo; <?php _e($curauth->display_name,'templatic'); ?> </div>
		</div>
<?php } if($curauth->ID == $current_user->ID) {?>
<div class="content-title"> 
	 <h1><?php echo DASHBOARD;?></h1> <?php //  echo templ_page_title_filter( $curauth->display_name); //page tilte filter?> 
</div>
<?php } ?>
<?php templ_page_title_below(); //page title below action hook?>

<div class="author_details">
    <div class="author_photo">
      <?php
		if(get_user_meta($curauth->ID,'user_photo',true) != "") { ?>
			<img src="<?php echo templ_thumbimage_filter($destination_path.get_user_meta($curauth->ID,'user_photo',true),'&amp;w=145&amp;h=160&amp;zc=1&amp;q=80');?>" alt="" />
      <?php } else { ?>
			<img src="<?php echo templ_thumbimage_filter(get_template_directory_uri()."/images/no-image.png",'&amp;w=145&amp;h=160&amp;zc=1&amp;q=80');?>" alt="" />
      <?php } ?>
    </div>
	<div class="author_content">
		<h3><?php echo $user_displayname;	?></h3>
		<p class="detail_links">
			<?php get_user_meta($curauth->ID,'user_fname',true). get_user_meta($curauth->ID,'user_lname',true); ?></a>
			<?php if(get_user_meta($curauth->ID,'user_website',true) != "" ) {?>
			<a href="<?php echo get_user_meta($curauth->ID,'user_website',true);?>" target="_blank"><?php _e('Visit website','templatic');?> </a>
			<?php } ?>
			<?php if(get_user_meta($curauth->ID,'user_twitter',true) != "" ) {?>
			<a href="<?php echo get_user_meta($curauth->ID,'user_twitter',true);?>" target="_blank"><?php _e('Twitter','templatic');?> </a>
			<?php } ?>
			<?php if(get_user_meta($curauth->ID,'user_facebook',true) != "" ) {?>
			<a href="<?php echo get_user_meta($curauth->ID,'user_facebook',true);?>" target="_blank"><?php _e('Facebook','templatic');?> </a>
			<?php } ?>
		</p>
		<ul class="user_detail">
			<li><?php _e(get_user_meta($curauth->ID,'user_about',true)); ?></li> 
		</ul>
    </div>
 </div>
<ul class="sort_by">
    	<li class="title"> <?php echo LISTING_TEXT;?></li>
		<?php if($curauth->ID == $current_user->ID) { ?>
       	<li class="<?php if($_REQUEST['list']==''){ echo 'current'; } ?>"> <a href="<?php echo get_author_posts_url($curauth->ID);?>">  <?php echo MY_SUBMISSION;?> </a></li>
       	<li class="<?php if($_REQUEST['list']=='favourite'){ echo 'current'; } ?>"> <a href="<?php echo $user_link; ?>">  <?php echo MY_FAVOURITE_TEXT;?> </a></li>
		<?php } else { ?>
			<li class="<?php if($_REQUEST['list']==''){ echo 'current'; } ?>"> <a href="<?php echo get_author_posts_url($curauth->ID);?>">  <?php _e('Submissions','templatic');?> </a></li>
		<?php }?>
		
     </ul>
<?php 
$request = str_replace("post_type = 'post'","post_type in ('".CUSTOM_POST_TYPE1."','".CUSTOM_POST_TYPE2."')",$request); 
	get_template_part('loop');
	?>
	 <div class="pagination">       
       <span class="i_previous" > <?php previous_posts_link(__(PREVIEW_TITLE)) ?> </span>
       <span class="i_next" ><?php next_posts_link(__(NEXT_TITLE)) ?> </span>
	   <?php
	 if (function_exists('wp_pagenavi')) {
	 wp_pagenavi(); } 	
?></div>
<!--  CONTENT AREA END -->
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>