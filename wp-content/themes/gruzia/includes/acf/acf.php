<?php

add_filter('acf/settings/save_json', function ( $path ) {
    return get_stylesheet_directory() . '/includes/acf/acf-json';
});

add_filter('acf/settings/load_json', function( $paths ) {
    return [get_stylesheet_directory() . '/includes/acf/acf-json'];
});