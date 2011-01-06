=== ActivityStream extension ===
Contributors: pfefferle
Donate link: http://notizblog.org
Tags: Activities, Activity Stream, Feed, RSS, Atom, OStatus, OStatus Stack
Requires at least: 2.5
Tested up to: 3.9
Stable tag: 0.5

An extensions which adds the ActivityStream ([activitystrea.ms](http://www.activitystrea.ms)) syntax to your Atom-Feed

Example:

` <entry>
    <id>http://notizblog.org/?p=1775</id>
    <author>
      <name>Matthias Pfefferle</name>
      <uri>http://notizblog.org</uri>
    </author>
    .
    .
    .
    <activity:verb>http://activitystrea.ms/schema/1.0/post</activity:verb>

    <activity:object>
      <activity:object-type>http://activitystrea.ms/schema/1.0/blog-entry</activity:object-type>
      <activity:object-type>http://activitystrea.ms/schema/1.0/article</activity:object-type>
      <id>tag:notizblog.org,2009-07-13:/post/1775</id>
      <title type="html"><![CDATA[Matthias Pfefferle posted a new blog-entry]]></title>
      <link rel="alternate" type="text/html" href="http://notizblog.org/2009/07/14/webstandards-kolumne/" />
    </activity:object>
  </entry>`


== Installation ==

* Upload the whole folder to your `wp-content/plugins` folder
* Activate it at the admin interface

Thats it

== Changelog ==

= 0.5 =
* some OStatus compatibility fixes
* added <activity:subject>
* added <activity:target>

= 0.3 =
* Fixed a namespace bug
* Added autodiscovery link