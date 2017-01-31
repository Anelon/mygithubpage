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
<?php genesis_before(); ?>

<div id="wrap">

<?php genesis_before_header(); ?>
<?php genesis_header(); ?>
<?php genesis_after_header(); ?>

<div id="inner">