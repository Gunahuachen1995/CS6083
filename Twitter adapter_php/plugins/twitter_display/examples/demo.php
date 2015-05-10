<?php
// if (PHP_SAPI != 'cli') {
// 	echo "<pre>";
// }

$string = $_GET["txt"];
/*array(
	1 => 'Weather today is rubbish',
	2 => 'This cake looks amazing',
	3 => 'His skills are mediocre',
	4 => 'He is very talented',
	5 => 'She is seemingly very agressive',
	6 => 'Marie was enthusiastic about the upcoming trip. Her brother was also passionate about her leaving - he would finally have the house for himself.',
	7 => 'To be or not to be?',
);*/

require_once __DIR__ . '/../autoload.php';
$sentiment = new \PHPInsight\Sentiment();
//foreach ($strings as $string) {

	// calculations:
	$scores = $sentiment->score($string);
	$class = $sentiment->categorise($string);

	// output:
	// echo "String: $string\n";
	// echo "Dominant: $class, scores: ";
	$maxInd = -1;
	$maxRes = array();
	foreach($scores as $category => $score) {
	    if($score > $maxInd) {
	        $maxInd = $score;
	        $maxRes = array();
	    }
	    if($score == $maxInd) {
	        $maxRes[] = $category;
		}
	}
	if(count($maxRes) > 1) {print('neu');}
	else {print($maxRes[0]);}
//}
