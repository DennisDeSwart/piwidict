<?php
class DB extends mysqli {
	protected $dbname = NAME_DB;
	protected $dbuser = 'javawiki';
	protected $dbhost = 'localhost';
	protected $dbpass = '';

	public $result;
	public $row;
	public $row_count;

	/* Connection to database */
	public function __construct() {
            @parent::__construct(
		$this->dbhost,
		$this->dbuser, 
		$this->dbpass,
                $this->dbname
            );
            if ($this->connect_error) {
    		printf("Connect failed: %s\n", $this->connect_error);
    		exit();
	    }

	    if (!$this->set_charset("binary")) {
    		printf("Error loading character set binary: %s\n", $this->error);
	    } 
	}

	/* Query to DB */

	public function query($q,$war_string) {
	    if (!$war_string) 
		$war_string = "SQL error: <b>$q</b>";

	    $this->result = @parent::query($q);

	    if (!$this->result)	{
		die("<div style=\"font-size:10px; color:#666666;\">$war_string: ".mysql_error()."</div>");
	    }
	    return $this->result;
	}

	/* Determine number of rows result set */
	public function query_count($result) {
	    $this->row_count = $result->num_rows;
	    return $this->row_count;
	}

	/* Transform an entire query in a two-dimensional array */
	public function fetch_all($result) {
	    $rows = array();	
	    while ($fetch = $result->fetch_assoc()) {
		$rows[]=$fetch;
	    }
	    return $rows;
	}

	/* Delete a record from $table by $id_field = $id */
	public function delete_record($table, $id_field, $id) {
		$q="DELETE FROM $table WHERE $id_field='$id'";
		$delete_record=$this->query($q);
		return $delete_record;
	}

	public function delete_set($table, $set) {
		$q="DELETE FROM $table WHERE $set";
		echo "<div style=\"font-size:10px; color:#CCCCCC;\">".$q."</div>";
		$delete_record = $this->query($q);
		return $delete_record;
	}

	/* Insert set of data $set in table $table */
	public function insert_record($table, $set) {
		$q="INSERT INTO $table SET $set";
		$this->query($q);
	}

	public function update_record($table, $set, $id_field, $id) {
		$q="UPDATE $table SET $set WHERE $id_field='$id'";
		$this->query($q);
	}

	public function get_last_id($table) {
		$q="SELECT LAST_INSERT_ID() as last_id FROM $table";
		$last = $this->fetch_assoc($this->query($q));
		return $last['last_id'];
	}
}
?>