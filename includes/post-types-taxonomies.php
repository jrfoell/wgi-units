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
		'name'              => __( 'Years', 'wgi' ),
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

function wgi_dropdown_category( $post, $box ) {
	$defaults = array( 'taxonomy' => 'category' );
	$args = ( ! isset($box['args'] ) || ! is_array( $box['args'] ) ) ? array() : $box['args'];

	$r = wp_parse_args( $args, $defaults );
    $tax_name = esc_attr( $r['taxonomy'] );
    $taxonomy = get_taxonomy( $r['taxonomy'] );
    ?>
    <div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">

    <?php //took out tabs for most recent here ?>

        <div id="<?php echo $tax_name; ?>-all">
            <?php
            $name = ( $tax_name == 'category' ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
            echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
            ?>
            <ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist form-no-clear">
                <?php //wp_terms_checklist( $post->ID, array( 'taxonomy' => $tax_name, 'popular_cats' => $popular_ids ) ); ?>
            </ul>

            <?php $term_obj = wp_get_object_terms($post->ID, $tax_name ); //_log($term_obj[0]->term_id) ?>
            <?php wp_dropdown_categories( array( 'taxonomy' => $tax_name, 'hide_empty' => 0, 'name' => "{$name}[]", 'selected' => $term_obj[0]->term_id, 'orderby' => 'name', 'hierarchical' => 0, 'show_option_none' => "Select $tax_name" ) ); ?>

        </div>
    <?php if ( current_user_can( $taxonomy->cap->edit_terms ) ) : 
            // removed code to add terms here dynamically, because doing so added a checkbox above the newly added drop menu, the drop menu would need to be re-rendered dynamically to display the newly added term ?>
        <?php endif; ?>

        <p><a href="<?php echo site_url(); ?>/wp-admin/edit-tags.php?taxonomy=<?php echo $tax_name ?>&post_type=YOUR_POST_TYPE">Add New</a></p>
    </div>
    <?php
}