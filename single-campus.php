<?
get_header();

while(have_posts()) {
  the_post(); 
  pageBanner();
  ?>
	  <div class="container container--narrow page-section">
	  	<div class="metabox metabox--position-up metabox--with-home-link">
      		<p>
				<a class="metabox__blog-home-link" href="<? echo get_post_type_archive_link('campus') ?>">
					<i class="fa fa-home" aria-hidden="true"></i> All Campuses
				</a> 
				<span class="metabox__main">
                    <? the_title() ?>
				</span>
			</p>
    	</div>
	  	<div class="generic-content"><? the_content(); ?></div>

          <div class="acf-map"/
          >
            <? $map = get_field('map_location'); ?>

            <div class="marker" data-lat="<? echo $map['lat'] ?>" data-lng="<? echo $map['lng'] ?>">
                <h3><? the_title() ?></h3>
                <? echo $map['address'] ?>
            </div>
    </div> 
		  <?

      // Related professors
      $programs = new WP_Query(array(
        'posts_per_page'  => -1,
        'post_type'       => 'program',
        'orderby'         => 'title',
        'order'           => 'ASC',
        'meta_query'      => array(
          array(
            'key'		=> 'related_campus',
            'compare'	=> 'LIKE',
            'value'	=> '"' . get_the_ID() . '"'
    )
        )
      ));

      if ($programs->have_posts()) {
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Programs Available At This Campus</h2>';

        echo '<ul class="min-list link-list">';

        while($programs->have_posts()) {
          $programs->the_post(); ?>
            <li>
                <a href="<? the_permalink(); ?>"><? the_title(); ?></a>
            </li>
       <? } wp_reset_postdata();
       echo '</ul>';
      } ?>

          

	  </div>
<? }

get_footer();

?>