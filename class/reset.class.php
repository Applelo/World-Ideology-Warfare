<?php

class Reset {

private $_dbCon;

public function __construct($dbCon) {
  $this->_dbCon = $dbCon;
}

public function all() {

  $sql = "TRUNCATE TABLE factions";
  $this->_dbCon->query($sql);
  $sql = "TRUNCATE TABLE joueurs";
  $this->_dbCon->query($sql);
//$sql = "ALTER TABLE `joueurs` ADD FOREIGN KEY (`faction`) REFERENCES `factions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE";
  //$this->_dbCon->query($sql);
}


}


?>
