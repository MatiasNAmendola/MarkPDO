<?php
/*	Super easy/basic database interactions on MySQL using PHP Data Objects
 *	Created by @dunphtastic
 *	http://github.com/markdunphy
 *	Use this however you want for whatever you want.  Modify it, but do not
 *  take credit as your own because that's just not cool, man.
 ************************************************************************/


class MarkPDO {
	
	private $dbh;
	private $debug = false; // Set this to true to see PDO errors.
	private $host = "localhost";
	private $user = "root";
	private $pass = "";
	private $dbname = "";
	
	// Construct new db handler
	function __construct($host=NULL, $dbname=NULL, $user=NULL, $pass=NULL) {
		if(!is_null($host) && !is_null($dbname) && !is_null($user) && !is_null($pass)) {
			$this->host = $host;
			$this->dbname = $dbname;
			$this->user = $user;
			$this->pass = $pass;
		}
		
		$this->dbh = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->user, $this->pass);
		
		if($this->debug)
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	// Custom Query
	public function cquery($sql) {
		return $this->dbh->query($sql);
	}
	
	/* Accepts an array $data of values to insert into table $table
	/* @param $table (String) [Required] - The table to insert into
	/* @param $data (Array) [Required] - The data to insert.  Keys should be the name of the column
								 		 with a corresponding value.
	/**************************************************************/
	public function insert($table, $data) {
		$numParams = count($data);
		$placeholder = "";
		$iterator = 1;
		
		// Set placeholder
		for($i=1;$i<=$numParams;$i++) {
			$placeholder .= ($i != $numParams) ? "?, " : "?";
		}
			
		// Get columns for query
		$columns = implode(", ", array_keys($data));
		
		// Prepare
		$sql = "INSERT INTO `$table` ($columns) VALUES ($placeholder)";
		$sth = $this->dbh->prepare($sql);
		
		// Bind values
		foreach($data as $value) {
			$sth->bindValue($iterator, $value);
			$iterator++;
		}

		return $sth->execute();
		
	}
	
	/* Accepts an array $data of values to update table $table
	/* @param $table (String) [Required] - The table to update.
	/* @param $data (Array) [Required] - The data to update.  Keys should be the name of the column
								 with a corresponding value.
	/* @param $where (Array) [Required] - Specify where clause to determine which row to update.
										  Key should be column name. Row must match ALL criteria in 
										  this array.
	/**************************************************************/
	public function update($table, $data, $where) {
		$iterator = 1;
		
		// Construct set
		$updatethis = implode("=?, ", array_keys($data))."=?";

		// Construct where clause
		$whereholder = implode("=? AND ", array_keys($where))."=?";
		
		// Prepare
		$sql = "UPDATE `$table` SET $updatethis WHERE $whereholder";
		$sth = $this->dbh->prepare($sql);
		
		// Bind update values
		foreach($data as $val) {
			$sth->bindValue($iterator, $val);
			$iterator++;
		}
		// Bind where values
		foreach($where as $val) {
			$sth->bindValue($iterator, $val);
			$iterator++;
		}

		return $sth->execute();
		
	}
	
	/* Accepts an array $data of values to update table $table
	/* @param $table (String) [Required] - The table to update.
	/* @param $where (Array) [Required] - Specify where clause to determine which row to delete.
										  Key should be column name. Row must match ALL criteria in 
										  this array.
	/**************************************************************/
	public function delete($table, $where) {
		$iterator = 1;
		// Placeholder set up
		$placeholder = implode("=? AND ", array_keys($where))."=?";
		
		// Prepare
		$sql = "DELETE FROM `$table` WHERE $placeholder";
		$sth = $this->dbh->prepare($sql);
		
		// Bind values
		foreach($where as $val) {
			$sth->bindValue($iterator, $val);
			$iterator++;
		}

		return $sth->execute();
		
	}
	
	/* Accepts arrays $select and $where to fetch from table $table.
	/* @param $table (String) [Required] - The table to fetch from.
	/* @param $select (Array) [Required] - An array where the values are the columns to be returned.
	/* @param $where (Array) [Required] - Specify where clause to determine which row to delete.
										  Key should be column name. Row must match ALL criteria in 
										  this array.
	/* @param @ORM (Boolean) [Optional] - Set to false to return an associative array. Set to true 
										  by default to return an array of objects.
	/**************************************************************/
	public function fetchOne($table, $select, $where, $ORM=true) {
		$iterator = 1;
		
		// Placeholder set up
		$placeholder = implode("=? AND ", array_keys($where))."=?";
		$select = implode(", ", $select);
		
		// Prepare
		$sql = "SELECT $select FROM `$table` WHERE $placeholder LIMIT 1";

		$sth = $this->dbh->prepare($sql);
		
		// Bind values
		foreach($where as $val) {
			$sth->bindValue($iterator, $val);
			$iterator++;
		}
		
		// Execute
		if($sth->execute())
			return $ORM ? $sth->fetch(PDO::FETCH_OBJ) : $sth->fetch(PDO::FETCH_ASSOC);
		
		return false;
		
	}
	
	/* Accepts arrays $select and $where to fetch from table $table.
	/* @param $table (String) [Required] - The table to fetch from.
	/* @param $select (Array) [Required] - An array where the values are the columns to be returned.
	/* @param $where (Array) [Required] - Specify where clause to determine which row to delete.
										  Key should be column name. Row must match ALL criteria in 
										  this array.
	/* @param @ORM (Boolean) [Optional] - Set to false to return an associative array. Set to true 
										  by default to return an array of objects.
	/**************************************************************/
	public function fetchAll($table, $select, $where, $ORM=true) {
		$iterator = 1;
		
		// Placeholder set up
		$placeholder = implode("=? AND ", array_keys($where))."=?";
		$select = implode(", ", $select);
		
		// Prepare
		$sql = "SELECT $select FROM `$table` WHERE $placeholder";
		
		if(!is_null($limit))
			$sql .= " LIMIT $limit";
			
		$sth = $this->dbh->prepare($sql);
		
		// Bind values
		foreach($where as $val) {
			$sth->bindValue($iterator, $val);
			$iterator++;
		}
		
		// Execute
		if($sth->execute())
			return $ORM ? $sth->fetchAll(PDO::FETCH_OBJ) : $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return false;
		
	}
	
	/* Accepts arrays $select and $where to fetch from table $table.
	/* @param $table (String) [Required] - The table to fetch from.
	/* @param $select (Array) [Required] - An array where the values are the columns to be returned.
	/* @param $where (Array) [Required] - Specify where clause to determine which row to delete.
										  Key should be column name. Row must match ALL criteria in 
										  this array.
	/* @param $limit (Integer) [Required] - Number of results to return.
	/* @param @ORM (Boolean) [Optional] - Set to false to return an associative array. Set to true 
										  by default to return an array of objects.
	/**************************************************************/
	public function fetchSome($table, $select, $where, $limit, $ORM=true) {
		$iterator = 1;
		
		// Placeholder set up
		$placeholder = implode("=? AND ", array_keys($where))."=?";
		$select = implode(", ", $select);
		
		// Prepare
		$sql = "SELECT $select FROM `$table` WHERE $placeholder LIMIT $limit";
			
		$sth = $this->dbh->prepare($sql);
		
		// Bind values
		foreach($where as $val) {
			$sth->bindValue($iterator, $val);
			$iterator++;
		}
		
		// Execute
		if($sth->execute())
			return $ORM ? $sth->fetchAll(PDO::FETCH_OBJ) : $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return false;
		
	}
}

?>
