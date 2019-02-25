<?php
class Detail_commandes extends Commandes{

  private $_quantiteArticle;
  private $_designation;
  private $_poids;
  private $_format;
  private $_type;
  private $_idCom;
  private $_idArt;

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

  public function calculerPoids($poids,$quantiteArticle){
    $this->_poids = $poids*$quantiteArticle;
    return $this->_poids;
  }

  public function calculerPrix($prix,$qt){
    $this->_prix = $prix*$qt;
    return $this->_prix;
  }


  // GETTERS
  public function idCom(){
    return $this->_idCom;
  }

  public function idArt(){
    return $this->_idArt;
  }


  public function quantiteArticle(){
    return $this->_quantiteArticle;
  }

  public function designation(){
    return $this->_designation;
  }

  public function poids(){
    return $this->_poids;
  }

  public function format(){
     return $this->_format;
  }

  public function type(){
     return $this->_type;
  }

  public function prix(){
   return $this->_prix;
  }

   // SETTERS

  public function setQuantiteArticle($quantiteArticle){
   if(is_int($quantiteArticle)){
     $this->_quantiteArticle = $quantiteArticle;
   }
  }

  public function setDesignation($designation){
   if(is_string($designation)){
     $designation = strtoupper($designation);
    $this->_designation = $designation;
   }
  }

  public function setPoids($poids){
    if(is_float($poids)){
    $this->_poids = $poids;
    }
  }

  public function setFormat($format){
   if(is_string($format)){
     $this->_format = $format;
    }
   }

  public function setType($type){
    if(is_string($type)){
      $type = strtoupper($type);
      $this->_type = $type;
    }
  }

  public function setIdCom($idCom){
    if(is_int($idCom)){
    $this->_idCom = $idCom;
    }
  }

  public function setIdArt($idArt){
    if(is_int($idArt)){
    $this->_idArt = $idArt;
    }
  }

  public function setPrix($prix){
   if(is_int($prix)){
     $this->_prix = $prix;
   }
  }

}
