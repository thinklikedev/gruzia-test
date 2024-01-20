<?php
register_post_type('promotion', [
	'label' => 'promotion',
	'labels' => [
		'name'               => 'Акции',
		'singular_name'      => 'Акция',
		'add_new'            => 'Добавить новую',
		'add_new_item'       => 'Добавление акции',
		'edit_item'          => 'Редактирование акции',
		'new_item'           => 'Новая акция',
		'view_item'          => 'Смотреть акцию',
		'search_items'       => 'Искать акцию',
		'not_found'          => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в корзине',
		'parent_item_colon'  => '',
		'menu_name'          => 'Акции',
	],
	'public'             => true,
	'menu_position'      => 50,
	'menu_icon'          => 'dashicons-megaphone',
	'hierarchical'       => false,
	'capability_type'    => 'post',
	'supports'           => ['title', 'editor', 'thumbnail'],
	'has_archive' 		 => 'akcii',
	'rewrite' 		 	 => [
		'slug' => 'akcii'
	],
]);