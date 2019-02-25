<?php
class Commandes {

  private $_idClient;
  private $_quantiteTotale;
  private $_statut;
  private $_prix;
  private $_poidsTotal;
  private $_id;
  private $_Preparateur;

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

  public function calculerQuantite($quantiteTotale){
    $this->_quantiteTotale += $quantiteTotale;
    return $this->_quantiteTotale;
  }

  public function calculerPrixTotal($prix){
    $this->_prix += $prix;
    return $this->_prix;
  }

  public function calculerPoidsTotal($poidsTotal){
    $this->_poidsTotal += $poidsTotal;
    return $this->_poidsTotal;
  }

  // GETTERS
   public function idClient(){
    return $this->_idClient;
   }

   public function statut(){
    return $this->_statut;
   }

   public function Preparateur(){
    return $this->_Preparateur;
   }

   public function quantiteTotale(){
    return $this->_quantiteTotale;
   }

   public function prix(){
    return $this->_prix;
   }

   public function poidsTotal(){
    return $this->_poidsTotal;
   }

   public function id(){
    return $this->_id;
   }

   // SETTERS

   public function setIdClient($idClient){
     if(is_int($idClient)){
       $this->_idClient = $idClient;
     }
   }

   public function setStatut($statut){
    if(is_string($statut)){
      $this->_statut = $statut;
    }
   }

   public function setPreparateur($Preparateur){
    if(is_string($Preparateur)){
      $this->_Preparateur = $Preparateur;
    }
   }

   public function setQuantiteTotale($quantiteTotale){
    if(is_int($quantiteTotale)){
      $this->_quantiteTotale = $quantiteTotale;
    }
   }

   public function setPrix($prix){
    if(is_int($prix)){
      $this->_prix = $prix;
    }
   }

   public function setPoidsTotal($poidsTotal){
    if(is_float($poidsTotal)){
      $this->_poidsTotal = $poidsTotal;
    }
   }

   public function setId($id){
    if(is_int($id)){
      $this->_id = $id;
    }
   }

}
