<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Twitter display adapter</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="default.css" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js">
</script>
<script type="text/javascript" src="site.js">
</script>
</head>
<body>
<div id="tfheader">
 		<h2 style="margin-top: 0px;">Twitter display adapter</h2>
		<div id="tfnewsearch">
		        <input type="text" class="tftextinput" name="q" size="21" maxlength="120">
		        <input type="submit" value="search" class="tfbutton">
		</div>
	<div class="tfclear"></div>
 </div>
 <div id="tweetslist">
<?php require('twitter_display.php'); ?>
<div id='aamap-label'></div>
</div>

<?php
if($_GET){
 
    if(isset($_GET['addr']) && isset($_GET['id'])) { // get latitude, longitude and formatted address
    $data_arr = geocode($_GET['addr']);
    $btn_id = $_GET['id'];
    // if able to geocode the address
    echo $btn_id . ' ';
    if($data_arr){
         
        $latitude = $data_arr[0];
        echo $latitude;
        $longitude = $data_arr[1];
        echo $longitude;
        $formatted_address = $data_arr[2];
                     
    ?>
    <!-- JavaScript to show google map -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>    
    <script type="text/javascript">
        function init_map() {
        	//alert('test');
        	//if (document.getElementById('<?php echo $btn_id; ?>').style.display == 'none') {
        		//alert('test');
        	    document.getElementById('<?php echo $btn_id; ?>').style.display = 'block';
		        var myOptions = {
		            zoom: 14,
		            center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
		            mapTypeId: google.maps.MapTypeId.ROADMAP
		        };
		        map = new google.maps.Map(document.getElementById('<?php echo $btn_id; ?>'), myOptions);
		        marker = new google.maps.Marker({
		            map: map,
		            position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
		        });
		        infowindow = new google.maps.InfoWindow({
		            content: "<?php echo $formatted_address; ?>"
		        });
		        google.maps.event.addListener(marker, "click", function () {
		            infowindow.open(map, marker);
		        });
		        infowindow.open(map, marker);
        	//}
        }

        google.maps.event.addDomListener(window, 'load', init_map);
        //document.getElementById("p1").innerHTML = "New text!";
        document.getElementById('<?php echo $btn_id; ?>').nextElementSibling.innerHTML = "latitude: <?php echo $latitude; ?>, longitude:<?php echo $longitude; ?>";
        //alert("test");
    </script>
 
    <?php
 
    // if unable to geocode the address
    }else{
        echo "No map found.";
    }
}
}
?>

<?php
 
// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}
?>

</body>
</html>
