<?php
/*
 * Retrun a permalink with the intersection between a post type and a taxonomy
 *
 * @param string $post_type
 * @param string $taxonomy
 * @param string $term
 *
 * @return string the permalink | false
 * @author Benjamin Niess
 */
function get_the_intersection_link( $post_type = '', $taxonomy = '', $term = '' ) {
	if ( ! isset( $post_type ) || empty( $post_type ) || ! isset( $taxonomy ) || empty( $taxonomy ) || ! isset( $term ) || empty( $term ) ) {
		return false;
	}

	//Get correct slugs for CTP and taxo
	$cpt_rewrite = PTTI_Client::get_cpt_rewrite_slug( $post_type );
	$taxo_rewrite = PTTI_Client::get_taxo_rewrite_slug( $taxonomy );
	if ( empty( $cpt_rewrite ) || empty( $taxo_rewrite ) ) {
		return false;
	}

	// Exception for post categories and post tags
	if ( 'post' === $post_type && ( 'category' === $taxonomy || 'post_tag' === $taxonomy ) ) {
		$term_link = get_term_link( $term, $taxonomy );
		if ( is_wp_error( $term_link ) || empty( $term_link ) ) {
			return false;
		}
		return $term_link;
	}
	return home_url( $cpt_rewrite . '/' . $taxo_rewrite . '/' . $term );
}

/*
 * Call the get_the_intersection_link function and echo the result
 *
 * @author Benjamin Niess
 */
function the_intersection_link( $post_type = '', $taxonomy = '', $term = '' ) {
	echo get_the_intersection_link( $post_type, $taxonomy, $term );
}
