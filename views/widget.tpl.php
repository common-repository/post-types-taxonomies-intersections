<?php if ( ! empty( $title ) ) : ?>
	<?php echo $before_title . esc_html( $title ) . $after_title; ?>
<?php endif; ?>

<ul>
	<?php foreach ( $taxo_terms as $term ) : ?>
		<li><a href="<?php the_intersection_link( $cpt, $taxonomy, $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
	<?php endforeach; ?> 
</ul>
