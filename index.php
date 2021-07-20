<?php
include 'config/config.php'; 

$recordset = new JSONRecordSet($ini['main']['database']['dbname']);

$page = new Router($recordset);
new View($page); 
?>