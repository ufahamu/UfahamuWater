<?php 
/*
Template Name: Page - Splash
*/
if(!isset($_POST['front_post_city_id'])) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title ( '|', true,'right' ); ?></title>
   <?php do_action('templ_head_meta');?>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <?php /*?><link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" /><?php */?>
    <?php do_action('templ_head_css');?>
	<?php
    wp_enqueue_script('jquery');
    wp_enqueue_script('cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', 'jquery', false);
    wp_enqueue_script('cookie', get_template_directory_uri() . '/js/jquery.cookie.js', 'jquery', false);
    if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
    do_action('templ_head_js');
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	wp_head();
	
	?>
    <style type="text/css">
		body { margin:0; padding:0; background:#00a3d3; border-top:5px solid #0880a3; font-family:Georgia, "Times New Roman", Times, serif; }
		.header { border-bottom:2px solid #0298c5; padding:40px 0; text-align:center;   }
		.header .site-title a { display:none;  }
		.header .site-description { font:bold 14px Arial, Helvetica, sans-serif; color:#b3dafa; padding-left:45px; }
		
		.select_city { margin: 40px auto 0; position: relative; text-align: center; width: 322px;  }
		.select_city h3 { margin:0; padding:0 0 20px 0; font-size:36px; color:#fff; font-weight:normal; text-shadow:1px 1px 1px #333; }
		.select_city p { margin:0 0 20px 0; padding:0; font-size:16px; color:#ececec;  }
		
		.styled-select select {
		   background: transparent;
		   width: 268px;
		   padding: 12px 20px 17px;
		   font: 16px Georgia, "Times New Roman", Times, serif;
 		   height: 50px;
		   border:none;
		   color:#b2b2b2;
		    float:left;
		}
		.styled-select select:focus { color:#333; }
		.styled-select {
		   width: 267px;
		   height: 45px;
		   overflow: hidden;
		   -webkit-border-radius: 24px;
			-moz-border-radius: 24px;
			border-radius: 24px;
			border-radius: 24px;
		   background:#fff;
		   margin-left:34px;
		   cursor:pointer;
		  
		}
		.b_go { display:block; width:89px; height:48px; background:url(<?php bloginfo('template_directory'); ?>/images/b_go.png) no-repeat left top; 
		position: absolute; text-indent:-9009px; right:0px; border:none; cursor:pointer;  } 
		.b_go:hover { background-position:left -48px; }
		
		@media screen and (min-width: 480px) and (max-width: 570px) {
		.select_city { width:55%; margin:18%; }
		.styled-select { height:auto; }
		.b_go { clear:both; position:inherit; }
		.styled-select { width:318px; background:url(<?php bloginfo('template_directory'); ?>/images/select_arrow.png) no-repeat left top; }
		.styled-select select { width:353px; margin-bottom:10px;  } 
		.header { width:100%;  }
		}
		
		@media screen and (max-width: 480px) { 
		
		.select_city { width:55%; margin:30px; }
		.styled-select { height:auto; }
		.b_go { clear:both; position:inherit; }
		.styled-select { width:318px; background:url(<?php bloginfo('template_directory'); ?>/images/select_arrow.png) no-repeat left top; }
		.styled-select select { width:353px; margin-bottom:10px;  } 
		.header { width:86%;  }
		
		}
		
	</style>
    
</head>
<body <?php body_class(); ?>>
<?php templ_body_start(); // Body Start hooks?>
<?php templ_header_start(); // Header Start hooks?>

	<div class="header">
    	<?php  templ_site_logo(); ?> 
    </div>
	 
     
     <div class="select_city">
     	<h3><?php echo SELECT_CITY_TPL;?></h3>
        <p><?php echo SELECT_CITY_DESC_TPL;?></p>
          <div class="styled-select">
           <form name="frmcity" id="frmcity" action="<?php echo site_url();?>/" method="post">
		   <?php echo get_multicit_select_dl('front_post_city_id','front_post_city_id','','onchange="document.frmcity.submit();" class="textfield textfield_x" ');?>
           </form>
         </div>
      </div>
    
    
</body>
</html>
<?php } else  {
	setcookie("multi_city1", $_POST['front_post_city_id'],time()+3600*24*30*12);
	$_SESSION['multi_city1'] = $_COOKIE['multi_city1'];
	wp_redirect(site_url().'/');
	exit;
}?>

