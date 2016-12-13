<?php
define("DB_HOST", 'localhost');
define("DB_USER", "root");
define("DB_PASS",  "root");
define("DB_NAME", "game");

class Db {

  private $dbc;

  function __construct()
  {
    try
    {
      $this->dbc = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS,
      array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
    }
    catch(PDOException $e)
    {
      exit("Error: ".$e->getMessage());
    }
  }

  function get()
  {
    return $this->dbc;
  }

  function close() {
    $this->dbc=null;
  }

}

?>
