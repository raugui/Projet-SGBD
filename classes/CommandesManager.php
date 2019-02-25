<?php

class CommandesManager {

  private $_bdd;

  public function __construct ($bdd){
    $this->setBdd($bdd);
  }
  // Ajoute une commande
  public function add(Commandes $tab)
  {
    $sql = $this->_bdd->prepare('INSERT INTO commandes(quantiteTotale,idClient,prix,statut,poidsTotal)
    VALUES(:quantiteTotale,:idClient,:prix,:statut,:poidsTotal)');
    $sql->bindValue(':quantiteTotale', $tab->quantiteTotale());
    $sql->bindValue(':idClient', $tab->idClient());
    $sql->bindValue(':prix', $tab->prix());
    $sql->bindValue(':statut',$tab->statut());
    $sql->bindValue(':poidsTotal', $tab->poidsTotal());

    $sql->execute();
  }
  // Affiche toutes les commandes
  public function getList(){
    $sql = $this->bdd()->prepare('SELECT * FROM commandes');
    $sql->execute();
    $donnees = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $donnees;
  }
  // Affiche toutes les commandes d'un utilisateur
  public function getListId($id){
    $sql = $this->bdd()->prepare('SELECT * FROM commandes where idClient =:id');
    $sql->execute([':id' => $id]);
    $donnees = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $donnees;
  }
  // Envoi la dernière commande
  public function getId(){
    $sql = $this->bdd()->prepare('SELECT * FROM `commandes` order by id desc limit 1');
    $sql->execute();
    $donnees = $sql->fetch(PDO::FETCH_ASSOC);
    return $donnees;
  }
  // Envoi toutes les informations du client et de ses commandes
  public function getNbComClient(){
    //JOINTURE
    $sql = $this->bdd()->prepare('SELECT * FROM users as cl JOIN commandes as co ON cl.id = co.idClient ');
    $sql->execute();
    $donnees = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $donnees;
  }
  // Envoi toutes les informations du client et de sa commande, ainsi que du détail
  public function getNomSocieteClientId($id,$idClient){
    //JOINTURE
    $sql = $this->bdd()->prepare('SELECT * FROM users as us JOIN  commandes as co
      ON us.id = co.idClient JOIN
      detail_commande as dc ON dc.idCom = co.id WHERE us.id =:idClient and co.id =:id');
    $sql->execute(['id' => $id, 'idClient' => $idClient]);
    $donnees = $sql->fetch(PDO::FETCH_ASSOC);
    return $donnees;
  }

  // Envoi les informations d'une seule commande
  public function getCommande($id){
    $sql = $this->bdd()->prepare('SELECT * FROM `commandes` WHERE id=:id');
    $sql->execute(['id'=>$id]);
    $donnees = $sql->fetch(PDO::FETCH_ASSOC);
    return $donnees;
  }

  // Permet de modifier la commande
  public function updateCommande(Commandes $donnees){
    $sql = $this->bdd()->prepare('UPDATE commandes set quantiteTotale=:quantiteTotale, statut=:statut, idClient=:idClient,
      prix=:prix, poidsTotal=:poidsTotal, Preparateur=:Preparateur where id=:id');
    $sql->bindValue(':id', $donnees->id());
    $sql->bindValue(':quantiteTotale', $donnees->quantiteTotale());
    $sql->bindValue(':idClient', $donnees->idClient());
    $sql->bindValue(':prix', $donnees->prix());
    $sql->bindValue(':statut',$donnees->statut());
    $sql->bindValue(':poidsTotal', $donnees->poidsTotal());
    $sql->bindValue(':Preparateur', $donnees->Preparateur());
    $sql->execute();
  }

  // Supprime une commande en fonction de son id
  public function delete($id){
    $sql = $this->bdd()->prepare('DELETE FROM commandes where id=:id');
    $sql->execute([':id' => $id]);
    return $sql;
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
