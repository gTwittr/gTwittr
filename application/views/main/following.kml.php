<Document>

</Document>

<?php 

	$placemark = new KmlElementGenerator('FollowingPlacemark');
	$placemark->setValues($kmlValues);
	echo $placemark->generate();

?>