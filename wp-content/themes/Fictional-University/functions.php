<?php
// Include external source file
require get_theme_file_path('/includes/search-route.php');
require get_theme_file_path('/includes/like-route.php');


// Creating and calling custom RestAPI field
  function university_custom_rest() {
    register_rest_field('post', 'authorName', array(
    'get_callback' => function() {return get_the_author();}
  ));
  register_rest_field('note', 'userNoteCount', array(
  'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
  ));
  }

  add_action('rest_api_init', 'university_custom_rest');

  function pageBanner($args = NULL) {
    // Set defaults and display logic for the banner area acrros the different pages
    if (!$args['title']) {
      $args['title'] = get_the_title();
    }

    if (!$args['subtitle']) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (!$args['photo']) {
      if (get_field('page_banner_background_image')) {
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      }else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
    ?>
    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>

  <?php }

  function load_default_site_files() {
    wp_enqueue_script('search-js', get_theme_file_uri('/js/modules/Search.js'), NULL, microtime() , true);
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyBQAXkgBtfIpwbXQb2lckLp5fwQGl_Dwl4', NULL, 1.0, true);
    wp_enqueue_style('google-fonts','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('awesome-fonts','//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university-main_styles', get_stylesheet_uri());
    wp_enqueue_script('site-js-script', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
    wp_localize_script('site-js-script', 'universityData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest')
      )
    );
  }

  add_action('wp_enqueue_scripts', 'load_default_site_files');


  function university_features() {
    // block the dynamic naviagtion menus
    // register_nav_menu('headerMenuLocation', 'Header Menu Location');
    // register_nav_menu('footerMenuLeft', 'Footer Menu Left Side');
    // register_nav_menu('footerMenuRight', 'Footer Menu Right Side');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    // adding custom size images
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
  }

  add_action('after_setup_theme', 'university_features');

  function adjusted_events_query($query) {
    if (! is_admin() AND is_post_type_archive('campus') AND $query -> is_main_query()) {
      $query -> set('posts_per_page', -1);
    }
    if (! is_admin() AND is_post_type_archive('program') AND $query -> is_main_query()) {
      $query -> set('orderby', 'title');
      $query -> set('order', 'ASC');
      $query -> set('posts_per_page', -1);
    }
    if (! is_admin() AND is_post_type_archive('event') AND $query -> is_main_query()) {
      $today = date('Ymd');
      $query -> set('post_type', 'event');
      $query -> set('meta_key', 'event_date');
      $query -> set('orderby', 'meta_value_num');
      $query -> set('order', 'ASC');
      $query -> set('meta_query', array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        )
      ) );
    }
  }

  add_action('pre_get_posts', 'adjusted_events_query');

  function mapKey($api){
    $api['key'] = 'AIzaSyASst3j1S8YUBDgHYWt_zubTX18PZqhhTc';
    return $api;
  }

  add_filter('acf/fields/google_map/api', 'mapKey');


  // Redirect subscriber accounts out of admin and onto $homepageEvents

  add_action('admin_init', 'redirectSubsToFrontend');

  function redirectSubsToFrontend() {
    $ourSubscriber = wp_get_current_user();
    if (count($ourSubscriber->roles) == 1 AND $ourSubscriber->roles[0] == 'subscriber') {
      wp_redirect(site_url('/'));
      exit;
    }
  }

  add_action('wp_loaded', 'noSubsAdminBar');

  function noSubsAdminBar() {
    $ourSubscriber = wp_get_current_user();
    if (count($ourSubscriber->roles) == 1 AND $ourSubscriber->roles[0] == 'subscriber') {
      show_admin_bar(false);
    }
  }

  // Customize Login imagegrabscreen
  add_filter('login_headerurl', 'ourHeaderUrl');

  function ourHeaderUrl() {
    return esc_url(site_url('/'));
  }

  add_action('login_enqueue_scripts', 'ourLoginCSS');

  function ourLoginCSS() {
    wp_enqueue_style('university_main_styles', get_stylesheet_uri());
    wp_enqueue_style('google-fonts','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  }

  add_filter('login_headertitle', 'ourHeaderTitle');

  function ourHeaderTitle() {
    return get_bloginfo('name');
  }

// Customize logout

add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

// Force note posts to be openssl_get_private

add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
  if($data['post_type'] == 'note') {
    if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID'] ){
      die("You have reached your notes limit");
    }
    $data['post_content'] == sanitize_textarea_field($data['post_content']);
    $data['post_title'] == sanitize_text_field($data['post_title']);
  }

  if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
    $data['post_status'] = "private";
  }

  return $data;
}

?>
