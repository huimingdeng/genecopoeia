<?php 
/**
 * ORF clone 规则实现
 */
class ORFRule 
{
	
	private $gci_web_2005 = null;
	private static $_instance = null;

	public function __construct()
	{
		global $gci_web_2005;
		$this->gci_web_2005 = $gci_web_2005;
	}

	/**
	 * nextday clone 规则, 
	 * @param string $catalog 
	 * @param string $cloned   
	 */
	public function NEXTDayClone($catalog, $cloned)
	{
		if( ( ( ( preg_match('/^GC-/', $catalog) && !preg_match('/^GC-OG/', $catalog) ) || (preg_match('/^EX-.*-(M02|M98|Lv105|Lv242)/i', $catalog) ) && !preg_match("/-(GS|CF)$/",$catalog) ) && ('1' == $cloned || '2' == $cloned || '3' == $cloned || '4' == $cloned || '5' == $cloned ) ) && $this->IsCloneInUS($catalog)  ){//|| (preg_match('/^EX-.*-(M35|Lv201)/i', $catalog)) bug#004003
			return true;
		}
		else{
			return false;
		}
	}

	public function TransferClone($catalog){
		if ( preg_match('/^GC-/', $catalog) || preg_match('/^EX-.*-(M0[2367]|M1[249]|M35|M6[78]|M98|Lv105|Lv15[1236]|Lv20[16]|Lv233|Lv24[25]|LX304)/i', $catalog) ) //bug#004003 add M35 and Lv201
		{
			return true;
		}else{
			return false;
		}
	}
	
	public function ORFClone($catalog){
		if ( preg_match('/^GC-/', $catalog) || !preg_match('/^EX-.*-(M0[2367]|M1[249]|M6[78]|M98|Lv105|Lv15[1236]|Lv206|Lv233|Lv24[25]|LX304)/i', $catalog) )
		{ 
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 是否有 Lv[0-9]{3}载体
	 * @param  [type] $vector [description]
	 * @return [type]         [description]
	 */
	public function hasLvVector($catalog){
		if( preg_match('/-Lv[0-9]{3}/i', $catalog) )
		{
			return true;
		}else{
			return false;
		}
	}
	/**
	 * M[0-9]{2}
	 * @param  [type] $catalog [description]
	 * @return [type]          [description]
	 */
	public function hasMVector($catalog){
		if( preg_match('/-M[0-9]{2}/i', $catalog) )
		{
			return true;
		}else{
			return false;
		}
	}

	public function hasBVector($catalog){
		if( preg_match('/-B[0-9]{2}/i', $catalog) )
		{
			return true;
		}else{
			return false;
		}
	}

	public function hasLXVector($catalog){
		if( preg_match('/-LX304/', $catalog)){
			return true;
		}else{
			return false;
		}
	}

	public function hasGCOG($catalog){
		if( preg_match('/^GC-OG/',$catalog) ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 判断 GC- 或 EX- 的是否在数据表中，存在则真，为NextDayClone的条件之一
	 * @param string $catalog 货号
	 */
	public function IsCloneInUS($catalog){
		
		if(preg_match("/(GC-OG|HOC|LX304)/",$catalog)) return true;
		if(preg_match("/EX-.*-(M35|Lv201)/",$catalog)) return true;
		
		# if(preg_match("/EX-EGFP-(M02|Lv105)/",$catalog)) return 1;
		
		$k_cato=split("-",$catalog);
		if(preg_match("/^GC-/",$catalog)) {
			$query_Recordset2 = sprintf("SELECT prod_id,vector_name from _glz_human_Gateway_sets where prod_id = '%s'",$k_cato[1]);
		} else {
			$query_Recordset2 = sprintf("SELECT t.prod_id,t.vector_name from _glz_human_transfer_vector t where t.prod_id = '%s' and t.vector_name like '%%%s'",$k_cato[1],$k_cato[2]);
		}
		$Recordset2 = $this->gci_web_2005->SelectLimit($query_Recordset2) or die($this->gci_web_2005->ErrorMsg());
		$totalRows_Recordset2 = $Recordset2->RecordCount();
		return $totalRows_Recordset2;
	}
	/**
	 * 单例模式注意高并发的情况，可能会无法保持一个实例对象
	 * @return instance ORFRule实例
	 */
	public static function getInstance(){
		if(null === self::$_instance)
			self::$_instance = new self();
		return self::$_instance;
	}

	public function __destruct(){
		$this->gci_web_2005 = null;
	}

}