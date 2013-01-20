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
    				"twitter"=>"@misterdunphy"
    			  );
    $dbh->insert($table, $table);
```

3. Update 
```
    // $table is the table to update.
    // $data is a key/value array where the key is the column name. 
    // $where is an SQL where clause to specify what to update.   
    // Returns boolean true/false on success/failure
    $table = "users";
    $data = array(
    				"fname"=>"Mark",
    				"lname"=>"Dunphy",
    				"email"=>"Mark@Dunphtastic.com",
    				"twitter"=>"@misterdunphy"
    			  );
    $where = "id='1234'";
    $dbh->update($table, $table, $where);
```

4. Delete
```
	// NOTE: This will create a where clause separated by AND meaning 
	// that it will only delete rows that match ALL criteria in the
	// $where array.  Adding a fancier delete method later to account
	// for more flexibility.
	//
    // $Table is the table to delete from
    // $where is a key/value array where the key is the column name.
    // Returns boolean true/false on success/failure
    $table = "users";
    $where = array(
    				"id"=>"1234"
    			  );
    $dbh->delete($table, $where);
```