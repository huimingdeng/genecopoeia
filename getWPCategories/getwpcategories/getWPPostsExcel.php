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
require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."PHPExcel".DIRECTORY_SEPARATOR."Classes".DIRECTORY_SEPARATOR."PHPExcel.php");
$filename = "Posts";
global $wpdb;

$sql = "SELECT ID,post_title FROM wp_posts WHERE `post_status` = 'publish' AND `post_type` = 'post' ORDER BY post_date DESC";
$results = $wpdb->get_results($sql,ARRAY_A);
if(!empty($results))
{
	$format = array();
	$host = home_url();
	// echo $host;
	foreach ($results as $k => $id_a) {
		$categories = explode('/', str_replace($host.'/','',get_permalink($id_a['ID'])));
		array_pop($categories);
        $sheetname = preg_replace('/-/', '_', $categories[0]);
        // echo $sheetname."<br>\n";
		$format[$sheetname][$id_a['ID']] = array(
			'title' => $id_a['post_title'],
			'url' => get_permalink($id_a['ID']),
			'categories' => implode(",", $categories)
		);
	}

    /*echo '<pre>';
    print_r($format);*/
	
    $PHPExcelObj = new PHPExcel();

    $sheetIndex = 0;
	$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
	// 存在数据，导出到Excel中，按照 categories[0] 进行设置一个 sheet
	if(!empty($format)){
        foreach($format as $sheetName => $data){

            if($sheetIndex > 0){ // 因为默认有一页, 所有从第二开始  
                $PHPExcelObj->createSheet(); // 创建内置表 
            }
            $PHPExcelObj->setActiveSheetIndex($sheetIndex);//选择打开 sheet 表
            $newSheet = $PHPExcelObj->getActiveSheet();
            // 设置 sheet 表的第一行为标题栏
            $newSheet->getColumnDimension('A')->setWidth(32);
            $newSheet->getColumnDimension('B')->setWidth(42);
            $newSheet->getColumnDimension('C')->setWidth(42);
            $newSheet->getStyle('A1')->getFont()->setName('Candara' );    // 设置单元格字体
            $newSheet->getStyle('A1')->getFont()->setSize(15);            // 设置单元格字体大小
            $newSheet->getStyle('A1')->getFont()->setBold(true);
            $newSheet->getStyle('B1')->getFont()->setName('Candara' );    // 设置单元格字体
            $newSheet->getStyle('B1')->getFont()->setSize(15);            // 设置单元格字体大小
            $newSheet->getStyle('B1')->getFont()->setBold(true); 
            $newSheet->getStyle('C1')->getFont()->setName('Candara' );    // 设置单元格字体
            $newSheet->getStyle('C1')->getFont()->setSize(15);            // 设置单元格字体大小
            $newSheet->getStyle('C1')->getFont()->setBold(true);  
            $newSheet->setCellValue('A1','Title')->setCellValue('B1','Url')->setCellValue('C1','Categories');
            $row = 2; // 行号
            // 循环获取数据，设置 sheet 表内容
            foreach ($data as $post) {
                // $post 代表文章，一篇文章表示一行数据
                $cellname = ord('A');
                foreach($post as $column){//获取列内容，组装
                    $newSheet->setCellValue(chr($cellname).$row,$column);
                    ++$cellname;
                }
                ++$row;
            }
            if(preg_match('/([a-zA-Z0-9]+)[_\-]/i',$sheetName, $matches)){
                $sheetName = $matches[1];
            }
            $newSheet->setTitle($sheetName);
            
            $sheetIndex++;
        }

        // 保存Excel表
        $sheetWrite = PHPExcel_IOFactory::createWriter($PHPExcelObj, 'Excel2007'); 
        $newfilename= $path.$filename."-".date("Y-m-d-H-i").".xlsx";
        
        // header('Content-Type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename="Posts.xlsx"');
        
        $sheetWrite -> save($newfilename);
        $PHPExcelObj->disconnectWorksheets();
    }
	
}

