<?php 
set_time_limit(0);
/**
 * [category] => Array --- sheet
        (
            [29643] => Array --- rows
                (
                    [url] => https://gcdev.fulengen.cn/product/qpcr-products/blazetaq-sybr-green-qpcr-mix/		--- A1
                    [title] => BlazeTaq™ SYBR Green qPCR Mix  --- B1
                    [categories] => Array 	-- C1
                        (
                            [0] => product
                            [1] => qpcr-products
                            [2] => blazetaq-sybr-green-qpcr-mix
                        )

                )

        )
 */
require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."wp-config.php");

$filename = "Posts";
global $wpdb;

$sql = "SELECT ID,post_title,post_content FROM wp_posts WHERE `post_status` = 'publish' AND `post_type` = 'post' ORDER BY post_date DESC";
$results = $wpdb->get_results($sql,ARRAY_A);
if(!empty($results))
{
	$format = array();
	$host = home_url();
	// echo $host;
	foreach ($results as $k => $id_a) {
		$categories = explode('/', str_replace($host.'/','',get_permalink($id_a['ID'])));
		array_pop($categories);
        // echo $sheetname."<br>\n";
        if('faq'==strtolower($categories[0])){
            $format[$id_a['ID']] = array(
                'title' => $id_a['post_title'],
                'content' => $id_a['post_content'],
                'url' => get_permalink($id_a['ID']),
                'categories' => implode(",", $categories)
            );
        }
		
	}

    // echo '<pre>';
    // print_r($format);

	// $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
	// 存在数据，导出到Excel中，按照 categories[0] 进行设置一个 sheet
	if(!empty($format)){
        $json = "{\n".'"RECORDS"'.":".json_encode($format)."\n}";
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'igenebio-post_content-'.date("ymdH").'.json';
        if(!file_exists($path)){
            file_put_contents($path, $json);
            echo "create success...<br>\n";
        }
    }
	
}

