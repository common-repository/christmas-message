<?php
/*
Plugin Name: CJ X-mas Message
Plugin URI: http://www.netchillies.com
Description: This plug-in helps you setup a custom christmas message with cool graphics for your visitors. Visit our <strong><a href="http://www.netchillies.com/forums">Support Forum</a></strong> to report bugs and request more features.
Version: 1.05
Author: NetChillies
Author URI: http://www.netchillies.com
/*  Copyright 2010 NetChillies.com  (email : support@netchillies.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
require_once( dirname(__FILE__) . '../../../../wp-load.php');
ob_start();
if ( !defined('WP_CONTENT_URL') )define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
/******************************************
 * DEFAULT VARIABLES 
******************************************/
global $cj_xmas_path, $shortname, $plugin_settings_name, $options;
// UPDATE THIS VALUE *****************************************************/
$cj_xmas_plugin_name = "CJ X-mas Message";

$cj_xmas_path = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));
$shortname = strtolower(str_replace(" ", "_", $cj_xmas_plugin_name)."_");
$plugin_settings_name = $shortname."settings";

/******************************************
 * ADMIN PAGE SETUP - ADMIN SCRIPTS
******************************************/
add_action('admin_init', 'cj_xmas_plugin_admin_init');
function cj_xmas_plugin_admin_init() {
    global $cj_xmas_path;
    wp_register_script('cj_xmas_plugin_scripts', $cj_xmas_path.'/admin/admin.js');
}
function cj_xmas_plugin_admin_styles() {
    wp_enqueue_script('cj_xmas_plugin_scripts');
}
function cj_xmas_admin_scripts(){
	global $cj_xmas_path;
	echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$cj_xmas_path.'/admin/admin.css" />';
}
add_action('admin_head', 'cj_xmas_admin_scripts');

