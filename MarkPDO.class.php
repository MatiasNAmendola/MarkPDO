<?php
/*	Super easy/basic database interactions on MySQL using PHP Data Objects
 *	Created by @misterdunphy
 *	http://github.com/markdunphy
 *	Use this however you want for whatever you want.  Modify it, but do not
 *  take credit as your own because that's just not cool, man.
 ************************************************************************/


class MarkPDO {
	
	private $dbh;
	private $host = "localhost";
	private $user = "root";
	private $pass = "";
	private $dbname = "fly_invoice";
	
	// Construct new db handler
	private function __construct($host=NULL, $dbname=NULL, $user=NULL, $pass=NULL) {
		if(!is_null($host) && !is_null($dbname) && !is_null($user) && !is_null($pass)) {
			$this->host = $host;
			$this->dbname = $dbname;
			$this->user = $user;
			$this->pass = $pass;
		}
		
		$this->dbh = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->user, $this->pass);
	}
	
	/* Accepts an array $data of values to insert into table $table
	/* @param $table [Required] - The table to insert into
	/* @param $data [Required] - The data to insert.  Keys should be the name of the column
								 with a corresponding value.
	/**************************************************************/
	public function insert($table, $data) {
		$numParams = count($data);

		// Separate keys from values
		foreach($data as $key=>$val) {
			$c[] = $key;
			$v[] = $val;
		}
		array_unshift($v, "spacer");
		$cols = implode(", ", $c);
		
		// Construct placeholder 
		$placeholder = "(?";
		for($i=1;$i<$numParams;$i++)
			$placeholder .= ", ?";
		$placeholder .= ")";
		// End placeholder construction		
		
		// Prepare
		$sql = "INSERT INTO `$table` ($cols) VALUES $placeholder";
		$sth = $this->dbh->prepare($sql);
		
		// Set sth
		foreach($v as $key=>$val) {
			if($key!=0)
				$sth->bindValue($key, $val);
		}
		
		
		
		if($sth->execute())
			return true;
		else
			return false;
		
	}
	
	
}

?>