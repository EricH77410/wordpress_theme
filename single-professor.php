<?

get_header();

while(have_posts()) {
	the_post(); 
	pageBanner();
	?>
	
	  <div class="container container--narrow page-section">
	  	
	  	<div class="generic-content">

			<div class="row group">
				<div class="one-third">
					<? the_post_thumbnail('professorPortrait'); ?>
				</div>
				<div class="two-thirds">
					<? 
						$likeCount = new WP_Query( array(
							'post_type' => 'like',
							'meta_query' => array(
								array(
									'key' => 'liked_professor_ID',
									'compare' => '=',
									'value' => get_the_ID()
								)
							)
						));

						$existStatus = 'no';

						if (is_user_logged_in()) {
							$existQuery = new WP_Query( array(
								'author' => get_current_user_id(),
								'post_type' => 'like',
								'meta_query' => array(
									array(
										'key' => 'liked_professor_ID',
										'compare' => '=',
										'value' => get_the_ID()
									)
								)
							));
	
							if ($existQuery->found_posts) {
								$existStatus = 'yes';
							}
						}						
 					?>
					<span class="like-box" data-like="<? echo $existQuery->posts[0]->ID; ?>" data-exists="<? echo $existStatus; ?>" data-professor="<? the_ID(); ?>">
						<i class="fa fa-heart-o" aria-hidden="true"></i>
						<i class="fa fa-heart" aria-hidden="true"></i>
						<span class="like-count"><? echo $likeCount->found_posts; ?></span>
					</span>
					<? the_content(); ?>
				</div>
			</div>  
		</div>
		  <?
			  $relatedPrograms = get_field('related_programs');

			  if ($relatedPrograms) {
				echo '<hr class="section-break">';
				echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
				echo '<ul class="link-list min-list">';
				foreach($relatedPrograms as $program) { ?>
					<li><a href="<? echo get_the_permalink($program); ?>"><? echo get_the_title($program) ?></a></li>
				  
				  <?  }  
					  echo '</ul>'; 
					}
				?> 
			  
	  </div>
<? }

get_footer();

?>