<?php 
/**
 * 文件操作系统
 */
class fileOperaion
{
	private $path;
	private $oldname;//旧名
	private $newname;//新名
	private $action;//动作
	private $arr;//结果
	private $dirsize;//目录大小
	private $ford;//文件或目录操作 
	private $errNo;//200 | 400 | 500

	public function __construct($path)
	{
		if(is_dir($path)){
			$this->path = $path;
			$this->ford = true;
			$this->oldname = pathinfo($this->path,PATHINFO_FILENAME);
		}else{
			$this->path = dirname($path);
			$this->ford = false;
			$this->oldname = basename($path);//pathinfo($path,PATHINFO_FILENAME);
		}

	}

	public function readDirectory() {
		$handle = opendir ( $this->path );
		while ( ($item = readdir ( $handle )) !== false ) {
			//.和..这2个特殊目录
			if ($item != "." && $item != "..") {
				if (is_file ( $this->path . "/" . $item )) {
					$arr ['file'] [] = $item;
				}
				if (is_dir ( $this->path . "/" . $item )) {
					$arr ['dir'] [] = $item;
				}
			
			}
		}
		closedir ( $handle );
		return $arr;
	}
	// 目录大小
	public function getDirSize(){
		$path = $this->path;
		$sum=0;
		$handle=opendir($path);
		while(($item=readdir($handle))!==false){
			if($item!="."&&$item!=".."){
				if(is_file($path."/".$item)){
					$sum+=filesize($path."/".$item);
				}
				if(is_dir($path."/".$item)){
					$func=__FUNCTION__;
					$func($path."/".$item);
				}
			}
			
		}
		closedir($handle);
		return $this->dirsize = $sum;
	}
	/**
	 * 创建目录
	 * @param  [type] $dirname [description]
	 * @return [type]          [description]
	 */
	public function createFolder($dirname){
		//检测文件夹名称的合法性
		if(checkFilename(basename($dirname))){
			//当前目录下是否存在同名文件夹名称
			if(!file_exists($dirname)){
				if(mkdir($dirname,0755,true)){
					$mes="The folder was created successfully.";
				}else{
					$mes="Folder creation failed.";
				}
			}else{
				$mes="The same folder name exists.";
			}
		}else{
			$mes="Illegal folder name.";
		}
		return $mes;
	}
	/**
	 * 负责文件夹
	 * @param  [type] $src [description]
	 * @param  [type] $dst [description]
	 * @return [type]      [description]
	 */
	public function copyFolder($src,$dst){
		if(!file_exists($dst)){
			mkdir($dst,0755,true);
		}
		$handle=opendir($src);
		while(($item=readdir($handle))!==false){
			if($item!="."&&$item!=".."){
				if(is_file($src."/".$item)){
					copy($src."/".$item,$dst."/".$item);
				}
				if(is_dir($src."/".$item)){
					$func=__FUNCTION__;
					$func($src."/".$item,$dst."/".$item);
				}
			}
		}
		closedir($handle);
		return "Successful directory replication.";
		
	}
	/**
	 * 复制文件
	 * @param string $filename
	 * @param string $dstname
	 * @return string
	 */
	public function copyFile($filename,$dstname){
		if(file_exists($dstname)){
			if(!file_exists($dstname."/".basename($filename))){
				if(copy($filename,$dstname."/".basename($filename))){
					$mes="File copied successfully.";
				}else{
					$mes="File copy failed.";
				}
			}else{
				$mes="File of the same name.";
			}
		}else{
			$mes="The target directory does not exist.";
		}
		return $mes;
	}
	/**
	 * 剪切文件夹
	 * @param string $src
	 * @param string $dst
	 * @return string
	 */
	public function cutFolder($src,$dst){
		if(file_exists($dst)){
			if(is_dir($dst)){
				if(!file_exists($dst."/".basename($src))){
					if(rename($src,$dst."/".basename($src))){
						$mes="Directory shearing success";
					}else{
						$mes="Directory clipping failure";
					}
				}else{
					$mes="Folder of the same name exists";
				}
			}else{
				$mes="Not a folder";
			}
		}else{
			$mes="The destination folder does not exist.";
		}
		return $mes;
	}
	/**
	 * 剪切文件
	 * @param  [type] $filename [description]
	 * @param  [type] $dstname  [description]
	 * @return [type]           [description]
	 */
	public function cutFile($filename,$dstname){
		if(file_exists($dstname)){
			if(!file_exists($dstname."/".basename($filename))){
				if(rename($filename,$dstname."/".basename($filename))){
					$mes="File clipping.";
				}else{
					$mes="File clipping failure.";
				}
			}else{
				$mes="File of the same name.";
			}
		}else{
			$mes="The target directory does not exist.";
		}
		return $mes;
	}

