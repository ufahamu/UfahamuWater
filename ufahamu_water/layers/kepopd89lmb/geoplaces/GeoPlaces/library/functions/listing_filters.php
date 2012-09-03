<?php
if(strstr($_SERVER['REQUEST_URI'],'/wp-admin/'))
{
	remove_filter('posts_where', 'search_cal_event_where');
	remove_filter('posts_orderby', 'searching_filter_orderby');
	remove_filter('posts_join', 'searching_filter_join');
	remove_filter('posts_where', 'searching_filter_where');
	remove_filter('posts_orderby', 'review_highest_orderby');
	remove_filter('posts_orderby', 'ratings_most_orderby');
	remove_filter('posts_orderby', 'archive_filter_orderby');
}
if(is_author()){
add_action('pre_get_posts', 'custom_post_author_archive');
}
add_action('pre_get_posts', 'search_filter');
function search_filter($local_wp_query) {
			if(isset($_REQUEST['sn']) && $_REQUEST['sn'] !=""){
	$sn = $_REQUEST['sn']; }
	if((isset($_REQUEST['sn']) && $_REQUEST['sn'] != "") || (isset($_REQUEST['s']) && $_REQUEST['s'] != "") || isset($_REQUEST['sort']) || isset($_REQUEST['etype']) || isset($_REQUEST['list']) || is_author()) { 
		if(!strstr($_SERVER['REQUEST_URI'],'/wp-admin/') && is_search() ){
			if($_REQUEST['as'] !=''){
			add_filter('posts_where', 'searching_filter_where');
			}
			if($_REQUEST['s']=='cal_event'){
				add_filter('posts_where', 'search_cal_event_where');
			} else if(isset($_REQUEST['t']) && $_REQUEST['t'] != "") {
				add_filter('posts_orderby', 'searching_filter_orderby');
			}
		}else if(is_author())	{ 
			global $current_user,$wp_query;
			$qvar = $wp_query->query_vars;
			$authname = $qvar['author_name'];
			$nicename = $current_user->user_nicename;
			if(($authname == $nicename) || ($_REQUEST['author']== $current_user->ID))
			{	
				add_filter('posts_where', 'author_filter_where');
				add_filter('posts_orderby', 'author_filter_orderby');
			}else
			{
				add_filter('posts_where', 'author_filter_where');
				remove_filter('posts_orderby', 'author_filter_orderby');
				remove_filter('posts_where', 'searching_filter_where');	
			}
		} else {
			if($_REQUEST['sort'] == 'rating' || $_REQUEST['etype'] !="" || $_REQUEST['sort'] == 'review'){
			add_filter('posts_where', 'searching_filter_where');
			ratings_sorting($local_wp_query);
			}			
		}
	} else {
		if(!strstr($_SERVER['REQUEST_URI'],'/wp-admin/') && is_tax()){
			add_filter('posts_where', 'searching_filter_where');
			add_filter('posts_orderby', 'archive_filter_orderby');
		}
	}
}

//================REVIEW RATING SHORTING START==========================//
function custom_post_author_archive( &$query )
{
   // if ( $query->is_author )
    $query->set('post_type', array('place', 'event','attachment'));
    $query->set('post_status', array('publish', 'draft'));
    $query->set('post_city_id',$_SESSION['multi_city']);
	if(!strstr($_SERVER['REQUEST_URI'],'/wp-admin/')) {
		add_filter('posts_orderby', 'archive_filter_orderby');
	}
    remove_action( 'pre_get_posts', 'custom_post_author_archive' ); // run once!
}

function ratings_sorting($local_wp_query) {
global $wp_query, $post;
		$current_term = $wp_query->get_queried_object();
		$blog_cat = get_blog_sub_cats_str($type='array');
	
		if(in_array($current_term->term_id,$blog_cat))
		{
			add_filter('posts_orderby', 'blog_filter_orderby');	
			remove_filter('posts_orderby', 'review_highest_orderby');
			remove_filter('posts_where', 'event_where');
		}else {
			add_filter('posts_where', 'event_where');
			if($_REQUEST['sort']=='review') { 
				add_filter('posts_orderby', 'review_highest_orderby');
				remove_filter('posts_orderby', 'ratings_most_orderby');
			} elseif($_REQUEST['sort']=='rating') {
				add_filter('posts_orderby', 'ratings_most_orderby');
				remove_filter('posts_orderby', 'review_highest_orderby');	
			}else	{
				add_filter('posts_orderby', 'archive_filter_orderby');
				remove_filter('posts_orderby', 'ratings_most_orderby');
				remove_filter('posts_orderby', 'review_highest_orderby');
			}
		}
}

