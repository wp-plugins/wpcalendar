<?php


add_shortcode( 'terminliste', 'terminliste_func' );
function terminliste_func( $atts ) {

	$atts = shortcode_atts( array('kategorie' => 'keine' ), $atts );
	$return = '';

	$myposts = get_posts(array('post_type'=>'termine',
		'orderby' => 'meta_value',
		'meta_key' => '_zeitstempel',
		'order' => 'ASC',
		'termine_type' => $atts['kategorie'],
		'meta_query' => array(
			array(
				'key' => '_zeitstempel',
				'value' => time(),
				'compare' =>'>='
				)
			)
		)
	);
	global $post; 
	foreach ( $myposts as $post ) {
		setup_postdata( $post );
		$termininfo=get_post_custom( get_the_ID());


		$return .= '<span class="termin_tag">' . strftime('%A, den %e. %B %G', $termininfo['_zeitstempel'][0]);

		if(isset($termininfo['_geostadt']) AND $termininfo['_geostadt'][0]!='')
			$return .=', ' . $termininfo['_geostadt'][0];

		$return .= ' ';

		$return .= '<a href="'. get_permalink() . '" class="weiterlesen">Termindetails ansehen</a></span>';

	}
	wp_reset_postdata();


	if ($return != '' ) $return = '<h2>Nächstes Treffen</h2>' . $return;
	return $return;
}




add_shortcode( 'wpcalendar', 'wpcal_overview' );
// code by ben
function wpcal_overview( $atts ) {
	$atts = shortcode_atts(
		array(
			'archiv' => false,
			'kat' => '',
		), $atts, 'bartag' );



	if( $atts['archiv']){
		$order='DESC'; $compare='<';
	}else{
		$order='ASC'; $compare='>=';
	}


		$args = array('post_type'=>'termine',
							'orderby' => 'meta_value',
	        				'meta_key' => '_zeitstempel',
	        			    'termine_type' => $atts['kat'],
							'order' => $order,
							'meta_query' => array(
									array(
										'key' => '_zeitstempel',
										'value' => time(),
										'compare' => $compare
									)
							)
						);
						
						
				global $wp_query,$paged,$post;
				$temp = $wp_query;
				$wp_query= null;
				$wp_query = new WP_Query();
				$query .= 'post_type=termine';
				
				$wp_query->query($args);
				ob_start();
				?>			

			<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
			
			<div class="wpcal-item" <?=$style?>>
				<?php  if(function_exists('the_termin_short')) the_termin_short(); ?>
				<h2><?php the_title(); ?></h2>
				<?php the_excerpt();?>
				<a href="<?php the_permalink();?>" class="weiterlesen">Termindetails Â»</a>

			</div>
			<br style="clear:both"/>
			<?php endwhile; ?>
					<?php $wp_query = null; $wp_query = $temp;
					$content = ob_get_contents();
					ob_end_clean();
					return $content;

}