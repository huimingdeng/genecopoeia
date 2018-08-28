<?php 
$arr=array(
  array(
    "meta_key"=>"_aioseop_title",
    "meta_value"=>"还是测试"
  ),
  array(
    "meta_key"=>"_aioseop_description",
    "meta_value"=>"测试能否获取SEO元素，包括description，keyword。"
  )
);

print_r($arr);
foreach ($arr as $k => $v) {
	// echo implode(',', $v)."\n";
	$tmp_fields=array($v['meta_key'],$v['meta_value']);
	// $tmp_fields[]=;
	$cs_fields[]=$tmp_fields;
}
// print_r($cs_fields);
/*require_once(dirname(__FILE__)."/../../../wp-config.php");
$a=get_option("wp2wp_server_option");
print_r($a);*/