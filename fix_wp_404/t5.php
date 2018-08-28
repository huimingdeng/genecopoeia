<?php 
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/problem_post.php');

$fix_wp_404 = new FIX_WP_404();


// print_r( $fix_wp_404->checked_url ) ;
$clone = $fix_wp_404->checked_url;

for ($i=0; $i < 10000; $i++) { 
	if( !$fix_wp_404->get_url_status_code(md5($i))){
		echo "in\n";
	}else{
		// array_push($clone , md5($i));
		$fix_wp_404->set_url_status_code(md5($i) , '300');
		echo $i.PHP_EOL;
	}
}

// print_r( $fix_wp_404->checked_url ) ;
// print_r( $clone ) ;




$time_e = microtime(true);
echo "Time use : " . ($time_e - $time_s) ."\n\n";
 ?>