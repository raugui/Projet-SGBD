<?php
require "connect/connect.php";
session_start();
require "connect/header.php";
// Id de l'utilisateur connecté
$id=$_SESSION["id"];
$ar = new ArticlesManager($bdd);
// on recupère la liste des articles
$detail = $ar->getList();
//stock minimal avant alerte
$min = 30;

if(isset($_GET['idsupp'])){
    //var_dump($_GET['id']);
    $idArt = $_GET['idsupp'];
    if($ar->delete($idArt)){
      echo "<div class='alert alert-success' role='alert'>
      L'article a bien été supprimé.</div>";
    }else{
      echo "<div class='alert alert-danger' role='alert'>
      Une erreur s'est produite.</div>";
    }
  }elseif(isset($_GET['idGererArt'])){
    ?><form method="post" action="" role="del">
      <section class="container login-form">
        <section>
            <h1 style='background-color:white'> Gestion stock</h1>
            <table class="table table-dark table-striped" >
      <thead>
        <tr>
          <th scope="col">Désignation</th>
          <th scope="col">Quantité totale restante</th>
          <th scope="col">Format</th>
          <th scope="col">Type</th>
          <th scope="col">Quantité</th>
          <th scope="col" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody><?php
    $idArticle=$_GET['idGererArt'];
    $donnees = $ar->getArticle($idArticle);
    if(isset($_POST['add'])){
      $ar->restock($_POST['qt'],$idArticle);
      header('Location:stock.php');
    }elseif(isset($_POST['remove'])){
      $ar->destock($_POST['qt'],$idArticle);
      header('Location:stock.php');
    }else{
      echo "<tr>";
      // Si la quantité existe encore
        echo "<td scope='row'>".$donnees['designation']."</td>";
        echo "<td scope='row'>".$donnees['quantite_stock']."</td>";
        echo "<td scope='row'>".$donnees['format']."</td>";
        echo "<td scope='row'>".$donnees['type']."</td>";
        echo "<td><input type='user' name='qt' class='form-control'/></td>";
        echo "<td scope='row'><button type='submit' name='add' class='btn btn-primary'>Ajouter</button></td>";
        echo "<td scope='row'><button type='submit' name='remove' class='btn btn-warning'>Retirer</button></td>";
    }
  }elseif(isset($_GET['Add'])){
    if(isset($_POST['addProd'])){
            $designation = strtoupper(trim(htmlspecialchars($_POST['designation'])));
            $quantite_stock = $_POST['qt'];
            $format = strtoupper(trim(htmlspecialchars($_POST['format'])));
            $type = strtoupper(trim(htmlspecialchars($_POST['type'])));
            $prix = $_POST['prix'];
            $poids = $_POST['poids'];
            $donnees = ([ 'designation' => $designation,
                          'quantite_stock' => (int)$quantite_stock,
                          'format' => $format,
                          'type' => $type,
                          'prix_unitaire' => (int)$prix,
                          'poids_unitaire' => (float)$poids]);
            //var_dump($donnees);
            if(!$ar->add($donnees)){
              header('Location:stock.php');
            }else{
              echo "<div class='alert alert-danger' role='alert'>
              Une erreur s'est produite.</div>";
            }
          }else{
            ?><table class="table table-dark table-striped" >
              <thead>
                <tr>
                  <th scope="col">Désignation</th>
                  <th scope="col">Quantité</th>
                  <th scope="col">Format</th>
                  <th scope="col">Type</th>
                  <th scope="col">Prix unitaire</th>
                  <th scope="col">Poids unitaire (kg)</th>
                </tr>
              </thead>
              <tbody><?php
            echo "<tr>";
            // Si la quantité existe encore
              echo "<td scope='row'></form><form method='post' action='stock.php?Add' ><input type='user' name='designation' placeholder='Exemple : coca' class='form-control'/></td>";
              echo "<td scope='row'><input type='user' name='qt' placeholder='Exemple : 50' class='form-control'/></td>";
              echo "<td scope='row'><input type='user' name='format' placeholder='Exemple : 24x25cl' class='form-control'/></td>";
              echo "<td scope='row'><input type='user' name='type' placeholder='Exemple : caisse' class='form-control'/></td>";
              echo "<td><input type='user' name='prix' placeholder='Exemple : 50' class='form-control'/></td>";
              echo "<td scope='row'><input type='user' name='poids' placeholder='Exemple : 50' class='form-control'/></td>";
              echo "<td scope='row'><button type='submit' name='addProd' class='btn btn-primary'>Ajouter</button></td></form>";
              echo "</tr>";
              echo "</table><a href='stock.php'>Retour</a>";
          }


// FOnctionne pas

  }else{


    ?>
    <form method="post" action="" role="del">
      <section class="container login-form">
        <section>
            <h1 style='background-color:white'> Gestion stock</h1>


            <!--   On met une barre de recherche pour le filtre possible sur le tableau -->
              <input class="form-control mr-sm-2" type="text" placeholder="Insérer le type ou la désignation"name="Recherche">
              <button class="btn btn-primary" type="submit" name="search">Rechercher</button>
            <table class="table table-dark table-striped" >
      <thead>
        <tr>
          <th scope="col">Désignation</th>
          <th scope="col"></form><form method="post" action="stock.php?trieQt" role="user"><button type="submit" name="trieQt" class="btn btn-link btn-block">Quantité totale restante</button></form></th>
          <th scope="col"></form><form method="post" action="stock.php?trieFormat" role="user"><button type="submit" name="trieFormat" class="btn btn-link btn-block">Format</button></form></th>
          <th scope="col"></form><form method="post" action="stock.php?trieType" role="user"><button type="submit" name="trieType" class="btn btn-link btn-block">Type</button></form></th>
        <?php if(isset($_GET['idGererArt'])): echo '<th scope="col">Quantité</th>';endif; ?>
          <th scope="col" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody><?php

              if((isset($_GET['trieFormat']))OR(isset($_GET['trieType']))OR(isset($_GET['trieQt']))){

                if ((isset($_GET['trieFormat']))&&((!isset($_SESSION['valeurFormat'])) OR ($_SESSION['valeurFormat'] == 2)))  {
                    array_multisort(array_column( $detail, "format"), SORT_DESC, $detail );
                    $_SESSION['valeurFormat'] = 1;
                }elseif ((isset($_GET['trieFormat']))&&($_SESSION['valeurFormat'] == 1)) {
                    array_multisort(array_column($detail, "format"), SORT_ASC, $detail);
                    $_SESSION['valeurFormat'] = 2;
                }

                if((isset($_GET['trieType']))&&((!isset($_SESSION['valeurType'])) OR ($_SESSION['valeurType'] == 2)))  {
                    array_multisort(array_column( $detail, "type"), SORT_DESC, $detail );
                    $_SESSION['valeurType'] = 1;
                }elseif ((isset($_GET['trieType']))&&($_SESSION['valeurType'] == 1)) {
                    array_multisort(array_column($detail, "type"), SORT_ASC, $detail);
                    $_SESSION['valeurType'] = 2;
                }

                if((isset($_GET['trieQt']))&&((!isset($_SESSION['valeurQt'])) OR ($_SESSION['valeurQt'] == 2)))  {
                    array_multisort(array_column( $detail, "quantite_stock"), SORT_DESC, $detail );
                    $_SESSION['valeurQt'] = 1;
                }elseif ((isset($_GET['trieQt']))&&($_SESSION['valeurQt'] == 1)) {
                    array_multisort(array_column($detail, "quantite_stock"), SORT_ASC, $detail);
                    $_SESSION['valeurQt'] = 2;
                }

              }

              if(isset($_POST['search'])){
                $nb = 0;
              //  var_dump($_POST['Recherche']);
                $recherche = strtoupper(trim(htmlspecialchars($_POST['Recherche'])));
                foreach ($detail as $donnees){
              //    var_dump($donnees['designation']);
              // Si le type ou la designation correspond a la recherche alors on affiche, si on envoi rien, on envoi la totalité du tableau
                  if (($donnees['type']== $recherche) OR ($donnees['designation']== $recherche) OR ($recherche == '')){
                    echo "<tr>";
                    // Si la quantité existe encore
                      echo "<td scope='row'>".$donnees['designation']."</td>";
                      if($donnees['quantite_stock'] <= $min){
                      echo  "<td scope='row' style='background-color:red'>".$donnees['quantite_stock']."</td>";
                      }else{
                      echo  "<td scope='row'>".$donnees['quantite_stock']."</td>";
                      }
                      echo "<td scope='row'>".$donnees['format']."</td>";
                      echo "<td scope='row'>".$donnees['type']."</td>";
                      echo "<td scope='row'><a href='stock.php?idGererArt=".$donnees['id']."' name='commande' class='btn btn-link'>Gérer</a></td>";
                      echo "<td scope='row'><a style='background-color:red' href='stock.php?idsupp=".$donnees['id']."' name='commande' class='btn btn-link'>Supprimer</a></td>";
                      echo "</tr>";
                      $nb++;
                  }
                }
                if($nb == 0){
                  echo "<div class='alert alert-danger' role='alert'>
        					Une erreur s'est produite. Ce produit n'existe pas ou vous l'avez mal orthographié..</div>";
                }
              }else{
                // Génere un tableau avec tous les articles
                foreach ($detail as $donnees){
                  echo "<tr>";
                  // Si la quantité existe encore
                    echo "<td scope='row'>".$donnees['designation']."</td>";
                    if($donnees['quantite_stock'] <= $min){
                    echo  "<td scope='row' style='background-color:red'>".$donnees['quantite_stock']."</td>";
                    }else{
                    echo  "<td scope='row'>".$donnees['quantite_stock']."</td>";
                    }
                    echo "<td scope='row'>".$donnees['format']."</td>";
                    echo "<td scope='row'>".$donnees['type']."</td>";
                    echo "<td scope='row'><a href='stock.php?idGererArt=".$donnees['id']."' name='commande' class='btn btn-link'>Gérer</a></td>";
                    echo "<td scope='row'><a style='background-color:red' href='stock.php?idsupp=".$donnees['id']."' name='commande' class='btn btn-link'>Supprimer</a></td>";
                    echo "</tr>";
                }
              }


  }if(!isset($_GET['Add'])){?>
 </tbody>
</table>
    </section>
      <a href='accueil.php' style='background-color:blue' name="annuler" class="btn btn-link">Retour accueil</a>
      <a href='stock.php?Add' style='background-color:green' name="annuler" class="btn btn-link">Ajouter produit</a>
  </section>
</form><?php }
