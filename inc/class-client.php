<?php
class PTTI_Client {

	public static $post_type = '';
	public static $taxonomy = '';
	public static $term = '';

	function __construct() {
		add_action( 'generate_rewrite_rules', array( __CLASS__, 'add_rewrite_rules' ) );
		add_action( 'parse_query', array( __CLASS__, 'parse_query' ) );
		add_action( 'query_vars', array( __CLASS__, 'add_query_vars' ) );
	}

	/**
	 * Add query var for speedup test parse query
	 *
	 * @param string $query
	 * @return void
	 * @author Benjamin Niess
	 */
	public static function add_query_vars( $query ) {
		$query[] = 'intersection';
		return $query;
	}

	/**
	 * Add rules rewriting for build intersection between custom post type and taxonomy
	 *
	 * @param object $rewrite
	 * @return void
	 * @author Benjamin Niess
	 */
	public static function add_rewrite_rules( $rewrite ) {
		$post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );
		$post_types[] = 'post';

		foreach ( $post_types as $post_type ) {
			foreach ( get_object_taxonomies( $post_type, 'objects' ) as $taxo ) {

				$cpt_rewrite = self::get_cpt_rewrite_slug( $post_type );
				$taxo_rewrite = self::get_taxo_rewrite_slug( $taxo->name );

				// Intersection
				$new_rules = array( $cpt_rewrite.'/' . $taxo_rewrite . '/([^/]+)/?$' => 'index.php?intersection=1&post_type=' . $post_type . '&' . $taxo->query_var .'=' . $rewrite->preg_index( 1 ) );
				$rewrite->rules = $new_rules + $rewrite->rules;

				// Pagination
				$new_rules = array( $cpt_rewrite.'/' . $taxo_rewrite . '/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?intersection=1&post_type=' . $post_type . '&' . $taxo->query_var .'=' . $rewrite->preg_index( 1 ) . '&paged=' . $rewrite->preg_index( 2 ) );
				$rewrite->rules = $new_rules + $rewrite->rules;

				// Feed
				$new_rules = array( $cpt_rewrite.'/' . $taxo_rewrite . '/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?intersection=1&post_type=' . $post_type . '&' . $taxo->query_var .'=' . $rewrite->preg_index( 1 ) . '&feed=' . $rewrite->preg_index( 2 ) );
				$rewrite->rules = $new_rules + $rewrite->rules;

				$new_rules = array( $cpt_rewrite.'/' . $taxo_rewrite . '/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?intersection=1&post_type=' . $post_type . '&' . $taxo->query_var .'=' . $rewrite->preg_index( 1 ) . '&feed=' . $rewrite->preg_index( 2 ) );
				$rewrite->rules = $new_rules + $rewrite->rules;
			}
		}
	}

	/**
	 * Parse query to detect intersection
	 *
	 * @param object $query
	 * @return void
	 * @author Benjamin Niess
	 */
	public static function parse_query( $query ) {
		$query->is_intersection = false;

		if ( ! isset( $query->query_vars['intersection'] ) || 1 !== (int) $query->query_vars['intersection'] ) {
			return false;
		}

		// Get current term
		$current_term = $query->get_queried_object();
		if ( is_wp_error( $current_term ) || ! isset( $current_term->taxonomy ) ) {
			return false;
		}

		// Fill var class for future usage
		self::$post_type = stripslashes( get_query_var( 'post_type' ) );
		self::$taxonomy = $current_term->taxonomy;
		self::$term = $current_term->slug;

		// Set query flag for intersection
		$query->is_tax = true;
		$query->is_home = false;
		$query->is_archive = true;
		$query->is_post_type_archive = false;
		$query->is_intersection = true;

		// Load template, remove canonical redirect
		add_action( 'template_redirect', array( __CLASS__, 'load_custom_template' ) );
		add_filter( 'redirect_canonical', array( __CLASS__, 'redirect_rewrite' ), 10, 2 );
	}

	/**
	 * Load custom template for intersection
	 *
	 * @return void
	 * @author Benjamin Niess
	 */
	public static function load_custom_template() {
		$post_type 	= get_post_type_object( self::$post_type );
		$taxonomy  	= get_taxonomy( self::$taxonomy );
		$term 		= get_term_by( 'slug', self::$term, self::$taxonomy );

		$templates = array();

		// Custom views
		$templates[] = 'archive-' . $post_type->name . '-' . $taxonomy->name . '-' . $term->slug . '.php';
		$templates[] = 'archive-' . $post_type->name . '-' . $taxonomy->name . '.php';
		$templates[] = 'archive-' . $post_type->name . '.php';

		// More generic views.
		array_push( $templates, 'archive.php', 'index.php' );

		locate_template( $templates, true );
		exit();
	}

	/**
	 * No redirect for intersection, return the original value
	 *
	 * @param string $redirect_url
	 * @param string $requested_url
	 * @return string
	 * @author Benjamin Niess
	 */
	public static function redirect_rewrite( $redirect_url = '', $requested_url = '' ) {
		return $requested_url;
	}

	/**
	 * Return the correct archive slug for a CPT
	 *
	 * @param string $cpt_name the slug of the CPT
	 * @return string the archive slug | false
	 * @author Benjamin Niess
	 */
	public static function get_cpt_rewrite_slug( $cpt_name ) {
		if ( ! isset( $cpt_name ) || empty( $cpt_name ) ) {
			return false;
		}

		$post_type_object = get_post_type_object( $cpt_name );
		if ( empty( $post_type_object ) || is_wp_error( $post_type_object ) ) {
			return false;
		}

		if ( isset( $post_type_object->has_archive ) && ! empty( $post_type_object->has_archive ) && true !== $post_type_object->has_archive ) {
			return $post_type_object->has_archive;
		} elseif ( isset( $post_type_object->query_var ) && ! empty( $post_type_object->query_var ) ) {
			return $post_type_object->query_var;
		} elseif ( isset( $post_type_object->name ) && ! empty( $post_type_object->name ) ) {
			return $post_type_object->name;
		} else {
			return false;
		}
	}

	/**
	 * Return the correct archive slug for a Taxo
	 *
	 * @param string $taxo_name the slug of the Taxo
	 * @return string the archive slug | false
	 * @author Benjamin Niess
	 */
	public static function get_taxo_rewrite_slug( $taxo_name ) {
		if ( ! isset( $taxo_name ) || empty( $taxo_name ) ) {
			return false;
		}

		$taxonomy_object = get_taxonomy( $taxo_name );
		if ( empty( $taxonomy_object ) || is_wp_error( $taxonomy_object ) ) {
			return false;
		}

		if ( isset( $taxonomy_object->rewrite['slug'] ) && ! empty( $taxonomy_object->rewrite['slug'] ) ) {
			return $taxonomy_object->rewrite['slug'];
		}
		if ( isset( $taxonomy_object->query_var ) && ! empty( $taxonomy_object->query_var ) ) {
			return $taxonomy_object->query_var;
		} elseif ( isset( $taxonomy_object->name ) && ! empty( $taxonomy_object->name ) ) {
			return $taxonomy_object->name;
		} else {
			return false;
		}
	}

	/**
	 * Get template file depending on theme
	 *
	 * @param (string) $tpl : the template name
	 * @return (string) the file path | false
	 *
	 * @author Benjamin Niess
	 */
	public static function get_template( $tpl = '' ) {
		if ( empty( $tpl ) ) {
			return false;
		}

		if ( is_file( STYLESHEETPATH . '/views/ptti/' . $tpl . '.tpl.php' ) ) {// Use custom template from child theme
			return ( STYLESHEETPATH . '/views/ptti/' . $tpl . '.tpl.php' );
		} elseif ( is_file( TEMPLATEPATH . '/ptti/' . $tpl . '.tpl.php' ) ) {// Use custom template from parent theme
			return (TEMPLATEPATH . '/views/ptti/' . $tpl . '.tpl.php' );
		} elseif ( is_file( PTTI_DIR . '/views/' . $tpl . '.tpl.php' ) ) {// Use builtin template
			return ( PTTI_DIR . 'views/' . $tpl . '.tpl.php' );
		}

		return false;
	}
}
