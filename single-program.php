<?
get_header();

while(have_posts()) {
  the_post(); 
  pageBanner();
  ?>
	  <div class="container container--narrow page-section">
	  	<div class="metabox metabox--position-up metabox--with-home-link">
      		<p>
				<a class="metabox__blog-home-link" href="<? echo get_post_type_archive_link('program') ?>">
					<i class="fa fa-home" aria-hidden="true"></i> All Programs
				</a> 
				<span class="metabox__main">
                    <? the_title() ?>
				</span>
			</p>
    	</div>
	  	<div class="generic-content"><? the_field('main_body_content'); ?></div>
			
		  <?

      // Related professors
      $professors = new WP_Query(array(
        'posts_per_page'  => -1,
        'post_type'       => 'professor',
        'orderby'         => 'title',
        'order'           => 'ASC',
        'meta_query'      => array(
          array(
            'key'		=> 'related_programs',
            'compare'	=> 'LIKE',
            'value'	=> '"' . get_the_ID() . '"'
    )
        )
      ));

      if ($professors->have_posts()) {
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professor(s)</h2>';

        echo '<ul class="professor-cards">';

        while($professors->have_posts()) {
          $professors->the_post(); ?>
            <li class="professor-card__list-item">
              <a class="professor-card" href="<? the_permalink(); ?>">
                <img class="professor-card__image" src="<? the_post_thumbnail_url('professorLandscape'); ?>">
                  <span class="professor-card__name"><? the_title(); ?></span>
              </a>
            </li>
       <? } wp_reset_postdata();
       echo '</ul>';
      } 

          // related programs
          $today = date('Ymd');
          $homeEvents = new WP_Query(array(
            'posts_per_page'  => 2,
            'post_type'       => 'event',
            'meta_key'        => 'event_date',
            'orderby'         => 'meta_value_num',
            'order'           => 'ASC',
            'meta_query'      => array(
              array(
                'key'     => 'event_date',
                'compare' => '>=',
                'value'   => $today,
                'type'    => 'numeric'
			  ),
			  array(
				  'key'		=> 'related_programs',
				  'compare'	=> 'LIKE',
				  'value'	=> '"' . get_the_ID() . '"'
			  )
            )
          ));

          if ($homeEvents->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
  
            while($homeEvents->have_posts()) {
              $homeEvents->the_post(); 
              get_template_part( 'template/content', 'event' );
            } wp_reset_postdata();
          } 
          
          // Campuses
          $relatedCampus = get_field('related_campus');
          if ($relatedCampus) {
            echo '<hr class="section-break">';
           echo '<h2 class="headline headline--medium">' . get_the_title() . ' is avaible at this Campuses :</h2>';
          echo '<ul class="min-list link-list">';
           foreach($relatedCampus as $campus) {
              ?>
                <li><a href="<? echo get_the_permalink($campus) ?>"><? echo get_the_title($campus)?></a></li>
              <?
           } 
           echo '</ul>';
          }
        ?> 

	  </div>
<? }

get_footer();

?>