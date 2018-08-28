 # **WORDPRESS插件开发** #
 
 ***WP2WPQAQ*** 插件是一个根据WordPress中的xmlrpc进行编写的实现两个WordPress站点进行文章同步的插件。目前该插件实现了文章的同步，媒体文件上传（上传到媒体库），但是分类(category)，标签（Tag）和自定义字段（custom_fields）的同步功能未能实现。

 该插件由于存在大部分不符合目前项目的具体需求，且因为缺乏WordPress对应API详细案例学习，导致开发不完善，没能实现如下功能：

**1.非媒体库文件上传到非媒体库.**<br>
   	eg.从下面的ablog站点将order/目录的image.jpeg图片上传到bblog的order/目录中。
目前实现的上传功能是这样的，假设当前时间是2017年12月份，则ablog的order/image.jpeg图片将会上传到bblog的wp-content/uploads/2017/12/image.jpeg。
> ablog---- <br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|-- order/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-- image.jpeg<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|--wp-content/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--uploads<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--2017/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--11/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--12/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|--wp-includes/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|--wp-admin/<br>
>  
>  

----------
eg.想上传到order/结果却是上传到wp-content/uploads/2017/12/目录。

> bblog---- <br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|-- order/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-- images2.png<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|--wp-content/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--uploads<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--2017/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--11/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--12/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--<span style="color:red;">image.jpeg</span><br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|--wp-includes/<br>
>  &nbsp;&nbsp;&nbsp;&nbsp;|--wp-admin/<br>
>  
>  

