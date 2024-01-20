<?php
get_header();

if (have_posts()) {
	echo '<div class="container">';

	while (have_posts()) {
		the_post();
		the_content();
	}

	echo '</div>';
}

get_footer();