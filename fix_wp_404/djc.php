 <?php  
 // header('content-type:text/html;charset=utf-8' );  
   
 // 必须加载扩展  
 if (!function_exists("pcntl_fork")) {  
     die("pcntl extention is must !");  
 }  
 //总进程的数量  
 $totals = 3;  
 // 执行的脚本数量  
 $cmdArr = array();  
 // 执行的脚本数量的数组  
 for ($i = 0; $i < $totals; $i++) {  
     $cmdArr[] = array("path" => __DIR__ . "/run.php",  'pid' =>$i ,'total' =>$totals);  
 }  
   
 /* 
 展开：$cmdArr 
 Array 
 ( 
     [0] => Array 
         ( 
             [path] => /var/www/html/company/pcntl/run.php 
             [pid] => 0 
             [total] => 3 
         ) 
  
     [1] => Array 
         ( 
             [path] => /var/www/html/company/pcntl/run.php 
             [pid] => 1 
             [total] => 3 
         ) 
  
     [2] => Array 
         ( 
             [path] => /var/www/html/company/pcntl/run.php 
             [pid] => 2 
             [total] => 3 
         ) 
  
 ) 
 */  
   
 pcntl_signal(SIGCHLD, SIG_IGN); //如果父进程不关心子进程什么时候结束,子进程结束后，内核会回收。  
 foreach ($cmdArr  as   $cmd) {  
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
         $path   = $cmd["path"];  
         $pid = $cmd['pid'] ;  
         $total = $cmd['total'] ;  
         echo exec("/usr/bin/php {$path} {$pid} {$total}")."\n";  
         exit(0) ;  
     }  
 }  
 ?>  