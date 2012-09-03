<?php
include(TEMPLATEPATH."/monetize/custom_post_type/custom_post_type_lang.php");
global $wpdb;
if(isset($_POST['save_icons']) && $_POST['save_icons'] != ""){
if($_POST['save_icons'])
{
	$ptype = explode(',',$_POST['post_type']);
	$my_post_type = $ptype[1];
	if($ptype[1] == "")
	{
	$catinfo = $wpdb->get_col("SELECT t.*  FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id WHERE tt.taxonomy = 'category'");
	}else{
	$catinfo = $wpdb->get_col("SELECT t.*  FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id WHERE tt.taxonomy = '".$my_post_type."'");
	}

	for($i=0;$i<count($catinfo);$i++)
	{
		$post_var = "term_icon".$catinfo[$i];
		$t_price = "cprice_".$catinfo[$i];
		$term_id=$catinfo[$i];
		$cat_icon = $_POST["$post_var"];
		$term_price = $_POST["$t_price"];
		$field_check = $wpdb->get_var("SHOW COLUMNS FROM $wpdb->terms LIKE 'term_icon'");
		if('term_icon' != $field_check)	{
			$dbuser_table_alter = $wpdb->query("ALTER TABLE $wpdb->terms ADD term_icon text NOT NULL");
		}
		$field_check2 = $wpdb->get_var("SHOW COLUMNS FROM $wpdb->terms LIKE 'term_price'");
		if('term_price' != $field_check2)	{
			$dbuser_table_alter = $wpdb->query("ALTER TABLE $wpdb->terms ADD term_price varchar(100) NOT NULL");
		}
		
		if($term_price != ""){
		$wpdb->query("update $wpdb->terms set term_price = \"$term_price\"  where term_id=\"$term_id\"");
		}else{
		$wpdb->query("update $wpdb->terms set term_price ='0'  where term_id=\"$term_id\"");
		}
		
		if($cat_icon != ""){ 
		$wpdb->query("update $wpdb->terms set term_icon=\"$cat_icon\" where term_id=\"$term_id\"");
		}
	}
	$location = site_url()."/wp-admin/admin.php";
		echo '<form action="'.$location.'#option_display_icons" method=get name="icon_success">
		<input type=hidden name="page" value="manage_settings"><input type=hidden name="msg" value="icon_success"></form>';
		echo '<script>document.icon_success.submit();</script>';
		exit;
	
}
}
?>
<script type="text/javascript">
function showicon_cat(str)
{  	
	if (str=="")
	  {
	  document.getElementById("categories_icon").innerHTML="";
	  return;
	  }else{
	  document.getElementById("categories_icon").innerHTML="";
	  document.getElementById("iprocess").style.display ="block";
	  }
		if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
		else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("iprocess").style.display ="none";
		document.getElementById("categories_icon").innerHTML=xmlhttp.responseText;
		}
	  } 
	  url = "<?php echo get_template_directory_uri(); ?>/monetize/manage_settings/ajax_custom_taxonomy.php?post_type="+str+"&caticon=1"
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
} 
/*--Insert record--*/

var http = createObject();
var nocache = 0;
function insert(cid) {

document.getElementById('insert_response'+cid).style.display = ""

var cprice= document.getElementById('cprice_'+cid).value;
var term_icon = encodeURI(document.getElementById('term_icon'+cid+'_text').value);

http.open('get', '<?php echo get_template_directory_uri(); ?>/monetize/manage_settings/ajax_custom_taxonomy.php?cprice='+cprice+'&mcatid='+cid+'&term_icon=' +term_icon+'&i=1');
http.onreadystatechange = insertReply;
http.send(cprice);
}
function insertReply() {
if(http.readyState == 4){ 
var response = http.responseText;
// else if login is ok show a message: "Site added+ site URL".
document.getElementById('cat_edit_'+response).style.display = 'none';	
document.getElementById('cat_price'+response).style.display = 'none';
document.getElementById('pricecat'+response).style.display = '';
document.getElementById('add_cat'+response).style.display = 'none';
document.getElementById('edit_cat'+response).style.display = '';
document.getElementById('pricecat'+response).innerHTML = document.getElementById('cprice_'+response).value;
document.getElementById('insert_response'+response).style.display = "none";
document.getElementById('insert_response'+cid).innerHTML = "Inserted"
}
}
</script>

<form action="<?php echo site_url();?>/wp-admin/admin.php?page=manage_settings#option_display_icons" method="post" name="payoptsetting_frm">
 <input type="submit" name="submit" class="button-framework-imp right position_top" value="<?php _e('Save all changes');?>">

<h4><?php echo MANAGE_CAT_SET_TEXT; ?></h4>
<p class="notes_spec"><?php echo CAT_SECTION_TITLE;?></p>

