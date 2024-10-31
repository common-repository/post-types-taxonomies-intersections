<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' , 'ptti' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'cpt' ) ); ?>"><?php esc_html_e( 'Post type', 'ptti' ); ?></label><br />
	<select id="<?php echo esc_attr( $this->get_field_id( 'cpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cpt' ) ); ?>" >
		<?php foreach ( $post_types as $post_type_slug => $post_type_object ) : ?>
			<option value="<?php echo esc_attr( $post_type_slug ); ?>" <?php selected( $cpt, $post_type_slug ); ?>><?php echo esc_html( $post_type_object->labels->name ); ?></option>
		<?php endforeach; ?>
	</select>
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php esc_html_e( 'Taxonomy', 'ptti' ); ?></label>
	<br />
	<select id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" >
		<?php foreach ( $taxonomies as $taxonomy_slug => $taxonomy_object ) : ?>
			<option value="<?php echo esc_attr( $taxonomy_slug ); ?>" <?php selected( $taxonomy, $taxonomy_slug ); ?>><?php echo esc_html( $taxonomy_object->labels->name ); ?></option>
		<?php endforeach; ?>
	</select>
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'query_args' ) ); ?>"><?php esc_attr_e( 'Query args: (for pros only)', 'ptti' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'query_args' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_args' ) ); ?>" type="text" value="<?php echo esc_attr( $query_args ); ?>" />
</p>
