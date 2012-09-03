<?php 
global $wpdb,$post;
if(!isset($_REQUEST['etype'])) {
			$_REQUEST['etype'] == 'all';
}
get_header(); ?>
<?php 
if(get_option('ptthemes_category_map_event') == 'yes' || get_option('ptthemes_category_map_event') == ''){
	if(file_exists(TEMPLATEPATH . '/library/map/category_listing_map.php')){
		include_once (TEMPLATEPATH . '/library/map/category_listing_map.php');
	}
	$map_display_category = 'no';
	global $map_display_category;
}  else {
	$map_display_category = 'yes';
	global $map_display_category;
}
	global $wp_query, $post;
	$current_term = $wp_query->get_queried_object();	
	$category_link = get_term_link( $current_term->slug, CUSTOM_CATEGORY_TYPE2 );
	if( $current_term->name){
		$ptitle = $current_term->name; 
	}	?>
<?php templ_page_title_above(); //page title above action hook?>
<div  class="<?php templ_content_css();?>" >
 <div class="breadcrumb clearfix">
				<?php if ( get_option( 'ptthemes_breadcrumbs' )) {  ?>
                	<div class="breadcrumb_in"><?php yoast_breadcrumb('','');  ?></div>
            <?php } ?>
            </div>
<div class="content-title">
  <?php 
		echo templ_page_title_filter($ptitle); //page tilte filter
	?>
</div>

<?php if (category_description( $category_id ) != null) {?> <div class="cat_desc"><?php echo _e(category_description(),'templatic'); ?> </div><?php } ?>

<?php templ_page_title_below(); //page title below action hook ?>
<?php
 if(templ_is_show_post_category()) {
	
	} 
$deptID = $current_term->term_id;
if(isset($deptID) && $deptID !=""){
$childCatID = $wpdb->get_col("SELECT term_id FROM $wpdb->term_taxonomy WHERE parent=$deptID");
if ($childCatID){
	echo '<div class="subcate_list">';
	foreach ($childCatID as $kid) {
		$childCatName = $wpdb->get_row("SELECT name, term_id,slug FROM $wpdb->terms WHERE term_id=$kid");
		$cat_link = get_term_link($childCatName->slug, CUSTOM_CATEGORY_TYPE2);
		?>
		<a href="<?php echo $cat_link; ?>"><?php echo $childCatName->name; ?></a>
		<?php
	}
	echo '</div>';
}
}
?>
<ul class="sort_by">
    	<li class="title"> <?php _e('Events');?> :</li>
       <?php
	 
	    $category_link =  get_term_link( $term, $taxonomy );?>
			<li class="<?php if($_REQUEST['etype']==''){ echo 'current'; }?>"> <a href="<?php echo $category_link;?>">  <?php _e('All');?> </a></li>
			<li class="<?php if($_REQUEST['etype']=='upcoming'){ echo 'current';}?>"> <a href="<?php if(strstr($category_link,'?')){ echo $cat_url = $category_link."&amp;etype=upcoming";}else{ echo $cat_url = $category_link."?etype=upcoming";}?>">  <?php _e('Upcoming');?> </a></li>
			<li class="<?php if($_REQUEST['etype']=='past'){ echo 'current';}?>"> <a href="<?php if(strstr($category_link,'?')){ echo $cat_url = $category_link."&amp;etype=past";}else{ echo $cat_url = $category_link."?etype=past";}?>">  <?php _e('Past');?> </a></li>
         
           <li class="i_next"> <?php next_posts_link(__(NEXT_TITLE)) ?>  </li>
            <li class="i_previous"><?php previous_posts_link(__(PREVIOUS)) ?> </li>
     </ul>
<?php templ_before_loop(); // before loop hooks
global $wp_query; 
?>
<?php if ( have_posts() ) : ?>
<div id="loop" class="<?php if (get_option('ptthemes_cat_listing')=='Grid'){ echo 'grid'; }  else { echo 'list clear work_list'; }?> ">

