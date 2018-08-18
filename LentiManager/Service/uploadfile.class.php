<?php 
	/**
	* 文件上传类（多文件，单文件）
	*/
	class uploadfile
	{
		protected $fileName;
		protected $imgflag;
		protected $uploadPath;
		protected $allowedMime;
		protected $allowedExt;
		protected $maxSize;
		protected $fileInfo;
		protected $error;
		protected $ext;
		protected $imgMime;
		protected $uniName;
		protected $destination;
		protected $errMsg;
		protected $errNo;
		protected $encrypt;
		
		/**
		 * 
		 * @param string  $fileName    文件对象名称(form表单file对象name属性)
		 * @param string  $uploadPath  文件上传路径
		 * @param array   $allowedExt  允许上传的文件扩展类型
		 * @param integer $maxSize     文件上传限制大小，5M=5242880
		 * @param array   $allowedMine 允许上传的文件/图片类型
		 * @param boolean $imgflag      图片是否验证，默认是
		 */
		public function __construct( $fileName='file', $uploadPath="./uploads", $allowedExt=array('jpeg','jpg','png','gif','txt','csv','xlsx','xlx'), $maxSize=5242880, $allowedMime=array('image/jpeg','image/png','image/gif','text/plain','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'), $imgflag=false, $encrypt=false )
		{
			$this->fileName=$fileName;//文件名
			$this->maxSize=$maxSize;
			$this->allowedMime=$allowedMime;
			$this->allowedExt=$allowedExt;//扩展名
			$this->uploadPath=$uploadPath;//上传保存路径
			$this->imgflag=$imgflag;
			$this->imgMime=array('image/jpeg','image/png','image/gif');
			$this->fileInfo=$_FILES[$this->fileName];
			$this->encrypt=$encrypt;
		}

		/**
		 * 检测文件上传是否错误[OK]
		 * @return boolean
		 */
		protected function checkError(){
			if($this->fileInfo['error']!=0){
				switch($this->fileInfo['error']){
					case 1:
						$this->error=$this->fileInfo['name']."上传的文件超过了PHP配置文件中upload_max_filesize选项限制的值";
						break;
					case 2:
						$this->error=$this->fileInfo['name']."上传文件的大小超过了表单中 MAX_FILE_SIZE 选项指定的值";
						break;
					case 3:
						$this->error=$this->fileInfo['name']."文件只有部分被上传";
						break;
					case 4:
						$this->error=$this->fileInfo['name']."没有文件被上传。";
						break;
					case 6:
						$this->error=$this->fileInfo['name']."找不到临时文件夹。";
						break;
					case 7:
						$this->error=$this->fileInfo['name']."文件写入失败。";
						break;
					case 8:
						$this->error=$this->fileInfo['name']."由于PHP扩展程序中断，无法上传";
						break;
				}
				return false;
			}
			return true;			
		}

		/**
		 * 检测文件大小[0K]
		 * @return boolean
		 */
		protected function checkSize(){
			if($this->fileInfo['size'] > $this->maxSize){
				$this->error=$this->fileInfo['name']."上传文件过大";
				return false;
			}
			return true;
		}

		/**
		 * 检测文件扩展名[OK]
		 * @return boolean
		 */
		protected function checkExt(){
			$this->ext=strtolower( pathinfo($this->fileInfo['name'],PATHINFO_EXTENSION) );
			if(!in_array($this->ext,$this->allowedExt)){
				$this->error=$this->fileInfo['name']."不允许的扩展名";
				return false;
			}
			return true;
		}
		/**
		 * 检测文件类型[OK]
		 * @return boolean
		 */
		protected function checkMime(){
			if(!in_array($this->fileInfo['type'],$this->allowedMime)){
				$this->error=$this->fileInfo['name']."文件类型错误";
				return false;
			}
			return true;
		}
		/**
		 * 检测是否为真实图片[OK]
		 * @return boolean
		 */
		protected function checkTrueImage(){
			if($this->imgflag){
				if(!@getimagesize($this->fileInfo['tmp_name'])){
					$this->error=$this->fileInfo['name']."文件真实类型不是图片";
					return false;
				}
			}
			return true;
		}
		/**
		 * 检测文件上传方式是否为http post上传的[OK]
		 * @return boolean
		 */
		protected function checkHTTPPost(){
			if(!is_uploaded_file($this->fileInfo['tmp_name'])){
				$this->error=$this->fileInfo['name']."文件不是通过HTTP POST上传的<br>\r\n";
				return false;
			}
			return true;
		}
		/**
		 * 显示错误信息，中断程序
		 */
		protected function showError(){
			$this->errMsg = '<span style="color:red">'.$this->error.'</span>';
			$this->errNo = 400;
			// exit;
			return json_encode(array('No'=>$this->errNo,'Msg'=>$this->errMsg));
		}
		/**
		 * 检查上传文件的路径是否存在，不存在则创建
		 */
		protected function checkUploadPath(){
			if(!file_exists($this->uploadPath)){
				mkdir($this->uploadPath,0755,true);
			}
		}
		/**
		 * 获取唯一字符串
		 * @return string
		 */
		protected function getUniName(){
			if($this->encrypt){
				return md5(uniqid(microtime(true),true));
			}
			else{
				date_default_timezone_set("Asia/Shanghai");
				$name = strtolower(pathinfo($this->fileInfo['name'],PATHINFO_FILENAME)).'-'.date("Y-m-d-H-i");
				return $name;
			}
		}
		/**
		 * 文件上传(单/多文件)
		 * @return string
		 */
		public function uploadFile(){

			$this->checkUploadPath();
			// 判断文件是否为多文件,如果文件名是数组则是多文件上传
			if(is_Array($this->fileInfo['name'])){
				// print_r($this->fileInfo);
				$tmpfile=array();
				$i=0;
				foreach ($this->fileInfo['name'] as $k => $v) {
					$tmpfile[$i]['name'] = $this->fileInfo['name'][$k];
					$tmpfile[$i]['type'] = $this->fileInfo['type'][$k];
					$tmpfile[$i]['tmp_name'] = $this->fileInfo['tmp_name'][$k];
					$tmpfile[$i]['size'] = $this->fileInfo['size'][$k];
					$tmpfile[$i]['error'] = $this->fileInfo['error'][$k];
					$i++;
				}
				// 如果数组不为空，则存在上传文件，移动文件
				if(!empty($tmpfile)){
					// 循环判断
					$tmperror=array();
					foreach ($tmpfile as $k => $v) {
						$this->fileInfo=$v;
						// 判断文件类型，是否启用判断真实图片
						if(in_array($this->fileInfo['type'],$this->imgMime)){
							$this->imgflag=true;
						}else{
							$this->imgflag=false;
						}
						// 检查错误信息
						if($this->checkError()&&$this->checkSize()&&$this->checkExt()&&$this->checkMime()&&$this->checkTrueImage()&&$this->checkHTTPPost()){
							$this->uniName=$this->getUniName();
							$this->destination=$this->uploadPath.'/'.$this->uniName.'.'.$this->ext;
							if(@move_uploaded_file($this->fileInfo['tmp_name'],$this->destination)){
								$msg[]=$this->fileInfo['name']."成功上传到".$this->destination."\r\n";
							}else{
								$tmperror[]=$this->fileInfo['name']."文件移动失败";
							}
						}else{
							$tmperror[]=$this->error;
						}
					}
				}
				// 判断是否存在错误信息
				if(count($tmperror)>0&&count($msg)==0){
					$this->error=implode(',', $tmperror);
					$this->showError();
				}elseif(count($msg)>0&&count($tmperror)==0){
					return json_encode(array("No"=>200,"Msg"=>implode(',', $msg)));
				}else{
					$this->error=implode(',',$msg).implode(',',$tmperror);
					$this->showError();
				}
				
			}else{
				if($this->checkError()&&$this->checkSize()&&$this->checkExt()&&$this->checkMime()&&$this->checkTrueImage()&&$this->checkHTTPPost()){
					
					$this->uniName=$this->getUniName();
					$this->destination=$this->uploadPath.'/'.$this->uniName.'.'.$this->ext;
					
					if( @move_uploaded_file( $this->fileInfo['tmp_name'],$this->destination ) ){
						return json_encode(array("No"=>200,"Msg"=>$this->destination));
					}else{
						$this->error="文件移动失败";
						$this->showError();
					}
				}else{
					$this->showError();
				}
			}
		}

	}

	/**
	 * 文件对比排除类
	 */
	class comparefile
	{
		protected $compareA;
		protected $exclude;
		protected $newfileA;
		protected $newexclude;

		/**
		 * 
		 * @param string $fileA           [description]
		 * @param string $excludeA        [description]
		 * @param string $newfname        [description]
		 * @param string $newexcludefname [description]
		 */
		public function __construct($fileA,$excludeA,$newfname='',$newexcludefname=''){
			$this->compareA = $fileA;
			$this->exclude = $excludeA;
			$this->newfileA = ($newfname!='')?($newfname):("compare_after_".$fileA);
			$this->newexclude = ($newexcludefname!='')?($newexcludefname):("exclude_".$fileA);
		}

		public function comparefile(){
			@$f0=fopen("compare/".$this->compareA,"r");//txt
			@$f1=fopen("compare/".$this->exclude,"r");//csv
			if($f0&&$f1){
				while (!feof($f1)) {
					$tmp=fgetcsv($f0);
					// 正则匹配
					if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",$tmp[0])){
						while (!feof($f0)) {
							if($email=str_replace(array("\r\n", "\r", "\n"),'',fgets($f0)) != $tmp[0]){
								$rs1.=$email.PHP_EOL;
							}
						}
					}
				}
			}
			fclose($f0);
			fclose($f1);
			return $rs1;
		}

	}