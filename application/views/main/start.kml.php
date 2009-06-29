<?php 

	$placemark = new KmlElementGenerator('StartPlacemark');
	$placemark->setValues($kmlValues);
	echo $placemark->generate();

?>