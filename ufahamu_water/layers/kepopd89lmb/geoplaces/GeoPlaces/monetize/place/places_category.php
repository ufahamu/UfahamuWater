<?php
global $wpdb;
$blog_cat = get_option('ptthemes_blogcategory');
if(is_array($blog_cat) && $blog_cat[0]!='')
{
	$blog_cat = get_blog_sub_cats_str($type='string');
}else
{
	$blog_cat = '';	
}
if($blog_cat)
{
	$blog_cat .= ",1";
}else
{
	$blog_cat .= "1";
}
global $price_db_table_name;
$package_cats = $wpdb->get_var("select group_concat(cat) from $price_db_table_name where cat>0 and amount>0");
if($package_cats)
{
	if($blog_cat){
	$blog_cat .= ",".$package_cats;
	}else
	{
	$blog_cat .= $package_cats;
	}
}
if($blog_cat)
{
	//$substr = " and c.term_id not in ($blog_cat)";	
	$substr = "";
}
$catsql = "select * from $wpdb->terms c,$wpdb->term_taxonomy tt  where tt.term_id=c.term_id and tt.taxonomy='".CUSTOM_CATEGORY_TYPE1."' and tt.parent=0 and c.name != 'Uncategorized' and c.name != 'Blog'  $substr order by c.term_id";

$catinfo = $wpdb->get_results($catsql);
global $cat_array;

$total_cp_price = 0;
$total_price_sql = $wpdb->get_results("select * from $wpdb->terms c,$wpdb->term_taxonomy tt  where tt.term_id=c.term_id and tt.taxonomy='".CUSTOM_CATEGORY_TYPE1."' and c.name != 'Uncategorized' and c.name != 'Blog' $substr order by c.name");
foreach($total_price_sql as $objtotal_price_sql){
	$total_cp_price += $objtotal_price_sql->term_price;
}

if($_REQUEST['backandedit'] != '' || $_REQUEST['renew'] != ''){
	$place_cat_arr = $cat_array;

} else {
for($i=0; $i < count($cat_array); $i++){
	$place_cat_arr[] = $cat_array[$i]->term_taxonomy_id;
}
}

