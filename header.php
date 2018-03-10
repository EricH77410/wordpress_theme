<!DOCTYPE html>
<html <? language_attributes(); ?>>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<meta charset="<? bloginfo('charset') ?>">
		<? wp_head(); ?>
		<title>Document</title>
	</head>
	<body <? body_class(); ?>>
		<header class="site-header">
    		<div class="container">
      			<h1 class="school-logo-text float-left"><a href="<? echo site_url() ?>"><strong>Fictional</strong> University</a></h1>
      			<a href="<? echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
      			<i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
			      <div class="site-header__menu group">
			        <nav class="main-navigation">
			          <ul>
			            <li <? if(is_page('about-us') or wp_get_post_parent_id(0) == 33) echo 'class="current-menu-item"' ?>><a href="<? echo site_url('/about-us') ?>">About Us</a></li>
			            <li <? if(get_post_type() == 'program') echo 'class="current-menu-item"' ?>><a href="<? echo get_post_type_archive_link('program'); ?>">Programs</a></li>
			            <li <? if(get_post_type() == 'event' OR is_page('past-events')) echo 'class="current-menu-item"' ?>><a href="<? echo get_post_type_archive_link('event') ?>">Events</a></li>
			            <li <? if(get_post_type() == 'campus') echo 'class="current-menu-item"' ?>><a href="<? echo get_post_type_archive_link('campus'); ?>">Campuses</a></li>
			            <li <? if(get_post_type()=='post') echo 'class="current-menu-item"' ?>><a href="<? echo site_url('/blog') ?>">Blog</a></li>
			          </ul>

			        </nav>
			        <div class="site-header__util">
								<? if(is_user_logged_in()) { ?>
									<a href="<? echo esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--orange float-left push-right">My Notes</a>
									<a href="<? echo wp_logout_url() ?>" class="btn btn--small  btn--dark-orange float-left btn--with-photo">
										<span class="site-header__avatar"><? get_avatar(get_current_user_id(), 60) ?></span>
										<span class="btn__text">Log Out</span>
									</a>
								<? } else { ?>
									<a href="<? echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
			          	<a href="<? echo wp_registration_url(); ?>" class="btn btn--small  btn--dark-orange float-left">Sign Up</a>
								<? } ?>
			          
			          <a href="<? esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
			        </div>
			      </div>
			</div>
		</header>