<?php if($_REQUEST['msg'] == 'icon_success'){?>
<div class="updated fade below-h2" id="message" style="padding:5px; font-size:11px;" >
 <?php _e('Category settings saved successfully.');?>
</div>
<?php }?>
<div class="option option-select">
    <h3><?php _e('Filter by: ','templatic');?></h3>
    <div class="section">
      <div class="element">
	  <?php
				$custom_post_types_args = array();  
                $custom_post_types = get_post_types($custom_post_types_args,'objects');   
				//print_r($custom_post_types);
				$url = str_replace('http://','',get_template_directory_uri());
	  ?>
                 <select name="post_type" id="post_type"  <?php if($post_val->is_delete=='1'){?> disabled="disabled" <?php }?> onChange="showicon_cat(this.value)">
				  <?php
					foreach ($custom_post_types as $content_type) {
                    if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page' && $content_type->name!='post'){
                  ?>
                  <option value="<?php echo $content_type->name.",".$content_type->taxonomies[0].",".$post_val->field_category; ?>" <?php if($post_val->post_type==$content_type->name){ echo 'selected="selected"';}?>><?php echo $content_type->label;?></option>
                 <?php }}?>
                  </select>
      	   </div>
      <div class="description"><?php _e('Select the post-type to show categories associated with that post-type.','templatic');?></div>
    </div>
  </div>
<span id='iprocess' style='display:none;margin-left:150px;'><img src="<?php echo get_template_directory_uri()."/images/loader.gif"; ?>" alt='Filtering results...' /></span>

 <input type="hidden" name="save_icons" value="1">
 <div id="categories_icon">
  <table  style=" width:100%" cellpadding="5" class="widefat post sub_table" >
    <thead>
     <tr>
        <th width="170" align="center"><?php _e('Category','templatic'); ?></th>
        <th width="100" align="center"><?php _e('Price','templatic'); ?></th>
        <th width="120" align="center"><?php _e('Icon','templatic'); ?></th>
        <th><?php _e('Action','templatic'); ?></th>
      </tr>
		<?php 
		$catinfo = $wpdb->get_results("SELECT t.*  FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id WHERE tt.taxonomy IN ('".CUSTOM_CATEGORY_TYPE1."','".CUSTOM_CATEGORY_TYPE2."')");
		foreach($catinfo as $catinfo_obj){
		$term_id = $catinfo_obj->term_id;
		$name = $catinfo_obj->name;
		$price = $catinfo_obj->term_price;
		if($price == "")
		{
			$price = 0;
		}
		$term_icon = $catinfo_obj->term_icon;
		$path= get_template_directory_uri().'/monetize/upload/index.php?img=term_icon'.$term_id.'&nonce=mktnonce&caticon=1';
		?>
      <tr>
        <td><?php echo $name;?> </td>
		<td><span id="pricecat<?php echo $term_id;?>"><?php echo $price; ?></span><span id="cat_price<?php echo $term_id;?>" style="display:none;"><input type="text" id="cprice_<?php echo $term_id;?>" name="cprice_<?php echo $term_id;?>" value="<?php echo $price; ?>" style="width:30px; display:inline;"/></span><?php echo " ".fetch_currency(get_option('currency_symbol'),'currency_symbol'); ?></td>
        <td ><?php if($term_icon != "") { ?><img id="term_icon<?php echo $term_id;?>_img" class="cat_icon" src="<?php echo $term_icon;?>" align="middle" height="34px" width="20px"><?php }else{ ?><img id="term_icon<?php echo $term_id;?>_img" src="<?php echo get_template_directory_uri()."/images/default.png"; ?>" class="cat_icon" align="middle" height="34px" width="20px"><?php } ?>
		<input size="50" type="hidden" value="<?php if($term_icon != "") { echo $term_icon; }else{ echo get_template_directory_uri()."/images/default.png"; }?>" name="term_icon<?php echo $term_id;?>" id="term_icon<?php echo $term_id;?>_text" style="width:260px;">
		<span style="display:none;" id="cat_edit_<?php echo $term_id;?>">
		<iframe name="mktlogoframe" id="upload_target" style="border: none; width:80px; height: 30px; " frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="<?php echo $path; ?>" ></iframe> 
		</span></td>
        <td ><span id="edit_cat<?php echo $term_id;?>"><a href="javascript:void(0);" onClick="edit_cat('<?php echo $term_id;?>','<?php echo $price;?>');" title="Edit settings"><img src="<?php echo get_template_directory_uri()."/images/edit.png"; ?>" alt = "<?php _e('Edit');?>"/></a></span>
		<span id="add_cat<?php echo $term_id;?>" style="display:none;"><a href="javascript:insert(<?php echo $term_id;?>);"title="Save settings"><img src="<?php echo get_template_directory_uri()."/images/save.png"; ?>" alt = "<?php _e('Save');?>"/></a></span>
		<span id="insert_response<?php echo $term_id;?>" style="display:none;"><img src="<?php echo get_template_directory_uri()."/images/loader.gif"; ?>"/></span>
		</td>
      </tr>
<?php }?>
    </thead>
  </table>
 </div>
 <input type="submit" name="submit" class="button-framework-imp right position_bottom" value="<?php _e('Save all changes');?>">
</form>