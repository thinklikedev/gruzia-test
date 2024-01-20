<?php
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Настройки сайта',
        'menu_title' => 'Настройки сайта',
        'menu_slug'  => 'site_settings',
    ));
    acf_add_options_sub_page(array(
        'page_title'  => 'Контакты',
        'menu_title'  => 'Контакты',
        'parent_slug' => 'site_settings',
        'menu_slug'   => 'company_contacts',
    ));
    acf_add_options_sub_page(array(
        'page_title'  => 'Другие',
        'menu_title'  => 'Другие',
        'parent_slug' => 'site_settings',
        'menu_slug'   => 'other_settings',
    ));
}