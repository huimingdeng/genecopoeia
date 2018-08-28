<?php
/**
 * 查出wp_post和wp_post_copy表相同ID的post_content。
 * 比对两个post_content的md5值，如果不一致，则把此post_content更改前后的值保存到2个文件中，用于比对软件的比对
 * wp_post_copy是更改前的备份
 * wp_post表是更改后的数据
 */
$time_s = microtime(true);
require_once(dirname(__FILE__).'/functions.php');
$config_array =  require_once(dirname(__FILE__).'/config_local.php');

//建立2个文件夹，用于保存修改前后的值
$before_path = "verify/before";
$after_path = "verify/after";

if(!file_exists($before_path)){
	mkdir($before_path  , 0777 , true);
}
if(!file_exists($after_path)){
	mkdir($after_path  , 0777 , true);
}
$tidy = new tidy();
// $clean = $tidy->repairString($buffer);

// $fix_wp_404 = new FIX_WP_404();
$mysqli = new mysqli($config_array['DB_HOST'] , $config_array['DB_USER'] , $config_array['DB_PASSWORD'] , $config_array['DB_NAME'] );
/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

/* change character set to utf8 */
if (!$mysqli->set_charset($config_array['DB_CHARSET'])) {
	printf("Error loading character set %s: %s\n", $config_array['DB_CHARSET'] , $mysqli->error);
}

function remove_html_tag($string){
	return preg_replace(array('~^[\w\W]+?<body>~','~</body>[\w\W]+$~') , '' , $string);
}
$sql = sprintf("SELECT old.ID , new.post_content AS new_content , old.post_content AS old_content FROM wp_posts AS new LEFT JOIN wp_posts_copy AS old ON new.ID=old.ID WHERE old.post_type='post' ORDER BY old.ID " );
$result = $mysqli->query($sql , MYSQLI_USE_RESULT);
if($result){
	// Cycle through results
	$tidy_option = array(
		'indent' => TRUE,
		'output-xhtml' => TRUE,
		'wrap' => 0
	);
	while ($row = $result->fetch_assoc()){
		if(md5($row['old_content']) != md5($row['new_content'])){
			$tidy->parseString($row['old_content'],$tidy_option);
			$tidy->cleanRepair();
			$old_content = remove_html_tag($tidy);
			// $old_content = (string)$tidy->body();

			$tidy->parseString($row['new_content'],$tidy_option);
			$tidy->cleanRepair();
			$new_content = remove_html_tag($tidy);
			// $new_content = $tidy;

			file_put_contents($before_path.'/'.$row['ID'].'.html', $old_content);
			file_put_contents($after_path.'/'.$row['ID'].'.html', $new_content);
			echo "Different Post <".$row['ID'].">\n";
		}
	}
	// Free result set
	$result->close();
	// $mysqli->next_result();
}else{
	echo($mysqli->error);
}