/******************************************
 * PLUGIN SETTINGS LINK
******************************************/
function cj_xmas_plugin_action_links($links, $file){
	static $this_plugin;
	if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	if( $file == $this_plugin ){
// UPDATE THIS VALUE *****************************************************/
		$settings_link = '<a href="options-general.php?page=cj-xmas.php">' . __('Settings') . '</a>';
		$links = array_merge( array($settings_link), $links);
	}
	return $links;
}
add_filter('plugin_action_links', 'cj_xmas_plugin_action_links', 10, 2 );
/******************************************
 * ADMIN PAGE SETUP - ADMIN PAGES
******************************************/
add_action('admin_menu', 'cj_xmas_admin_menu');
function cj_xmas_admin_menu(){
// UPDATE THIS VALUE *****************************************************/
	$page = add_submenu_page('options-general.php', 'CJ X-mas Message', 'CJ X-mas Message', 8, 'cj-xmas', 'cj_xmas_options_page');
	add_action('admin_print_scripts-' . $page, 'cj_xmas_plugin_admin_styles');
}
function cj_xmas_options_page(){
	global $plugin_settings_name, $shortname;
	$options = array (
	// START EDITING HERE
	array(
	    "oid" => $shortname."plugin_configuration",
	    "oname" => "Plugin Settings",
	    "oinfo" => '',
	    "oclass" => 'cj_xmas_plugin_settings',
	    "otype" => "heading",
	    "ovalue" => 'Plugin Settings'),
	array(
	    "oid" => $shortname."enable",
	    "oname" => "Enable X-mas message",
	    "oinfo" => '',
	    "oclass" => 'cj_xmas_plugin_settings',
	    "otype" => "radio",
	    "ovalue" => array('Enable', 'Disable')),
	array(
	    "oid" => $shortname."cookie",
	    "oname" => "Expire Cookie in # days",
	    "oinfo" => 'Enter the number of days you want to set the cookie to expire',
	    "oclass" => 'cj_xmas_plugin_settings',
	    "otype" => "text",
	    "ovalue" => "1"),
	array(
	    "oid" => $shortname."heading",
	    "oname" => "Heading",
	    "oinfo" => 'Enter the heading to be displayedo in the panel',
	    "oclass" => 'cj_xmas_plugin_settings',
	    "otype" => "text",
	    "ovalue" => 'Merry Christmas'),
	array(
	    "oid" => $shortname."message",
	    "oname" => "Message",
	    "oinfo" => 'Enter the message to be displayed in the panel. You can use any valid xHTML code here.',
	    "oclass" => 'cj_xmas_plugin_settings',
	    "otype" => "textarea",
	    "ovalue" => '<p>Update this message from the plugin settings. Use any xhtml code here.</p>
<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
<p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>'),
	// END EDITING HERE
);
/**** INSTALL SETTINGS / ADD OPTIONS ***************************/
foreach($options as $value){
	$saveoptions[$value['oid']] = $value['ovalue'];
}
add_option($plugin_settings_name, $saveoptions);
/**** SAVE SETTINGS / UPDATE OPTIONS ***************************/
if(isset($_REQUEST['cjsave'])){
foreach($options as $value){
	$saveoptions[$value['oid']] = $_REQUEST[$value['oid']];
}
update_option($plugin_settings_name, $saveoptions);
echo '<div id="message" class="updated fade"><p><strong>Theme settings updated.</strong></p></div>';
}
/**** RESET SETTINGS / DELETE OPTIONS ***************************/
if(isset($_REQUEST['cjreset'])){
delete_option($plugin_settings_name);
foreach($options as $value){
	$saveoptions[$value['oid']] = $value['ovalue'];
}
update_option($plugin_settings_name, $saveoptions);
echo '<div id="message" class="updated fade"><p><strong>Theme settings updated.</strong></p></div>';
}
/**** SAVE SETTINGS / ADD OPTIONS ***************************/
if(isset($_REQUEST['cjremove'])){
delete_option($plugin_settings_name);
$reseturl =  get_bloginfo("wpurl")."/wp-admin/themes.php";
switch_theme('default', 'default');
wp_redirect($reseturl);
}
/**** GET SAVED VALUES ***************************/
function cj_xmas_gop($mykey){
global $plugin_settings_name;
$sopt = get_option($plugin_settings_name);
	foreach($sopt as $key=>$opt){
		if($key == $mykey){
			if(!is_array($opt)){
                            return stripcslashes($opt);
                        }else{
                            return $opt;
                        }
		}
	}
}
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>Plugin Settings</h2>
<p>Get a chance to win any of our Premium Products every month. Join our <a href="http://bit.ly/nc_offers" title="" target="_blank">Special Offers</a> mailing list.</p>
<div id="cjwarp">
<form action="" method="post">
<?php
foreach($options as $key){ ?>
<?php if($key['otype'] == "heading"){ ?>
	<h1 id="<?php echo $key['oclass']; ?>" class="cjhead"><?php echo $key['ovalue']; ?></h1>
<?php } ?>
<?php if($key['otype'] == "text"){ ?>
<div class="<?php echo $key['oclass']; ?> cjcontent cjclearfix">
	<div class="cjlabel">
		<?php echo $key['oname']; ?>
	</div><!-- /label -->
	<div class="cjfield">
		<input size="35" name="<?php echo $key['oid']; ?>" type="text" value="<?php echo cj_xmas_gop($key['oid']); ?>" />
		<span class="cjdesc"><?php echo $key['oinfo']; ?></span>
	</div><!-- /cjfield -->
</div><!-- /cjcontent -->
<?php } ?>
<?php if($key['otype'] == "info"){ ?>
<div class="<?php echo $key['oclass']; ?> cjcontent cjclearfix">
	<div class="cjlabel">
		<label class="cjt-field"><?php echo $key['oname']; ?></label>
	</div><!-- /label -->
	<div class="cjfield">
		<span class="cjt-desc"><?php echo $key['ovalue']; ?></span>
	</div><!-- /cjfield -->
</div><!-- /cjcontent -->
<?php } ?>
<?php if($key['otype'] == "textarea"){ ?>
<div class="<?php echo $key['oclass']; ?> cjcontent cjclearfix">
	<div class="cjlabel">
		<?php echo $key['oname']; ?>
	</div><!-- /label -->
	<div class="cjfield">
		<textarea rows="5" cols="50" name="<?php echo $key['oid']; ?>"><?php echo cj_xmas_gop($key['oid']); ?></textarea>
		<span class="cjdesc"><?php echo $key['oinfo']; ?></span>
	</div><!-- /cjfield -->
</div><!-- /cjcontent -->
<?php } ?>
<?php if($key['otype'] == "select"){ ?>
<div class="<?php echo $key['oclass']; ?> cjcontent cjclearfix">
	<div class="cjlabel">
		<?php echo $key['oname']; ?>
	</div><!-- /label -->
	<div class="cjfield">
		<?php $soptions = $key['ovalue']; ?>
		<select class="cjselect" name="<?php echo $key['oid']; ?>">
			<option value="Please Select">Please Select</option>
			<?php
			foreach($soptions as $svalue){ ?>
				<option <?php if($svalue == cj_xmas_gop($key['oid'])){echo 'selected="selected"';} ?> value="<?php echo $svalue ?>"><?php echo $svalue ?></option>
			<?php }
			?>
		</select>
		<span class="cjdesc"><?php echo $key['oinfo']; ?></span>
	</div><!-- /cjfield -->
</div><!-- /cjcontent -->
<?php } ?>
<?php if($key['otype'] == "radio"){ ?>
<div class="<?php echo $key['oclass']; ?> cjcontent cjclearfix">
	<div class="cjlabel">
		<?php echo $key['oname']; ?>
	</div><!-- /label -->
	<div class="cjfield">
		<?php $roptions = $key['ovalue']; ?>
			<?php
			foreach($roptions as $rvalue){ ?>
				<span class="cjradio">
				<input type="radio" class="cjradio" name="<?php echo $key['oid']; ?>" <?php if($rvalue == cj_xmas_gop($key['oid'])){echo 'checked="checked"';} ?> value="<?php echo $rvalue; ?>" /> <?php echo $rvalue; ?>
				</span>
			<?php }
			?>
		<span class="cjdesc"><?php echo $key['oinfo']; ?></span>
	</div><!-- /cjfield -->
</div><!-- /cjcontent -->
<?php } ?>
<?php if($key['otype'] == "categories"){ ?>
<div class="<?php echo $key['oclass']; ?> cjcontent cjclearfix">
	<div class="cjlabel">
		<?php echo $key['oname']; ?>
	</div><!-- /label -->
	<div class="cjfield">
		<input size="35" name="<?php echo $key['oid']; ?>" type="text" value="<?php echo cj_xmas_gop($key['oid']); ?>" />
                <a class="cjshowcatids" href="" title="Display Category IDs">[Show Category IDs]</a>
		<span class="cjdesc"><?php echo $key['oinfo']; ?></span>
		<div>
	<ul class="cjids cjcats cjclearfix">
		<?php
		    $category_ids = get_all_category_ids();
			foreach($category_ids as $cat_id){
				$cat_name = get_cat_name($cat_id);
				echo '<li><b>' .$cat_id . '</b> - '.$cat_name.'</li>';
			}
		?>
	</ul>
		</div>
	</div><!-- /cjfield -->
</div><!-- /cjcontent -->
<?php } ?>
<?php if($key['otype'] == "pages"){ ?>
<div class="<?php echo $key['oclass']; ?> cjcontent cjclearfix">
	<div class="cjlabel">
		<?php echo $key['oname']; ?>
	</div><!-- /label -->
	<div class="cjfield">
		<input size="35" name="<?php echo $key['oid']; ?>" type="text" value="<?php echo cj_xmas_gop($key['oid']); ?>" />
                <a class="cjshowpageids" href="" title="Display Page IDs">[Show Page IDs]</a>
		<span class="cjdesc"><?php echo $key['oinfo']; ?></span>
		<div>
	<ul class="cjids cjpages cjclearfix">
		<?php
		    $pages = get_pages();
				foreach ($pages as $pagg) {
					echo '<li><b>' .$pagg->ID . '</b> - '.$pagg->post_title.'</li>';
				}
		?>
	</ul>
		</div>
	</div><!-- /cjfield -->
</div><!-- /cjcontent -->
<?php } 
} ?>
<div class="cjbuttons cjclearfix">
	<input name="cjsave" class="button-primary" type="submit" value="Save Settings" />
	<input name="cjreset" class="button" type="submit" value="Restore Defaults" />
</div>
<div style="border-top:0px;" class="cjbuttons cjclearfix">
If you like this plugin, consider
<a target="_blank" href="http://www.cssjockey.com/files/donate.php" title="Donate">making a donation</a>.
Your support will help me spend more time on further development of this plugin and keep it free forever.
</div>
</form>
<div style="border-top:0px;" class="cjbuttons cjclearfix">
	<b>New @ NetChillies.com</b> &raquo;
<?php // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed('http://feeds.feedburner.com/netchillies');
$maxitems = $rss->get_item_quantity(1); 
$rss_items = $rss->get_items(0, $maxitems); 
?>
    <?php if ($maxitems == 0) echo '<li>No items.</li>';
    else
    // Loop through each feed item and display each item as a hyperlink.
    foreach ( $rss_items as $item ) : ?>
        <a target="_blank" href='<?php echo $item->get_permalink(); ?>' title='<?php echo $item->get_title(); ?>'>
        	<?php echo $item->get_title(); ?>
		</a>
    <?php endforeach; ?>
</div><!-- / -->
</div><!-- /cjt-wrap -->
</div><!-- /wrap -->	
<?php
    if(get_option('cj_xmas_settings_check') == '0'){
        update_option('cj_xmas_settings_check' , '1');
    }
} //options page
/******************************************
 * PLUGIN SETTINGS CHECK
******************************************/
register_activation_hook(__FILE__, 'cj_xmas_settings_check');
function cj_xmas_settings_check(){
    delete_option('cj_xmas_settings_check');
    update_option('cj_xmas_settings_check', '0');
}//continued above // options page
if(get_option('cj_xmas_settings_check') == '0'){
    ob_start();
    header('location:'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=cj-xmas'); // Change URL as per page
}
/************************************************************************************************************/
/******************************************* ADMIN SETUP ENDS HERE ******************************************/
/************************************************************************************************************/
?>
<?php
function cj_xmas_top($mykey){
global $plugin_settings_name, $shortname;
$mykey = $shortname.$mykey;
$sopt = get_option($plugin_settings_name);
foreach ($sopt as $key => $opt) {
        if ($key == $mykey) {
            if(!is_array($opt)){
            return stripcslashes($opt);
            }else{
                return $opt;
            }
        }
    }
}
?>
<?php $cj_xmas_enable = cj_xmas_top('enable');
if($cj_xmas_enable == "Enable"){

?>
<?php
add_action('wp_footer', 'xmas_xhtml');
function xmas_xhtml(){ global $cj_xmas_path; ?>
<div id="xmas" class="xmas-hidden">
    <div id="xmas-santa">
	<img class="pngfix" src="<?php echo $cj_xmas_path.'/theme/'; ?>xmas-santa.png" alt="" />
    </div><!-- /xmas-santa -->
    <div id="xmas-bg" class="pngfix">
	<h1 class="xmas-h1"><?php echo cj_xmas_top('heading'); ?></h1>
	<div class="xmas-msg">
	    <?php echo cj_xmas_top('message'); ?>
	</div><!-- /xmas-msg -->
	<div id="xmas-close"><a id="xmasclose" href="#" title="Close">Close</a></div><!-- /xmas-close -->
    </div><!-- /xmas-bg -->
</div><!-- /xmas -->
<?php } ?>
<?php
add_action('wp_head', 'xmas_scripts');
function xmas_scripts(){ global $cj_xmas_path; ?>
<!-- CJ X-mas Message Plugin | Created by CSSJockey | http://www.cssjockey.com  -->
<link rel="stylesheet" href="<?php echo $cj_xmas_path.'/theme/'; ?>style.css" media="screen" />
<link rel="stylesheet" href="<?php echo $cj_xmas_path.'/theme/'; ?>scroll.css" media="screen" />
<script type="text/javascript" src="<?php echo $cj_xmas_path.'/theme/'; ?>jquery.js"></script>
<script type="text/javascript" src="<?php echo $cj_xmas_path.'/theme/'; ?>scroll.js"></script>
<script type="text/javascript" src="<?php echo $cj_xmas_path.'/theme/'; ?>rounded.js"></script>
<script type="text/javascript" src="<?php echo $cj_xmas_path.'/theme/'; ?>jquery.cookie.js"></script>
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="<?php echo $cj_xmas_path.'/theme/'; ?>styleie6.css" media="screen" />
<script type="text/javascript"> DD_roundies.addRule('.pngfix'); </script>
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="<?php echo $cj_xmas_path.'/theme/'; ?>styleie7.css" media="screen" />
<![endif]-->
<script type="text/javascript">
    var $xmas = jQuery.noConflict();
    $xmas(window).ready(function(){
	var show = $xmas.cookie("xmas");
	if(show == null){
	    $xmas('#xmas').fadeIn(300, function(){
		$xmas(this).removeClass('hidden');
	    });
	}
    })
    $xmas(document).ready(function(){
	$xmas('a#xmasclose').click(function(){
	    $xmas('#xmas').fadeOut(300, function(){
		$xmas(this).addClass('hidden');
	    })
	    $xmas.cookie("xmas", "0", {expires: <?php echo cj_xmas_top('cookie'); ?>});
	    return false;
	})

    })
    $xmas(function(){
	$xmas('.xmas-msg').jScrollPane({showArrows:true});
    });

</script>
<?php } ?>
<?php }//enable ends ?>