	// 转换成可视化理解字节
	public function transByte($size) {
		$arr = array ("B", "KB", "MB", "GB", "TB", "EB" );
		$i = 0;
		while ( $size >= 1024 ) {
			$size /= 1024;
			$i ++;
		}
		return round ( $size, 2 ) . $arr [$i];
	}

	public function Rename($newname){
		$this->newname = $newname ;
		if($this->ford){
			$this->renameFolder();
		}else{
			$this->renameFile();
		}
	}

	/**
	 * 重命名文件夹/文件
	 * @param string $oldname
	 * @param string $newname
	 * @return string
	 */
	private function renameFolder(){
		$oldname = $this->oldname;
		$newname = $this->newname;
		//检测文件夹名称的合法性
		if(self::checkFilename(basename($newname))){
			//检测当前目录下是否存在同名文件夹名称
			if(!file_exists($newname)){
				if(rename($oldname,$newname)){
					$mes="Renaming success.";//重命名成功
				}else{
					$mes="Rename failed.";//重命名失败
				}
			}else{
				$mes="Folder of the same name exists.";//存在同名文件夹
			}
		}else{
			$mes="Illegal folder name.";//非法文件夹名称
		}
		return $mes;
	}

	/**
	 * 重命名文件
	 * @param string $oldname
	 * @param string $newname
	 * @return string
	 */
	private function renameFile(){
		$oldname = $this->oldname;
		$newname = $this->newname;
		//验证文件名是否合法
		if(self::checkFilename($newname)){
			//检测当前目录下是否存在同名文件
			$path=dirname($oldname);
			if(!file_exists($path . DIRECTORY_SEPARATOR . $newname)){
				//进行重命名
				if(rename($oldname,$path . DIRECTORY_SEPARATOR . $newname)){
					return "Renaming success.";
				}else{
					return "Rename failed.";
				}
			}else{
				return "The file with the same name exists. Please rename it.";//存在同名文件，请重新命名
			}
		}else{
			return "Illegal file name.";//非法文件名
		}
	}

	/**
	 *检测文件名是否合法
	 * @param string $filename
	 * @return boolean
	 */
	public static function checkFilename($filename){
		$pattern = "/[\/,\*,<>,\?\|]/";
		if (preg_match ( $pattern,  $filename )) {
			return false;
		}else{
			return true;
		}
	}
	/**
	 * 创建文件
	 * @param  [type] $filename [description]
	 * @return [type]           [description]
	 */
	public function createFile($filename) {
		$pattern = "/[\/,\*,<>,\?\|]/";
		if (! preg_match ( $pattern, basename ( $filename ) )) {
			//检测当前目录下是否存在同名文件
			if (! file_exists ( $filename )) {
				//通过touch($filename)来创建
				if (touch ( $filename )) {
					return "File created successfully.";
				} else {
					return "File creation failed.";
				}
			} else {
				return "The file already exists. Rename it and create it.";
			}
		} else {
			return "Illegal file name";
		}
	}

	/**
	 * 删除文件
	 * @param string $filename
	 * @return string
	 */
	public function delFile($filename){
		if(unlink($filename)){
			$this->errNo = 200;
			$mes="File deleted successfully.";
		}else{
			$this->errNo = 500;
			$mes="File deletion failed.";
		}
		return array('Msg'=>$mes,'Status'=>$this->errNo);
	}
	/**
	 * 删除文件夹
	 * @param string $path
	 * @return string
	 */
	public function delFolder($path){
		$handle=opendir($path);
		while(($item=readdir($handle))!==false){
			if($item!="."&&$item!=".."){
				if(is_file($path."/".$item)){
					unlink($path."/".$item);
				}
				if(is_dir($path."/".$item)){
					$func=__FUNCTION__;
					$func($path."/".$item);
				}
			}
		}
		closedir($handle);
		rmdir($path);
		return "Folder deletion succeeded.";
	}

}
