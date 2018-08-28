<?php
// declare(ticks=1);//配合信号量杀死进程用
/**
 * 由于运行时间较长，请在CLI模式下运行
 * 从problem_post.php文件加载需要检查的post_id。（如果没有，则从wp_post中取出全部的post_id）
 * 检查每个post_id对应的post_content中的属于本域名下的链接（a标签的href属性），判断其是否失效（404状态）
 * 删除404状态的链接，使用tidy库整理html文本
 * 保存更改post_content前、后的文本到文件中，用于比对检查
 * 命令行使用：
 * php multi_check.php ：运行本程序，对获得的post_id重头到尾扫描一次
 */
// 必须加载扩展  
if (!function_exists("pcntl_fork")) {  
	die("pcntl extention is must !");  
} 
$time_s = microtime(true);

// require_once(dirname(__FILE__).'/problem_post.php');

// $fix_wp_404 = new FIX_WP_404();
// //create some paths
// $fix_wp_404->mk_check_path();


// if(!isset($problem_post_id_list) || count($problem_post_id_list)==0){
// 	$problem_post_id_list = array();
// 	foreach ($fix_wp_404->get_post_content('all', 'all' ,array('ID') , false) as $value) {
// 		array_push($problem_post_id_list , $value['ID']);
// 	};

// }
// $fix_wp_404->set_queued_post_id($problem_post_id_list);





 
//总进程的数量  
$totals = 2;  

function run(){
	// pcntl_signal(SIGINT, function ($signo) {
	// 	if ($signo == SIGINT) {
	// 		exit();//接收到Ctrl+C后，程序意外关闭，会调用__destruct()方法
	// 	}
	// });
	require_once(dirname(__FILE__).'/functions.php');
	// echo "jhh";
	$fix_wp_404 = new CHILD_PROGRESS_FIX_WP_404();
	$i=0;
	while (1) {
		$post_id = $fix_wp_404->get_queued_post_id();
		// echo "post_id:" .$post_id . PHP_EOL;
		if($post_id){
			$id_content_array = $fix_wp_404->get_post_content_from_post_id($post_id );
			// print_r(  $id_content_array );
			$fix_wp_404->check_post_content_404($id_content_array);
			$fix_wp_404->save_message_to_file();
			// $fix_wp_404->set_checked_post($post_id);
		}else{
			echo "Finished.".PHP_EOL;
			return;
		}

		if(++$i > 1000){
			break;
		}
	}
}

// pcntl_signal(SIGCHLD, SIG_IGN); //如果父进程不关心子进程什么时候结束,子进程结束后，内核会回收。  
for ($i=0; $i<$totals;$i++) {  
	$pid = pcntl_fork();    //创建子进程  
	//父进程和子进程都会执行下面代码  
	if ($pid == -1) {  
		//错误处理：创建子进程失败时返回-1.  
		die('could not fork');  
	} else if ($pid) {  
		//父进程会得到子进程号，所以这里是父进程执行的逻辑  
		//如果不需要阻塞进程，而又想得到子进程的退出状态，则可以注释掉pcntl_wait($status)语句，或写成：  
		pcntl_wait($status,WNOHANG); //等待子进程中断，防止子进程成为僵尸进程。  
	} else { 
		
		//子进程得到的$pid为0, 所以这里是子进程执行的逻辑。  
		// echo "sssss";
		run();
		exit(0);
	}  
}  



// $i=0;
// $sum = count($problem_post_id_list);
// foreach ($problem_post_id_list as $post_id) {
// 	echo $post_id . PHP_EOL;
// 	if($fix_wp_404->get_checked_post($post_id)===false){
// 		$id_content_array = $fix_wp_404->get_post_content_from_post_id($post_id );
// 		$fix_wp_404->check_post_content_404($id_content_array);
// 		$fix_wp_404->save_message_to_file();
// 		$fix_wp_404->set_checked_post($post_id);
// 	}
// 	$fix_wp_404->work_status(++$i , $sum);
// }

$time_e = microtime(true);
echo "Time use : " . ($time_e - $time_s) ."\n\n";