<?php
//=================================================================
//
//   [PostgreSQL] テーブル情報更新クラス PHP5.0以降用
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2008/09/21 Rev 1.0.0 koba
//  --------------------------------------------------------------
//   execUpdate関数からSQL作成部分を別関数に分離(makeSQLInsert, makeSQLUpdate)
//   　　　　　　　　　　　　　　　 2010/12/02 Rev 1.0.1 koba
//  --------------------------------------------------------------
//
//=================================================================

class dbExec
{
	protected
		$dbcon = null,
		$table_name = null,
		$primary_key = null,
		$primary_key_type = null,
		$primary_value = null,
		$sequence = null,
		$fields = array();

	// 登録/更新
	public function execUpdate($hData)
	{
		$type = 0;	// 0: insert / 1: update
		$primary_key = $hData[$this->primary_key];
		$tmp_primary_value = $hData[$this->primary_key];

		// 登録か更新か判別
		if ($primary_key != ''){
			if ($this->primary_key_type == 'text'){
				$tmp_primary_value = "'".$tmp_primary_value."'";
			}
			
			$strSQL = 'select count(*) as reccount from '.$this->table_name.' where '.$this->primary_key.' = '.$tmp_primary_value.';';
			$ahRet = $this->dbcon->fetch_all($this->dbcon->query($strSQL));

			if ($ahRet[0]['reccount'] > 0){
				$type = 1;
			}
		}

		// プライマリキーをセット
		if ($primary_key == '' && $this->sequence != ''){
			$strSQL = "select nextval('".$this->sequence."') as seq";
			$ahRet = $this->dbcon->fetch_all($this->dbcon->query($strSQL));

			$primary_key = $ahRet[0]['seq'];
		} elseif ($this->fields[$this->primary_key] == ''){
			print "CLASS[DB_EXEC] Err_01";
			return false;
		}

		$hData[$this->primary_key] = $primary_key;
		// クエリ作成
		$strSQL = '';
		// 新規時
		if ($type == 0){
			$strSQL = $this->makeSQLInsert($hData);
		}
		// 更新時
		elseif ($type == 1){
			$strSQL = $this->makeSQLUpdate($hData, $this->primary_key." = ".$tmp_primary_value);
		}
		
		// クエリ実行
		if ($this->dbcon->query($strSQL)){
			$this->primary_value = $primary_key;
			return true;
		} else {
print $strSQL."\n";
			return false;
		}
	}

	// 削除
	public function execDelete($hData)
	{
		if ($hData[$this->primary_key] != ''){
			$strSQL = "delete from ".$this->table_name." where ".$this->primary_key." = ".$hData[$this->primary_key].";";

			// クエリ実行
			if ($this->dbcon->query($strSQL)){
				$this->primary_value = $primary_key;
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	// insert用SQL作成
	public function makeSQLInsert(&$hData)
	{
		$strSQLFields = '';
		$strSQLValues = '';
		
		foreach ($this->fields as $key => $value){
			$strSQLFields .= ', '.$key;
			$strSQLValues .= ', '.call_user_func(array($this, 'setValue'.ucfirst(strtolower($value))), $hData[$key]);
		}
		$strSQLFields = preg_replace("/^, */", "", $strSQLFields);
		$strSQLValues = preg_replace("/^, */", "", $strSQLValues);
		$strSQL  = "insert into ".$this->table_name." (";
		$strSQL .= $strSQLFields;
		$strSQL .= ") values (";
		$strSQL .= $strSQLValues;
		$strSQL .= ");";
		
		return $strSQL;
	}

	// update用SQL作成
	public function makeSQLUpdate(&$hData, $where = "")
	{
		$strSQL  = "update ".$this->table_name." set ";
		foreach ($this->fields as $key => $value){
			$strSQL .= $key;
			$strSQL .= " = ";
			$strSQL .= call_user_func(array($this, 'setValue'.ucfirst(strtolower($value))), $hData[$key]);
			$strSQL .= ", ";
		}
		$strSQL = preg_replace("/, *$/", " ", $strSQL);
		if ($where != ""){
			$strSQL .= "where ".$where.";";
		}
		
		return $strSQL;
	}

	// プライマリキーをセットする
	public function setId($str)
	{
		$this->primary_key = $str;
	}
	
	// プライマリキーを返す
	public function getId()
	{
		return $this->primary_value;
	}

	// テーブル名セット
	public function setTableName($table_name)
	{
		$this->table_name = $table_name;
	}

	// シーケンスセット
	public function setSequence($key)
	{
		$this->sequence = $key;
	}

	// INT型データ作成
	public function setValueInt($value){
		if ($value == null){
			return 'null';
		} elseif ($value == ''){
			return 'null';
		} elseif (ctype_digit($value)){
			return $value;
		} else {
			return 'null';
		}
	}

	// INT2型データ作成
	public function setValueInt2($value){
		return $this->setValueInt($value);
	}

	// INT4型データ作成
	public function setValueInt4($value){
		return $this->setValueInt($value);
	}

	// INT8型データ作成
	public function setValueInt8($value){
		return $this->setValueInt($value);
	}

	// FLOAT型データ作成
	public function setValueFloat($value){
		if ($value == null){
			return 'null';
		} elseif ($value == ''){
			return 'null';
		} elseif (preg_match("/^[0-9]+(\.[0-9]*)?$/", $value)){
			return $value;
		} else {
			return 'null';
		}
	}

	// FLOAT8型データ作成
	public function setValueFloat8($value){
		return $this->setValueFloat($value);
	}

	// TEXT型データ作成
	public function setValueText($value){
		if ($value == null){
			return 'null';
		} else {
			return '\''.pg_escape_string($value).'\'';
		}
	}

	// VARCHAR型データ作成
	public function setValueVarchar($value){
		return $this->setValueText($value);
	}

	// TIMESTAMP型データ作成
	public function setValueTimestamp($value){
		if ($value == ""){
			return "null";
		} else {
			if (!preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}( [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2})?$/", $value)){
				return "null";
			}
			return "'".pg_escape_string($value)."'";
		}
	}

	// DATE型データ作成
	public function setValueDate($value){
		return $this->setValueTimestamp($value);
	}

	// BOOLEAN型データ作成
	public function setValueBoolean($value){
		if ($value == null){
			return 'false';
		} elseif ($value == ''){
			return 'false';
		} elseif ($value == '0'){
			return 'false';
		} elseif ($value == '1'){
			return 'true';
		} else {
			return 'true';
		}
	}

	// BOOL型データ作成
	public function setValueBool($value){
		return $this->setValueBoolean($value);
	}

	// INT配列型データ作成
	public function setValueInt_array($value){
		if ($value == ""){
			return "null";
		} elseif (is_array($value)){
			$null_count = 0;
			$ret = "array[";
			foreach ($value as $key => $data){
				if ($data == ""){
					//$ret .= "null,";
					$ret .= "-1,";
					$null_count++;
				} else {
					$ret .= $data.",";
				}
			}
			$ret = preg_replace("/,$/", "", $ret);
			$ret .= "]";
			
			if ($null_count == count($value)){
				$ret = "null";
			}
			
			return $ret;
		} else {
			return "array[".$value."]";
		}
	}
	
	// 文字列配列型データ作成
	public function setValueVarchar_array($value){
		if ($value == ""){
			return "null";
		} elseif (is_array($value)){
			$ret = "array[";
			foreach ($value as $key => $data){
				$ret .= "'".$data."'".",";
			}
			$ret = preg_replace("/,$/", "", $ret);
			$ret .= "]";
			return $ret;
		} else {
			return "array[".$value."]";
		}
	}
}
?>