**2.custom_fields字段无法按照[API](https://codex.wordpress.org/XML-RPC_WordPress_API/Posts "xmlrpc API")中所述可以更新custom_fields字段，所以导致使用的插件[ALL-IN-ONE-SEO-PACK](https://cn.wordpress.org/plugins/all-in-one-seo-pack/ "ALL-IN-ONE-SEO-PACK")的信息无法同步到目标站点。**

**3.分类同步，假设在ablog站点中，存在分类目录叫A，而bblog中没有A分类，则需要实现同步A分类到bblog中，目前是因为不知道如何检查bblog中是否存在A分类，如果直接使用API中的wp.newCategory是否会重复添加分类。**

**4.标签同步，API中不提供标签的新增，只存在wp.getTags的实现。**

插件目录结构：
	
> **WP2WPQAQ** <br>
> &nbsp;&nbsp;&nbsp;&nbsp;|-asset/<br>
> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-- js/<br>
> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-- wp2wp-sync.js<br>
> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-- css/<br>
> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-- wp2wp-admin.css<br>
> &nbsp;&nbsp;&nbsp;&nbsp;|--lang/<br>
> &nbsp;&nbsp;&nbsp;&nbsp;|-- wp2wpqaq.php  ----插件主入口文件<br>
> &nbsp;&nbsp;&nbsp;&nbsp;|-- wp2wp-options.php ----配置同步目标站点页<br>
> &nbsp;&nbsp;&nbsp;&nbsp;|-- wp2wp-ajax.php ----异步实现同步操作实现功能<br>
> &nbsp;&nbsp;&nbsp;&nbsp;|-- uninstall.php ----卸载插件<br>
> &nbsp;&nbsp;




- **wp2wpqaq.php** 代码如下：<br>

	    <?php 
	    /**
	     * Plugin Name:	WP2WPQAQ
	     * Plugin URI:	#
	     * Description: Synchronize articles between two WordPress sites.
	     * Author: DHM(huimingdeng)
	     * Author URI: #
	     * Version: 0.0.1
	     */
	    // Nothing in this plugin works outside of the admin area, so don't bother loading it if we're not looking at the admin panel...
	    if(is_admin()){
	    
	    	define( 'WP_OPTIONS_VERSION', 1 );
	    
	    	add_action( 'admin_menu', 'wp2wp_menu' );//Add Menu
	    	// add_action('admin_head','wp2wp_active_style');
	    	add_action( 'admin_init', 'wp2wp_register_settings' );
	    	add_action('add_meta_boxes','my_meta_box');
	    	add_filter( 'plugin_action_links', 'wp2wp_settings_link', 10, 2 );
	    	
	    	// add_action('add_meta_boxes', array('sync-details-container', 'wp2wp_view'));
	    
	    	// Activate the style sheets and scripts.
	    	function wp2wp_active_style(){
	    		$css = plugins_url('assets/css/',__FILE__).'wp2wp-admin.css';
	    		$js = plugins_url('assets/js/',__FILE__).'wp2wp-sync.js';//echo $js;
	    		wp_register_script('wp2wp-sync',$js,array());
	    		wp_register_style('wp2wp-admin',$css,array());
	    		wp_enqueue_script('wp2wp-sync');
	    		wp_enqueue_style('wp2wp-admin');
	    	  	
	    	}
	    	// add a meta box...
	    	function my_meta_box(){
	    		$screen = get_current_screen();
	    		if('add' !== $screen->action){
	    			add_meta_box('wp2wp_view','WP2WPQAQ-synchronize','wp2wp_view','post','side','high');
	    			add_action('admin_enqueue_scripts','wp2wp_active_style');
	    		}
	    	}
	    
	    	// Create a template...
	    	function wp2wp_view(){
	    		?>
	    		<div id="sync-details-container">
	    			<div class="sync-span-body"><span><?php _e('Push content to Target: <br>', 'wp2wpqaq');?><span title="配置页设置的同步对象."><b><?php $server=get_option("wp2wp_server_option"); echo $server['host'];?></b></span></span></div>
	    			<div id="sync-content">
	    			</div>
	    			<button type="button" class="btn button-primary" onclick="wp2wppush(<?php the_ID();?>)">Push to Target</button>
	    			<input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)) . '/wp2wp-ajax.php'; ?>">
	    		</div>
	    		<?php
	    	}
	    	// Add submenu...
	    	function wp2wp_menu() {
	    		add_submenu_page( 'options-general.php', 'WP2WPQAQ Settings', 'WP2WPQAQ', 'manage_options', 'wp2wpqaq', 'wp2wp_setting' );
	    	}
	    	// Setting page...
	    	function wp2wp_setting(){
	    		$css_uri=plugins_url('assets/css/wp2wp-admin.css',__FILE__);
	    	  	// wp_enqueue_style('wp2wp-admin.css',$css_uri);
	    		require_once( 'wp2wp-options.php' );
	    	}
	    	// Register valid admin options...
	    	function wp2wp_register_settings(){
	    		register_setting( "wp2wpqaq_options", "wp2wp_server_option" , "wp2wp_option_save");
	    	}
	    	// Save option...
	    	function wp2wp_option_save($options){
	    		if(!empty($options)){
	    			$remoteServer = trim( $options['host'] );
	    			if ( ( 'http://' != substr( $remoteServer, 0, 7 ) ) && ( 'https://' != substr( $remoteServer, 0, 8 ) ) ) {
	    				$remoteServer = 'http://'.$remoteServer;
	    			}
	    			if ( '/' != substr( $remoteServer, -1 ) ) {
	    				$remoteServer .= '/';
	    			}
	    			if ( include_once(  ABSPATH . WPINC . '/class-IXR.php' ) ) {
	    				if ( include_once(  ABSPATH . WPINC . '/class-wp-http-ixr-client.php' ) ) {
	    					$xmlrpc = new WP_HTTP_IXR_CLIENT( $remoteServer.'xmlrpc.php' );
	    					$xmlrpc->query( 'wp.getOptions', array( 0, $options['user'], $options['pass'], array( 'software_name', 'software_version', 'so_api' ) ) );
	    					$xmlrpcResponse = $xmlrpc->getResponse();
	    					if ( null == $xmlrpcResponse ) {
	    						if ( -32300 == $xmlrpc->getErrorCode() ) {
	    							$options['authenticated'] = false;
	    							$newOptions['group'][$groupId]['servers'][$serverKey]['api'] = 'API Unavailable';
	    						} else {
	    							$options['authenticated'] = false;
	    							$options['api'] = 'Unknown';
	    						}
	    					}else{
	    						if ( isset( $xmlrpcResponse['faultString'] ) ) {
	    							$options['authenticated'] = false;
	    							$options['api'] = __( trim( $xmlrpcResponse['faultString'], ' .' ), 'WP2WPQAQ' );
	    						}else{
	    							$options['authenticated'] = true;
	    							if ( isset( $xmlrpcResponse['so_api'] ) ) {
	    								$options['api'] = sprintf( __( 'WP2WPQAQ Synchronize API v%s', 'WP2WPQAQ' ), $xmlrpcResponse['so_api']['value'] );
	    							} else {
	    								$options['api'] = $xmlrpcResponse['software_name']['value'].' '.$xmlrpcResponse['software_version']['value'];
	    							}
	    						}
	    					}
	    				}
	    			}
	    		}
	    		return $options;
	    	}
	    	
	    	// Settings link on plugins page...
	    	function wp2wp_settings_link( $links, $file ) {
	    		if ( plugin_basename( __FILE__ ) == $file ) {
	    			array_push( $links, '<a href="options-general.php?page=wp2wpqaq">'.__( 'Settings', 'wp2wpqaq' ).'</a>' );
	    		}
	    		return $links;
	    
	    	}
	    }



