<?php

class DetailCommandesManager {

  private $_bdd;

  public function __construct ($bdd){
    $this->setBdd($bdd);
  }

  public function add(Detail_commandes $donnees)
  {
    $sql = $this->_bdd->prepare('INSERT INTO detail_commande(idCom,idArt,quantite,format,designation,type,poids,prix)
    VALUES(:idCom,:idArt,:quantiteArticle,:format,:designation,:type,:poids,:prix)');
    $sql->bindValue(':idCom', $donnees->idCom());
    $sql->bindValue(':idArt', $donnees->idArt());
    $sql->bindValue(':quantiteArticle', $donnees->quantiteArticle());
    $sql->bindValue(':format', $donnees->format());
    $sql->bindValue(':designation', $donnees->designation());
    $sql->bindValue(':type', $donnees->type());
    $sql->bindValue(':poids', $donnees->poids());
    $sql->bindValue(':prix', $donnees->prix());

    $sql->execute();
  }

  public function getList(){
    $sql = $this->bdd()->prepare('SELECT * FROM detail_commande');
    $sql->execute();
    $donnees = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $donnees;
  }

  public function getListId($idCom){
    $sql = $this->bdd()->prepare('SELECT * FROM detail_commande where idCom =:idCom ');
    $sql->execute([':idCom' => $idCom]);
    $donnees = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $donnees;
  }

  public function updateArt($idArt,$idCom,$quantite){
    $sql = $this->bdd()->prepare('UPDATE `detail_commande` SET quantite =:quantite WHERE idCom =:idCom AND idArt =:idArt');
    $sql->bindValue(':idArt', $idArt);
    $sql->bindValue(':idCom', $idCom);
    $sql->bindValue(':quantite', $quantite);
    $sql->execute();
  }

  public function bdd(){
    return $this->_bdd;
  }

  public function setBdd(PDO $bdd)
  {
    $this->_bdd = $bdd;
  }




}



 ?>
