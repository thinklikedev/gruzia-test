<?php
require_once 'main_menu_walker.php';
require_once 'mobile_menu_walker.php';

function wp_register_menus() {
    register_nav_menu('main-menu', 'Главное меню');
    register_nav_menu('mobile-menu', 'Мобильное меню');
}
add_action('init', 'wp_register_menus');