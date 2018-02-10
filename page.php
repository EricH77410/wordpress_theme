<?

get_header();

while(have_posts()) {
	the_post(); ?>

	<div class="page-banner">
    	<div class="page-banner__bg-image" style="background-image: url(<? echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
    		<div class="page-banner__content container container--narrow">
      		<h1 class="page-banner__title"><? the_title(); ?></h1>
      	<div class="page-banner__intro">
        	<p>We should see the page subtitle</p>
      	</div>
    	</div>  
  </div>

  <div class="container container--narrow page-section">
	<? 
		$theParent = wp_get_post_parent_id(get_the_ID());
		if ($theParent) {
	?>
		<div class="metabox metabox--position-up metabox--with-home-link">
      		<p>
						<a class="metabox__blog-home-link" href="<? echo get_permalink($theParent); ?>">
							<i class="fa fa-home" aria-hidden="true"></i> Back to <? echo get_the_title($theParent); ?>
						</a> 
						<span class="metabox__main"><? the_title(); ?></span>
					</p>
    	</div>
	<?
		}
	?>
	
	<? 
	$testArray = get_pages(array(
		'child_of' => get_the_ID()
	));
	if ($theParent or $testArray) { ?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<? echo get_permalink($theParent); ?>"><? echo get_the_title($theParent)?></a></h2>
      <ul class="min-list">
        <?
        	if ($theParent) {
        		$childrenOf = $theParent;
        	} else {
        		$childrenOf = get_the_ID();
        	}

        	wp_list_pages(array(
        		'title_li' => NULL,
        		'child_of' => $childrenOf,
        		'sort_column' => 'menu_order'
        	));
        ?>
      </ul>
    </div>
    <? } ?>

    <div class="generic-content">
    	<? the_content(); ?>
    </div>

  </div>

<? }

get_footer();

?>