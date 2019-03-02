<?php
require "connect/connect.php";
session_start();
require "connect/header.php";
$dc = new DetailCommandesManager($bdd);
$ac = new ArticlesManager($bdd);
$co = new CommandesManager($bdd);
// Id de l'utilisateur
$idUser = $_SESSION["id"];
// connaitre le type d'utilisateur
$user = new UserManager($bdd);
$TypeUser = $user->getUser($idUser);
// Id de la commande
if($TypeUser['type'] == 'Employes'):$id = $_GET['id'];endif;
if($TypeUser['type'] == 'Client'){
  if(isset($_GET['idCo'])){
    $id = $_GET['idCo'];
  }elseif(isset($_POST['Modif'])){
    $id = $_POST['idCom'];
  }elseif(isset($_POST['confirmer'])){
    if(isset($_POST['confirmer'])){
      // on recupère la commande
      $data = $_SESSION['data'];
      // on hydrate l'objet commande
      $commande = new Commandes($data);
      // On ajoute la commande et on vérifie qu'on a pas d'erreur en réponse
  //    var_dump($data);
      if(!$co->add($commande)){
          $id=$co->getId()['id'];
        //  var_dump($id);
      $valeurpot = $_SESSION['valeurpot'];
      // On récupère chaque détail de la commande
      for($i=1;$i<=$valeurpot;$i++){
        //var_dump($_SESSION['pot'][$i]);
        $objet = $_SESSION['pot'][$i];
        // on redéfini un objet avec l'id de la commande en plus
        $objetDetail = ([
          'idCom' => (int)$id,
          'idArt' => (int)$objet['idArt'],
          'quantiteArticle' => (int)$objet['quantite'],
          'format' => $objet['format'],
          'designation' => $objet['designation'],
          'type' => $objet['type'],
          'poids' => (float)$objet['poids'],
          'prix' => (int)$objet['prix']
        ]);
        //var_dump($objetDetail);
        //var_dump($objet);
        $DetailCommande = new Detail_commandes($objetDetail);
        // on ajoute le détail de la commande en base de donnée
        $dc->add($DetailCommande);
        // On retire les produits du stock
        $ac->destock($objet['quantite'],$objet['idArt']);
      }
      echo "<div class='alert alert-primary' role='alert'>
        Votre commande à été ajoutée, celle-ci est en cours de préparation.</div>";
          // Si on a une erreur on affiche un message
          }else{  echo "<div class='alert alert-danger' role='alert'>
            Une erreur s'est produite, veuillez contacter l'administrateur.</div>";
            echo "<a href='accueil.php' name='annuler' class='btn btn-link'>Retour accueil</a>";
        }
      }
  }else{
    $id = $_GET['id'];
  }
}
if($TypeUser['type'] == 'Admin'){
  if(isset($_GET['id'])){
    $id = $_GET['id'];
  }elseif(isset($_POST['Modif'])){
    $id = $_POST['idCom'];
  }else{
    $id = $_GET['id'];
  }
}
//var_dump($id);
// On recupère tous les articles de la commande
$detail = $dc->getListId($id);
$dcc = new Detail_commandes($detail);
$commande = $co->getCommande($detail[0]['idCom']);
//var_dump($id);
if(!isset($_POST['confirmer'])): echo '
<section class="container login-form">
  <section>
    <form method="post" action="" role="nouvelle_commande">
      <h1> Detail commande </h1>
      <table class="table table-dark table-striped" >
        <thead>
          <tr>
            <th scope="col">Désignation</th>
            <th scope="col">Quantité totale</th>
            <th scope="col">Format</th>
            ';
            if($TypeUser["type"]== "Client"): echo '<th scope="col">Prix Unitaire</th>';endif;
            if(($TypeUser['type']== 'Client')&&(!isset($_GET['idArt'])&&(!isset($_GET['idCo'])))): echo '<th scope="col">Prix*Quantité</th>';endif;
            if(($TypeUser['type']== 'Client')&&($id)&&($commande['statut'] == 'En cours')): echo ' <th scope="col">Action</th>';endif;
        echo '  </tr>
        </thead>
        <tbody> ' ;endif;