function archive_filter_orderby($orderby) {
	global $wpdb,$wp_query;
	$current_term = $wp_query->get_queried_object();
	if(!isset($_REQUEST['sort']) && $_REQUEST['sort'] == ""){
	if($current_term->taxonomy == CUSTOM_CATEGORY_TYPE2)
	{ 
		if(get_option('ptthemes_listing_order') == ALPHA_ORDER_TEXT ){ 
		$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"featured_type\") asc,$wpdb->posts.post_title ASC";	
		}else if(get_option('ptthemes_listing_order') == RANDOM_ORDER_TEXT) {  
		$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") asc,rand()";
		}else{ 
		$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") asc,$wpdb->posts.post_date desc";	
		}
	}else{
		if(get_option('ptthemes_listing_order') == ALPHA_ORDER_TEXT){
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"featured_type\") asc,$wpdb->posts.post_title asc";	
		 }else if(get_option('ptthemes_listing_order') == RANDOM_ORDER_TEXT) { 
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") asc,rand()";
		 }else{  
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"featured_type\") asc,$wpdb->posts.post_date desc";	
		 }
	}
	}
	return $orderby;	
}
function event_where($where)
{
	global $wpdb,$wp_query;
	$current_term = $wp_query->get_queried_object();
	if(is_archive())
	{
		global $wp_query, $post;
		$current_term = $wp_query->get_queried_object();
		$blog_cat = get_blog_sub_cats_str($type='array');
		if($_SESSION['multi_city'])
		{
			$multi_city_id = $_SESSION['multi_city'];
			$where .= " AND  ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='post_city_id' and ($wpdb->postmeta.meta_value like \"%,$multi_city_id,%\" or $wpdb->postmeta.meta_value like \"$multi_city_id,%\" or $wpdb->postmeta.meta_value like \"%,$multi_city_id\" or $wpdb->postmeta.meta_value like \"$multi_city_id\" or $wpdb->postmeta.meta_value='' or $wpdb->postmeta.meta_value='0'))) ";
		}
		if($current_term->taxonomy==CUSTOM_CATEGORY_TYPE2)
		{
			if($_REQUEST['etype']=='')
			{
				$_REQUEST['etype']='upcoming';
			}
			if($_REQUEST['etype']=='upcoming')
			{
				$today = date('Y-m-d');
				$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='end_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d %H:%i')>='".$today."')) ";
				
			}elseif($_REQUEST['etype']=='past')
			{
				$today = date('Y-m-d');
				$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='end_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d %H:%i')<='".$today."')) ";
			}
		}
	}
	
	return $where;
}
function review_highest_orderby($content) {
	global $wpdb;
	$orderby = 'desc';
	$content = "comment_count $orderby,(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"is_featured\")+0 desc";
	return $content;
}
function ratings_most_orderby($content) {
	global $wpdb,$rating_table_name;
	$content = "(select avg(rt.rating_rating) as rating_counter from $rating_table_name as rt where rt.comment_id in (select cm.comment_ID from $wpdb->comments cm where cm.comment_post_ID=$wpdb->posts.ID and cm.comment_approved=1)) desc, comment_count desc";
	return $content;	
}

