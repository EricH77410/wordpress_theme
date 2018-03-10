<?
    if (!is_user_logged_in()) {
        wp_redirect(esc_url(site_url('/')));
        exit;
    }
get_header();

while(have_posts()) {
	the_post(); 
	pageBanner();
	?>

  <div class="container container--narrow page-section">

    <div class="create-note">
        <h2 class="headline headline--medium">Create New Note</h2>
        <input class="new-note-title" type="text" placeholder="Title">
        <textarea class="new-note-body" placeholder="Your note here..."></textarea>
        <span class="submit-note">Create Note</span>
        <span class="note-limit-message">Note limit reached, Delete a note to enter a new one</span>
    </div>

    <ul class="min-list link-list" id="my-notes">
        <? 
            $userNotes = new WP_Query(array(
                'post_type' => 'note',
                'post_per_page' => -1,
                'author' => get_current_user_id()
            ));

            while($userNotes->have_posts()) {
                $userNotes->the_post(); ?>
                <li data-id="<? the_ID(); ?>">
                    <input readonly class="note-title-field" type="text" value="<? echo str_replace('Privé : ','',esc_attr(get_the_title()));?>">
                    <span class="edit-note"><i class="fa fa-pencil" area-hidden="true"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" area-hidden="true"></i> Delete</span>
                    <textarea readonly class="note-body-field"><? echo esc_attr(get_the_content()); ?></textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" area-hidden="true"></i> Save</span>
                </li>
            <? }
        ?>
    </ul>
  </div>

<? }

get_footer();

?>