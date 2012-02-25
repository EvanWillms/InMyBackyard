<div class="row-fluid">
  <div class="span12">

	<?= (isset($_GET['location'])) ? '<h2>Location: '.$_GET['location'].'</h2>' : '' ?>

	<? if (isset($_GET['location'])) : ?>

		<?
		// this is to be perfected but it works for now...
		$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.urlencode($_GET['location']).'&sensor=false');
		$output= json_decode($geocode);
		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;
		$radius = 2; // 2 miles

    $events = DB::query(sprintf("SELECT *, ( 3959 * acos( cos( radians('%s') ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( `latitude` ) ) ) ) AS distance FROM events HAVING distance < '%s' ORDER BY distance LIMIT 0 , 18", $lat, $long, $lat, $radius));

    if ($events && is_array($events) && !empty($events)) :

	    	foreach ($events as $event) {

					echo '<div class="well">';
		    		echo '<p><strong>Event Name</strong>: '.$event->name.'</p>';
		    		echo '<p><strong>Event Description</strong>: '.$event->description.'</p>';
		    		echo '<p><strong>Event Start Date</strong>: '.date('F jS Y', strtotime($event->start_date)).'</p>';
		    		echo '<p><strong>Event End Date</strong>: '.date('F jS Y', strtotime($event->end_date)).'</p>';
		    		echo '<p><strong>Email to contact regarding this event</strong>: '.$event->contact_email.'</p>';
			    echo '</div>';

	    	}


    else:

    	echo '<h3>No events found for your area <br>';
    	echo '<a href="'.URL::to_asset('/').'">&laquo; Try Again</a>';
    	echo '</h3>';

    endif;


			// if events for location
			// loop through each location
			// else
			// endif
		?>

	<? else: ?>

		<form method="get" action="<?= URL::to_asset('/') ?>" class="form-horizontal">
		  <fieldset>
		    <legend>Your Area of interest</legend>
		    <div class="control-group">
		      <label class="control-label" for="location">Postal Code or Address:</label>
		      <div class="controls">
		        <input type="text" class="input-xlarge" id="location" name="location">
		        <input class="btn btn-primary" type="submit" value="Submit">
		      </div>
		    </div>
		  </fieldset>
		</form>

	<? endif; ?>

  </div>
</div>