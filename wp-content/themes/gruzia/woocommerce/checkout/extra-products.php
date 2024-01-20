<?php
global $wpdb;

$extra_prods_cats = $wpdb->get_results("SELECT t.term_id, t.name FROM {$wpdb->terms} t INNER JOIN {$wpdb->termmeta} tm ON (t.term_id = tm.term_id) WHERE tm.meta_key = 'is_extra' AND tm.meta_value = 1", OBJECT_K);

$all_prod_cats = [];

foreach (WC()->cart->get_cart() as $key => $item) {
	$prod_cats = get_the_terms($item['data']->get_id(), 'product_cat') ?: [];

	foreach ($prod_cats as $cat) {
		$all_prod_cats[] = $cat->term_id;
	}
}

$extra_prods = [];

foreach ($extra_prods_cats as $cat) {
	$apply_to_cats = get_field('extra_products', 'term_' . $cat->term_id);

	foreach ($apply_to_cats as $cat_id) {
		if (!isset($extra_prods[$cat->term_id]) && in_array($cat_id, $all_prod_cats)) {
			$products = wc_get_products([
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'tax_query' => [
					[
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => [$cat->term_id]
					]
				]
			]);

			foreach ($products as $product) {
				$extra_prods[$cat->term_id][$product->get_id()] = $product;
			}
		}
	}
}

$cat_selected = array_key_first($extra_prods);
$prods_quantity = [];

foreach (WC()->cart->get_cart() as $cart_item) {
	$prods_quantity[$cart_item['data']->get_id()] = $cart_item['quantity'];
}

?>
<?php if ($extra_prods) : ?>
	<div class="extra-products-popup" style="display: none">
		<div class="extra-products">
			<h4>Что нибудь еще?</h4>
			<span class="close"></span>

			<ul class="extra-prods-cats">
				<?php foreach ($extra_prods as $cat_id => $value) : ?>
					<li class="<?= $cat_id == $cat_selected ? 'active' : ''; ?>" data-cat-id="<?= $cat_id; ?>"><?= $extra_prods_cats[$cat_id]->name; ?></li>
				<?php endforeach; ?>
			</ul>

			<div class="ep-list">
				<?php foreach ($extra_prods as $cat_id => $products) :
					foreach ($products as $id => $product) :
						$prod_image = get_the_post_thumbnail_url($id, 'medium') ?: wc_placeholder_img_src('medium');
						?>
						<div class="ep-item post-<?= $id; ?>" data-cat-id="<?= $cat_id; ?>" style="<?= $cat_selected != $cat_id ? 'display: none;' : ''; ?>">
							<a href="<?= get_the_permalink($id); ?>" class="ep-image">
						    	<img src="<?= $prod_image; ?>">
						    </a>
						    <a href="<?= get_the_permalink($id); ?>" class="ep-title"><?= $product->get_name(); ?></a>
						    <div class="ep-actions">
							    <div class="ep-price"><?= $product->get_price(); ?>р</div>
							    <div class="ep-buttons">
							        <div class="quantity-minus" data-cart_item_key="<?= WC()->cart->generate_cart_id($id); ?>"></div>
							        <div class="ep-quantity quantity-number"><span><?= $prods_quantity[$id] ?? 0; ?></span></div>
							        <div class="quantity-plus add_to_cart_button ajax_add_to_cart" data-product_id="<?= $id; ?>"></div>
							    </div>
							</div>
						</div>
					<?php endforeach;
				endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>