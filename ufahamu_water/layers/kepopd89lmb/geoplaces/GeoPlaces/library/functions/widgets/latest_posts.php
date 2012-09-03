<?php
// ===============================  Latest Posts - widget ======================================
if(!class_exists('templ_latest_posts_with_images')){
	class templ_latest_posts_with_images extends WP_Widget {
	
		function templ_latest_posts_with_images() {
		//Constructor
		global $thumb_url;
			$widget_ops = array('classname' => 'widget special', 'description' => apply_filters('templ_latestpost_with_img_widget_desc_filter',__('Post with image and date','templatic')) );
			$this->WP_Widget('latest_posts_with_images',apply_filters('templ_latestpost_with_img_widget_title_filter',__('T &rarr; Post with image and date','templatic')), $widget_ops);
		}
	 
		function widget($args, $instance) {
		// prints the widget
	
			extract($args, EXTR_SKIP);
	 
			echo $before_widget;
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$category = empty($instance['category']) ? '' : apply_filters('widget_category', $instance['category']);
			$number = empty($instance['number']) ? '5' : apply_filters('widget_number', $instance['number']);
			$my_post_type = empty($instance['post_type']) ? 'post' : apply_filters('widget_post_type', $instance['post_type']);
			global $post,$wpdb;
			
					if($category)
					{
						//$arg = "&cat=$category";	
						$arg = "AND  $wpdb->posts.ID in (select tr.object_id from $wpdb->term_relationships tr join $wpdb->term_taxonomy t on t.term_taxonomy_id=tr.term_taxonomy_id where t.term_id in ($category)  )";
					}
		
				$multi_city_id = $_SESSION['multi_city'];
				if($my_post_type !="post"){
				$temp_city = "AND ($wpdb->postmeta.meta_key = 'post_city_id' 
				AND ($wpdb->postmeta.meta_value LIKE '%,".$multi_city_id.",%' OR $wpdb->postmeta.meta_value LIKE '%".$multi_city_id.",%' OR $wpdb->postmeta.meta_value LIKE '%,".$multi_city_id."%' OR $wpdb->postmeta.meta_value LIKE '%".$multi_city_id."%'))";
				}	
     			$today_special = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.ID ,$wpdb->posts.*
				FROM $wpdb->posts, $wpdb->postmeta
				WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
				$temp_city
				AND $wpdb->posts.post_status = 'publish' 
				AND $wpdb->posts.post_type = '".$my_post_type."'
				$arg 
				ORDER BY $wpdb->posts.post_date DESC LIMIT 0,$number");  
			
						
			if($today_special) {

			?>
			
		 <?php if($title){?> <h3 class="i_publication"><?php _e($title,'templatic');?></h3> <?php }?>
					<ul class="latest_posts"> 
			 <?php 
					foreach($today_special as $post) :
					setup_postdata($post);
					 ?>
			<?php $post_images = bdw_get_images($post->ID); ?>	
			<li>
         
        
			 <?php 
				if(get_the_post_thumbnail( $post->ID)){?>
				 <a  class="post_img"  href="<?php the_permalink(); ?>">
				 <?php echo get_the_post_thumbnail( $post->ID, array(50,50),array('class'	=> "",));?>
				 </a>
				<?php }elseif($post_images = bdw_get_images($post->ID)){ ?>
				 <a  class="post_img" href="<?php the_permalink(); ?>">
				 <img  src="<?php echo templ_thumbimage_filter($post_images[0],'&amp;w=50&amp;h=50&amp;zc=1&amp;q=80',1);?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
				<?php
				}?>
						
				<h4> <a class="widget-title" href="<?php the_permalink(); ?>">
					  <?php the_title(); ?>
					  </a> <span class="post_author"><?php _e('by','templatic');?> <?php the_author_posts_link(); ?> <?php _e('at','templatic');?> <?php the_time(templ_get_date_format()) ?> / <?php comments_popup_link(__('No Comments','templatic'), __('1 Comment','templatic'), __('% Comments','templatic'), '', __('Comments Closed','templatic')); ?> </span></h4> 
					  
					  <p> <?php echo bm_better_excerpt(175, ''); ?> <a href="<?php the_permalink(); ?>"> <?php _e('more...','templatic');?> </a></p> 
			</li>
	<?php endforeach; ?>
				</ul>
		
	<?php
		}
			echo $after_widget;
		}
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['category'] = strip_tags($new_instance['category']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['post_type'] = strip_tags($new_instance['post_type']);
			return $instance;
		}
	 
		function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'category' => '', 'number' => '' ) );
			$title = strip_tags($instance['title']);
			$category = strip_tags($instance['category']);
			$number = strip_tags($instance['number']);
			$my_post_type = strip_tags($instance['post_type']);
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','templatic');?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
	
	<p>
	  <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','templatic');?>
	  <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo attribute_escape($number); ?>" />
	  </label>
	</p>
    <p>
<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post Type:','templatic')?>
<select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
<?php
$custom_post_types_args = array();  
$custom_post_types = get_post_types($custom_post_types_args,'objects');   
foreach ($custom_post_types as $content_type) {
if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page'){
?>
<option value="<?php _e($content_type->name);?>" <?php if(attribute_escape($my_post_type)==$content_type->name){ echo 'selected="selected"';}?>><?php _e($content_type->label);?></option>
<?php }}?>
</select>
</label>
</p>
	<p>
	  <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Categories (<code>IDs</code> separated by commas):','templatic');?>
	  <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo attribute_escape($category); ?>" />
	  </label>
	</p>
	<?php
		}
	}
	register_widget('templ_latest_posts_with_images');
}
?>