function blog_filter_orderby($content)
{
	global $wpdb;
	return "$wpdb->posts.post_date DESC,$wpdb->posts.post_title ";
}
//================REVIEW RATING SHORTING END==========================//
function search_cal_event_where($where)
{
	global $wpdb,$wp_query;
	$m = $wp_query->query_vars['m'];
	$py = substr($m,0,4);
	$pm = substr($m,4,2);
	$pd = substr($m,6,2);
	$the_req_date = "$py-$pm-$pd";
	$event_of_month_sql = "select p.ID from $wpdb->posts p where p.post_type in ('".CUSTOM_POST_TYPE2."','attachment') and p.ID in (select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'st_date' and pm.meta_value <= \"$the_req_date\" and pm.post_id in ((select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'end_date' and pm.meta_value>=\"$the_req_date\")))";
	$where = " AND $wpdb->posts.post_type in ('".CUSTOM_POST_TYPE2."','attachment') AND $wpdb->posts.ID in ($event_of_month_sql) and $wpdb->posts.post_status in ('publish','private','attachment') ";
	return $where;
}
function searching_filter_orderby($orderby) {
	global $wpdb;
	$orderby = "  (select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"is_featured\")+0 desc,$wpdb->posts.post_title ";
	return $orderby;	
}
function author_filter_where($where)
{
	global $wpdb,$current_user,$curauth,$wp_query;
	
	$query_var = $wp_query->query_vars;
	$user_id = $query_var['author'];
	$post_ids = get_user_meta($current_user->ID,'user_favourite_post',true);
	$final_ids = '';
	if($post_ids)
	  {
		foreach($post_ids as $key=>$value)
		 {
		  if($value != '')
		    {
			 $final_ids .= $value.',';
		    }
	    }
		$post_ids = substr($final_ids,0,-1);
	 }
	if($_REQUEST['list']=='favourite')	{
		$where = " AND ($wpdb->posts.ID in ($post_ids)) AND ($wpdb->posts.post_type in('place','event','attachment') OR $wpdb->posts.post_type = '".CUSTOM_POST_TYPE1."' OR  $wpdb->posts.post_type = '".CUSTOM_POST_TYPE2."') AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private' OR $wpdb->posts.post_status = 'draft' OR $wpdb->posts.post_status = 'attachment') ";			
	}else
	{	
		$where = " AND ($wpdb->posts.post_author = $user_id) AND ( $wpdb->posts.post_type ='place' OR $wpdb->posts.post_type ='event' ) AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'draft' OR $wpdb->posts.post_status = 'attachment') ";
	}
	return $where;
}
function searching_filter_join($join) {

	global $wpdb;
	echo $join .= " join $wpdb->postmeta on $wpdb->postmeta.post_id=$wpdb->posts.ID left join $wpdb->term_relationships on  $wpdb->term_relationships.object_id = $wpdb->postmeta.post_id left join  $wpdb->term_taxonomy on $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id left join $wpdb->terms on $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id and $wpdb->terms.name like '".$_REQUEST['s']."'";
	return $join;
}

