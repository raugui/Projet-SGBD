<?php

class User {

    private $_id;
    private $_nom;
    private $_prenom;
    private $_adresse;
    private $_mail;
    private $_dateNaiss;
    private $_telephone;
    private $_societe;
    private $_login;
    private $_psw;
    private $_codePostal;
    private $_ville;
    private $_pays;
    private $_type;
    private $_role;

    public function __construct(array $donnees)
    {
      $this->hydrate($donnees);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value){
        $method = 'set'.ucfirst($key);
        if (method_exists($this, $method)){
          $this->$method($value);
      }
    }
  }

    // GETTERS
    public function id(){
      return $this->_id;
    }

    public function nom(){
      return $this->_nom;
    }

    public function prenom(){
      return $this->_prenom;
    }

    public function adresse(){
      return $this->_adresse;
    }

    public function mail(){
      return $this->_mail;
    }

    public function dateNaiss(){
    //  var_dump($this->_dateNaiss);
      return $this->_dateNaiss;
    }

    public function telephone(){
      return $this->_telephone;
    }

    public function societe(){
      return $this->_societe;
    }

    public function login(){
      return $this->_login;
    }

    public function psw(){
      return $this->_psw;
    }

    public function codePostal(){
      return $this->_codePostal;
    }

    public function ville(){
      return $this->_ville;
    }

    public function pays(){
      return $this->_pays;
    }

    public function type(){
      return $this->_type;
    }

    public function role(){
      return $this->_role;
    }
    // SETTERS

    public function setId($id){
      $id = (int) $id;

      if ($id > 0){
        $this->_id = $id;
      }
    }

    public function setNom($nom){
      if (is_string($nom)){
        $nom = strtoupper(trim($nom));
        $this->_nom = $nom;
      }
    }

    public function setPrenom($prenom){
      if (is_string($prenom)){
        $prenom = strtoupper(trim($prenom));
        $this->_prenom = $prenom;
      }
    }

    public function setAdresse($adresse){
      if (is_string($adresse)){
        $adresse = strtoupper(trim($adresse));
        $this->_adresse = $adresse;
      }
    }

    public function setMail($mail){
      $mail = strtoupper(trim($mail));
      if (filter_var($mail,FILTER_VALIDATE_EMAIL)){
          $this->_mail = $mail;
      }else{
        return 'Adresse mail incorrecte.';
      }
    }

    public function setDateNaiss($dateNaiss){
        $this->_dateNaiss = $dateNaiss;
    }

    public function setTelephone($telephone){
        if(strlen($telephone) == 9){       
            if (filter_var($telephone,FILTER_VALIDATE_INT)){
                $this->_telephone = $telephone;
            }
        }else{
          return 'Numéro de téléphone invalide.';
        }
    }

    public function setsociete($societe){
      if (is_string($societe)){
        $societe = strtoupper(trim($societe));
        $this->_societe = $societe;
      }
    }

    public function setLogin($login){
      if (is_string($login)){
        $this->_login = $login;
      }
    }

    public function setPsw($psw){
      if (is_string($psw)){
      //  var_dump($psw);
    //    $psw = password_hash($psw, PASSWORD_DEFAULT);
        $psw = md5($psw);
        //var_dump($psw);
        $this->_psw = $psw;
      }
    }

      public function setCodePostal($codePostal){
        if (is_int($codePostal)){
          $this->_codePostal = $codePostal;
        }
      }

      public function setVille($ville){
        if(is_string($ville)){
          $this->_ville = $ville;
        }
      }

        public function setPays($pays){
          if(is_string($pays)){
            $this->_pays = $pays;
          }
        }

        public function setType($type){
          if(is_string($type)){
            $this->_type = $type;
          }
        }

        public function setRole($role){
          if(is_string($role)){
            $this->_role = $role;
          }
        }
}
