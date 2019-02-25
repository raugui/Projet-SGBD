<?php
class UserManager{

  private $bdd;
  public function __construct($bdd){
    $this->setBdd($bdd);
  }

  public function add(User $donnees)
  {
    $sql = $this->_bdd->prepare('INSERT INTO users(nom,prenom,adresse,mail,dateNaiss,telephone,role,societe,login,psw,type,codePostal,ville,pays)
    VALUES(:nom, :prenom,:adresse,:mail,:dateNaiss,:telephone,:role,:societe,:login,:psw,:type,:codePostal,:ville,:pays)');
    $sql->bindValue(':nom', $donnees->nom());
    $sql->bindValue(':prenom',$donnees->prenom());
    $sql->bindValue(':dateNaiss',$donnees->dateNaiss());
    $sql->bindValue(':adresse', $donnees->adresse());
    $sql->bindValue(':codePostal', $donnees->codePostal());
    $sql->bindValue(':ville', $donnees->ville());
    $sql->bindValue(':pays', $donnees->pays());
    $sql->bindValue(':mail', $donnees->mail());
    $sql->bindValue(':telephone', $donnees->telephone());
    $sql->bindValue(':role', $donnees->role());
    $sql->bindValue(':societe', $donnees->societe());
    $sql->bindValue(':login',$donnees->login());
    $sql->bindValue(':psw', $donnees->psw());
    $sql->bindValue(':type', $donnees->type());

    $sql->execute();

  }
  // Verifie si l'utilisateur existe
      public function exists($login,$psw)
      {
        $sql = $this->bdd()->prepare('SELECT COUNT(*), psw FROM users WHERE login =:login AND psw =:psw');
        $sql->execute([':login' => $login, ':psw' => $psw]);
        return (bool) $sql->fetchColumn();
      }

  // envoi les informations de l'utilisateur
      public function getUser($id){
        $sql = $this->bdd()->prepare('SELECT * FROM users WHERE id =:id');
        $sql->execute([':id' => $id]);
        $donnees = $sql->fetch(PDO::FETCH_ASSOC);
        return $donnees;
      }

      // Récupère tous les utilisateurs
      public function getAllUser(){
        $sql = $this->bdd()->prepare('SELECT * FROM users');
        $sql->execute();
        $donnees = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $donnees;
      }

  // Recupère l'id de l'utilisateur
      public function getId($login,$psw)
      {
        $sql = $this->bdd()->prepare('SELECT id FROM users WHERE login =:login AND psw =:psw');
        $sql->execute([':login' => $login, ':psw' => $psw]);
        while ($donnees = $sql->fetch(PDO::FETCH_ASSOC))
        {
          $tlogin = $donnees['id'];
        }
        return $tlogin;
      }

  // Mettre à jours les informations du client
      public function updateUser(User $donnees){
        $sql = $this->bdd()->prepare('UPDATE users set id=:id,nom=:nom,prenom=:prenom,dateNaiss=:dateNaiss,adresse=:adresse,codePostal=:codePostal,ville=:ville,pays=:pays,
          mail=:mail,telephone=:telephone,societe=:societe,login=:login,psw=:psw,type=:type where id=:id ');
        $sql->bindValue(':id',$donnees->id());
        $sql->bindValue(':nom', $donnees->nom());
        $sql->bindValue(':prenom',$donnees->prenom());
        $sql->bindValue(':dateNaiss',$donnees->dateNaiss());
        $sql->bindValue(':adresse', $donnees->adresse());
        $sql->bindValue(':codePostal', $donnees->codePostal());
        $sql->bindValue(':ville', $donnees->ville());
        $sql->bindValue(':pays', $donnees->pays());
        $sql->bindValue(':mail', $donnees->mail());
        $sql->bindValue(':telephone', $donnees->telephone());
        $sql->bindValue(':societe', $donnees->societe());
        $sql->bindValue(':login',$donnees->login());
        $sql->bindValue(':psw', $donnees->psw());
        $sql->bindValue(':type', $donnees->type());
        $sql->execute();
      }

      // Supprime un utilisateur en fonction de son id
      public function delete($id){
        $sql = $this->bdd()->prepare('DELETE FROM users WHERE id=:id');
        $sql->execute([':id' => $id]);
        return $sql;
      }

      public function setBdd(PDO $bdd)
      {
        $this->_bdd = $bdd;
      }

      public function bdd(){
        return $this->_bdd;
      }
}


 ?>