- wp2wp-options.php 代码如下：<br>

        <div class="wrap">
    	<h2>WP2WPQAQ Settings:</h2>
    	<div>
    		<form action="options.php" method="post">
    		<?php settings_fields( 'wp2wpqaq_options' ); ?>
    		<?php $options=get_option("wp2wp_server_option");?>
    			<table class="form-table">
    				<tbody>
    					<tr>	
    						<th>Host Name of Target:</th>
    						<td><input type="text" name="wp2wp_server_option[host]" id="wp2wp_server_option" value="<?php echo $options['host'];?>"></td>
    					</tr>
    					<tr>
    						<th>Username on Target:</th>
    						<td><input type="text" name="wp2wp_server_option[user]" id="wp2wp_server_option" autocomplete="off" value="<?php echo $options['user'];?>"></td>
    					</tr>
    					<tr>
    						<th>Password on Target:</th>
    						<td><input type="password" name="wp2wp_server_option[pass]" id="wp2wp_server_option" autocomplete="new-password" value="<?php echo $options['pass'];?>"><span class="dashicons dashicons-yes"><?php if(isset($options['authenticated'])){echo 'auth:'.$options['authenticated'];}else{echo 'auth:'.$options['api'];}?></span></td>
    					</tr>
    				</tbody>
    			</table>
    			<!-- <input type="submit" name="submit" class="button button-primary" value="保存更改"> -->
    			<?php submit_button(); ?>
    		</form>
    	</div>
    </div>