if($catinfo) {
	$cat_display=get_option('ptthemes_category_dislay');
	if($cat_display==''){$cat_display='checkbox';}
	$counter = 0;
	if($cat_display=='select'){?>
	<div class="form_cat">
    <select name="category" id="category_<?php echo $counter;?>" class="textfield" onChange='document.forms["categoryform"].submit(this.value);' >
	<option value="0"><?php _e('Select category','templatic'); ?></option>

	<?php } else if($cat_display=='checkbox'){ ?>
		<div class="form_cat" ><label><input type="checkbox" name="selectall" onclick="displaychk(); allplaces_packages('<?php echo $total_cp_price;?>'); " id="selectall" /><?php echo SELECT_ALL;?></label></div>
	<?php }
	foreach($catinfo as $catinfo_obj)
	{
		$counter++;
		$termid = $catinfo_obj->term_taxonomy_id;
		$term_tax_id = $catinfo_obj->term_id;
		$name = $catinfo_obj->name;
		$cat_term = explode(',',$_REQUEST['category']);

		if($cat_display=='checkbox'){
		$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='".$termid."' and t.term_id = tt.term_id");

		$cp = $catprice->term_price; 
		?>

		 <div class="form_cat">
         	<label><input type="checkbox" name="category[]" id="category_<?php echo $counter; ?>" value="<?php if($cp != ""){ echo $termid.",".$catprice->term_price; }else{ echo $termid.",".'0'; }?>" class="checkbox" <?php if(isset($place_cat_arr) && in_array($termid,$place_cat_arr)){ echo 'checked=checked'; }?>  onclick="fetch_packages('<?php echo $catinfo_obj->term_id; ?>',this.form,'<?php echo $cp; ?>')"/>&nbsp;<?php if($cp != ""){ echo $name."<span style='color:#990000;'> (".display_amount_with_currency($cp).")</span> "; }else{ echo $name."<span> (".display_amount_with_currency('0').")</span> "; } ?>
            </label>
         </div>
		
		<?php
		 $child = get_term_children( $term_tax_id ,CUSTOM_CATEGORY_TYPE1);
		 foreach($child as $child_of)
		 { 
			$term = get_term_by( 'id', $child_of, CUSTOM_CATEGORY_TYPE1);
			$termid = $term->term_taxonomy_id;
			$term_tax_id = $term->term_id;
			$name = $term->name;
			$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='".$term->term_taxonomy_id ."' and t.term_id = tt.term_id");
			$cp = $catprice->term_price; 
		 ?>
			<div class="form_cat" style="margin-left:15px;"><label><input type="checkbox" name="category[]" id="category_<?php echo $counter; ?>" value="<?php if($cp != ""){ echo $termid.",".$catprice->term_price; }else{ echo $termid.",".'0'; }?>" class="checkbox" <?php if(isset($place_cat_arr) && in_array($termid,$place_cat_arr)){echo 'checked="checked"'; }?>  onclick="fetch_packages('<?php echo $catprice->term_id; ?>',this.form)"/>&nbsp;<?php if($cp != ""){ echo $name."<span style='color:#990000;'> (".display_amount_with_currency($cp).")</span>"; }else{ echo $name."<span style='color:#990000;'> (".display_amount_with_currency('0').")</span>"; } ?></label></div>
		<?php }
		}elseif($cat_display=='radio')
		{
		?>
        <div class="form_cat" ><label class="r_lbl"><input type="radio" name="category[]" id="category_<?php echo $counter;?>" value="<?php echo $termid; ?>" class="checkbox" <?php if(isset($place_cat_arr) && in_array($termid,$place_cat_arr)){echo 'checked="checked"'; }?> />&nbsp;<?php echo $name; ?></label></div>
		<?php
		}elseif($cat_display=='select')
		{  
		$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where t.term_id='".$catinfo_obj->term_id."' and t.term_id = tt.term_id");
		$cp = $catprice->term_price; 
			if((isset($_REQUEST['category']) && $_REQUEST['category'] != '') || (isset($_SESSION['place_info']['category']) && $_SESSION['place_info']['category'] != '') ) { 
				if($_REQUEST['category'] !=""){
				$cat_term = explode(',',$_REQUEST['category']); }else{
				$cat_term = explode(',',$_SESSION['place_info']['category']);
				}
				if($cat_term[0] == $termid){ ?>
					<option <?php if($cat_term[0] == $termid){echo 'selected="selected"'; }?> value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo $name."(".display_amount_with_currency($cp).") "; }else{ echo $name."(".display_amount_with_currency('0').") "; } ?></option>
					<?php
				 $child = get_term_children( $term_tax_id ,CUSTOM_CATEGORY_TYPE1);
				 foreach($child as $child_of)
				 { 
					$term = get_term_by( 'id', $child_of, CUSTOM_CATEGORY_TYPE1);
					$termid = $term->term_taxonomy_id;
					$term_tax_id = $term->term_id;
					$name = $term->name;
					$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='".$term->term_taxonomy_id ."' and t.term_id = tt.term_id");
					$cp = $catprice->term_price; 
				 ?>
					<option <?php  if($cat_term[0] == $termid){ echo 'selected="selected"'; } ?> value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo " - ".$name."(".display_amount_with_currency($cp).")"; }else{ echo " - ".$name."(".display_amount_with_currency('0').")"; } ?></option>
				<?php }
				 } else { ?>
					<option <?php if(isset($_SESSION['place_info']['category']) && $_SESSION['place_info']['category'] == $termid){echo 'selected="selected"'; }?> value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo $name." (".display_amount_with_currency($cp).")"; }else{ echo $name."(".display_amount_with_currency('0').") "; } ?></option>
					<?php
				 $child = get_term_children( $term_tax_id ,CUSTOM_CATEGORY_TYPE1);
				 foreach($child as $child_of)
				 { 
					$term = get_term_by( 'id', $child_of, CUSTOM_CATEGORY_TYPE1);
					$termid = $term->term_taxonomy_id;
					$term_tax_id = $term->term_id;
					$name = $term->name;
					$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='".$term->term_taxonomy_id ."' and t.term_id = tt.term_id");
					$cp = $catprice->term_price; 
				 ?>
					<option <?php  if($cat_term[0]  == $termid){ echo 'selected="selected"'; } ?> value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo " - ".$name."(".display_amount_with_currency($cp).")"; }else{ echo " - ".$name."(".display_amount_with_currency('0').")"; } ?></option>
				<?php }
			 }	?>
				
			<?php } else if($_REQUEST['pid'] != ''){ ?>
				<option <?php  if($cat_array[0]->term_taxonomy_id == $termid){ echo 'selected="selected"'; } ?> value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo $name."(".display_amount_with_currency($cp).")"; }else{ echo $name."(".display_amount_with_currency('0').")"; } ?></option>
				<?php
				 $child = get_term_children( $term_tax_id ,CUSTOM_CATEGORY_TYPE1);
				
				 foreach($child as $child_of)
				 { 
					$term = get_term_by( 'id', $child_of, CUSTOM_CATEGORY_TYPE1);
					$termid = $term->term_taxonomy_id;
					$term_tax_id = $term->term_id;
					$name = $term->name;
					$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='".$term->term_taxonomy_id ."' and t.term_id = tt.term_id");
					$cp = $catprice->term_price; 
				 ?>
					<option <?php  if($cat_term[0]  == $termid){ echo 'selected="selected"'; } ?> value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo " - ".$name."(".display_amount_with_currency($cp).")"; }else{ echo " - ".$name."(".display_amount_with_currency('0').")"; } ?></option>
				<?php }
		 } else { 
		$currency = fetch_currency(get_option('currency_symbol'),'currency_symbol');
		$position = fetch_currency(get_option('currency_symbol'),'symbol_position');
		$amount =0;
		 if($position == '1'){
		$amt_display = $currency.$amount;
		} else if($position == '2'){
		$amt_display = $currency.' '.$amount;
		} else if($position == '3'){
		$amt_display = $amount.$currency;
		} else {
		$amt_display = $amount.' '.$currency;
		}
		 ?>
				<option  value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo $name."(".display_amount_with_currency($cp).")"; }else{ echo $name."(".$amt_display.")"; } ?></option>
				<?php
				 $child = get_term_children( $term_tax_id ,CUSTOM_CATEGORY_TYPE1);
			
				 foreach($child as $child_of)
				 { 
					$term = get_term_by( 'id', $child_of, CUSTOM_CATEGORY_TYPE1);
					$termid = $term->term_taxonomy_id;
					$term_tax_id = $term->term_id;
					$name = $term->name;
					$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='".$term->term_taxonomy_id ."' and t.term_id = tt.term_id");
					$cp = $catprice->term_price; 
				 ?>
					<option <?php  if($term->term_taxonomy_id == $termid){ echo 'selected="selected"'; } ?> value="<?php if($cp != ""){ echo $termid.",".$term_tax_id.",".$catprice->term_price; }else{ echo $termid.",".$term_tax_id.","."0"; }?>"><?php if($cp != ""){ echo " - ".$name."(".display_amount_with_currency($cp).")"; }else{ echo " - ".$name."(".display_amount_with_currency('0').")"; } ?></option>
				<?php }
		}
		?>
        
       <?php
		}
	}
	if($cat_display=='select'){?>
	 </select></div>
	<?php }
}
?>
<script type="text/javascript">
function displaychk(){
	dml=document.forms['propertyform'];
	chk = dml.elements['category[]'];
	len = dml.elements['category[]'].length;
	if(document.propertyform.selectall.checked == true) {
		for (i = 0; i < len; i++)
		chk[i].checked = true ;
	} else {
		for (i = 0; i < len; i++)
		chk[i].checked = false ;
	}
}
</script>