if(isset($_GET['idArt'])&&isset($_GET['idCo'])){
  $donnees = $ac->getArticle($_GET['idArt']);
  echo "</form><form method='post' action='detail_commande.php'>
  <input type='hidden' name='idArt' value='".$_GET['idArt']."' class='form-control'/>
  <input type='hidden' name='idCom' value='".$_GET['idCo']."' class='form-control'/>";
  echo "<tr>";
    echo "<td scope='row'>".$donnees['designation']."</td>".
        "<td><input type='article' name='qtModif' class='form-control'/></td>".
        "<td scope='row'>".$donnees['type']."</td>".
        "<td scope='row'>".$donnees['format']."</td>".
        "<td scope='row'><button type='submit' name='Modif' class='btn btn-link'>Valider</button></form></td>";
    echo "</tr>";
  exit();
  //header('Location:detail_commande?idArt='.$_GET['idArt'].'&amp;idCo='.$_GET['idCo'].'&amp;idMod='.$valeur.'');
}
/* ------------------------------------------------------- */
/* -----------------------CLIENT-------------------------- */
/* ------------------------------------------------------- */
if($TypeUser['type']== 'Client'){
  if(!isset($_POST['confirmer'])):
  // Confirmation de la commande
      // Génere un tableau avec tous les articles
      foreach ($detail as $donnees){
        // on recupère le statut de la commande
        $commande = $co->getCommande($donnees['idCom']);
        //var_dump($commande);
        // on récupère la quantité
        $qt = $donnees['quantite'];
        // on récupère l'id de l'article
        $id_art = $donnees['idArt'];
        // on récupère le prix unitaire de l'article
        $prix = $ac->getArticle($id_art);
        //var_dump($prix);
        /***********************************/
        if(isset($_POST['Modif'])){
          if($_POST['idArt'] == $id_art){
           // var_dump($commande);
            // On recupère la quantitée qui est modifiée
            $quantite = (int)$_POST['qtModif'];//
            // On récupère la quantite de la base de donnée
            $ArticleInitial =$dc->getListId($donnees['idCom']);//
            // on recupère la différence des deux 
            // on récupère l'article en question et ca quantitée
            foreach ($ArticleInitial as $qi){
                //var_dump($qi);
                if ($qi['idArt'] == $donnees['idArt']){
                    /* QUANTITE */
                    $art = $ac->getArticle($qi['idArt']);
                    $qti = (int)$qi['quantite'];    
                    /* POIDS */
                    $newpoids = $dcc->calculerPoids($art['poids_unitaire'],$quantite);
                    $poidsInit = (float)$dcc->calculerPoids($art['poids_unitaire'],$qi['quantite']);
                    $poidsFinal = $poidsInit - $newpoids;
                    //var_dump($newpoids,$poidsInit,$poidsFinal); 
                    /* PRIX */
                    $newprix = $dcc->calculerPrix($art['prix_unitaire'],$quantite) ;
                    $prixInit = $dcc->calculerPrix($art['prix_unitaire'],$qi['quantite']) ;
                    $prixFinal = $prixInit - $newprix;
                   // var_dump($qi);     
                }
            }
            $quantiteTotale = (int)$commande['quantiteTotale']-($qti-$quantite);
            $poidstotal = (float)$commande['poidsTotal']-$poidsFinal;
            $prixTotal = (int)$commande['prix']-$prixFinal;           
            //var_dump($commande['prix'],$prixInit,$prixFinal,$prixTotal);
            $commande = ([
                'id' => (int)$commande['id'],
                'quantiteTotale' => $quantiteTotale,
                'idClient' => (int)$commande['idClient'],
                'prix' => $prixTotal,
                'statut' => $commande['statut'],
                'poidsTotal' => $poidstotal,
                'Preparateur' => $commande['Preparateur']
            ]);
            $com = new Commandes($commande);       
            $co->updateCommande($com);
            //  var_dump($quantite);
            // on rajoute dans le stock la quantité demandée de base
            $ac->restock($qt,$id_art);
            
            $qt = $quantite;
            // On modifie la valeur dans le detail de la commande
            $dc->updateArt($donnees['idArt'],$donnees['idCom'],$qt);
            // On retire du stock
            $ac->destock($quantite,$id_art);
          }
        }
        echo "<tr>";
        // Si la quantité existe encore
        if ($donnees['quantite'] != 0){
          echo "<td scope='row'>".$donnees['designation']."</td>".
              "<td scope='row'>".$qt."</td>".
              "<td scope='row'>".$donnees['format']."</td>".
              "<td scope='row'>".$prix['prix_unitaire']."€/u</td>";
          echo "<td scope='row'>";
          // on envoie a la méthode calculer et on recupère le prix
          $pot = $dcc->calculerPrix((int)$qt,((int)$prix['prix_unitaire']));
        //  var_dump($pot);
          echo $pot."€</td>";
          if($commande['statut'] == 'En cours'){
            echo "<td scope='row'><a href='detail_commande.php?idArt=".$donnees['idArt']."&idCo=".$donnees['idCom']."' class='btn btn-link'>Modifier</a>";
          }
          echo "</tr>";
          $prixTotal = $dcc->calculerPrixTotal($pot);
        }
      }
      echo "</table></form><h2><span class='badge badge-light' style='font-size:15'>Prix total de la commande : <span style='color:red'>".$prixTotal."</span>€</span>";
    endif;
  }
