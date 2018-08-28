<?php

$urls = array(
	"http://gcdev.fulengen.cn/product/clone-collections/",
	"http://gcdev.fulengen.cn/cellbiology/cell-biology-reagents/",
	"http://gcdev.fulengen.cn/product/genome-editing/",
	"http://gcdev.fulengen.cn/product/mirna/",
	"http://gcdev.fulengen.cn/product/lentiviral-system/",
	"http://gcdev.fulengen.cn/product/qpcr-products/",
	"http://gcdev.fulengen.cn/product/products/",
	"http://gcdev.fulengen.cn/product/reagents-kits/",
	"http://gcdev.fulengen.cn/product/endofectin/",
	"http://gcdev.fulengen.cn/product/mirna-inhibitor/",

);

$mh = curl_multi_init();

foreach ($urls as $i => $url) {
    $conn[$i] = curl_init($url);
    curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
    curl_multi_add_handle($mh, $conn[$i]);
}

do {
    $status = curl_multi_exec($mh, $active);
    $info = curl_multi_info_read($mh);
    if (false !== $info) {
        var_dump($info);
    }


    
} while ($status === CURLM_CALL_MULTI_PERFORM || $active);

foreach ($urls as $i => $url) {
    // $res[$i] = curl_multi_getcontent($conn[$i]);

    // curl_close($conn[$i]);


    
    if(!curl_errno($conn[$i])){
    	$status_code = curl_getinfo($conn[$i] , CURLINFO_HTTP_CODE);
    	curl_close($conn[$i]);
    	//debug
    	// if($this->config['DEBUG']){
    		echo " <". $status_code ."> \n";
    	// }
    	
    	// return $status_code;
    }
}

var_dump(curl_multi_info_read($mh));

?> 