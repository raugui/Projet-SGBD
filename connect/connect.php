<?php
function chargerClasse($classname)
{ // Si le fichier n'est pas un pdf alors on charge la classe dans le dossier classes
  if($classname != 'FPDF' && $classname != 'PDF'){
    //require 'c:\xampp\htdocs\ecole/Projet-SGBD\classes/'.$classname.'.php';
     require 'D:\wamp64\www\sgbd\Projet\Projet-SGBD/classes/'.$classname.'.php';
  }else{
    include('../PDF/'.strtolower($classname).'.php');
  }

}

spl_autoload_register('chargerClasse');

try{
  $bdd = new PDO('mysql:host=localhost;dbname=projet_sgbd', 'root', '');
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.
}
//retourne un message d'erreur lorsqu'une
// exception est levée
catch (\Exception $e){
 echo $e->getMessage();
}
