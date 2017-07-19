<?php
/*
Plugin Name: DD Favourite Posts
Plugin URI: http://basri.my
Description: Put favourite posts on sidebar.
Version: 1.0
Author: basri.my
Author URI: http://basri.my/
*/
add_action('admin_menu', 'ddfp_add_page');

function ddfp_add_page() {
	$mypage = add_options_page('DD Favourite Posts', 'DD Favourite Posts', 8, 'ddfp', 'ddfp_options_page');
	add_action( "admin_print_scripts-$mypage", 'ddfp_admin_head' );
}

function ddfp_options_page() {
	echo "<div class='wrap'>
	<h2>DD Favourite Posts</h2>";
	if (isset($_POST['savelist'])) {
		?><div id="message" class="updated fade"><p><strong><?php
		update_option('ddfp_list', (string) $_POST["ddfp_list"]);
		echo "Updated!";
		?></strong></p></div><?php
	}	
	echo "
	<table>
		<tr>
			<td colspan='4'>
			Search posts:
			<br />
			<input type='text' name='searchpost' id='searchpost' style='width: 385px;' onkeyup='searchitems(\"searchpost\",\"lista\")'>
			</td>
		</tr>
		<tr>
			<td><div>".ddfp_get_listA()."</div></td>
			<td>
				<input type='button' value='&gt;' onclick='moveitem(\"lista\",\"listb\")' id='l2r'>
				<br /.
				<input type='button' value='&lt;' onclick='moveitem(\"listb\",\"lista\")' id='r2l'>
			</td>
			<td><div>".ddfp_get_listB()."</div></td>
			<td>
				<input type='button' value='MOVE UP' onclick='changeitem(\"listb\",1)'>
				<br />
				<input type='button' value='MOVE DOWN' onclick='changeitem(\"listb\",2)'>
			</td>
		</tr>
	</table>
	<div class='submit'>
		<form method='post' action='".$_SERVER["REQUEST_URI"]."' id='f1' name='f1'>
		<input type='hidden' value='' name='ddfp_list'>
		<input type='hidden' value='savelist' name='savelist'>
		<input type='button' value='Update Favourite' onclick='ddfp_list.value=getitems(\"listb\");document.f1.submit();' />
		</form>
	</div>	
	</div>
	";
}

function ddfp_admin_head() {
	$plugindir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
	wp_enqueue_script('ddfp', $plugindir . '/ddfp.js');
	echo "<link rel='stylesheet' href='$plugindir/ddfp.css' type='text/css' />\n";
}

function ddfp_get_listA()
{
	$str="<SELECT NAME='lista' ID='lista' MULTIPLE style='padding: 2px; font-size: 11px; height: 22em; width: 35em'>\n";
	$posts = get_posts('numberposts=-1&orderby=ID&order=DESC&exclude='.get_option("ddfp_list"));
	foreach($posts as $post)
	{
		$str.="<option value='".$post->ID."'>".$post->post_title."\n";
	}	
	$str.="</SELECT>\n";
	return $str;
}

function ddfp_get_listB()
{
	$str="<SELECT NAME='listb' ID='listb' MULTIPLE style='padding: 2px; font-size: 11px; height: 22em; width: 35em'>\n";
	//$posts = get_posts('numberposts=-1&include='.get_option("ddfp_list"));
	$posts=explode(",",get_option("ddfp_list"));
	foreach($posts as $post_id)
	{
		if($mypost= get_post($post_id, ARRAY_A))$str.="<option value='".$post_id."'>".$mypost["post_title"]."\n";
	}	
	$str.="</SELECT>\n";
	return $str;
}

function ddfp_widget($args) {
  $data = get_option('ddfp_widget');
  extract($args);
  echo $before_widget . $before_title . $data["title"] . $after_title . '<ul>' . "\n";
  $posts=explode(",",get_option("ddfp_list"));
  $total=0;
  foreach($posts as $post_id)
  {
	  if($post_id<>0 && $total<$data['num'])
	  {
  		if($mypost= get_post($post_id, ARRAY_A))echo "<li><a href='".get_permalink($post_id)."'>".$mypost["post_title"]."</a></li>\n";
	  }
  $total++;
  }
  echo  '</ul>' . "\n" . $after_widget;
}

function ddfp_widget_control(){
  $data = get_option('ddfp_widget');
  if (!is_array($data))$data = array('title' => 'DD Favourite Posts','num' => 10);
  ?>
  <p><label>Put Widget Title<input name="ddfp_widget_title" type="text" value="<?php echo $data['title']; ?>" /></label></p>
  <p><label>Number of posts<input name="ddfp_widget_num" type="text" value="<?php echo $data['num']; ?>" /></label></p>
  <?php
   if (isset($_POST['ddfp_widget_title'])){
    $data['title'] = attribute_escape($_POST['ddfp_widget_title']);
    $data['num'] = attribute_escape($_POST['ddfp_widget_num']);
    update_option('ddfp_widget', $data);
  }
}
 
function ddfp_init()
{
  register_sidebar_widget(__('DD Favourite Posts'), 'ddfp_widget');
  register_widget_control(__('DD Favourite Posts'), 'ddfp_widget_control');
}

function ddfp_activate(){
	add_option('ddfp_list', '');
	add_option('ddfp_widget' , array( 'title' => 'DD Favourite Posts', 'num' =>10));
}
function ddfp_deactivate(){
	delete_option('ddfp_list');
	delete_option('ddfp_widget');
}


add_action("plugins_loaded", "ddfp_init");
register_deactivation_hook( __FILE__, 'ddfp_activate');
register_deactivation_hook( __FILE__, 'ddfp_deactivate');
?>