- **wp2wp-ajax.php** 代码如下：<br>

    	<?php 
    	require_once(dirname(__FILE__)."/../../../wp-config.php");
	    include_once(ABSPATH . WPINC . '/class-IXR.php');
	    global $wpdb;
	    $action = $_REQUEST['action'];
	    if($action=='push'){
	    	$postId = $_POST['postId'];
	    	// $postId=142;//116;
	    		
	    	// Test 
	    	$server = get_host();
	    	if($server['authenticated']){
	    		$post_tab = $table_prefix."posts";
	    		$sql = "SELECT * FROM $post_tab where ID=$postId";
	    		$post=$wpdb->get_row($sql,ARRAY_A);
	    		$seo_tab = $table_prefix."postmeta";
	    		$seo = "SELECT meta_key,meta_value FROM $seo_tab WHERE post_id = $postId AND (meta_key LIKE '_aioseop_%')";
	    		$custom_fields = $wpdb->get_results($seo,ARRAY_A);
	    		$host = addslashes(site_url().'/');
	    		//设置远程站点信息
	    		$target = $server['host'];
	    		if ( '/' != substr( $target, -1 ) ) {
	    			$target .= '/';
	    		}
	    		$username = $server['user'];
	    		$password = $server['pass'];
	    		$result=sync_post($post,$username,$password,$target,$host,$custom_fields);
	    	}else{
	    		$result=array('status'=>500,'msg'=>$server['api']);
	    	}
	    	echo json_encode($result);
	    	
	    }else{
	    	echo json_encode(array('status'=>404,'msg'=>'No operation.'));
	    }
    
	    // 替换源站点附件
	    function replace_host($string,$target,$host){
	    	if('' == $target || '' == $host) return false;
	    	// 判断连接是否为图片，PDF附件等
	    	$allowedExts=array('jpeg','jpg','png','gif','txt','pdf');
	    	$pattern = "(http://[a-zA-Z0-9\./]+)";
	    	preg_match_all($pattern, $string, $urls);
	    	//如果源站点的附件等才替换，其他的不替换
	    	if(!empty($urls)&&!empty($urls[0])){
	    		foreach ($urls[0] as $v) {
	    			$ext = strtolower(pathinfo($v,PATHINFO_EXTENSION));
	    			if(in_array($ext, $allowedExts)){
	    				$tmp=preg_replace('('.$host.')',$target,$v);
	    				// echo $tmp."\r\n";
	    				$string=str_replace($v,$tmp,$string);//替换掉附件的url域名，非附件不替换
	    				// echo $v."\r\n";
	    				// $string = $tmp;
	    			}
	    		}
	    	}
	    	// echo $string;
	    	return $string;
	    	//测试
	    	// return preg_replace('('.$host.')',$target,$string);
	    }
	    /**
	     * 远程同步文章(A->B)
	     * @param  array  $post 需要同步的文章信息
	     * @param  string $user 同步站点的用户（登录用）
	     * @param  string $pass 同步站点的用户的登陆密码
	     * @param  string $target   目标站点域名（同步站点）
	     * @param  string $host 来源站点域名（本站点）
	     * @param  array  $custom_fields 需要同步的自定义字段
	     * @return array   返回消息记录（成功200失败400）
	     */
	    function sync_post($post,$user,$pass,$target,$host,$custom_fields){
	    	
	    	$xmlrpcurl=$target.'xmlrpc.php';
	    	$blogid=get_current_blog_id();
	    	$GLOBALS['xmlrpc_internalencoding'] = 'UTF-8';
	    	$client = new IXR_Client($xmlrpcurl);
	    	//先获取文章主体内容，用于上传内容中的附件
	    	$file=$post['post_content'];
	    	// $post['post_content']=replace_host($post['post_content'],$target,$host);//替换成目标站点url
	    	$post['guid']='';
	    	if(!empty($custom_fields)){//如果用户自定义非空，则添加到$post中
	    		$cs_fields=array();
	    		foreach ($custom_fields as $k => $v) {
	    			$tmp_fields['key']=$v['meta_key'];
	    			$tmp_fields['value']=$v['meta_value'];
	    			// $tmp_fields=array($v['meta_key'],$v['meta_value']);
	    			$cs_fields[]=$tmp_fields;
	    			
	    		}
	    		$post['custom_fields']=$cs_fields;
	    		
	    		// $post['custom_fields']=array(array('key' => '_aioseop_title', 'value' => '还是测试'));
	    	}
	    	// 新的文章则进行新增，否则修改 
	    	$target_id = get_option("target_postid_".$post['ID']);
	    	if(!$target_id){
	    		
	    		$client->query("wp.newPost",$blogid,$user,$pass,$post);
	    		$response=$client->getResponse();
	    		// 成功后设置
	    		if ($response['faultCode']==0){
	    			if( is_a( $client->message, '\IXR_Message' ) ){
	    				// 需要设置 target_postid_{the_ID} => 返回的post_id
	    				if(!$client->message->message->params[0]){//如果无法获取post_id,则根据返回的xml获取
	    					if(preg_match('/<string>(\d+)<\/string>/', $client->message->message,$rs)){
	    						//如果正则匹配成功
	    						if(!empty($rs)&&!empty($rs[1])){
	    							update_option("target_postid_".$post['ID'],$rs[1]);
	    							/*if(!empty($custom_fields)){//同步SEO元素
	    								foreach ($custom_fields as $k => $v) {
	    									
	    								}
	    							}*/
	    						}
	    					}
	    				}else{
	    					update_option("target_postid_".$post['ID'],$client->message->message->params[0]);
	    				}
	    			}
	    			$msg = "The article uploaded successfully.";
	    			$result=update_media($file,$target,$host,$user,$pass);
	    			$is_true=200;  	
	    		} else {
	    			$is_true=400;//文章同步失败
	    		   	$msg = 'Fail:' . $response['faultString'];
	    		}
	    	}else{//echo "yes";
	    		$post['ID'] = intval($target_id);//换成目标网站的文章id
	    		$post['post_modified'] = $post['post_modified_gmt'] = date("Y-m-d H:i:s");
	    		// 需要修改的字段
	    		$content = array(
	    			'post_status' => $post['post_status'],//文章状态
	    			'post_title' => $post['post_title'],//标题
	    			'post_excerpt' => $post['post_excerpt'],//摘要
	    			'post_content' => $post['post_content'],//正文
	    			'post_name' => $post['post_name'],
	    			'post_password' => $post['post_password'],
	    			'post_thumbnail' => intval($post['post_thumbnail']),
	    			// 'custom_fields' => $post['custom_fields'],
	    		);
	    		$client->query("wp.editPost",$blogid,$user,$pass,intval($target_id),$content);
	    		$response=$client->getResponse();
	    		if($response['faultCode']==0){
	    			$msg = "The article uploaded successfully.";
	    			$result=update_media($file,$target,$host,$user,$pass);
	    			$is_true=200;//  
	    			/*if(!empty($custom_fields)){//同步SEO元素
	    				foreach ($custom_fields as $k => $v) {
	    					
	    				}
	    			}*/	
	    		} else {
	    			$is_true=400;//文章同步失败
	    		   	$msg = 'Fail:' . $response['faultString'];
	    		}
	    	}	//	exit;
	    	return array("status"=>$is_true,"msg"=>$msg);
	    	// return $is_true;
	    }
	    // 获取目标站点的域名
	    function get_host(){
	    	$option = get_option('wp2wp_server_option');//获取设置中保存的配置项
	    	return $option;
	    }
    
    
	    // 附件处理,只能上传到目标站点的/wp-content/uploads/{Year}/{Month} {Year},{Month}为当前的年月
	    // 返回值中 status 的值大于1则成功
	    function update_media($postContent,$target,$host,$user,$pass){
	    	$is_true=0;
	    	$xmlrpcurl=$target.'xmlrpc.php';
	    	$client = new IXR_Client($xmlrpcurl);
	    	// Determine whether the connection is a picture, PDF attachment, etc
	    	$allowedExts=array('jpeg','jpg','png','gif','txt','pdf');
	    	// reference：http://www.jb51.net/article/23511.htm
	    	$mimetype=array(
	    		'jpg'=>'image/jpeg',
	    		'gif'=>'image/gif',
	    		'png'=>'image/png',
	    		'bmp'=>'image/bmp',
	    		'pdf'=>'application/pdf',
	    		'txt'=>'text/plain'
	    		);
	    	// Matching gets the url.
	    	$pattern = "(http://[a-zA-Z0-9\./]+)";
	    	preg_match_all($pattern, $postContent, $urls);
	    	$msg = "";
	    	// print_r($urls);
	    	// Gets the file suffix name for the url. pathinfo($fileInfo['name'],PATHINFO_EXTENSION)
	    	if(!empty($urls)&&!empty($urls[0])){
	    		foreach ($urls[0] as $v) {
	    			$ext = strtolower(pathinfo($v,PATHINFO_EXTENSION));
	    			if(in_array($ext, $allowedExts)){
	    				$tmp=preg_replace('('.$host.')','',$v);
	    				// $attach[]=$tmp;
	    				$arr=explode('/',$v);
	    				$filename=end($arr);
	    				// echo $filename."\r\n";
	    				//替换目录，得到附件
	    				$path = str_replace($filename,'',$tmp);
	    				// echo $path."\r\n";
	    				$upload_file = $tmp;
	    				// echo $upload_file."\r\n";
	    				$attachment = array(
	    					'name' => $filename,//pathinfo($filename,PATHINFO_FILENAME),
	    					'type' => $mimetype[$ext],
	    					'bits' => new IXR_Base64( file_get_contents( ABSPATH.$path.$filename )),
	    					'overwrite' => true
	    					);
	    				$client->query('wp.uploadFile','',$user,$pass,$attachment);
	    				// $clinet->query('metaWeblog.newMediaObject','',$user,$pass,$attachment);
	    				$response=$client->getResponse();
	    				// print_r($client);
	    				if($response['faultCode']==0){
	    					$is_true +=1;
	    					$msg .= $filename." upload success. \r\n";
	    				}else{
	    					$is_true -= 1;//不成功则减一
	    					$msg .= $filename." upload failured... \r\n";
	    				}
	    			}
	    		}
	    	}
	    	// print_r($attachment);
	    	// echo $msg;
	    	return array("status"=>$is_true,"msg"=>$msg);
	    }

- **wp2wp-sync.js** 代码如下：<br>
		
		function wp2wppush(postId){
			var ajaxurl = $('#ajaxurl').val();
			var data = {postId:postId,action:'push'};
			$.ajax({
				url:ajaxurl,
				data:data,
				type:'POST',
				dataType:'json',
				beforeSend:function(){
					$("#sync-content").html("<span class='sync-loading'>Loding....</span>");
				},
				success:function(msg){
					if(msg.status==200){
					// if(msg==200){
						// alert(msg.status);
						$("#sync-content").html("<span class='sync-success'>当前文章同步成功!</span>").css({"margin-bottom":"10px","border":"1px dashed gray"});
					}else{
						var html = "<span class='sync-error'>同步失败!</span>";
						html+="<div class='error-msg'>"+msg.msg+"</div>";
						$("#sync-content").html(html).css("margin-bottom","10px");
					}
				},
				error:function(msg) {
					html="<span>Sorry,unknown error.";
					$("#sync-content").html(html);
				}
			})
			// alert(postId);
		}
		
希望大家提供您宝贵的经验，帮助完善插件功能，非常感谢。
