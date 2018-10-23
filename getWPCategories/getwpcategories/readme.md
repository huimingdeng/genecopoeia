# getwpcategories 获取wp已发布文章固定连接 #
接口为实现，生成和删除 wp 中已经发布的文章的固定连接的json文件。

PHP curl：调用示例(仅供参考)：

	function curlpost($url,$param){
		$postUrl = $url;
		$curlPost = $param;
		$ch = curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		return $data;
	}

	$url = "https://xxxx/getwpcategories/getwpcategories.php";
	//删除 实例
	$param = array(
		"operation" => "delete",
		"filename" => "link-18101302.json"
	);
	//生成实例
	$param = array('operation' => "generate", );
	//生成后，忘记了 json 调用的URL路径，可以查询：
	$param = array('operation' => "getjsonurl", );
	
	$data = curlpost($url,$param);
	print_r($data);

----------
例如：

	//生成实例返回 json 信息示例：
	{
		"status":200, // 状态码
		"path":"/home/xxxx/httpdocs/getwpcategories/data/link-18102304.json",
		"url":"https:\/\/xxxxx\/data\/link-18102304.json", //调用生成的json地址
		"info":"Ok, Generate success."
	}
	// 生成后，忘记 json 文件调用路径可以查询
	{
		"status":200,
		"url":"https:\/\/xxxxx\/getwpcategories\/data\/link-18102304.json",
		"info":"Ok, success."
	}

使用 Python 脚本调用（仅供参考）：

	#!/usr/bin/env python3
	import requests
	url='https://xxxxx/getwpcategories/getwpcategories.php'
	# 创建 json 文件
	# param = {'operation':'generate'}
	# 获取 json 文件链接
	param = {'operation': 'getjsonurl'}
	r = requests.post(url, data=param)
	
	# print(r.text)
	print(r.content)
	# 返回格式：
	b'{"status":200,"url":"https:\/\/xxxxx\/getwpcategories\/data\/link-18102304.json","info":"Ok, success."}'