function searching_filter_where($where) {

	global $wpdb;
	$sn = trim($_REQUEST['sn']);
	$s = trim($_REQUEST['s']);
	$scat = trim($_REQUEST['catdrop']);
	$todate = trim($_REQUEST['todate']);
	$frmdate = trim($_REQUEST['frmdate']);
	$articleauthor = trim($_REQUEST['articleauthor']);
	$exactyes = trim($_REQUEST['exactyes']);

	if($_SESSION['multi_city'])
	{	
		$multi_city_name = $_REQUEST['sn'];
		$citytable = $wpdb->prefix."multicity";
		if($_SESSION['multi_city'] != ''){
			$multi_city_id = $_SESSION['multi_city'];
		
		} else {
			if($multi_city_name){
			$cityid = $wpdb->get_row("select * from $citytable where cityname LIKE '%".$multi_city_name."%'");
			$multi_city_id = $cityid->city_id;
			}else{
			$multi_city_id = $_SESSION['multi_city'];
			}
		}
		if(strstr($_SERVER['REQUEST_URI'],'/wp-admin/')){
			$where .= " AND  ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='post_city_id' and ($wpdb->postmeta.meta_value like \"%,$multi_city_id,%\" or $wpdb->postmeta.meta_value like \"$multi_city_id,%\" or $wpdb->postmeta.meta_value like \"%,$multi_city_id\" or $wpdb->postmeta.meta_value like \"$multi_city_id\" or $wpdb->postmeta.meta_value='' or $wpdb->postmeta.meta_value='0'))) ";
		} else { 
			if($sn !=""){
			$qry = " OR ($wpdb->postmeta.meta_key='geo_address' and $wpdb->postmeta.meta_value like \"%$sn%\")";
			}
		if($multi_city_name !=""){
			
			$where .= " AND  ($wpdb->posts.post_type in ('place','event','attachment')) AND  ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='post_city_id' and ($wpdb->postmeta.meta_value like \"%,$multi_city_id,%\" or  $wpdb->postmeta.meta_value like \"$multi_city_id,%\" or $wpdb->postmeta.meta_value like \"%,$multi_city_id\" or $wpdb->postmeta.meta_value like \"$multi_city_id\") $qry ))";
			}else{
			//$where .= " AND  ($wpdb->posts.post_type in ('place','event','attachment')) AND  ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='post_city_id' and ($wpdb->postmeta.meta_value like \"%,$multi_city_id,%\" or  $wpdb->postmeta.meta_value like \"$multi_city_id,%\" or $wpdb->postmeta.meta_value like \"%,$multi_city_id\" or $wpdb->postmeta.meta_value like \"$multi_city_id\") $qry ))";
			$where .= " AND  ($wpdb->posts.post_type in ('place','event','attachment')) AND  ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='post_city_id' and ($wpdb->postmeta.meta_value like \"%,$multi_city_id,%\" or  $wpdb->postmeta.meta_value like \"$multi_city_id,%\" or $wpdb->postmeta.meta_value like \"%,$multi_city_id\" or $wpdb->postmeta.meta_value like \"$multi_city_id\") $qry ))";
			}
			if($todate!="" && $frmdate!="")
			{
				$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') BETWEEN '".$todate."' and '".$frmdate."'";
			}else if($scat>0)
			{
				$where .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id and $wpdb->term_taxonomy.term_id=\"$scat\" ) ";
			}
			else if($todate!="")
			{
				$where .= " AND   DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') >='".$todate."'";
			}
		}
	}else if($scat>0)
	{
		$where .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id and $wpdb->term_taxonomy.term_id=\"$scat\" ) ";
	}
	else if($todate!="")
	{
		$where .= " AND   DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') >='".$todate."'";
	}
	else if($frmdate!="")
	{
		$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') <='".$frmdate."'";
	}
	else if($todate!="" && $frmdate!="")
	{
		$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') BETWEEN '".$todate."' and '".$frmdate."'";
	}
	if($articleauthor!="" && $exactyes!=1)
	{
		//$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  like '".$articleauthor."') ";
	}
	if($articleauthor!="" && $exactyes==1)
	{
		//$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  = '".$articleauthor."') ";
	}
	$serch_post_types = "'place','event','attachment'";
	$custom_metaboxes = get_post_custom_fields_templ($serch_post_types,'','user_side','1');
	foreach($custom_metaboxes as $key=>$val) {
	$name = $key;
		if($_REQUEST[$name]){ 
			$value = $_REQUEST[$name];
			if($name == 'proprty_desc' || $name == 'event_desc'){
				$where .= " AND ($wpdb->posts.post_content like \"%$value%\" )";
			} else if($name == 'property_name'){
				$where .= " AND ($wpdb->posts.post_title like \"%$value%\" )";
			}else {
				$where .= " OR ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='$name' and ($wpdb->postmeta.meta_value like \"%$value%\" ))) ";
			}
		}
	}
	
	// Added for tags
	$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '".$s."' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
	// End for tags
	
	//echo $where;
	return $where;
}


function searching_no_filter_where($where) {
	global $wpdb;
	$s = trim($_REQUEST['s']);
	$where = " AND $wpdb->posts.post_type  in ('post','".CUSTOM_POST_TYPE1."','".CUSTOM_POST_TYPE2."','attachment') AND (($wpdb->posts.post_title LIKE \"%$s%\") OR ($wpdb->posts.post_content LIKE \"%$s%\") OR ($wpdb->postmeta.meta_key like 'geo_address' and $wpdb->postmeta.meta_value like \"%$s%\"))) ";
	return $where;
}
function author_filter_orderby($orderby) {
	global $wpdb;
	$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"is_featured\")+0 desc"; 
	
	return $orderby;
}
?>