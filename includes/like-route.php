<?

add_action('rest_api_init', 'likeRoutes');

function likeRoutes () {
    register_rest_route('like/v1','manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route('like/v1','manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {

    if (is_user_logged_in()) {
        $profId = sanitize_text_field($data['professorId']);
        
        $existQuery = new WP_Query( array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_ID',
                    'compare' => '=',
                    'value' => $profId
                )
            )
        ));

        if ($existQuery->found_posts == 0 AND get_post_type($profId) == 'professor') {
            // Create new like
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => '2nd PHP create post test',
                'meta_input' => array(
                    'liked_professor_id' => $profId
                )
            ));
        } else {
            die('Invalid prof id');
        }        
    } else {
        die('Only logged in users can like');
    }
}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['like']);
    if (get_current_user_id()== get_post_field('post_author',$likeId) AND
     get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true);
        return 'Like deleted';
    } else {
        die ("You do not have the permission");
    }   
}