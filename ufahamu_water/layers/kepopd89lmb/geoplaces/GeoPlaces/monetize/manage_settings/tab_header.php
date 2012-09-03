<?php include TT_ADMIN_TPL_PATH.'header.php'; ?>
<div class="info top-info"></div>
<div class="ajax-message<?php if ( isset( $message ) ) { echo ' show'; } ?>">
	<?php if ( isset( $message ) ) { echo $message; } ?>
</div>
	<div id="content">
		<div id="options_tabs">
			<ul class="options_tabs">
				<li><a href="#option_emails"><?php _e('E-mail notifications &amp; messages','templatic');?></a><span></span></li>
				<li><a href="#option_set_role"><?php _e('Manage permissions','templatic');?></a><span></span></li>
				<li><a href="#option_display_custom_fields"><?php _e('Custom fields','templatic');?></a><span></span></li>
				<li><a href="#option_display_custom_usermeta"><?php _e('User profile fields','templatic');?></a><span></span></li>
				<li><a href="#option_display_icons"><?php _e('Category settings','templatic');?></a><span></span></li>					
				<li><a href="#option_display_city"><?php _e('Manage cities','templatic');?></a><span></span></li>					
				<li><a href="#option_display_price"><?php _e('Manage price package','templatic');?></a><span></span></li>
				<li><a href="#currency_setup"><?php _e('Manage currency','templatic');?></a><span></span></li>					
				<li><a href="#option_payment"><?php _e('Payment options','templatic');?></a><span></span></li>
				<li><a href="#option_transaction_settings"><?php _e('Transaction report','templatic');?></a><span></span></li>				
				<li><a href="#option_display_coupon"><?php _e('Manage coupons','templatic');?></a><span></span></li>	
				<li><a href="#option_bulk_upload"><?php _e('Bulk upload','templatic');?></a><span></span></li>
				<li><a href="#option_ip_settings"><?php _e('Manage IP','templatic');?></a><span></span></li>
			
			</ul> 