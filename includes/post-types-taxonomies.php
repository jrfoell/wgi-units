<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

add_action( 'init', 'wgi_unit_init' );
/**
 * Register a unit post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function wgi_unit_init() {
	$unit_info_labels = array(
		'name'               => _x( 'Unit Info', 'post type general name', 'wgi' ),
		'add_new_item'       => __( 'Add New Unit Info', 'wgi' ),
		'new_item'           => __( 'New Unit Info', 'wgi' ),
		'edit_item'          => __( 'Edit Unit Info', 'wgi' ),
		'view_item'          => __( 'View Unit Info', 'wgi' ),
		'all_items'          => __( 'All Unit Info', 'wgi' ),
		'search_items'       => __( 'Search Unit Info', 'wgi' ),
		'not_found'          => __( 'No unit info found.', 'wgi' ),
		'not_found_in_trash' => __( 'No unit info found in Trash.', 'wgi' )
	);

	$unit_info_args = array(
		'labels'             => $unit_info_labels,
        'description'        => __( 'Description.', 'wgi' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'unit' ),
		'capability_type'    => 'unit_info',
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail' )
	);

	register_post_type( 'wgi_unit_info', $unit_info_args );

	$class_labels = array(
		'name'              => __( 'Class', 'wgi' ),
		'add_new_item'      => __( 'Add Class', 'wgi' ),
		'edit_item'         => __( 'Edit Class', 'wgi' ),
	);

	$class_args = array(
		'labels'            => $class_labels,
		'hierarchical'      => false,
		'meta_box_cb'       => 'wgi_dropdown_category',
		'show_ui'           => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'class' ),
		'capabilities'      => array(
			'manage_terms' => 'manage_categories',
			'edit_terms'   => 'manage_categories',
			'delete_terms' => 'manage_categories',
			'assign_terms' => 'edit_unit_infos',
		),
	);

	register_taxonomy( 'wgi_class', array( 'wgi_unit_info' ), $class_args );

	$year_labels = array(
		'name'              => __( 'Year', 'wgi' ),
		'name_singular'     => __( 'Year', 'wgi' ),
		'add_new_item'      => __( 'Add Year', 'wgi' ),
		'edit_item'         => __( 'Edit Year', 'wgi' ),
	);

	$year_args = array(
		'labels'            => $year_labels,
		'hierarchical'      => false,
		'meta_box_cb'       => 'wgi_dropdown_category',
		'show_ui'           => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'year' ),
		'capabilities'      => array(
			'manage_terms' => 'manage_categories',
			'edit_terms'   => 'manage_categories',
			'delete_terms' => 'manage_categories',
			'assign_terms' => 'edit_unit_infos',
		),
	);

	register_taxonomy( 'wgi_year', array( 'wgi_unit_info' ), $year_args );

}

/**
 * Idea from:
 * @see http://wordpress.stackexchange.com/questions/50077/display-a-custom-taxonomy-as-a-dropdown-on-the-edit-posts-page
 */
function wgi_dropdown_category( $post, $box ) {
	$defaults = array( 'taxonomy' => 'category' );
	$args = ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) ? array() : $box['args'];

	$r = wp_parse_args( $args, $defaults );
	$tax_name = esc_attr( $r['taxonomy'] );
	$taxonomy = get_taxonomy( $r['taxonomy'] );
	$name = ( $tax_name == 'category' ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
	?>
	<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
		<div id="<?php echo $tax_name; ?>-all">
			<?php
			$term_obj = wp_get_object_terms( $post->ID, $tax_name );
			$selected = null;
			if ( is_array( $term_obj ) && ! empty( $term_obj ) ) {
				$one_term = current( $term_obj );
				$selected = $one_term->name;
			}
			wp_dropdown_categories( array( 'taxonomy' => $tax_name, 'hide_empty' => false, 'name' => $name, 'value_field' => 'name', 'selected' => $selected, 'orderby' => 'name', 'hierarchical' => 0, 'show_option_none' => "Select {$taxonomy->label}" ) );
			?>
		</div>
	</div>
	<?php
}
