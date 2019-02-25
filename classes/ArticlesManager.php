<?php
class ArticlesManager {
  private $_bdd;

  public function __construct ($bdd){
    $this->setBdd($bdd);
  }

  public function add(array $donnees)
  {
    $sql = $this->_bdd->prepare('INSERT INTO articles(designation,quantite_stock,format,type,prix_unitaire,poids_unitaire)
    VALUES(:designation, :quantite_stock,:format,:type,:prix_unitaire,:poids_unitaire)');
    $sql->bindValue(':designation', $donnees['designation']);
    $sql->bindValue(':quantite_stock', $donnees['quantite_stock']);
    $sql->bindValue(':format', $donnees['format']);
    $sql->bindValue(':type', $donnees['type']);
    $sql->bindValue(':prix_unitaire', $donnees['prix_unitaire']);
    $sql->bindValue(':poids_unitaire', $donnees['poids_unitaire']);

    $sql->execute();
  }
  // Affiche tous les articles en stock
  public function getList(){
    $sql = $this->bdd()->prepare('SELECT * FROM articles');
    $sql->execute();
    $des = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $des;
  }
  // Affiche un article en fonction de son id
  public function getArticle($id){
    $sql = $this->bdd()->prepare('SELECT * FROM articles where id=:id');
    $sql->execute([':id' => $id]);
    $des = $sql->fetch(PDO::FETCH_ASSOC);
    return $des;
  }
  // Supprime un article en fonction de son id
  public function delete($id){
    $sql = $this->bdd()->prepare('DELETE FROM articles where id=:id');
    $sql->execute([':id' => $id]);
    return $sql;
  }
  // Met a jour les articles dans le stock (retire des articles)
  public function destock($valeur,$id){
    $sql = $this->bdd()->prepare('UPDATE articles as a
      JOIN articles as b ON b.id = a.id SET a.quantite_stock= b.quantite_stock - :valeur where a.id=:id');
    $sql->bindValue(':valeur' , $valeur);
    $sql->bindValue(':id',$id);
    $sql->execute();
  }
  // Met a jour les articles dans le stock (ajoute des articles)
  public function restock($valeur,$id){
    $sql = $this->bdd()->prepare('UPDATE articles as a
      JOIN articles as b ON b.id = a.id SET a.quantite_stock= b.quantite_stock + :valeur where a.id=:id');
    $sql->bindValue(':valeur' , $valeur);
    $sql->bindValue(':id',$id);
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
