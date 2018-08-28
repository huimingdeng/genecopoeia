<?php
error_reporting(0);
require_once(dirname(__FILE__).'/../wp-blog-header.php');

$post_id = 4244;
$post = get_post($post_id);
var_dump($post);