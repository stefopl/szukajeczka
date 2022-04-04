<?php
/**
 * Plugin Name: Counter Website by KalmusDesign
 * Plugin URI: #
 * Description: This plugin is counter websites.
 * Version: 1.0.1
 * Author: Grzegorz Kalmus
 * Author URI: ####
 */


function total_websites() {
$total = wp_count_posts( 'strona' )->publish;
echo '<h4 class="post-counter"> Wszystkich Stron: </br> ' .$total.'</h4>';
}
add_shortcode('total_jobs','total_websites');
