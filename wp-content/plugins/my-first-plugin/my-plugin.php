<?php

/*
Plugin Name: My Plugin
Description: Plugin Tutorial
*/

add_filter('the_content', 'autoContentAddition');

function autoContentAddition($content) {

  $content = $content . '<p>All conetnt belongs to Fictional University.</p>';
  return $content;
}

add_shortcode('programsCount', 'programsCountFunction');

function programsCountFunction() {
  $programCount = wp_count_posts('program');
  return $programCount->publish;
}
