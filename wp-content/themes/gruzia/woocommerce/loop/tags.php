<?php
global $wpdb;

$cat_id = (int)get_queried_object_id();

if ($cat_id) :
	$products_id = $wpdb->get_results("SELECT ID FROM wp_posts INNER JOIN wp_term_relationships tr ON (ID = tr.object_id) WHERE post_status = 'publish' AND (SELECT COUNT(t.term_id) FROM wp_term_relationships tr2 INNER JOIN wp_terms t ON (tr2.term_taxonomy_id = t.term_id) WHERE tr2.object_id = ID AND t.slug = 'delivery_hide') = 0 AND tr.term_taxonomy_id = $cat_id");
	$products_id = array_map(function($p) { return $p->ID; }, $products_id);

	$tags = $wpdb->get_results("SELECT DISTINCT t.term_id, t.name FROM wp_terms t INNER JOIN wp_term_relationships tr ON (tr.term_taxonomy_id = t.term_id) INNER JOIN wp_term_taxonomy tt ON (tt.term_id = t.term_id) WHERE tt.taxonomy = 'product_tag' AND tr.object_id IN (". implode(",", $products_id) .")");

	$current_tags = isset($_GET['tag_id']) ? explode(',', $_GET['tag_id']) : [];

	?>

	<?php if ($tags) : ?>
		<div class="product-tags">
			<ul>
				<?php foreach ($tags as $tag) : ?>
					<li class="<?= in_array($tag->term_id, $current_tags) ? 'active' : ''; ?>" data-tag-id="<?= $tag->term_id; ?>"><?= $tag->name; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif;
endif;