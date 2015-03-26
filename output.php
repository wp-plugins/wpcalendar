<?php

function the_termin(){

  $meta_angabe_zeit=get_post_custom( get_the_ID());


  $echo = "\n\n<div class='termin_meta'>\n";

    if(date('G',$meta_angabe_zeit['_zeitstempel'][0])==0){
      // Ohne Stundenangabe

        $echo .= '<span class="termin_tag">'.strftime('%A, den %e. %B %G',$meta_angabe_zeit['_zeitstempel'][0]);
        if(isset($meta_angabe_zeit['_bis']) AND $meta_angabe_zeit['_bis'][0]!='') $echo .= ' bis '.$meta_angabe_zeit['_bis'][0];
      $echo .= "</span>\n";
    }else{
      if(isset($meta_angabe_zeit['_bis']) AND $meta_angabe_zeit['_bis'][0]!='') {
          $echo .= utf8_encode( strftime('<span class="termin_tag">%A, den %d. %B %Y </span><span class="termin_zeit">%H:%M Uhr',$meta_angabe_zeit['_zeitstempel'][0]).' bis '.$meta_angabe_zeit['_bis'][0] );
      $echo .= "</span>\n";
      }else {
          $echo .= strftime('<span class="termin_tag">%A, den %e. %d %B</span><span class="termin_zeit">%I:%M Uhr</span>', $meta_angabe_zeit['_zeitstempel'][0]);
           $echo .= "</span>\n";
      }
    }

    if(isset($meta_angabe_zeit['_geostadt']) AND $meta_angabe_zeit['_geostadt'][0]!=''){
    	$echo .= '<span class="termin_ort">';
      $echo .= $meta_angabe_zeit['_geoshow'][0];
    	if($meta_angabe_zeit['_geoshow'][0]!='' AND isset($meta_angabe_zeit['_geostadt'])) $echo .= ', ';
    	$echo .= $meta_angabe_zeit['_geostadt'][0];
    	$echo .= '</span>';

    }

    $echo .= "\n</div><!-- /meta -->\n";

    return $echo;
}





function the_termin_short(){
  setlocale(LC_ALL, 'de_DE.UTF-8');
  $meta_angabe_zeit=get_post_custom( get_the_ID());

  echo "\n\n<div class='termin_meta_kurz'>\n";


      // Ohne Stundenangabe
		echo '<span class="termin_wochentag_kurz">'.strftime('%A',$meta_angabe_zeit['_zeitstempel'][0]).'</span>';
		echo '<span class="termin_datum_kurz">'.strftime('%d.%m.%y',$meta_angabe_zeit['_zeitstempel'][0]).'</span>';
		if(isset($meta_angabe_zeit['_geostadt']) AND $meta_angabe_zeit['_geostadt'][0]!=''){ echo '<span class="termin_ort_kurz">'.$meta_angabe_zeit['_geostadt'][0].'</span>'; }

		echo "\n</div><!-- /meta -->\n";
}



function the_termin_geo(){

  $custom=get_post_custom( get_the_ID());
  if(isset($custom['_geoshow']) AND $custom['_geoshow'][0]!=''){
    $geo=urlencode($custom['_geo'][0]);

  


/*<strong>Ort: </strong><?=$custom['_geoshow'][0]?><br />*/
ob_start();
?>
<div id="termin_map_wrapper">
<div id="termin_map" style="width:100%; height:300px"></div>
<a href="http://www.openstreetmap.org/?mlat=<?=$custom['_lat'][0]?>&mlon=<?=$custom['_lon'][0]?>#map=<?=$custom['_zoom'][0]?>/<?=$custom['_lat'][0]?>/<?=$custom['_lon'][0]?>" target="_blank">Kartenausschnitt auf OpenStreetMap anzeigen</a>
<script>
var map = L.map('termin_map').setView([<?=$custom['_lat'][0]?>, <?=$custom['_lon'][0]?>], <?=$custom['_zoom'][0]?>);
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
var marker = L.marker([<?=$custom['_lat'][0]?>, <?=$custom['_lon'][0]?>],{icon:greenIcon}).addTo(map).bindPopup("<a href=<?="http://www.openstreetmap.org/?mlat={$custom['_lat'][0]}&mlon={$custom['_lon'][0]}#map={$custom['_zoom'][0]}/{$custom['_lat'][0]}/{$custom['_lon'][0]}"?> class=\"noicon\">&rarr; <?=$custom['_geoshow'][0]?></a>").openPopup();
</script>
</div>
<?php
$return = ob_get_contents();
ob_end_clean();
return $return;
  }

}



function wpcal_add_content( $content ) {
    if( 'termine' != get_post_type() )
      return $content;

    $custom_content = the_termin();
    $custom_content .= $content;
    $custom_content .= the_termin_geo(); 
    return $custom_content;
}
add_filter( 'the_content', 'wpcal_add_content' );