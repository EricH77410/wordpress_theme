<?

get_header();

while(have_posts()) {
	the_post(); 
	pageBanner();
	?>
	  <div class="container container--narrow page-section">
	  	<div class="metabox metabox--position-up metabox--with-home-link">
      		<p>
				<a class="metabox__blog-home-link" href="<? echo site_url('/blog') ?>">
					<i class="fa fa-home" aria-hidden="true"></i> Blog Home
				</a> 
				<span class="metabox__main">
				Posted by <? the_author_posts_link();  ?> on <? the_time('d/m/Y'); ?> in <? echo get_the_category_list(', '); ?>
				</span>
			</p>
    	</div>
	  	<div class="generic-content">
	  		<? the_content(); ?> 
	  	</div>
	  </div>
<? }

get_footer();

?>