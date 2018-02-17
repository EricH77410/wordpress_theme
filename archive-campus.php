<?
  get_header();
  pageBanner(array(
      'title' => 'Our Campuses',
      'subtitle' => 'We have several campuses in your area!'
  ));
?>

<div class="container container--narrow page-section">
    <div class="acf-map"/>
        <? while(have_posts()) {
            the_post(); 
            $map = get_field('map_location');
            ?>
            
            <div class="marker" data-lat="<? echo $map['lat'] ?>" data-lng="<? echo $map['lng'] ?>">
                <h3><a href="<? the_permalink(); ?>"><? the_title() ?></a></h3>
                <? echo $map['address'] ?>
            </div>
        <? } ?>
    </div>  
</div>
<?
  get_footer();
?>