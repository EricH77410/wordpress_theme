<?
  get_header();
  pageBanner(array(
      'title' => 'All Programs',
      'subtitle' => 'There is something for everyone. Have a look around!'
  ));
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <? while(have_posts()) {
            the_post(); ?>
            <li><a href="<? the_permalink() ?>"><? the_title() ?></a></li>
        <?  
        } 
        echo paginate_links();
        ?>
    </ul>  
</div>
<?
  get_footer();
?>