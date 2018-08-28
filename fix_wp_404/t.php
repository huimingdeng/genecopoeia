<?php
http://www.genecopoeia.com/product/crispr-cas9/#Cas9 Nuclease Expression Clones
$url ='http://www.genecopoeia.com/product/crispr-cas9/#Cas9 Nuclease Expression Clones';
// $url ='http://www.genecopoeia.com/product/crispr-cas9/index.php';
// $url ='http://www.genecopoeia.com/product/crispr-cas9/#Cas9 Nuclease Expression Clones/#abc';
// $r = parse_url($url);
// print_r($r);
// $exploded = explode('#', $url);
// print_r($exploded);

// $url = preg_replace(array("~^([^#]+)~" , "~ ~"), array("$1" , "%20"), trim($url));
// var_dump($url);

$exploded = explode('#', $url);
$url = reset($exploded);//去掉url的fragment（#xxxx），解决这个问题：http://www.genecopoeia.com/product/crispr-cas9/#Cas9 Nuclease Expression Clones
$url = str_replace(' ' , '%20', trim($url));
var_dump($url);

// echo md5('http://www.genecopoeia.com/product/crispr-cas9/#Cas9 Nuclease Expression Clones');