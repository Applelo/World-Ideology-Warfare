<?php
//Informations sur les joueurs
class Joueur {
  protected $_namePlayer;
  static protected $_numberPlayer = 0;

//Getter
  public function getNamePlayer() {
    return $this->_namePlayer;
  }

  public function getNumberPlayer() {
    return self::$_numberPlayer;
  }

//Setter
public function setNamePlayer($namePlayer) {
  $this->_namePlayer = $namePlayer;
}


}
 ?>
