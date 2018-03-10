<div class="post-item">
      <h2 class="headline headline--medium headline--post-title"><a href="<? the_permalink(); ?>"><? the_title() ?></a></h2>
      
      <div class="metabox">
        <p>Posted by <? the_author_posts_link();  ?> on <? the_time('d/m/Y'); ?> in <? echo get_the_category_list(', '); ?></p>
      </div>

      <div class="generic-content">
        <? the_excerpt(); ?>
        <p><a class="btn btn--blue" href="<? the_permalink() ?>">Continue reading ...</a></p>
      </div>
    </div>