<?php
// //数据库链接
// define('DB_NAME', 'genec1');
// define('DB_USER', 'genec1');
// define('DB_PASSWORD', 'k0nob1');
// define('DB_HOST', 'localhost');
// define('DB_CHARSET', 'utf8');

// //域名设置
// // define('DOMAIN' , 'gcdev.fulengen.cn');
// define('DOMAIN' , 'genecopoeia.com');

// //调试
// define('DEBUG' , true);

return array(
	//数据库链接
	'DB_NAME'=>'genec1',
	'DB_USER'=>'genec1',
	'DB_PASSWORD'=>'k0nob1',
	'DB_HOST'=>'localhost',
	'DB_CHARSET'=>'utf8',
	//域名设置
	'DOMAIN'=>'genecopoeia.com',
	// 'DOMAIN'=>'gcdev.fulengen.cn',
	//是否调试
	'DEBUG'=>true,
	
	'CHECTED_FILE_PATH'=>'./check',//检查文件记录保存位置
	
	'ERROR_LOG'=>'logs/error_message.log',//错误日志
	
	'URL_STATUS_CODE_LOG'=>'logs/url_status_code.log',//url的状态码记录，可以快速判断url的状态，不需要再测试一次
	
	'POST_CHECKED_LOG'=>'logs/post_checked.log',//已检查过的文章记录

	'PROBLEM_POST'=> 'logs/queued_post.txt',
	
	'REPLACE_404_LINK_TO'=>'',//把含404的链接替换为。（默认空，则删除链接和文字；非空，则把连接指向这个值）
);
