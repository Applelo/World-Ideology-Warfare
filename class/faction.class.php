<?php

class Faction extends Joueur {
  private $_nameFaction;
  protected $_faction;
  protected $_population;
  protected $_puissance;
  protected $_production;
  protected $_id;
  protected $_dbCon;

public function __construct($dbCon, $censure) {
  if (!$censure)
    $this->_nameFaction = array("NAZI","URSS","USA");
  else
    $this->_nameFaction = array("LAA-LAA","DIPSY","PO");

  $this->_dbCon = $dbCon;
  self::$_numberPlayer += 1;
}

//Getter
  public function getPopulation() {
    return $this->_population;
  }

  public function getPuissance() {
    return $this->_puissance;
  }

  public function getProduction() {
    return $this->_production;
  }

  public function getFaction() {
    return $this->_faction;
  }

  public function getFactionName() {
    return $this->_nameFaction[$this->_faction];
  }

  public function getId() {
    return $this->_id;
  }

//Setter

public function setPopulation($population) {
  $this->_population = $population;
}

public function setPuissance($puissance) {
  $this->_puissance = $puissance;
}

public function setProduction($production) {
  $this->_production = $production;
}

//Functions
public function defaultDataFaction($faction, $namePlayer) {//Set default value for the faction (use only one time)
  $this->_faction = $faction;
  $this->_namePlayer = $namePlayer;

  //Initialisation des paramètres de bases de chaque faction
  $sql = "INSERT INTO factions (id, name, population, production, puissance)
  VALUES (NULL, :nameFaction, :population, :production, :puissance)";
  $query = $this->_dbCon->prepare($sql);
  $query->bindParam(":nameFaction",$this->_nameFaction[$faction],PDO::PARAM_STR);
  $query->bindValue(":population", 100, PDO::PARAM_INT);
  $query->bindValue(":production", 50, PDO::PARAM_INT);
  $query->bindValue(":puissance", 20,PDO::PARAM_INT);
  $query->execute();
  $query = null;

  // Joueurs
  $sql = "INSERT INTO joueurs (id, pseudo, faction)
  VALUES (NULL, :pseudo, :numfaction)";
  $query = $this->_dbCon->prepare($sql);
  $query->bindParam(":pseudo",$namePlayer,PDO::PARAM_STR);
  $query->bindParam(":numfaction",$faction,PDO::PARAM_INT);
  $query->execute();
  $query = null;
}

public function updateDataFaction($choix) {//Update data in database

  $avant["attack"] = array(
    $this->getPopulation(),
    $this->getProduction(),
    $this->getPuissance()
  );

  if ($choix == 0)
    $this->setPopulation($this->getPopulation() + 20);
  elseif ($choix == 1) {
    $this->setProduction($this->getProduction() + floor($this->getPopulation()/4));//Production
    $this->setPopulation(floor($this->getPopulation()/2));//Moins de population
  }
  elseif ($choix == 2) {
    $this->setPuissance($this->getProduction() + floor($this->getProduction()/4));//Puissance
    $this->setProduction(floor($this->getProduction()/2));//Moins de production
  }

  $population = $this->getPopulation();
  $production = $this->getProduction();
  $puissance = $this->getPuissance();

  $sql = "UPDATE factions SET population = :population, production = :production, puissance = :puissance WHERE id = " . $this->getId();
  $query = $this->_dbCon->prepare($sql);
  $query->bindParam(":population", $population, PDO::PARAM_INT);
  $query->bindParam(":production", $production, PDO::PARAM_INT);
  $query->bindParam(":puissance", $puissance, PDO::PARAM_INT);
  $query->execute();
  $query = null;

  return $avant;
}

public function getDataFaction($player) {//Get data in database

  $sql = "SELECT *
  FROM factions
  WHERE id = " . $player;
  $exec = $this->_dbCon->query($sql);
  $factions = $exec->fetch(PDO::FETCH_OBJ);
  $this->_population = $factions->population;
  $this->_production = $factions->production;
  $this->_puissance = $factions->puissance;

  $sql = "SELECT *
  FROM joueurs
  WHERE id = " . $player;
  $exec = $this->_dbCon->query($sql);
  $joueurs = $exec->fetch(PDO::FETCH_OBJ);
  $this->_faction = $joueurs->faction;
  $this->_id = $joueurs->id;
  $this->_namePlayer = $joueurs->pseudo;

}

public function getEnnemis($player) {//Get data in database

  $sql = "SELECT *
  FROM joueurs
  WHERE id != " . $player;
  $exec = $this->_dbCon->query($sql);
  $joueurs = $exec->fetchAll(PDO::FETCH_OBJ);

  $sql = "SELECT *
  FROM factions
  WHERE id != " . $player;
  $exec = $this->_dbCon->query($sql);
  $factions = $exec->fetchAll(PDO::FETCH_OBJ);

  $i = 0;
  foreach($factions as $f) {
    $dead[$i] = ($f->population == 0) ? true : false;
    $i++;
  }

  $i = 0;
  $h = 0;
  foreach($joueurs as $j) {

    if ($dead[$i]==false) {
      $ennemis["info"][$h] = $this->_nameFaction[$j->faction] . " - " . $j->pseudo;
      $ennemis["number"][$h] = $j->id;
      $h++;
    }
    $i++;
  }

  return $ennemis;
}


public function attackEnnemi($player, $choix) {

  $avant["attack"] = array(
    $this->getPopulation(),
    $this->getProduction(),
    $this->getPuissance()
  );
  $avant["ennemi"] = array(
    $player->getPopulation(),
    $player->getProduction(),
    $player->getPuissance()
  );
  if ($choix == 0)
    $player->setPopulation($player->getPopulation() - $this->_puissance);//Population
  elseif ($choix == 1)
    $player->setProduction($player->getProduction() - $this->_puissance);//Production
  else
    $player->setPuissance($player->getPuissance() - $this->_puissance);//Puissance

  $this->setPuissance($this->getPuissance() - 10);//Baisse de la puissance de feu suite à l'attaque

//Mets à 0, faut pas exagérer
  if ($player->getPopulation() < 0) {
    $player->setPopulation(0);
    throw new Exception('Population ne peut pas être inférieur à 0');
  }
  if ($player->getPuissance() < 0) {
    $player->setPuissance(0);
    throw new Exception('Puissance ne peut pas être inférieur à 0');
  }
  if ($player->getProduction() < 0) {
    $player->setProduction(0);
    throw new Exception('Production ne peut pas être inférieur à 0');
  }
  try // Nous allons essayer d'effectuer les instructions situées dans ce bloc.
  {
  $population = $player->getPopulation();
  $production = $player->getProduction();
  $puissance = $player->getPuissance();

  $sql = "UPDATE factions SET population = :population, production = :production, puissance = :puissance WHERE id = " . $player->getId();
  $query = $this->_dbCon->prepare($sql);
  $query->bindParam(":population", $population, PDO::PARAM_INT);
  $query->bindParam(":production", $production, PDO::PARAM_INT);
  $query->bindParam(":puissance", $puissance, PDO::PARAM_INT);
  $query->execute();
  $query = null;
  }
  catch (Exception $e) // Nous allons attraper les exceptions "Exception" s'il y en a une qui est levée.
{
  echo 'Une exception a été lancée. Message d\'erreur : ', $e->getMessage();
}

  return $avant;
}

}
 ?>
