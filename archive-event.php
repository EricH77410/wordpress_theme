<?
  get_header();
  pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world'
  ));
?>


<div class="container container--narrow page-section">
  <? while(have_posts()) {
    the_post(); 
    get_template_part( 'template/content', 'event' );  
  } 
    echo paginate_links();
  ?>
  <hr class="section-break">
  <p>Looking for a recap of past events ? <a href="<? echo site_url('/past-events') ?>">Check out our past events archive</a></p>

</div>
<?
  get_footer();
?>