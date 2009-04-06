<?php
/*
Plugin Name: ActivityStream extension
Plugin URI: 
Description: An extensions which adds the ActivityStream (<a href="http://www.activitystrea.ms">activitystrea.ms</a>) syntax to your Atom-Feed
Author: Matthias Pfefferle
Version: 0.2
Author URI: http://notizblog.org
*/

if (isset($wp_version)) {
  add_action('atom_ns', array('ActivityExtension', 'addActivityNamespace'));
  add_action('atom_entry', array('ActivityExtension', 'addActivityObject'));
  add_action('comment_atom_ns', array('ActivityExtension', 'addActivityNamespace'));
  add_action('comment_atom_entry', array('ActivityExtension', 'addCommentActivityObject'));
}

/**
 *
 */
class ActivityExtension {
  
  /**
   * echos the activitystream namespace
   */
  function addActivityNamespace() {
    echo 'xmlns:activity="http://activitystrea.ms/schema/1.0/"';
  }
  
  function getDomain() {
    $url = parse_url(get_bloginfo('url'));
    return $url['host'];
  }

  /**
   * echos the activity verb and object for the wordpress entries
   */
  function addActivityObject() {
?>
    <activity:verb>http://activitystrea.ms/schema/1.0/post/</activity:verb>
    <activity:object>
      <activity:object-type>http://activitystrea.ms/schema/1.0/blog-entry/</activity:object-type>
      <activity:object-type>http://activitystrea.ms/schema/1.0/article/</activity:object-type>
      <id>tag:<?php echo self::getDomain(); ?>,<?php echo get_post_modified_time('Y-m-d', true); ?>:/post/<?php the_id(); ?></id>
      <title type="<?php html_type_rss(); ?>"><![CDATA[<?php the_author() ?> posted a new blog-entry]]></title>
      <link rel="alternate" type="text/html" href="<?php the_permalink_rss() ?>" />
    </activity:object>
<?php
  }
  
  /**
   * echos the activity verb and object for the wordpress comments
   */
  function addCommentActivityObject() {
?>
    <activity:verb>http://activitystrea.ms/schema/1.0/post/</activity:verb>
    <activity:object>
      <id>tag:<?php echo self::getDomain(); ?>,<?php echo get_post_modified_time('Y-m-d', true); ?>:/comment/<?php comment_id(); ?></id>
      <title type="<?php html_type_rss(); ?>"><![CDATA[<?php comment_author_rss() ?> posted a comment]]></title>
      <link rel="alternate" type="text/html" href="<?php comment_link() ?>" />
      <thr:in-reply-to ref="<?php the_guid() ?>" href="<?php the_permalink_rss() ?>" type="<?php bloginfo_rss('html_type'); ?>" />
    </activity:object>
<?php
  }
}