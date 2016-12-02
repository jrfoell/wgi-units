<?php

add_filter( 'gform_pre_render', 'populate_posts' );
add_filter( 'gform_pre_validation', 'populate_posts' );
add_filter( 'gform_pre_submission_filter', 'populate_posts' );
add_filter( 'gform_admin_pre_render', 'populate_posts' );
function populate_posts( $form ) {

	if ( ! is_user_logged_in() ) {
		return $form;
	}

	$user = wp_get_current_user();
	
    foreach ( $form['fields'] as &$field ) {

        if ( $field->type != 'select' || ! in_array( 'my-units', explode( ' ', $field->cssClass ) ) ) {
            continue;
        }

	$unit_args = array(	
		'post_type' => 'wgi_unit_info',
		'orderby' => 'date',
		'order' => 'desc',
		'posts_per_page' => 100,
	);

	// Only show own groups to non-admin.
	if ( ! array_intersect( array( 'administrator' ), $user->roles ) ) {
		$unit_args['author'] = $user->ID;
	}

        $unit_query = new WP_Query( $unit_args );

        $choices = array();

        foreach ( $unit_query->posts as $post ) {
            $choices[] = array( 'text' => $post->post_title, 'value' => $post->post_title );
        }

        $field->placeholder = 'Select a Unit';
        $field->choices = $choices;

    }

    return $form;
}
