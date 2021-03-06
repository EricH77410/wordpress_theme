<?

require get_theme_file_path('/includes/search-route.php');
require get_theme_file_path('/includes/like-route.php');

// Custom fields for rest api
function custom_rest () {
	register_rest_field('post','authorName', array(
		'get_callback' => function() {return get_the_author();}
	));

	register_rest_field('note','noteCount', array(
		'get_callback' => function() {return count_user_posts(get_current_user_id(),'note');}
	));
}

add_action('rest_api_init', 'custom_rest');



function pageBanner($args = null) {
	if (!$args['title']) {
		$args['title'] = get_the_title();
	}

	if (!$args['subtitle']) {
		$args['subtitle'] = get_field('page_banner_subtitle');
	}

	if (!$args['photo']) {
		if (get_field('page_banner_background')) {
			$args['photo'] = get_field('page_banner_background')['sizes']['pageBanner'];
		} else {
			$args['photo'] = get_theme_file_uri('/images/ocean.jpg');
		}
	}

	?>
		<div class="page-banner">
    		<div class="page-banner__bg-image" style="background-image: url(<? echo $args['photo']; ?>);"></div>
    			<div class="page-banner__content container container--narrow">
      				<h1 class="page-banner__title"><? echo $args['title']; ?></h1>
      				<div class="page-banner__intro">
        				<p><? echo $args['subtitle'] ?></p>
      				</div>
    			</div>  
  		</div>
<?}

function university_files() {
	wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyBKH4KlBvFEeufyeZDkkNZgnoy5qQpXaR0', NULL, '1.0', true);
	wp_enqueue_script('main-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
	wp_enqueue_style('ggl-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('font-awsome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('university_main_styles', get_stylesheet_uri());
	
	// Crée un objet JS directement dans le main document
	wp_localize_script('main-js','universityData', array(
		'root_url'	=> get_site_url(),
		'nonce'		=> wp_create_nonce('wp_rest')
	));
}

add_action('wp_enqueue_scripts','university_files');
add_action('after_setup_theme','university_features');

function university_features() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('professorLandscape', 400, 260, true);
	add_image_size('professorPortrait', 480, 650, true );
	add_image_size( 'pageBanner', 1500, 350, true );
}

function university_adjust_query($query) {

	if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1);
	}

	if (!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
		$query->set('posts_per_page', -1);
	}

	if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
		$today = date('Ymd');
		$query->set('meta_key','event_date');
		$query->set('orderby','meta_value_num');
		$query->set('order','ASC');
		$query->set('meta_query', array(
			array(
				'key'		=> 'event_date',
				'compare' 	=> '>=',
				'value'		=> $today,
				'type'		=> 'numeric' 
			)
			));
	}
}

function university_map_key($api) {
	$api['key'] = 'AIzaSyBKH4KlBvFEeufyeZDkkNZgnoy5qQpXaR0';
	return $api;
}

add_action('pre_get_posts','university_adjust_query');
add_filter( 'acf/fields/google_map/api', 'university_map_key');

// Redirect subscriber out of admi nand onto home
add_action( 'admin_init', 'redirectSubs');

function redirectSubs() {
	$user = wp_get_current_user();

	if (count($user->roles) == 1 AND $user->roles[0] == 'subscriber') {
		wp_redirect(site_url('/'));
		exit;
	}
}

add_action( 'wp_loaded', 'noAdminBar');

function noAdminBar() {
	$user = wp_get_current_user();

	if (count($user->roles) == 1 AND $user->roles[0] == 'subscriber') {
		show_admin_bar( false );
	}
}

// Custom login screen
add_filter('login_headerurl','headerUrl');

function headerUrl() {
	return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'loginCss');

function loginCss () {
	wp_enqueue_style('university_main_styles', get_stylesheet_uri());
	wp_enqueue_style('ggl-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_filter('login_headertitle', 'loginTitle');

function loginTitle() {
	return get_bloginfo('name');
}

// Force note post to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postArr) {

	if ($data['post_type']=='note') {

		if(count_user_posts(get_current_user_id(),'note') > 5 AND !$postArr['ID']) {
			die('You have reached your note limit !');
		}

		$data['post_content'] = sanitize_textarea_field($data['post_content']);
		$data['post_title'] = sanitize_text_field($data['post_title']);
	}

	if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
		$data['post_status'] = 'private';
	}
	
	return $data;
}