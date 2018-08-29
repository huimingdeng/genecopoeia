<?php
require_once '../wp-config.php';
return array(
	//数据库链接
	'DB_NAME'=>DB_NAME,
	'DB_USER'=>DB_USER,
	'DB_PASSWORD'=>DB_PASSWORD,
	'DB_HOST'=>DB_HOST,
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
