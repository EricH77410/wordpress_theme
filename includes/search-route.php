<?

add_action('rest_api_init', 'registerSearch');

function registerSearch () {
    register_rest_route( 'university/v1', 'search', array(
        'methods'   => WP_REST_SERVER::READABLE,
        'callback'  => 'searchResults'
    ));
}

function searchResults ($data) {
    $mainQuery = new WP_Query(array(
       'post_type'  => array('post','page','professor','program','event', 'campus'),
       's'          => sanitize_text_field($data['term'])
    ));

    $results = array(
        'generalInfo'   => array(),
        'professors'    => array(),
        'programs'      => array(),
        'events'        => array(),
        'campuses'      => array()
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if(get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'url'   => get_the_permalink(),
                'postType'  => get_post_type(),
                'authorName' => get_the_author()
            ));
        }

        if(get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'url'   => get_the_permalink(),
                'photo' => get_the_post_thumbnail_url(0,'professorLandscape')
            ));
        }

        if(get_post_type() == 'program') {
            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses) {
                foreach($relatedCampuses as $campus) {
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'url'   => get_the_permalink($campus)
                    ));
                }
            }
            

            array_push($results['programs'], array(
                'title' => get_the_title(),
                'url'   => get_the_permalink(),
                'id'    => get_the_ID()
            ));
        }

        if(get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $desc = null;
            if (has_excerpt()){
                $desc = get_the_excerpt();
            } else {
                $desc = wp_trim_words( get_the_content(), 18 ); 
            }            

            array_push($results['events'], array(
                'title' => get_the_title(),
                'url'   => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day'   => $eventDate->format('d'),
                'desc'  => $desc
            ));
        }

        if(get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'url'   => get_the_permalink()
            ));
        }        
    }

    if ($results['programs']) {
        $programsMetaQuery = array('relation' => 'OR');

    foreach($results['programs'] as $item) {
        array_push($programsMetaQuery, array(
            'key'       => 'related_programs',
            'compare'   => 'LIKE',
            'value'     => '"' . $item['id'] . '"'
        ));
    }    

    $programRelationship = new WP_Query( array(
        'post_type'     => array('professor','event'),
        'meta_query'    => $programsMetaQuery
    ));

        while($programRelationship->have_posts()) {
            $programRelationship->the_post();

            if(get_post_type() == 'professor') {
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'url'   => get_the_permalink(),
                    'photo' => get_the_post_thumbnail_url(0,'professorLandscape')
                ));
            }

            if(get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $desc = null;
                if (has_excerpt()){
                    $desc = get_the_excerpt();
                } else {
                    $desc = wp_trim_words( get_the_content(), 18 ); 
                }            
    
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'url'   => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day'   => $eventDate->format('d'),
                    'desc'  => $desc
                ));
            }
        }
    }    

    $results['professors'] = array_values(array_unique($results['professors'],SORT_REGULAR));
   // $results['events'] = array_values(array_unique($results['events'],SORT_REGULAR));
    return $results;
}