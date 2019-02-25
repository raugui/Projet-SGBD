<?php
class Articles extends Detail_commandes {

  private $_quantite;
  private $_prix;
  private $_poidsUnitaire;

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

  //GETTERS

  public function quantite(){
    return $this->_quantite;
  }

  public function prix(){
    return $this->_prix;
  }

  public function poidsUnitaire(){
    return $this->_poidsUnitaire;
  }

  //SETTERS


  public function setQuantite($quantite){
    if(is_int($quantite)){
      $this->_quantite = $quantite;
    }
  }

  public function setPrix($prix){
    if(is_int($prix)){
      $this->_prix = $prix;
    }
  }

  public function setPoidsUnitaire($poidsUnitaire){
    if(is_float($poidsUnitaire)){
      $this->_poidsUnitaire = $poidsUnitaire;
    }
  }

}
