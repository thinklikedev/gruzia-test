<?php
register_post_type('job', [
	'label'  => null,
		'labels' => [
			'name'               => 'Вакансии',
			'singular_name'      => 'Вакансия',
			'add_new'            => 'Добавить вакансию',
			'add_new_item'       => 'Добавление вакансии',
			'edit_item'          => 'Редактирование вакансии',
			'new_item'           => 'Новая вакансия',
			'view_item'          => 'Смотреть вакансии',
			'search_items'       => 'Искать вакансии',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon'  => '',
			'menu_name'          => 'Вакансии',
		],
		'public'             => true,
		// 'publicly_queryable' => true,
		'query_var' 		 => false,
		'menu_position'      => 50,
		'menu_icon'          => 'dashicons-businesswoman',
		'hierarchical'       => false,
		'capability_type'    => 'post',
		'supports'           => ['title', 'editor'],
		'has_archive' 		 => 'jobs',
		'rewrite' 		 	 => [
			'slug' => 'jobs'
		],
]);