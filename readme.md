#MarkPDO
A simple wrapper for interacting with MySQL databases using PHP Data Objects.

##Usage
Include MarkPDO.class.php on whatever page you need to use it on.

1. Instantiate 
```
    // Use connection info defined in MarkPDO.class.php
    $dbh = new MarkPDO();

    // Use your own connection info,
    $dbh = new MarkPDO($host, $dbname, $user, $password);
```

2. Insert
```
	// $table is the table to insert into.
    // $data is a key/value array where the key is the column name.
    // Returns boolean true/false on success/failure
    $table = "users";
    $data = array(
    				"fname"=>"Mark",
    				"lname"=>"Dunphy",
    				"email"=>"Mark@Dunphtastic.com",
    				"twitter"=>"@dunphtastic"
    			  );
    $dbh->insert($table, $data);
```

3. Update 
```
    // $table is the table to update.
    // $data is a key/value array where the key is the column name. 
    // $where is a key/value array where the key is the column name.
    //         Where clause is separated by AND so the row must match ALL
    //		   criteria in this array for it to be updated.  More flexible
    //		   options coming eventually.
    // Returns boolean true/false on success/failure
    $table = "users";
    $data = array(
    				"fname"=>"Mark",
    				"lname"=>"Dunphy",
    				"email"=>"Mark@Dunphtastic.com",
    				"twitter"=>"@dunphtastic"
    			  );
    $where = array("id"=>"1234");
    $dbh->update($table, $data, $where);
```

4. Delete
```
	// NOTE: This will create a where clause separated by AND meaning 
	// that it will only delete rows that match ALL criteria in the
	// $where array.  Adding a fancier delete method later to account
	// for more flexibility.
	//
    // $table is the table to delete from
    // $where is a key/value array where the key is the column name.
    // Returns boolean true/false on success/failure
    $table = "users";
    $where = array(
    				"id"=>"1234"
    			  );
    $dbh->delete($table, $where);
```

5. Fetch/Fetch All/Fetch Some
```
	// Like all other methods in this class, the where clause is constructed
	// with ANDs.  See class comments for more info.

	$table  = "users";
	$select = array("id", "name");
	$where = array("email"=>"Mark@Dunphtastic.com");

	// Get ONE result. Return object.
	$dbh->fetch($table, $select, $where);

	// Get ONE result.  Return associative array.
	$dbh->fetch($table, $select, $where, false);

	// Get all results.  Return object.
	$dbh->fetchAll($table, $select, $where);

	// Get all results. Return associative array.
	$dbh->fetchAll($table, $select, $where);

	// Get 2 results. Return object.
	$dbh->fetchSome($table, $select, $where, 2);

	// Get 2 results. Return associative array.
	$dbh->fetchSome($table, $select, $where, 2, false);

```


```


6. Custom Query
```
   $stmt = "ENTER SQL QUERY HERE";
   $dbh->cquery($stmt);
