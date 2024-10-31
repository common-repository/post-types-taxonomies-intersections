<?php 
/**
 * PTTI Custom Term List widget class
 * Extends the default category widget
 *
 * @Author Benjamin Niess
 */
class PTTI_Widget_Custom_Terms_List extends WP_Widget {

	function PTTI_Widget_Custom_Terms_List() {
		$this->WP_Widget( 'PPTI_widget_custom_terms_list', esc_html__( 'Intersection Widget', 'ptti' ), array( 'classname' => 'widget_categories widget_intersection', 'description' => esc_html__( 'A list of custom terms', 'ptti' ) ) );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//the title of th widget
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Categories', 'ptti' ) : $instance['title'], $instance, $this->id_base );

		//the custom post type
		$cpt = apply_filters( 'ptti_widget_cpt', empty( $instance['cpt'] ) ? '' : $instance['cpt'], $instance, $this->id_base );

		//the taxonomy
		$taxonomy = apply_filters( 'ptti_taxonomy', empty( $instance['taxonomy'] ) ? esc_html__( 'Taxonomy:', 'ptti' ) : $instance['taxonomy'], $instance, $this->id_base );

		//the custom query args
		$query_args = apply_filters( 'ptti_query_args', empty( $instance['query_args'] ) ? esc_html__( 'Query args:', 'ptti' ) : $instance['query_args'], $instance, $this->id_base );

		//get all the taxonomy terms depending on the query args
		$taxo_terms = get_terms( $taxonomy, $query_args );
		if ( empty( $taxo_terms ) ) {
			return false;
		}

		// Get the template (inside the theme or inside the plugin)
		$tpl = PTTI_Client::get_template( 'widget' );
		if ( empty( $tpl ) ) {
			return false;
		}

		echo $before_widget;
		include( $tpl );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cpt'] = strip_tags( $new_instance['cpt'] );
		$instance['query_args'] = strip_tags( $new_instance['query_args'] );
		$instance['taxonomy'] = strip_tags( $new_instance['taxonomy'] );

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = esc_attr( $instance['title'] );
		$cpt = esc_attr( $instance['cpt'] );
		$taxonomy = esc_attr( $instance['taxonomy'] );
		$query_args = esc_attr( $instance['query_args'] );

		//get taxonomies and post types
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$tpl = PTTI_Client::get_template( 'admin/widget-form' );
		if ( empty( $tpl ) ) {
			return false;
		}

		include( $tpl );
		return true;
	}
}
