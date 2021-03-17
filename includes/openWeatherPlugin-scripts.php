<?php

function owp_add_scripts() {
    //Add Google Fonts
    wp_enqueue_style('yts-main-style', plugins_url().'/openWeatherPlugin/css/style.css');
    //Add Main CSS
    wp_enqueue_style('yts-main-style', plugins_url().'/openWeatherPlugin/css/style.css');
    //Add Main JS
    wp_enqueue_script('yts-main-script', plugins_url().'/openWeatherPlugin/js/main.js');
}

    add_action('wp_enqueue_scripts', 'owp_add_scripts');
    