<?php

$content = file_get_contents('logs/error_message.log');
//replace
$content = preg_replace( array("/^.+?ERROR_LINK:(.+?)(#.+)?$/m" , "/^https?:\/\/www\.genecopoeia\.com/m" , "/^/m"  ),  array("$1" , "" , "http://www.genecopoeia.com"  ), $content);

//unique
$content2array = explode("\n" , $content);
$content2array = array_unique($content2array);
sort($content2array);
$content = implode("\n" , $content2array);

//get images
preg_match_all("/\/([^\/]+\.(?:jpe?g|png|gif|bmp))$/im", $content, $matches);
$images_array = $matches[1];

//get pdf
preg_match_all("/\/([^\/]+\.pdf)$/im", $content, $matches);
$pdfs_array = $matches[1];

// var_dump($images_array);
// var_dump($pdfs_array);

//save file
file_put_contents("404_links.txt", $content);
file_put_contents("404_images.txt", implode("\n" , $images_array));
file_put_contents("404_pdfs.txt", implode("\n" , $pdfs_array));

foreach ($images_array as $key => $each) {
	$commands[] = sprintf('find / -name "*%s*" 2>/dev/null 1>>found.txt' , $each);
}

foreach ($commands as $value) {
	echo "Runing:" . $value . PHP_EOL;
	exec($value);
}

// $filter = ;