<?php
add_action('init', function() {
	require_once 'post-type-callback.php';
	require_once 'post-type-promotion.php';
	require_once 'post-type-job.php';
	require_once 'post-type-gallery_item.php';
});