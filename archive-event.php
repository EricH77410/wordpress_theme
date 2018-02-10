<?
  get_header();
?>

<div class="page-banner">
    	<div class="page-banner__bg-image" style="background-image: url(<? echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
    		<div class="page-banner__content container container--narrow">
      		<h1 class="page-banner__title">
                All Events
          </h1>
      	<div class="page-banner__intro">
        	<p>See what is going on in our world.</p>
      	</div>
    	</div>  
</div>

<div class="container container--narrow page-section">
  <? while(have_posts()) {
    the_post(); ?>
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<? the_permalink() ?>">
                <span class="event-summary__month"><? the_time('M') ?></span>
                <span class="event-summary__day"><? the_time('d') ?></span>  
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="<? the_permalink() ?>"><? the_title() ?></a></h5>
                <p> <? echo wp_trim_words( get_the_content(), 18 ) ?> <a href="<? the_permalink() ?>" class="nu gray">Learn more</a></p>
            </div>
        </div>
  <?  
  } 
    echo paginate_links();
  ?>



</div>
<?
  get_footer();
?>