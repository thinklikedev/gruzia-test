<?php
class Mobile_Menu_Nav extends Walker_Nav_Menu {
    
	function start_lvl( &$output, $depth = 0, $args = NULL ) {
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' );
		$display_depth = ( $depth + 1);
		$class =  'dropdown-menu';

		$output .= "\n" . $indent . '<ul class="' . $class . '">' . "\n";
	}

	function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		global $wp_query;

		$item = $data_object;

		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		$is_parent = in_array('menu-item-has-children', $data_object->classes);
		$item->title = $is_parent ? $item->title . '<b class="caret"></b>' : $item->title;

		if ($is_parent) {
			$item->classes[] = 'dropdown';
			$args->after .= '<span class="dropdown-icon"></span>';
		}

		$output .= $indent . '<li class="' . implode(' ', $item->classes) . '">';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		if (is_numeric($item->object_id) && get_option('woocommerce_shop_page_id') == $item->object_id) {
			$categories = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => 0, 'exclude' => [91, 15]]);

			$output .= '<span class="dropdown-icon"></span>';
			$output .= '<ul class="menu-item-submenu menu-item-submenu">';

			foreach ($categories as $cat) {
				if ($cat->slug != 'akcii') {
					$output .= '<li class="menu-item">';
					$output .= '<a href="' . get_term_link($cat) . '" class="menu-link">' . $cat->name . '</a>';
					$output .= '</li>';
				}
			}
            
			$output .= '</ul>';
		}
	}

}