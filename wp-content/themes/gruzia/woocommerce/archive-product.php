<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

?>
<div class="page-content">

<section id="products-archive">
    <div class="container">
<?php
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	// do_action( 'woocommerce_before_shop_loop' );
	?>

	<div id="sidebar">
		<span>
			<img src="<?= get_template_directory_uri(); ?>/images/forkandspoon.png">
			<h1 class="menu-title">Меню ресторана</h1>
		</span>
	    <?php
	    // 91 - упаковки
	    $categories = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'exclude' => [91, 15]]);
		?>

	    <ul id="catalog-cats">
    	<?php
    	$index = 0;
    	foreach ($categories as $cat) :
    		$index++;
    		if ($index > 4) $index = 1;

    		if ($cat->slug == 'akcii') {
				continue;
			}

    		$current = strpos($_SERVER['REQUEST_URI'], "category/{$cat->slug}") !== false;
    		$tag = $current ? 'span' : 'a';
    		// $icon = wp_get_attachment_image_url(get_field('icon', $cat));
    		$icon = "/test/$index.png";
    		?>
	    	<li>
	    		<a href="<?= get_term_link($cat); ?>" class="<?= $current ? 'current' : ''; ?>">
	    			<?= get_field('icon', "term_{$cat->term_id}"); ?>
	    			<?= $cat->name; ?>
	    		</a>
	    	</li>
	    <?php endforeach; ?>
		</ul>
	</div>

	<div class="products-list">
		<div class="breadcrumbs_wrapper container">
			<div class="breadcrumbs">
				<ul>
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
						<a href="/" itemprop="url"><span itemprop="title">Ресторан Грузия</span></a>
					</li>
					<?php if (is_product_category()) : ?>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<a style="display:none;" href="<?php echo get_term_link($GLOBALS['curr_cat']) ?>" itemprop="url"><span itemprop="title">Меню ресторана</span></a>
						</li>
					<?php endif; ?>
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><?php woocommerce_page_title(); ?></li>
				</ul>

				<h1><?php woocommerce_page_title(); ?></h1>
			</div>
		</div>
		
		<!-- START filter attr block-->
		<?php 

			// get slug of the category
			$cat_slug = get_queried_object()->slug;

			// get term by the cat slug
			$category = get_term_by( 'slug', $cat_slug , 'product_cat' );

			// get all products by the cat slug
			$products = get_posts(array('post_type' => 'product','posts_per_page' => -1, 'product_cat' => $cat_slug ));

			$arrOfAttrs = array();
			foreach ($products as $product) {
				if ( ! wc_get_product_terms($product->ID, 'pa_tip')) continue;
				$arrOfAttrs[] = array(
					'id'    => $product->ID,
					'attr'  => wc_get_product_terms($product->ID, 'pa_tip')
				);
			}

			function get_attr_links($arrOfAttrs = []) {
				$attr_links = [];
				foreach ($arrOfAttrs as $attrs) {
					if(empty($attrs['attr'])) continue;
					foreach ($attrs as $key => $attr) {
						if($key !== 'attr') continue;
						foreach ($attr as $one_attr) {
							$attr_links[] = $one_attr;
						}
					}
				}

				return array_unique($attr_links, SORT_REGULAR);
			}
		?>

		<?php if ((count($arrOfAttrs) > 0 )) : ?>
		<div class="categories_list filterAttr">
		<!-- show all attributtes from specific cat -->
		<ul>
			<?php $arrOfLinks = get_attr_links($arrOfAttrs); ?>
			<?php foreach ($arrOfLinks as $attr) : ?>
					<li><a data-attr="<?= $attr->slug; ?>"><?= $attr->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
		</div>
		<!-- END filter attr block-->
		<?php endif; ?>

		<?php
		wc_get_template('loop/tags.php');

		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}

		woocommerce_product_loop_end();

		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );
	?>
	</div>
	<?php
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

?>
			<?php if (isset($_GET['casta-test'])) :
				$field_name = is_toscana() ? 'toscana_seo_descr' : 'seo_descr';
				?>
				<?php if ($seo_descr = get_field($field_name, $page_id)) : ?>
					<div class="page-description">
						<div class="page-descr-content">
							<?= $seo_descr; ?>
						</div>
						<button>Показать полностью</button>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
    </div>
</section>

</div>

<?php 
get_footer( 'shop' );