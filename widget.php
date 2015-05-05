<?php

class termine_widget extends WP_Widget{
  function termine_widget(){
    $widget_ops = array('classname' => 'termine_widget', 'description' => 'Alle Termine auf einer Karte' );
    $this->WP_Widget('termine_widget', 'Termine Widget', $widget_ops);
  }
 
  function form($instance)  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
  
    // WIDGET CODE GOES HERE
    
  ?>
<div id="termin_karte" style="margin: -10px -10px -40px -10px;">
<div id="termin_karte_map" style="width:235px; height:265px;margin:0"></div>
<?php /* <h3 class="widget-title" style="position: relative;top: -260px;left:10px;padding-left:1em;width:120px;background:white;"><?=$title?></h3> */ ?>
<script>
<?php $lat = 48; $lon=11; $zoom = 11; ?>
var map = L.map('termin_karte_map',{ zoomControl:false }).setView([<?=$lat?>, <?=$lon?>], <?=$zoom?>);
mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
L.tileLayer(
'http://{s}.tiles.mapbox.com/v3/examples.map-i87786ca/{z}/{x}/{y}.png', {
attribution: 'Map data © ' + mapLink,
maxZoom: 18,
}).addTo(map);

var greenIcon = L.icon({
  iconUrl: ' <?= plugin_dir_url( __FILE__ ) . 'map/images/icon.png'?>',
    shadowUrl: '<?= plugin_dir_url( __FILE__ ) . 'map/images/icon-shadow.png'?>',

    iconSize:     [21, 34], // size of the icon
    shadowSize:   [35, 34], // size of the shadow
    iconAnchor:   [11, 34], // point of the icon which will correspond to marker's location
    shadowAnchor: [10, 34],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

var blueIcon = L.icon({
    iconUrl: ' <?= plugin_dir_url( __FILE__ ) . 'map/images/icon.png'?>',
    shadowUrl: '<?= plugin_dir_url( __FILE__ ) . 'map/images/icon-shadow.png'?>',

    iconSize:     [21, 34], // size of the icon
    shadowSize:   [35, 34], // size of the shadow
    iconAnchor:   [11, 34], // point of the icon which will correspond to marker's location
    shadowAnchor: [10, 34],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});
<?php

  // kommende Termine
	$termine = get_posts(array('post_type'=>'termine',
						'orderby' => 'meta_value',
        				'meta_key' => '_zeitstempel',
        			   'posts_per_page' => 50,
						      'order' => 'DESC',
						      'meta_query' => array(
								array(
									'key' => '_zeitstempel',
									'value' => time(),
									'compare' => '>='
								)
						)
					)
	);





	foreach ($termine AS $termin){

		//echo $termin->post_title;
		//echo "<br/>";
		//echo $termin->post_name;
		// print_r(get_post_meta( $termin->ID ));
		//print_r($termin);
		$lat = get_post_meta( $termin->ID, '_lat', true );
		$lon = get_post_meta( $termin->ID, '_lon', true );

    $ort = preg_replace('/,/', '\\,' , utf8_decode( get_post_meta( $termin->ID, '_geoshow', true ) . ' ' . get_post_meta( $termin->ID, '_geostadt', true ) ) );
    $tag = sprintf( '%04d%02d%02d' , 
        get_post_meta( $termin->ID, '_jahr', true ),
        get_post_meta( $termin->ID, '_monat', true ),
        get_post_meta( $termin->ID, '_tag', true ) );
    
    $stunde = sprintf( '%02d', get_post_meta( $termin->ID, '_stunde', true ) );
    $minute = sprintf( '%02d', get_post_meta( $termin->ID, '_minute', true ) );

    $dtstart = "{$tag}T{$stunde}{$minute}00Z";
    $stundebis = sprintf( '%02d', ($stunde + 1 ) );
    $dtend = "{$tag}T{$stundebis}{$minute}00Z";
    $titelcal = preg_replace('/,/', '\\,' , utf8_decode($termin->post_title) );
    $descriptioncal = preg_replace('/,/', '\\,' , utf8_decode($termin->post_name) );

		if( $lat == '' ) continue;
		//echo "$lat<hr/>";	
		?>
		var marker = L.marker([<?=$lat?>, <?=$lon?>],{icon:greenIcon}).addTo(map).bindPopup("<a href=\"/termin/<?="$termin->post_name"?>\" class=\"noicon\">&rarr; <?=wordwrap($termin->post_title, 20, '<br/>')?></a>");
		<?php



	}


 

  // Alle Beiträge
  $termine = get_posts(array('post_type'=>'post',         
            'posts_per_page' => 50,
            'order' => 'DESC'
         
          )
  );
  $tz=0;
  foreach ($termine AS $termin){

    $lat = get_post_meta( $termin->ID, '_lat', true );
    $lon = get_post_meta( $termin->ID, '_lon', true );

    if( $lat == '') continue;
    $tz++;
    if($tz > 7 ) break;
    //echo "$lat<hr/>"; 
    ?>
  var marker = L.marker([<?=$lat?>, <?=$lon?>],{icon:blueIcon}).addTo(map).bindPopup("<a href=\"/<?=$termin->post_name;?>\" class=\"noicon\">&rarr; <?=wordwrap($termin->post_title, 20, '<br/>')?></a>"); 

    <?php
     }
    ?>

</script>
</div>

<?php 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("termine_widget");') );?>