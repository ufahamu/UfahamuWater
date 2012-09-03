<?php get_header(); ?>
<?php if($_SERVER['HTTP_REFERER'] == '' || !strstr($_SERVER['HTTP_REFERER'],$_SERVER['REQUEST_URI'])) {
$viewed_count = get_post_meta($post->ID,'viewed_count',true);
$viewed_count_daily = get_post_meta($post->ID,'viewed_count_daily',true);
$daily_date = get_post_meta($post->ID,'daily_date',true);

update_post_meta($post->ID,'viewed_count',$viewed_count+1);

if(get_post_meta($post->ID,'daily_date',true) == date('Y-m-d')){
	update_post_meta($post->ID,'viewed_count_daily',$viewed_count_daily+1);
} else {
	update_post_meta($post->ID,'viewed_count_daily','1');
}
update_post_meta($post->ID,'daily_date',date('Y-m-d'));
}
?>
<div  class="<?php templ_content_css();?>" >
<?php if ( get_option( 'ptthemes_breadcrumbs' ) == 'Yes') { ?>
 <div class="breadcrumb clearfix"><div class="breadcrumb_in">
 <?php  
						
					yoast_breadcrumb('','');
 } ?>
  </div></div>
 <?php //templ_page_title_above(); //page title above action hook?>
  <div class="content-title"> <?php echo templ_page_title_filter(get_the_title()); //page title filter?>
      </div>
  <?php templ_page_title_below(); //page title below action hook?>



  <!--  CONTENT AREA START. -->
  <?php templ_before_single_entry(); // before single entry  hooks?>
  <?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
 
  <div class="entry">
    <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
      <div class="post-meta single_meta">
        <?php 					if(templ_is_show_post_author()){?>
        <?php echo By ;?> <span class="post-author"> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>">
        <?php the_author(); ?>
        </a> </span>
        <?php } ?>
        <?php if(templ_is_show_post_date()){?>
        <?php echo on ;?>
        <span class="post-date">
        <?php the_time(templ_get_date_format()) ?>
        </span> 
        <?php 	} 
			echo '<span class="post-total-view">'.VIEW_LIST_TEXT.user_post_visit_count($post->ID).'</span>';

			echo '<span class="post-daily-view">'.VIEW_LIST_TEXT_DAILY.user_post_visit_count_daily($post->ID).'</span>';
		
			if(templ_is_show_post_comment()){?>
        <?php 						comments_popup_link(__('No Comments','templatic'), __('1 Comment','templatic'), __('% Comments','templatic'), '', __('Comments closed','templatic')); ?>
        <?php 					} ?>
       </div>
      
       <?php templ_before_single_post_content(); // BEFORE  single post content  hooks?>
      <!--  Post Content Condition for Post Format-->
      <?php if ( has_post_format( 'chat' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }elseif(has_post_format( 'gallery' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }elseif(has_post_format( 'image' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }elseif(has_post_format( 'link' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }elseif(has_post_format( 'video' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }elseif(has_post_format( 'audio' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }elseif(has_post_format( 'quote' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }elseif(has_post_format( 'status' )){?>
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php }else{?>
      <div class="post-content">
        <script src="<?php bloginfo('template_directory'); ?>/js/galleria.js" type="text/javascript" ></script>
        <?php $post_images = bdw_get_images($post->ID,'large');?>
        <div id="galleria">
          <?php
                if(count($post_images)>0)
				{
					for($im=0;$im<count($post_images);$im++)
					{
					?>
          <div class="small" > <a href="<?php echo $post_images[$im];?>"> <img src="<?php echo $post_images[$im];?>" alt=""  /> </a> </div>
          <?php	
					}
				}
				?>
        </div>
        <?php the_content(); ?>
         <?php if(get_post_meta($post->ID,'video',true)){?>
            <div style="margin-bottom:20px;">
            <?php echo get_post_meta($post->ID,'video',true);?>
            </div>
         <?php }?>
        
        <div class="register_info">     
           <p><?php echo get_post_meta($post->ID,'reg_desc',true);?></p><?php if(get_post_meta($post->ID,'register_link',true) != '') { ?><p><a class="button" href="<?php echo get_post_meta($post->ID,'register_link',true);?>"><?php echo REGISTER_NOW;?></a></p> <?php } ?>
       
           	 <p><?php echo FEES_TEXT;?><span class="fees"><?php echo get_post_meta($post->ID,'reg_fees',true);?> </span></p>   
          
      		 </div> <!-- register info #end -->
             
             
             
           	  
            
        
        
      </div>
      <?php }?>
      <!--  Post Content Condition for Post Format-->
      <?php templ_after_single_post_content(); // after single post content hooks?>
      <!-- twitter & facebook likethis option-->
      <?php 
            templ_show_twitter_button();
            templ_show_facebook_button();
            
        ?>
<div class="googleplus">
	   <!-- Place this tag where you want the +1 button to render -->
		<div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div>
		<!-- Place this render call where appropriate -->
				<script type="text/javascript">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
	   </div>
	
     <div class="post_bottom">   
         <?php	if(templ_is_show_post_category()){	
			$taxonomy_category = get_the_taxonomies();
			$taxonomy_category = $taxonomy_category[CUSTOM_CATEGORY_TYPE2];
			$taxonomy_category = str_replace(CUSTOM_MENU_CAT_TITLE2.':','',$taxonomy_category);
			$taxonomy_category = str_replace(', and',',',$taxonomy_category);
			$taxonomy_category = str_replace(' and ',', ',$taxonomy_category);
			?>
        <?php _e('','templatic');  if ($taxonomy_category != ''){?>
        <span class="post-category"><?php echo $taxonomy_category; ?></span>
        <?php }
		} ?>
       
        <?php if(templ_is_show_post_tags()){?>
         <?php $taxonomy_tags = get_the_taxonomies();
		$taxonomy_tags = $taxonomy_tags[CUSTOM_TAG_TYPE2];
		$taxonomy_tags = str_replace(CUSTOM_MENU_TAG_LABEL.':','',$taxonomy_tags);
		$taxonomy_tags = str_replace(', and',',',$taxonomy_tags);
		$taxonomy_tags = str_replace(' and ',', ',$taxonomy_tags);
		 ?>
        <?php if ($taxonomy_tags != ''){?>
    
        <span class="post-tags"><?php echo $taxonomy_tags; ?></span>
        <?php } }?>
     </div> <!-- post bottom #end -->


    </div>
	<?php
            $prev_post = get_adjacent_post(false, '', true);
            $next_post = get_adjacent_post(false, '', false);
	if($prev_post != '' || $next_post != '') {?>
    <div class="post-navigation clear">
      
      <?php if ($prev_post) : $prev_post_url = get_permalink($prev_post->ID); $prev_post_title = PREVIOUS."<br/>".$prev_post->post_title; ?>
      <a class="post-prev" href="<?php echo $prev_post_url; ?>"><?php echo PREVIOUS; ?> </a>
      <?php endif; ?>
      <?php if ($next_post) : $next_post_url = get_permalink($next_post->ID); $next_post_title = NEXT."<br/>".$next_post->post_title; ?>
      <a class="post-next" href="<?php echo $next_post_url; ?>">
      <?php echo NEXT;?>
      </a>
      <?php endif; ?>
    </div>
	<?php } ?>
  </div>
 <?php 
 if (function_exists('dynamic_sidebar')){ dynamic_sidebar('event_detail_content_banner'); }
 
  $city_id = get_post_meta($post->ID,'post_city_id',true); 
  if(get_option('ptthemes_related_listing') == 'Yes') {
	get_related_posts($post,CUSTOM_POST_TYPE2,CUSTOM_TAG_TYPE2,CUSTOM_CATEGORY_TYPE2,$city_id); 
  }
  endwhile; 
  endif;
  templ_after_single_entry(); // after single entry  hooks
  if (function_exists('dynamic_sidebar')){ dynamic_sidebar('single_post_below'); }
  comments_template(); ?>
  <script type="text/javascript">
var $cg = jQuery.noConflict();
// Load theme
Galleria.loadTheme('<?php bloginfo('template_directory'); ?>/js/galleria.classic.js');
// run galleria and add some options
    $cg('#galleria').galleria({
        image_crop: true, // crop all images to fit
        thumb_crop: true, // crop all thumbnails to fit
        transition: 'fade', // crossfade photos
        transition_speed: 700, // slow down the crossfade
		autoplay: <?php if(get_option('ptthemes_photo_gallery') == 'Yes'){ echo 'true';} else { echo 'false';}?>,
        data_config: function(img) {
            // will extract and return image captions from the source:
            return  {
                title: $cg(img).parent().next('strong').html(),
                description: $cg(img).parent().next('strong').next().html()
            };
        },
        extend: function() {
            this.bind(Galleria.IMAGE, function(e) {
                // bind a click event to the active image
                $cg(e.imageTarget).css('cursor','pointer').click(this.proxy(function() {
                    // open the image in a lightbox
                    this.openLightbox();
                }));
            });
        }
    });
</script>
  <!--  CONTENT AREA END -->
</div>
<?php include_once ('library/includes/sidebar_event_detail.php'); ?>
<?php get_footer(); ?>
