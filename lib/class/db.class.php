<?php
//
// Project    : MySQL操作クラス
// CreateDate : 2006/05/11
// ModifyDate : 2006/05/23
// ModifyDate : 2008/04/08 - define版に
// File       : class_mysql.php
// By.koba+sudo
//

class db
{
	// DBコネクション
	var $dbcon;
	var $query;
	var $rows;
	var $exec_logfile;
	var $err_logfile;
	
	// 接続
	function connect ( $uname = "", $pass = "",  $dbname = "", $hostname = "" )
	{
		$this->dbcon = @mysql_connect($hostname, $uname, $pass);
		
		if (!$this->dbcon){
			die('DBに接続できません(S1): ');
//			die('DBに接続できません(S1): '.mysql_error());
		}
//		}
		if (!mysql_select_db($dbname)) {
			die ("DBに接続できません (S2)");
//			die ("DBに接続できません (S2)".mysql_error());
		}
		
		// ログファイルセット
		$this->setLog(CNF_SQL_LOG, CNF_SQL_ERR_LOG);
		
		// 文字コードをUTFに設定
		$strSQL = "SET CHARACTER SET utf8";
		$this->query($strSQL);
	}
	
	// SQLログ保存先セット
	function setLog($exec_log, $err_log){
		$this->exec_logfile = $exec_log;
		$this->err_logfile = $err_log;
	}
	
	// クエリ実行
	function query ( $sQuery )
	{
		if ( $sQuery != "" ){
			// 通常エラー処理版
			if(!$Ret = mysql_query($sQuery)){
				if ($this->err_logfile != ""){
					error_log (CNF_NOW_DATETIME."\t".$sQuery."\t".$_SERVER["REQUEST_URI"]."\t".$_SERVER['REMOTE_ADDR']."\t".mysql_error()."\n", 3, $this->err_logfile);
				}
			}
			
			/* DBエラー処理版 class_err_log.php
			$Ret = mysql_query($sQuery)
				or trigger_error(sprintf("%s\n%d:%s\n%s", $sQuery, mysql_errno(), mysql_error(), array_shift(get_included_files())), E_USER_ERROR);
			*/

			// SQLログを書き込む
//			if (preg_match("/^SELECT|INSERT|UPDATE|DELETE/i", $sQuery)){
			if (preg_match("/^INSERT|UPDATE|DELETE/i", $sQuery)){
				if ($this->exec_logfile != ""){
					error_log (CNF_NOW_DATETIME."\t".$sQuery."\t".$_SERVER["REQUEST_URI"]."\t".$_SERVER['REMOTE_ADDR']."\n", 3, $this->exec_logfile);
				}
			}
			return $Ret;
		}
	}
	
	
	// DBからデータ取得
	function select ( $aField, $sTblName = "", $sWhere = "", $sOpt = "" )
	{
		$sQuery = "SELECT ";
		for ($i=0; $i<count($aField); $i++){
			$sQuery .= $aField[$i].", ";
		}
		$sQuery = ereg_replace(", $", "", $sQuery);
		
		if ($sTblName != ""){
			$sQuery .= " FROM ".$sTblName." ";
		}
		
		if ($sWhere != ""){
			$sQuery .= "WHERE ".$sWhere;
		}
		
		if ($sOpt != ""){
			$sQuery .= $sOpt;
		}
		
		$sQuery .= ";";
		
		$Ret = $this->query($sQuery);
		
		// データ取得, 戻す
		return $this->fetch_all($Ret);
	}

	
	// 全データを取得
	function fetch_all(&$Ret)
	{
		$aResult = array();
		
		if(!$Ret) {
			return FALSE;
		}
		else {
			while ($row = mysql_fetch_assoc($Ret)) {
				array_push($aResult, $row);
			}	
		}
		return $aResult;
	}
	
	// データ取得
	function getList($table, $key, $name, $where = "", $order_by = "", $value = "")
	{
		$strSQL  = "select ".$key.", ";
		$strSQL .= ($value == "") ? $name : $value;
		$strSQL .= " from ".$table;
		if ($where != ""){
			$strSQL .= " where ".$where;
		}
		if ($order_by != ""){
			$strSQL .= " order by ".$order_by;
		}
		$ahList = $this->fetch_all($this->query($strSQL));

		$ret = array();
		foreach ($ahList as $i => $value){
			$ret[$value[$key]] = $value[$name];
		}

		return $ret;
	}
	
	// エスケープ
	function escape($str)
	{
		return mysql_real_escape_string($str);
	}
}

?>
