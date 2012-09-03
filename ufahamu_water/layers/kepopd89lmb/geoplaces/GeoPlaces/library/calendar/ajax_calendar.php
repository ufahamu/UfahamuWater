<?php
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");
global $post,$wpdb;

	$monthNames = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	if (!isset($_REQUEST["mnth"])) $_REQUEST["mnth"] = date("n");
	if (!isset($_REQUEST["yr"])) $_REQUEST["yr"] = date("Y");
	
	$cMonth = $_REQUEST["mnth"];
	$cYear = $_REQUEST["yr"];
	
	$prev_year = $cYear;
	$next_year = $cYear;
	$prev_month = $cMonth-1;
	$next_month = $cMonth+1;
	
	if ($prev_month == 0 ) {
		$prev_month = 12;
		$prev_year = $cYear - 1;
	}
	if ($next_month == 13 ) {
		$next_month = 1;
		$next_year = $cYear + 1;
	}
	$mainlink = $_SERVER['REQUEST_URI'];
	if(strstr($_SERVER['REQUEST_URI'],'?mnth') && strstr($_SERVER['REQUEST_URI'],'&yr'))
	{
		$replacestr = "?mnth=".$_REQUEST['mnth'].'&yr='.$_REQUEST['yr'];
		$mainlink = str_replace($replacestr,'',$mainlink);
	}elseif(strstr($_SERVER['REQUEST_URI'],'&mnth') && strstr($_SERVER['REQUEST_URI'],'&yr'))
	{
		$replacestr = "&mnth=".$_REQUEST['mnth'].'&yr='.$_REQUEST['yr'];
		$mainlink = str_replace($replacestr,'',$mainlink);
	}
	if(strstr($_SERVER['REQUEST_URI'],'?') && !strstr($_SERVER['REQUEST_URI'],'?mnth'))
	{
		$pre_link = $mainlink."&mnth=". $prev_month . "&yr=" . $prev_year;
		$next_link = $mainlink."&mnth=". $next_month . "&yr=" . $next_year;
	}else
	{
		$pre_link = $mainlink."?mnth=". $prev_month . "&yr=" . $prev_year;	
		$next_link = $mainlink."?mnth=". $next_month . "&yr=" . $next_year;
	}

	
	?> 
 <table width="100%">
	<tr align="center">
	<td > 
    
    		<table width="100%">
            	 <tr align="center" class="title">
    <td width="10%" class="title"> <a href="javascript:void(0);" onclick="change_calendar(<?php echo $prev_month; ?>,<?php echo $prev_year; ?>)"> <img src="<?php bloginfo('template_directory'); ?>/library/calendar/previous.png" alt=""  /></a></td>
	<td   class="title"><?php echo $monthNames[$cMonth-1].' '.$cYear; ?></td>
    <td width="10%" class="title"><a href="javascript:void(0);"  onclick="change_calendar(<?php echo $next_month; ?>,<?php echo $next_year; ?>)">  <img src="<?php bloginfo('template_directory'); ?>/library/calendar/next.png" alt=""  /></a> </td>
	</tr>
            </table>
    
     </td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    <td  ></td>
	<td align="right"  ></td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td align="center">
	<table width="100%" border="0" cellpadding="2" cellspacing="2"  class="calendar_widget">
	
	<tr>
	<td align="center" class="days" ><strong>S</strong></td>
	<td align="center" class="days" ><strong>M</strong></td>
	<td align="center"  class="days" ><strong>T</strong></td>
	<td align="center" class="days" ><strong>W</strong></td>
	<td align="center" class="days" ><strong>T</strong></td>
	<td align="center" class="days" ><strong>F</strong></td>
	<td align="center" class="days" ><strong>S</strong></td>
	</tr> 
	<?php
	$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
	$maxday = date("t",$timestamp);
	$thismonth = getdate ($timestamp);
	$startday = $thismonth['wday'];
	
	if($_GET['m'])
	{
		$m = $_GET['m'];	
		$py=substr($m,0,4);
		$pm=substr($m,4,2);
		$pd=substr($m,6,2);
		$monthstdate = "$cYear-$cMonth-01";
		$monthenddate = "$cYear-$cMonth-$maxday";
	}
	global $wpdb;
	for ($i=0; $i<($maxday+$startday); $i++) {
		if(($i % 7) == 0 ) echo "<tr>\n";
		if($i < $startday){
			echo "<td></td>\n";
		}
		else 
		{
			$cal_date = $i - $startday + 1;
			$calday = $cal_date;
			if(strlen($cal_date)==1)
			{
				$calday="0".$cal_date;
			}
			$cMonth1 = $cMonth;
			if(strlen($cMonth)==1)
			{
				$cMonth1="0".$cMonth;
			}
			$urlddate = "$cYear$cMonth1$calday";
			$thelink = site_url()."/?s=cal_event&m=$urlddate";

			$the_cal_date = $cal_date;
			if(strlen($the_cal_date)==1){$the_cal_date = '0'.$the_cal_date;}
			$todaydate = "$cYear-$cMonth1-$the_cal_date";
			$event_of_month_sql = "select p.* from $wpdb->posts p where p.post_type = 'event' and (p.post_status = 'publish' OR p.post_status = 'private' ) and p.ID in (select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'st_date' and pm.meta_value <= \"$todaydate\" and pm.post_id in ((select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'end_date' and pm.meta_value>=\"$todaydate\")))";
			$event_post_info_perday = $wpdb->get_results($event_of_month_sql);
			$post_info = '';
				if($event_post_info_perday)
				{
					$post_info .='<span class="popup_event">';
					foreach($event_post_info_perday as $event_post_info_obj)
					{
						$post_info .= ' <a class="event_title" href="'.get_permalink($event_post_info_obj->ID).'">'.$event_post_info_obj->post_title.'</a><small>'.
						 __('<b>Location : </b>').get_post_meta($event_post_info_obj->ID,'geo_address',true) .'<br>'.
						 __('<b>Start Date : </b>').get_formated_date(get_post_meta($event_post_info_obj->ID,'st_date',true)).' '.get_formated_time(get_post_meta($event_post_info_obj->ID,'st_time',true)) .'<br />'. 
						   __('<b>End Date : </b>').get_formated_date(get_post_meta($event_post_info_obj->ID,'end_date',true)).' '.get_formated_time(get_post_meta($event_post_info_obj->ID,'end_time',true)) .'</small>';
					}
					$post_info .='</span>';
				}
				echo "<td class='date_n' align='center' valign='middle' height='20px'>	";
				if($event_post_info_perday)
				{
					echo "<div><a class=\"event_highlight\" href=\"$thelink\" >". ($cal_date) . "</a>".$post_info;
				}else
				{
						echo "<span class=\"no_event\" >". ($cal_date) . "</span>";
				}
				echo "</div></td>\n";
		}
		if(($i % 7) == 6 ) echo "</tr>\n";
	}
	?>
	</table>
	</td>
	</tr>
	</table>
  