<?php
//print_r($wp_query);
genesis_doctype();
genesis_title();
genesis_meta();

wp_head(); // we need this for plugins
?>
</head>
<body <?php body_class(); ?>>
<?php genesis_before(); ?>

<div id="wrap">
<?php genesis_before_header(); ?>
<?php genesis_header(); ?>
<?php genesis_after_header(); ?>
<div id="inner">