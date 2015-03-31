<?php

add_action('init' , 'termine_taxonomies' );
function termine_taxonomies(){
	register_taxonomy('termine_type',array('termine'), array(
		'hierarchical' => true,
		'labels' => array(
			'name' => 'Terminkategorie',
			'singular_name' => 'Terminkategorie',
			'search_items' =>  'Terminkategorien durchsuchen',
			'all_items' => 'Alle Terminkategorien',
			'parent_item' => __( 'Parent Job Types' ),
			'parent_item_colon' => __( 'Parent Job Type:' ),
			'edit_item' => 'Terminkategorie bearbeiten',
			'update_item' => 'Terminkategorie ändern',
			'add_new_item' => 'Terminkategorie hinzufügen',
			'new_item_name' => 'Neue Terminkategorie',
			),
		'show_ui' => true,
		'query_var' => true,
		'show_in_nav_menus' => true,
		'rewrite' => array('slug' => 'termine', 'with_front' => false),
		));

}


add_action('init', 'post_type_termine');
function post_type_termine() {
	register_post_type(
		'termine',
		array(
			'labels' => array ('name' => 'Termine', 'singular_name' => 'Termin'),
			'public' => true,
			'menu_icon' => 'dashicons-calendar',
			'rewrite' => array( 'slug' => 'termin'),
			'show_ui' => true,
			'taxonomies' => array('termine_types'),
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'custom-fields',				
				'revisions')
			)
		);
}



add_action( 'save_post', 'termine_save_postdata' );
function termine_save_postdata( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	
	if ( !wp_verify_nonce( $_POST['wpcalendar_noncename'], plugin_dir_url( __FILE__ ) ) )
		return;

	if ( !current_user_can( 'edit_post', $post_id ) ) 
		return;

	 update_post_meta($_POST['post_ID'], '_wpcal_from', $_POST['wpc_from'], false); 
	
	 update_post_meta($_POST['post_ID'], '_lat', $_POST['wpc_lat'], false); 
	 update_post_meta($_POST['post_ID'], '_lon', $_POST['wpc_lon'], false); 
	 update_post_meta($_POST['post_ID'], '_geoshow', $_POST['wpc_geoshow'], false); 
	 update_post_meta($_POST['post_ID'], '_zoom', $_POST['wpc_zoom'], false); 
	 update_post_meta($_POST['post_ID'], '_geostadt', $_POST['wpc_geocity'], false); 


	 update_post_meta($_POST['post_ID'], '_bis', $_POST['wpc_until'], false); 

	 $zeitstempel=strftime( strToTime( $_POST['wpc_from'] ) );
	 update_post_meta($_POST['post_ID'], '_zeitstempel', $zeitstempel, false); 
}