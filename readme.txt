=== Post types / taxonomies intersections ===
Contributors: benjaminniess, momo360modena
Donate link: http://beapi.fr/donate
Tags: intersection, cpt, custom, post, type, rewrite, template, widget
Requires at least: 3.1
Tested up to: 4.7
Stable tag: 2.1

== Description ==

Allow to create intersections between a post type and a taxonomy with url such as :

mywebsite.com/mypost-type/mytaxo/my-term

Allow to use template files such as :

archive-posttype-taxo-term.php
archive-posttype-taxo.php 
archive-posttype.php 
archive.php 
index.php 

Contain a widget that list all terms of a specific taxonomy linked to a post type

== Installation ==

1. Upload and activate the plugin
2. Update permalink on the admin panel (VERY IMPORTANT)
3. Go the the widget area if you want to add the widget
4. You can use the function described on the Usage section if you want to create a link to the archive view


== Usage ==

<?php the_intersection_link( $post_type, $taxonomy, $term ); ?>

Parameters : 
$post_type (string) : The slug of your post type
$taxonomy (string) : The slug of your post taxonomy
$term (string) : The taxonomy term

or get_the_intersection_link( $post_type, $taxonomy, $term ); ?> (if you don't want the function to echo the link)

Example : <a href="<?php the_intersection_link( 'my-custom-post-type-slug', 'my-taxonomy-slug', 'my-term-slug' )">Go to my page</a>

== Changelog ==

* 2.1
    * Coding standards review
    * Added extra security sanitizing checks
    * Tested up to 4.7
* 2.0.1
	* Fix php tag in default tpl
* 2.0
	* Code refactoring
	* Use MVC modele. Allow to use custom template in theme
* 1.0
	* First release