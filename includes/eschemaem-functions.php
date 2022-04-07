<?php
function e_shchema_em(){

	
if (is_singular('event')) {?>
<script type="application/ld+json">
 [{
    "@context": "http://schema.org",
 	"@type": "<?php echo get_post_meta(get_the_ID(), 'Event Type', true); ?>",

    "name": "<?php the_title (); ?>",
    "description": "<?php echo(get_the_excerpt()) ?>",
 	"image": "<?php the_post_thumbnail_url(); ?>",
 	"url": "<?php echo get_permalink(); ?>",
    "startDate": "<?php echo get_post_meta(get_the_ID(), '_event_start_date', true);echo " "; echo get_post_meta(get_the_ID(), '_event_start_time', true); ?>",
 	"endDate": "<?php echo get_post_meta(get_the_ID(), '_event_end_date', true);echo " "; echo get_post_meta(get_the_ID(), '_event_end_time', true); ?>",
"location": {
    <?php 
	global $wpdb;
	$locationId = get_post_meta(get_the_ID(), '_location_id', true); ?>
	  "@type": "Place",
    "name": "<?php  $locationName = $wpdb->get_var('SELECT location_name FROM '.$wpdb->prefix.'em_locations WHERE location_id ='.$locationId); echo $locationName; ?>",
    "description": "<?php  $locationDesc = $wpdb->get_var('SELECT post_content FROM '.$wpdb->prefix.'em_locations WHERE location_id ='.$locationId); echo $locationDesc; ?>",
	"url": "<?php echo home_url(); ?> ",
    "address":{
		"@type": "PostalAddress",
		"streetAddress": "<?php  $locationStreet = $wpdb->get_var('SELECT location_address FROM '.$wpdb->prefix.'em_locations WHERE location_id ='.$locationId); echo $locationStreet; ?>",
		"addressLocality": "<?php  $locationTown = $wpdb->get_var('SELECT location_town FROM '.$wpdb->prefix.'em_locations WHERE location_id ='.$locationId); echo $locationTown; ?>",
 		"addressRegion":  "<?php  $locationState = $wpdb->get_var('SELECT location_state FROM '.$wpdb->prefix.'em_locations WHERE location_id ='.$locationId); echo $locationState; ?>",
		"postalCode":  "<?php  $locationZip = $wpdb->get_var('SELECT location_postcode FROM '.$wpdb->prefix.'em_locations WHERE location_id ='.$locationId); echo $locationZip; ?>",
		"addressCountry": "United States",
		"telephone": "<?php $locationPostid = $wpdb->get_var('SELECT post_id FROM '.$wpdb->prefix.'em_locations WHERE location_id ='.$locationId); echo get_post_meta($locationPostid, "phoneNumber", true); ?>",
		"sameAs": "<?php echo home_url(); ?>"
}
}, 	
<?php $performer = get_post_meta(get_the_ID(), 'Artist Name', true); 
                         if($performer){ ?>
    "performer": {
        "@type": "PerformingGroup",
 		"name": "<?php echo get_post_meta(get_the_ID(), 'Artist Name', true); ?>",
 		"sameAs": "<?php echo get_post_meta(get_the_ID(), 'Artist Website', true); ?>"
 	},
<?php }; ?>

 	"offers": {
 		"@type": "Offer",
 		"priceCurrency": "USD",
		<?php $eventId = $wpdb->get_var('SELECT event_id FROM '.$wpdb->prefix.'em_events WHERE post_id ='.get_the_ID()); ?>
		"price" : "<?php $ticketPrice = $wpdb->get_var('SELECT ticket_price FROM '.$wpdb->prefix.'em_tickets WHERE event_id ='.$eventId); if($ticketPrice){ echo (float)$ticketPrice;} else { echo "0";} ?>",
<?php $ticketStart = $wpdb->get_var('SELECT ticket_start FROM '.$wpdb->prefix.'em_tickets WHERE event_id ='.$eventId); ?>
		<?php if($ticketStart){ ?>"validFrom" : "<?php echo $ticketStart; ?>",
<?php } ?>
		"url": "<?php echo get_permalink(); ?>"

 	}
 }]
</script>
<?php  }} ?>