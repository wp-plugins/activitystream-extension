<?php
/**
 * json template
 */
$output = array();
while (have_posts()) {
  the_post();
  $output['items'][] = array('postTime' => get_post_modified_time('Y-m-d\TH:i:s\Z', true),
                             'verb' => 'post',
                             'target' => array('id' => get_feed_link('json'),
                                     'permalinkUrl' => get_feed_link('json'),
                                     'objectType' => 'blog',
                                     'displayName' => get_bloginfo('name')
                             ),
                             'object' => array('id' => get_the_guid(),
                                     'displayName' => get_the_title(),
                                     'objectType' => 'article',
                                     'summary' => get_the_excerpt(),
                                     'postTime' => get_the_time('F j, Y H:i'),
                                     'permalinkUrl' => get_permalink()
                              ),
                              'actor' => array('id' => get_author_posts_url(get_the_author_meta('id'), get_the_author_meta('nicename')),
                                     'displayName' => get_the_author(),
                                     'objectType' => 'person',
                                     'permalinkUrl' => get_author_posts_url(get_the_author_meta('id'), get_the_author_meta('nicename')),
                                     'image' => array('width' => 80,
                                                      'height' => 80,
                                                      'url' => 'http://www.gravatar.com/avatar/'.md5( get_the_author_meta('email') ).'.jpg')
                              )
                        );
  
  // add your own data
  $output = apply_filters('activitystream_json', $output);
}

header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);

// check callback param
if ($callback = get_query_var('callback')) {
  echo $callback.'('.json_encode($output).');';
} else {
  echo json_encode($output);
}
?>