<?php
/**
* abstract class that creates a database connection and returns a recordset
* Follows the recordset pattern
* @author Nicholas Coyles
*/
abstract class RecordSet {
 protected $conn;
 protected $stmt;

 function __construct($dbname) {
   $this->conn = PDOdb::getConnection($dbname);
 }

 /**
 * This function will execute the query as a prepared statement if there is a
 * params array. If not, it executes as a regular statament. 
 *
 * @param string $query  The sql for the recordset
 * @param array $params  An optional associative array if you want a prepared statement
 * @return PDO_STATEMENT
 */
 function getRecordSet($query, $params = null) {
   if (is_array($params)) {
     $this->stmt = $this->conn->prepare($query);
     $this->stmt->execute($params);
   } else {
     $this->stmt = $this->conn->query($query);
   }
   return $this->stmt;
 }
}
?>