/* ------------------------------------------------------- */
/* ---------------------EMPLOYES-------------------------- */
/* ------------------------------------------------------- */
if(($TypeUser['type']== 'Employes')OR($TypeUser['type']== 'Admin')){
  // Si on clique sur impression
  if(isset($_POST['pdf'])){
  header('Location:PDF/creer_pdf.php?idCom='.$id.'');
  }else{
  // Génere un tableau avec tous les articles
    foreach ($detail as $donnees){
      //var_dump($donnees);exit();
      // on récupère la quantité
      $qt = $donnees['quantite'];
      // on récupère l'id de l'article
      $id_art = $donnees['idArt'];
      // on récupère le prix unitaire de l'article
      $prix = $ac->getArticle($id_art);
      //var_dump($prix);
      echo "<tr>";
      // Si la quantité existe encore
      if ($donnees['quantite'] != 0){
        echo "<td scope='row'>".$donnees['designation']."</td>".
            "<td scope='row'>".$qt."</td>".
            "<td scope='row'>".$donnees['format']."</td>";
        echo "<td scope='row'>";
        echo "</tr>";
      }
    }
    echo '</table></form><form method="post" action="" target=_blank><button type="submit" name="pdf" class="btn btn-warning btn-block">Impression</button></form><form method="post" action="" role="nouvelle_commande">';
  }  $etat = $co->getCommande($id);
  // si la commande n'est pas terminée, on peux la passé en "terminée"
  if($etat['statut'] == 'En cours'):echo '</br><button type="submit" name="pret" class="btn btn-primary btn-block">Commande terminée</button>';endif;
  if(isset($_POST['pret'])){
    //var_dump($ac->updateStatut($idl['id_com']));
      //var_dump($etat);
      $etat = ([
        "id" => (int)$etat['id'],
        "quantiteTotale" => (int)$etat['quantiteTotale'],
        "statut" => 'terminee',
        "idClient" => (int)$etat['idClient'],
        "prix" => (int)$etat['prix'],
        "poidsTotal" => (float)$etat['poidsTotal'],
        "Preparateur" => $TypeUser['nom'].' '.$TypeUser['prenom']
      ]);
      //var_dump($etat);
      $commande = new Commandes($etat);
      // si la commande est modifiée , on revient au menu
      if(!$co->updateCommande($commande)):header('Location:accueil.php');endif;
    }
    // si on modifie un article
}
?>
          </tbody>
      </form>
    </section>
      <a href='accueil.php' name="annuler" class="btn btn-link">Retour accueil</a>
  </section>
