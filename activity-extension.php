<?php
/*
Plugin Name: ActivityStream extension
Plugin URI:
Description: An extensions which adds the ActivityStream (<a href="http://www.activitystrea.ms">activitystrea.ms</a>) syntax to your Atom-Feed
Author: Matthias Pfefferle
Version: 0.5
Author URI: http://notizblog.org
*/

if (isset($wp_version)) {
  add_action('atom_ns', array('ActivityExtension', 'addActivityNamespace'));
  add_action('atom_entry', array('ActivityExtension', 'addActivityObject'));
  add_action('atom_head', array('ActivityExtension', 'addActivityProvider'));
  add_action('comment_atom_ns', array('ActivityExtension', 'addActivityNamespace'));
  add_action('comment_atom_entry', array('ActivityExtension', 'addCommentActivityObject'));
  add_action('comments_atom_head', array('ActivityExtension', 'addActivityProvider'));
  add_action('wp_head', array('ActivityExtension', 'addHtmlHeader'), 5);
}

/**
 * ActivityStream Extension
 * 
 * @author Matthias Pfefferle
 */
class ActivityExtension {
  /**
   * echos the activitystream namespace
   */
  function addActivityNamespace() {
    echo 'xmlns:activity="http://activitystrea.ms/spec/1.0/"'." \n";
    echo 'xmlns:service="http://activitystrea.ms/service-provider"'." \n";
    echo 'xmlns:media="http://purl.org/syndication/atommedia"'." \n";
    echo 'xmlns:poco="http://portablecontacts.net/spec/1.0"'." \n";
  }

  function getDomain() {
    $url = parse_url(get_bloginfo('url'));
    return $url['host'];
  }
  
  /**
   * echos autodiscovery link
   */
  function addHtmlHeader() {
    echo '<link rel="activitystream" title="" type="application/atom+xml" href="'.get_bloginfo('atom_url').'" class="activitystream" />'."\n";
  }

  /**
   * echos the activity verb and object for the wordpress entries
   */
  function addActivityObject() {
?>
    <activity:verb>http://activitystrea.ms/schema/1.0/post</activity:verb>
    <activity:object>
      <activity:object-type>http://activitystrea.ms/schema/1.0/blog-entry</activity:object-type>
      <activity:object-type>http://activitystrea.ms/schema/1.0/article</activity:object-type>
      <id><?php the_guid(); ?></id>
      <title type="<?php html_type_rss(); ?>"><![CDATA[<?php the_title(); ?>]]></title>
      <summary type="<?php html_type_rss(); ?>"><![CDATA[<?php the_excerpt_rss(); ?>]]></summary>
      <link rel="alternate" type="text/html" href="<?php the_permalink_rss() ?>" />
    </activity:object>
<?php
  }

  /**
   * echos the service provider
   */
  function addActivityProvider() {
?>
    <service:provider>
      <name><?php bloginfo_rss('name'); wp_title_rss(); ?></name>
      <icon><?php bloginfo_rss('home') ?>/favicon.ico</icon>
      <uri><?php bloginfo_rss('home') ?></uri>
    </service:provider>
<?php
  if (is_author()) {
    if(get_query_var('author_name')) :
      $user = get_user_by('slug', get_query_var('author_name'));
    else :
      $user = get_userdata(get_query_var('author'));
    endif;
    
    $gravatar = "http://www.gravatar.com/avatar/".md5(strtolower($user->user_email));
?>
    <activity:subject>
      <activity:object-type>http://activitystrea.ms/schema/1.0/person</activity:object-type>
      <id><?php echo get_author_posts_url($user->ID, $user->user_nicename); ?></id>
      <title><?php echo $user->display_name; ?>s stream</title>
      <link rel="alternate" type="text/html" href="<?php echo get_author_posts_url($user->ID, $user->user_nicename); ?>"/>
      <link rel="avatar" type="image/jpeg" media:width="300" media:height="300" href="<?php echo $gravatar ?>?s=300" />
      <link rel="avatar" type="image/jpeg" media:width="96" media:height="96" href="<?php echo $gravatar ?>?s=96"/>
      <link rel="avatar" type="image/jpeg" media:width="48" media:height="48" href="<?php echo $gravatar ?>?s=48"/>
      <link rel="avatar" type="image/jpeg" media:width="24" media:height="24" href="<?php echo $gravatar ?>?s=24"/>
      <poco:preferredUsername><?php echo $user->user_nicename; ?></poco:preferredUsername>
      <poco:displayName><?php echo $user->display_name; ?></poco:displayName>
    </activity:subject>

<?php
    }
  }

  /**
   * echos the activity verb and object for the wordpress comments
   */
  function addCommentActivityObject() {
?>
    <activity:verb>http://activitystrea.ms/schema/1.0/post</activity:verb>
    <activity:object>
      <activity:object-type>http://activitystrea.ms/schema/1.0/comment</activity:object-type>
      <id><?php comment_guid(); ?></id>
      <content type="html" xml:base="<?php comment_link(); ?>"><![CDATA[<?php comment_text(); ?>]]></content>
      <link rel="alternate" href="<?php comment_link(); ?>" type="<?php bloginfo_rss('html_type'); ?>" />
      <thr:in-reply-to ref="<?php the_guid() ?>" href="<?php the_permalink_rss() ?>" type="<?php bloginfo_rss('html_type'); ?>" />
    </activity:object>
    <activity:target>
      <activity:object-type>http://activitystrea.ms/schema/1.0/blog-entry</activity:object-type>
      <activity:object-type>http://activitystrea.ms/schema/1.0/article</activity:object-type>
      <id><?php the_guid(); ?></id>
      <title type="<?php html_type_rss(); ?>"><![CDATA[<?php the_title(); ?>]]></title>
      <summary type="<?php html_type_rss(); ?>"><![CDATA[<?php the_excerpt_rss(); ?>]]></summary>
      <link rel="alternate" type="text/html" href="<?php the_permalink_rss() ?>" />
    </activity:target>
<?php
  }
}