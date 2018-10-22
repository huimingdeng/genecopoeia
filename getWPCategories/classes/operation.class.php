<?php 
/**
 * Operation
 */
class Operation extends Input
{
	private $wpdb; // WordPress Database handle
	private $results;
	private $filename;
	private $slug = array();//slug name

	function __construct()
	{
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->Action();
	}

	private function Action()
	{
		// set headers
		header('Content-Type: application/json; charset=utf-8');
		header('Content-Encoding: ajax');
		header('Cache-Control: private, max-age=0');
		header('Expires: -1');
		$type = $this->post('operation');
		
		$this->filename = pathinfo($this->post('filename'),PATHINFO_FILENAME);
		
		switch ($type) {
			case 'link':
				$this->generateLink();
				break;

			case 'category':
				$this->generateCategory();
				break;

			case 'product'://get genecopoeia product
				# code...
				break;
			
		}
	}

	private function generateLink()
	{
		$sql = "SELECT ID FROM wp_posts WHERE `post_status` = 'publish' AND `post_type` = 'post';";
		$this->results = $this->wpdb->get_results($sql,ARRAY_A);
		if(!empty($this->results))
		{
			$format = array();
			foreach ($this->results as $k => $id_a) {
				$format[] = array(
					$id_a['ID'] => get_permalink($id_a['ID'])
				);
			}
			$json = "{\n".'"RECORDS"'.":".json_encode($format)."\n}";
			$path = GetWPCategories::get_plugin_path().DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$this->filename.date("ymd").'.json';
			if(!file_exists($path)){
				file_put_contents($path, $json);
				if(file_exists($path)){
					echo json_encode(array('status' => 200, 'path' => $path, 'info' => _('Ok, Generate success.',"getwpcategories") ));
					exit(0);
				}else{
					echo json_encode(array('status' => 500, 'path' => '', 'info'=>_('Sorry, json file cannot be generated, please check if the directory has writable permission.',"getwpcategories") ));
					exit(0);
				}
			}else{
				echo json_encode(array('status'=>200, 'path'=>$path, 'info' => _("The file has been generated.","getwpcategories") ));
				exit(0);
			}
		}
		else
		{
			return false;
		}

	}

	private function generateCategory()
	{
		
		$sql = "SELECT r.object_id,t.`name`,t.slug,ta.parent FROM wp_term_relationships AS r LEFT JOIN wp_term_taxonomy AS ta ON ta.term_taxonomy_id = r.term_taxonomy_id LEFT JOIN wp_terms AS t ON ta.term_id = t.term_id";
		$this->results = $this->wpdb->get_results($sql,ARRAY_A);
		$format = array();
		if(!empty($this->results)){
			foreach ($this->results as $k => $arr) {
				if($arr['parent'] !== '0'){
					array_push($this->slug,$arr['slug']);
					$parent_obj = $this->getCategory($arr['parent']);//
					$format[] = array(
						$arr['object_id'] => $this->slug
					);
					$this->slug = array();
				}else{
					$format[] = array(
						$arr['object_id'] => $arr['slug']
					);
				}
			}
			
			$json = "{\n".'"RECORDS"'.":".json_encode($format)."\n}";
			$path = GetWPCategories::get_plugin_path().DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$this->filename.date("ymd").'.json';
			if(!file_exists($path)){
				file_put_contents($path, $json);
				if(file_exists($path)){
					echo json_encode(array('status' => 200, 'path' => $path, 'info' => _('Ok, Generate success.',"getwpcategories") ));
					exit(0);
				}else{
					echo json_encode(array('status' => 500, 'path' => '', 'info'=>_('Sorry, json file cannot be generated, please check if the directory has writable permission.',"getwpcategories") ));
					exit(0);
				}
			}else{
				echo json_encode(array('status'=>200, 'path'=>$path, 'info' => _("The file has been generated.","getwpcategories") ));
				exit(0);
			}
		}else{
			return false;
		}
	}

	public function getCategory($id){
		$obj = get_category($id);
		if($obj->parent !== 0){
			array_push($this->slug,$obj->slug);
			return $this->getCategory($obj->parent);
		}else{
			array_push($this->slug,$obj->slug);
			return true;
		}
	}

}