<?php 
	$pcount=0; 
	while ( have_posts() ) : the_post(); 
	$pcount++;	
	$post_images = bdw_get_images($post->ID,'thumb');
	$post_images1 = bdw_get_images($post->ID,'thumb');
	if( $post_images){

	 $post_images = templ_thumbimage_filter($post_images[0],'&amp;w=158&amp;h=105&amp;zc=1&amp;q=80',1);

	}else{ 
	 $post_images =  templ_thumbimage_filter($post_images[0],'&amp;w=158&amp;h=105&amp;zc=1&amp;q=80',1);
	}
	?>
  <div  id="post_<?php the_ID(); ?>" <?php if(get_post_meta($post->ID,'is_featured',true) == 1 && (get_post_meta($post->ID,'featured_type',true) =="c" || get_post_meta($post->ID,'featured_type',true) =="both" )){ post_class('post featured_post');} else { post_class('post');}?>>
  
    <?php templ_before_loop_post_content(); // before loop post content hooks?>
    
    <!--  Post Content Condition for Post Format-->
    <?php if ( has_post_format( 'chat' )){?>
    
    <div class="post-content">
      <?php the_excerpt()?>
    </div>
    
    <?php }elseif(has_post_format( 'gallery' )){?>
    
    <div class="post-content">
	
    <?php 			
	
	if(get_post_meta($post->ID,'is_featured',true) == 1 && (get_post_meta($post->ID,'featured_type',true) =="c" || get_post_meta($post->ID,'featured_type',true) =="both" )){ ?> <span class="featured_strip"></span> <?php } 
      	if($post_images1[0]){ ?>
					<a  class="post_img" href="<?php the_permalink(); ?>"> <img src="<?php echo $post_images;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
<?php 			}else{ ?>
						<a class="img_no_available" href="<?php the_permalink(); ?>"> <?php echo IMAGE_NOT_AVAILABLE_TEXT;?> </a>

<?php } ?>
    </div>
    
     <?php }elseif(has_post_format( 'image' )){?>
     
     <div class="post-content">
      <?php 
           	if($post_images1[0]){ ?>
					<a  class="post_img" href="<?php the_permalink(); ?>"> <img src="<?php echo $post_images;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
<?php 			}else{ ?>
						<a class="img_no_available" href="<?php the_permalink(); ?>"> <?php echo IMAGE_NOT_AVAILABLE_TEXT;?> </a>

<?php } ?>
      <?php the_excerpt()?>
    </div>
    
     <?php }elseif(has_post_format( 'link' )){?>
     
      <div class="post-content">
      <?php the_excerpt()?>
      </div>
      
     <?php }elseif(has_post_format( 'video' )){?>
     
     <div class="post-content">
      <?php the_excerpt()?>
      </div>
      
     <?php }elseif(has_post_format( 'audio' )){?>
     
     <div class="post-content">
     
      <?php the_excerpt()?>
      </div>
      
     <?php }elseif(has_post_format( 'quote' )){?> 
     
     <div class="post-content">
      <?php the_excerpt()?>
      </div>
      
     <?php }elseif(has_post_format( 'status' )){?> 
     
     <div class="post-content">
      <?php the_excerpt()?>
      </div>
      
      <?php }else{?>
      
       <div class="post-content ">
	   <?php if(get_post_meta($post->ID,'is_featured',true) == 1 && (get_post_meta($post->ID,'featured_type',true) =="c" || get_post_meta($post->ID,'featured_type',true) =="both")){?>
       <span class="featured_img"><?php _e('featured','templatic');?></span>
	   <?php } 
		if($post_images1[0]){ ?>
					<a  class="post_img" href="<?php the_permalink(); ?>"> <img src="<?php echo $post_images;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
<?php 			}else{ ?>
						<a class="img_no_available" href="<?php the_permalink(); ?>"> <?php echo IMAGE_NOT_AVAILABLE_TEXT;?> </a>

<?php } ?>
            
            <div class="post_content">
         			<!--  Post Title Condition for Post Format-->
    <?php if ( has_post_format( 'chat' )){?>
    <h2><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'gallery' )){?>
      <h2><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'image' )){?>
       <h2><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'link' )){?>
       <h2><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'video' )){?>
       <h2><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
     <?php }elseif(has_post_format( 'audio' )){?>
       <h2><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
       <?php }else{?>
       <h2><a href="<?php the_permalink() ?>">
      <?php the_title(); ?>
      </a></h2>
       <?php }?>
     <!--  Post Title Condition for Post Format-->
	 
	 
		<?php
				if (get_option('ptthemes_cat_listing')=='Listing'){ ?>
					<div class="post_right">
					<a href="<?php the_permalink(); ?>#commentarea" class="pcomments" ><?php comments_number(__('0 '.REVIEW.''), __('1 '.REVIEW.''), __('% '.REVIEW.'')); ?> </a>
					<?php if(get_option('ptthemes_disable_rating') == 'no') { 	?>	
					<span class="rating"><?php echo get_post_rating_star($post->ID);?></span>
					<?php }				
							if(get_post_meta($post->ID,'geo_address',true) != '') { ?>
							<a href="#map_canvas"  class="ping" id="pinpoint_<?php echo $post->ID; ?>"><?php _e('Pinpoint');?></a> <?php } ?>
							<?php favourite_html($post->post_author,$post->ID); ?>
                   
				</div>
				<p class="timing"> <span><?php _e('Date :','templatic');?></span> <?php echo date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));?><br> <span><?php _e('Timing :','templatic');?></span> <?php echo get_post_meta($post->ID,'st_time',true).' to '.get_post_meta($post->ID,'end_time',true);?> </p>
				
				
				
                <?php if(get_post_meta($post->ID,'geo_address',true) != '') { echo '<p class="address"><span>'.LOCATION.'</span> '.get_post_meta($post->ID,'geo_address',true).'</p>'; } ?>
				<?php echo get_post_custom_for_listing_page($post->ID,'<p><span>{#TITLE#} : </span>{#VALUE#}</p>','',CUSTOM_POST_TYPE2);?>
				<?php  echo '<p>';
					echo excerpt(get_option('ptthemes_content_excerpt_count'));
					  echo '</p>'; ?>
				<?php }
				else{ ?>
					
					<p class="timing"> <span><?php _e('Date :','templatic');?></span> <?php echo date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));?><br> <span><?php _e('Timing :','templatic');?></span> <?php echo get_post_meta($post->ID,'st_time',true).' to '.get_post_meta($post->ID,'end_time',true);?> </p>
					<span class="rating"><?php echo get_post_rating_star($post->ID);?></span>
					
                   
				<?php if(get_post_meta($post->ID,'geo_address',true) != '') { echo '<p class="address"><span>'.LOCATION.'</span> '.get_post_meta($post->ID,'geo_address',true).'</p>'; } ?>
				<?php echo get_post_custom_for_listing_page($post->ID,'<p><span>{#TITLE#} : </span>{#VALUE#}</p>','',CUSTOM_POST_TYPE2);?>
				<?php echo '<p>';
							echo excerpt(get_option('ptthemes_content_excerpt_count'));
					  echo '</p>';
						
				?>
				<?php
					
						?>
						 <?php if(get_post_meta($post->ID,'geo_address',true) != '') { ?>
							<span class="ping"><a href="#map_canvas" id="pinpoint_<?php echo $post->ID; ?>"><?php _e('Pinpoint');?></a></span> <?php } ?>
						<?php favourite_html($post->post_author,$post->ID); ?>
						<p class="review clearfix">    
							<a href="<?php the_permalink(); ?>#commentarea" class="pcomments" ><?php comments_number(__('0'), __('1'), __('%')); ?> </a>
							<span class="readmore"> <a href="<?php the_permalink(); ?>"><?php echo READ_MORE_LABEL; ?> </a> </span>
						</p>
				
				<?php
				}
				
				?>
	 
        </div>
		 
		 
     </div>
      <?php }?>  
    <!--  Post Content Condition for Post Format-->
     <?php templ_after_loop_post_content(); // after loop post content hooks?>
  </div>
        <?php 
		$page_layout = templ_get_page_layout();
		if($page_layout=='full_width'){
					if($pcount==4){
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
				else if ($pcount==3){
					$pcount=0; 
					?>
                <div class="hr clearfix"></div>
                <?php }?>
        
  <?php endwhile; ?>
</div>
<?php else : ?>	
<?php echo NO_EVENT_TEXT;?>
<?php endif; ?>
  <?php 
  if ($current_term->count > get_option('posts_per_page')) { ?>
    <?php
  function get_pagination($targetpage,$total_pages,$limit=10,$page=0,$extra_url = '')
		{ 
			/* Setup page vars for display. */
			if ($page == 0) $page = 1;					//if no page var is given, default to 1.
			$prev = $page - 1;							//previous page is page - 1
			$next = $page + 1;							//next page is page + 1
			$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
			$lpm1 = $lastpage - 1;						//last page minus 1
			
			if(strstr($targetpage,'?'))
			{
				$querystr = "&amp;paged";
			}else
			{
				$querystr = "?paged";
			}
			$pagination = "";
			if($lastpage > 1)
			{	
				
				//previous button
				if ($page > 1) 
					$pagination.= '<a class="previouspostslink" href="'.$targetpage.$querystr.'='.$prev.$extra_url.'">'.PREVIOUS.'</a>';
				//else
					//$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	
				$pagination .= "<div class=\"Navi\">";
				//pages	
				if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
				{	
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"on\">$counter</span>";
						else
							$pagination.= '<a href="'.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
					}
				}
				elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
				{
					//close to beginning; only hide later pages
					if($page < 1 + ($adjacents * 2))		
					{
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"on\">$counter</span>";
							else
								$pagination.= '<a href="'.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
						}
						$pagination.= "...";
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lpm1.$extra_url.'">'.$lpm1.'</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lastpage.$extra_url.'">'.$lastpage.'</a>';		
					}
					//in middle; hide some front and some back
					elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
					{
						$pagination.= '<a href="'.$targetpage.$querystr.'=1'.$extra_url.'">1</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'=2'.$extra_url.'">2</a>';
						$pagination.= "...";
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"on\">$counter</span>";
							else
								$pagination.= '<a href='.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
						}
						$pagination.= "...";
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lpm1.$extra_url.'">'.$lpm1.'</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lastpage.$extra_url.'">'.$lastpage.'</a>';		
					}
					//close to end; only hide early pages
					else
					{
						$pagination.= '<a href="'.$targetpage.$querystr.'=1'.$extra_url.'">1</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'=2'.$extra_url.'">2</a>';
						$pagination.= "...";
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"on\">$counter</span>";
							else
								$pagination.= '<a href="'.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
						}
					}
				}
				$pagination.= "</div>\n";	
				//next button
				if ($page < $counter - 1) 
					$pagination.= '<a class="nextpostslink" href="'.$targetpage.$querystr.'='.$next.$extra_url.'">'.NEXT_TITLE.'</a>';
				//else
					//$pagination.= "<span class=\"disabled\">next &raquo;</span>";
					
			}
			return $pagination;
		}
			$targetpage = site_url("/?eventcategory=".$current_term->slug);
			
			$postmeta_db_table_name = $wpdb->prefix . "postmeta";
			$post_db_table_name = $wpdb->prefix . "posts";
			if($current_term->term_id != '') {
				$sqlsql = "and p.ID = pm.post_id and pm.meta_key= 'post_city_id' and (pm.meta_value = '".$_SESSION['multi_city']."' OR pm.meta_value LIKE '%,".$_SESSION['multi_city']."' OR pm.meta_value LIKE '%,".$_SESSION['multi_city'].",%' OR pm.meta_value LIKE '".$_SESSION['multi_city'].",%') and p.ID in (select tr.object_id from $wpdb->term_relationships tr join $wpdb->term_taxonomy t on t.term_taxonomy_id=tr.term_taxonomy_id where t.term_id in ($current_term->term_id)  )";
			}
			
			//$dealcnt = "select * from $post_db_table_name  where $sqlsql and post_status = 'publish' ORDER BY ID DESC ";
		
			$dealcnt = "select * from $post_db_table_name p,$postmeta_db_table_name pm where p.post_type = 'event' and p.post_status = 'publish' $sqlsql";
			$all_total_pages =  count($wpdb->get_results($dealcnt));
			$recordsperpage = get_option('posts_per_page');
			$all_pagination = $_REQUEST['paged'];
			if($all_pagination == '') {
				$all_pagination = 1;
			}
			$strtlimit = ($all_pagination-1)*$recordsperpage;
			$endlimit = $strtlimit+$recordsperpage;
			$dealcnt_sql = $wpdb->get_results($dealcnt." limit $strtlimit,$recordsperpage ");
			if($all_total_pages>$recordsperpage)
			{
				echo '<div  class="pagination" >'.get_pagination($targetpage,$all_total_pages,$recordsperpage,$all_pagination).'</div>';
			}
  ?>
  
  <?php 
  if(is_home())
  {
	$request = str_replace("post_type = 'post'","post_type = '".CUSTOM_POST_TYPE2."'",$request);
  	get_template_part('pagination');
  }
   ?>
     <?php } ?> 
<?php templ_after_loop(); // after loop hooks?>


<!--  CONTENT AREA END -->
</div>
<?php include_once ('library/includes/sidebar_event_listing.php'); ?>
<?php get_footer(); ?>