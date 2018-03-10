<?

get_header();

while(have_posts()) {
	the_post(); 
	pageBanner();
	?>

	

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
    	<? get_search_form(); ?>
    </div>

  </div>

<? }

get_footer();

?>