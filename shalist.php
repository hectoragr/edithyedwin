<?php
$sharr = array();
for ($i = 1; $i <= 150 ; $i++) { 
	$sharr[$i] = substr(sha1($i), -6);
	// echo "$i : ".substr(sha1($i), -5). "<br>";
}
echo "<pre>";
print_r(array_unique($sharr));
echo "Arr count: ".count(array_unique($sharr));
echo "</pre>";

?>