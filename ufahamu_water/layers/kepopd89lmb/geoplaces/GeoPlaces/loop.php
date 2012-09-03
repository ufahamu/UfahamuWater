<?php templ_before_loop(); // before loop hooks?>
<div id="loop" class="list clear">
<?php
global $wp_query,$current_user;
if(isset($_GET['author_name'])) :
	$curauth = get_userdatabylogin($author_name);
else :
	$curauth = get_userdata(intval($author));
endif;


// have some posts?
if (have_posts()) :
	while (have_posts()) : the_post(); // begin the Loop
	$pcount++;
	$post_images = bdw_get_images($post->ID,'thumb');
	$post_images1 = bdw_get_images($post->ID,'thumb');
	if( $post_images){

	 $post_images = templ_thumbimage_filter($post_images[0],'&amp;w=158&amp;h=105&amp;zc=1&amp;q=80',1);

	}else{ 
	 $post_images =  templ_thumbimage_filter($post_images[0],'&amp;w=158&amp;h=105&amp;zc=1&amp;q=80',1);
	}	?>
   <div <?php if(get_post_meta($post->ID,'is_featured',true) == 1 && (get_post_meta($post->ID,'featured_type',true) =="c" || get_post_meta($post->ID,'featured_type',true) =="both" || get_post_meta($post->ID,'featured_type',true) =="h")){ post_class('post featured_post');} else { post_class('post');}?> id="post_<?php the_ID(); ?>">
    <!--  Post Content Condition for Post Format-->
	
   <div class="post-content">	
   <?php if ( has_post_format( 'chat' )){?>
      <?php the_excerpt();?>
    <?php }elseif(has_post_format( 'gallery' )){?>
       <?php 
           	if($post_images){ ?>
					<a  class="post_img" href="<?php the_permalink(); ?>"> <img  src="<?php echo $post_images;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
<?php 			}else{ ?>
						<a class="img_no_available" href="<?php the_permalink(); ?>"> <?php echo IMAGE_NOT_AVAILABLE_TEXT;?> </a>

<?php } 

     }elseif(has_post_format( 'image' )){?>

      <?php 
           	if($post_images){ ?>
					<a  class="post_img" href="<?php the_permalink(); ?>"> <img src="<?php echo $post_images;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
<?php 			}else{ ?>
						<a class="img_no_available" href="<?php the_permalink(); ?>"> <?php echo IMAGE_NOT_AVAILABLE_TEXT;?> </a>

<?php } ?>
			
      <?php the_excerpt()?>

     <?php }elseif(has_post_format( 'link' )){?>

      <?php the_excerpt();?>
 
     <?php }elseif(has_post_format( 'video' )){?>

      <?php the_excerpt();?>

     <?php }elseif(has_post_format( 'audio' )){?>
      <?php the_excerpt();?>
      
     <?php }elseif(has_post_format( 'quote' )){?> 
      <?php the_excerpt();?>

      
     <?php }elseif(has_post_format( 'status' )){?> 

      <?php the_excerpt();?>
      
      <?php }else{?>
	   <?php if(get_post_meta($post->ID,'is_featured',true) == 1 && (get_post_meta($post->ID,'featured_type',true) =="c" || get_post_meta($post->ID,'featured_type',true) =="both" || get_post_meta($post->ID,'featured_type',true) =="h")){ ?> <span class="featured_img"><?php _e('featured','templatic');?></span> <?php } ?>
      <?php if(get_the_post_thumbnail( $post->ID)){ ?>
			<a href="<?php the_permalink(); ?>" class="post_img"> <?php echo get_the_post_thumbnail( $post->ID, array(150,150),array('class'	=> "post_img",));?> </a>
      <?php } elseif($post_images){ 

           	if($post_images1[0]){ ?>
					<a  class="post_img" href="<?php the_permalink(); ?>"> <img  src="<?php echo $post_images;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
<?php 			}else{ ?>
						<a class="img_no_available" href="<?php the_permalink(); ?>"> <?php echo IMAGE_NOT_AVAILABLE_TEXT;?> </a>

<?php } 
            }else { ?>
		<a class="img_no_available" href="<?php the_permalink(); ?>"> <?php echo IMAGE_NOT_AVAILABLE_TEXT;?> </a>
		
		<?php } 
		if($post->post_type == 'post')
		  {
			if(has_post_format( 'chat' ))
				{
		?>
        	<h2>
             <a href="<?php the_permalink() ?>">
      			<?php the_title(); ?>
           	 </a>
           </h2>
         <?php }
	  elseif(has_post_format('gallery')){?>
      <h2 ><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'image' )){?>
       <h2 class="blog_title"><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'link' )){?>
       <h2 ><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'video' )){?>
       <h2 ><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'audio' )){?>
       <h2 ><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
       <?php }else{?>
       <h2 ><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
       <?php }
    }
	
	if($post->post_type != "post"){	?>
<!--  Post Title Condition for Post Format-->
    <?php if (has_post_format( 'chat' )){?>
    <h2 ><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
    <?php }elseif(has_post_format('gallery')){?>
      <h2 ><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
    <?php }elseif(has_post_format( 'image' )){?>
       <h2 class="blog_title"><a href="<?php the_permalink() ?>">
    <?php the_title(); ?>
      </a></h2>
    <?php }elseif(has_post_format( 'link' )){?>
       <h2 ><a href="<?php the_permalink() ?>">
    <?php the_title(); ?>
      </a></h2>
    <?php }elseif(has_post_format( 'video' )){?>
       <h2 ><a href="<?php the_permalink() ?>">
    <?php the_title(); ?>
      </a></h2>
    <?php }elseif(has_post_format( 'audio' )){?>
       <h2 ><a href="<?php the_permalink() ?>">
    <?php the_title(); ?>
      </a></h2>
    <?php }else{?>
       <h2 ><a href="<?php the_permalink() ?>">
    <?php the_title(); ?>
      </a></h2>
    <?php }}?>
     <!--  Post Title Condition for Post Format-->
       
    <div class="post-meta listing_meta">
      <?php if(templ_is_show_listing_author()){ ?>
      <?php echo By;?> <span class="post-author"> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>">
      <?php the_author(); ?>
      </a> </span>
      <?php }  ?>
      <?php if(templ_is_show_listing_date()){?>
      <?php echo on;?>  <span class="post-date">
      <?php the_time(templ_get_date_format()) ?>
      </span>
      <?php } 
 			echo '<span class="post-total-view"> '.VIEW_LIST_TEXT.user_post_visit_count($post->ID).'</span>';
 			echo '<span class="post-daily-view"> '.VIEW_LIST_TEXT_DAILY.user_post_visit_count_daily($post->ID).'</span>';

	  
      if(get_post_format( $post->ID )){
		$format = get_post_format( $post->ID );
		?>
      <em>&bull; </em>
	
      <a href="<?php echo get_post_format_link($format); ?>" title="<?php esc_attr_e( VIEW_TEXT . $format, 'templatic' ); ?>"><?php _e( MORE_TEXT . $format, 'templatic' ); ?></a>
      <?php } ?>
    </div>
    <?php templ_before_loop_post_content(); // before loop post content hooks?>

      	<div class="post_right">
		 <?php if(templ_is_show_listing_comment()){?>
					<a href="<?php the_permalink(); ?>#commentarea" class="pcomments" ><?php comments_number(__('0 review'), __('1 review'), __('% review')); ?> </a> 	
				<?php } ?>	
				<?php  if($post->post_type != 'post' && get_option('ptthemes_disable_rating') == 'no') {  ?>
				
				<span class="rating"><?php echo get_post_rating_star($post->ID);?></span><?php } ?>
				<?php if(get_post_meta($post->ID,'geo_address',true) != '' && !is_search() && !is_author()) { ?>
					<a href="#map_canvas"  class="ping" id="pinpoint_<?php echo $post->ID; ?>"><?php _e('Pinpoint');?></a> <?php } ?>
					<?php favourite_html($post->post_author,$post->ID); ?>
					 <?php
	   if(is_author() && ($current_user->ID == $curauth->ID || $current_user->ID == 1)) { ?>
        <span class="author_link"> 
        <?php if($_REQUEST['list'] !=='favourite'){
	    if(get_time_difference($post->post_date, $post->ID ))
		{
		?>
        <a href="<?php echo site_url();?>/?ptype=<?php if($post->post_type=='event'){echo 'post_event';}else{echo 'post_listing';}?>&pid=<?php echo $post->ID;?>"><?php echo EDIT_TEXT;?></a> | 
        <?php	
		}else
		{
		?>
        <a href="<?php echo site_url();?>/?ptype=<?php if($post->post_type=='event'){echo 'post_event';}else{echo 'post_listing';}?>&renew=1&pid=<?php echo $post->ID;?>"><?php echo RENEW_TEXT;?></a> |
		<?php
		}
		
		?>        
        <a href="<?php echo site_url();?>/?ptype=<?php if($post->post_type=='event'){echo 'preview_event';}else{echo __('preview');}?>&pid=<?php echo $post->ID;?>"><?php echo DELETE_TEXT;?></a> 
        </span>  
        <?php }
		}?>
                   
		</div>
			
		<?php echo excerpt(get_option('ptthemes_content_excerpt_count'));//the_excerpt();?>
      <div class="post_bottom list_bottom">
        	 <?php if(templ_is_show_post_tags()){?>
           <?php the_tags('<span class="post-tags">', ', ', '</span>'); ?>
          <?php } ?>
          <?php if(templ_is_show_post_category()){?>
          	<span class="post-category" ><?php the_category(' <span>/</span> '); ?></span>
          <?php } ?>
        </div>

      <?php }?> 
	</div>
    <!--  Post Content Condition for Post Format-->
	
     <?php  templ_after_loop_post_content(); // after loop post content hooks?>
  </div>
        <?php 
		$page_layout = templ_get_page_layout();
		if($page_layout=='full_width'){
					if($pcount==3){
					$pcount=0; 
					?>
                 		<div class="hr clearfix"></div>
                <?php } }
				else if(($page_layout=='3_col_fix' ) || ($page_layout=='3_col_right') ||( $page_layout=='3_col_left')){
					if($pcount==2){
					$pcount=0; 
					?>
                 		<div class="hr clearfix"></div>
                <?php }
				}
				else if ($pcount==2){
					$pcount=0; 
					?>
                <div class="hr clearfix"></div>
                <?php }
				?>
        
  <?php endwhile; ?>
 
<?php else : 
if(is_author()) { ?>
<h4><?php echo LISTING_NOT_AVAIL_MSG;?> </h4>
 <?php 
} else {
?>	
<?php get_search_form(); ?>
<p><?php echo SEARCH_RESULT_NOT_FOUND; ?></p>
<?php } ?>
<?php endif; ?>

<?php wp_reset_query(); // reset the query ?>
</div>
<?php templ_after_loop(); // after loop hooks?>