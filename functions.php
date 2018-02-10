<?

function university_files() {
	wp_enqueue_script('main-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
	wp_enqueue_style('ggl-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('font-awsome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('university_main_styles', get_stylesheet_uri());

}

add_action('wp_enqueue_scripts','university_files');
add_action('after_setup_theme','university_features');

function university_features() {
	add_theme_support('title-tag');
	//register_nav_menu('headerMenu', 'Header Menu');
	//register_nav_menu('footerMenu1', 'Footer Menu 1');
	//register_nav_menu('footerMenu2', 'Footer Menu 2');
}

