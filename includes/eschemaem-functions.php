<?php
function e_schema_em1 () {
	/*see if the post is an event and add schema if true*/
if (is_singular( 'event' )){
  e_shchema_em();
}
};
/*find data for schema and create arrays*/
function e_shchema_em(){
	global $wpdb;
	/*get some id info for later*/ 
	  $postId = get_the_ID();
	  $eventId = $wpdb->get_var($wpdb->prepare("SELECT event_id FROM {$wpdb->prefix}em_events WHERE post_id = %d", $postId ) );
	  $elocationId = get_post_meta($postId, '_location_id', true);
	  $elocationPostid = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}em_locations WHERE location_id = %d", $elocationId ) );
	/*grab data with wp hooks*/
	  $eventTitle = get_the_title(); 
	  $eventExcerpt = get_the_excerpt();
	  $eventImage = get_the_post_thumbnail_url();
	  $eventUrl = get_permalink();
	  $url = home_url();
	  /*meta info with post Id*/
	  $eventType = get_post_meta($postId, 'Event Type', true); 
	  $ePerformer = get_post_meta($postId, 'Artist Name', true);
	  $eperfWeb	= get_post_meta($postId, 'Artist Website', true);
	  $eventStart =  get_post_meta($postId, '_event_start_date', true)." ".get_post_meta($postId, '_event_start_time', true);
	  $eventEnd =  get_post_meta($postId, '_event_end_date', true)." ".get_post_meta($postId, '_event_end_time', true);
	  /*meta info with location Id*/
	  $eventPhone = get_post_meta($elocationPostid, 'phoneNumber', true); 
	  /*grab all location info from Events Manager table */
	  $eLocationinfo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}em_locations WHERE location_id = %d", $elocationId ) ); 
      /* grab info from ticket table*/
	  $eTicketinfo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}em_tickets WHERE event_id = %d", $eventId) ); 
//
  $eaSinfo = array(
	  "@context"=> "http://schema.org",
	  "@type"=> $eventType,
	  "name" => $eventTitle,
	  "description" => $eventExcerpt,
	  "image" => $eventImage,
	  "url"=> $eventUrl,
	  "startDate"=> $eventStart,
	  "endDate"=> $eventEnd,
	  "location"=> array (
		"@type"=> "Place",
		"name"=> $eLocationinfo->location_name,
		"description"=> $eLocationinfo->post_content,
		"url"=> $url,
		  "address"=> array(
		    "@type"=> "PostalAddress",
		    "streetAddress"=> $eLocationinfo->location_address,
		    "addressLocality"=> $eLocationinfo->location_town,
		    "postalCode"=> $eLocationinfo->location_postcode,
		    "addressCountry"=> "United States",
		    "telephone"=> $eventPhone,
		    "sameAs"=> $url,
		    ),
	     ),
	  "offers"=> array(
 	    "@type"=> "Offer",
 		"priceCurrency"=> "USD",
		"price" =>  number_format($eTicketinfo->ticket_price, 2),
		"validFrom"=> $eTicketinfo->ticket_start,
		"url"=> $eventUrl,
	  )
  );
  $eaSinfop = array(
	  "@context"=> "http://schema.org",
	  "@type"=> $eventType,
	  "name" => $eventTitle,
	  "description" => $eventExcerpt,
	  "image" => $eventImage,
	  "url"=> $eventUrl,
	  "startDate"=> $eventStart,
	  "endDate"=> $eventEnd,
	  "location"=> array (
		"@type"=> "Place",
		"name"=> $eLocationinfo->location_name,
		"description"=> $eLocationinfo->post_content,
		"url"=> $url,
		  "address"=> array(
		    "@type"=> "PostalAddress",
		    "streetAddress"=> $eLocationinfo->location_address,
		    "addressLocality"=> $eLocationinfo->location_town,
		    "postalCode"=> $eLocationinfo->location_postcode,
		    "addressCountry"=> "United States",
		    "telephone"=> $eventPhone,
		    "sameAs"=> $url,
		    ),
	     ),
	  "performer"=> array(
        "@type" =>"PerformingGroup",
 		"name"=> $ePerformer,
		"sameAs"=> $eperfWeb,

	  ),
	  "offers"=> array(
 	    "@type"=> "Offer",
 		"priceCurrency"=> "USD",
		"price" =>  number_format($eTicketinfo->ticket_price, 2),
		"validFrom"=> $eTicketinfo->ticket_start,
		"url"=> $eventUrl,
	  )
  );
  ?>
  <script type="application/ld+json">
 <?php
 /*output one array as json for schema based on $ePerformer variable*/
	if($ePerformer!=""){ echo json_encode($eaSinfop,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);}
      else {echo json_encode($eaSinfo,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);} 
?>
</script>
<?php
}
?>