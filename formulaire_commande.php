<?php
require "connect/connect.php";
session_start();
require "connect/header.php";
// On garde l'id du client connecté
$id=$_SESSION["id"];
// On garde le type de l'utilisateur connecté
$type=$_SESSION['type'];

$ar = new ArticlesManager($bdd);
$art = $ar->getList();
$entete = "  <form method='post' action='' role=''>
        <table class='table table-dark table-striped' >
          <thead>
            <tr>
              <th scope='col'>Désignation</th>
              <th scope='col'>Quantité</th>
              <th scope='col'>Type</th>
              <th scope='col'>Format</th>
              <th scope='col'>Prix unitaire</th>
            </tr>
          </thead>
          <tbody>
" ;

if(isset($_POST['terminer'])){

  $dc = new DetailCommandesManager($bdd);
  $dcc = new Detail_commandes($art);
  $co = new CommandesManager($bdd);
  // permettra de savoir si un article a été commandée
  $pot = 0;

  echo '<section class="container login-form">
        <h2> Recapitulatif de la commande </h2>
          <section>';
  echo $entete;

  //var_dump($id_com);

  foreach ($art as $donnees){
    if(!$donnees['quantite_stock'] <= 0){
    //  var_dump($donnees);
      // on supprime les espaces entre les caractères
      $text= str_replace (' ','',$donnees['id']);
      $valeur = $_POST['quantiteTot-'.$text];
      //var_dump($valeur);
      // Si une valeur existe pour l'article
      if($valeur != (0 && null)){
        echo "<tr>";
        echo "<td scope='row'>".$donnees['designation']."</td>".
            "<td scope ='row'>".$valeur."</td>".
            "<td scope='row' name='type'>".$donnees['type']."</td>".
            "<td scope='row'>".$donnees['format']."</td>".
            "<td scope='row'>".$donnees['prix_unitaire']."€</td>";
        echo "</tr>";
            // on calcule le poids par rapport a la quantité
            $poids = $dcc->calculerPoids($donnees['poids_unitaire'],$valeur);
            // On calcule le poids total
            $poidsTotal = $dcc->calculerPoidsTotal($poids);
            // on calcule le prix par rapport a la quantité
            $prix = $dcc->calculerPrix($donnees['prix_unitaire'],$valeur);
            // On calcule le prix total
            $prixTotal = $dcc->calculerPrixTotal($prix);
            // On calcule le nombre total d'articles
            $quantiteTotal = $dcc->calculerQuantite($valeur);
            // On crée l'objet du détail de commande
            $objetDetail = ([
              'idArt' => (int)$donnees['id'],
              'quantite' => (int)$valeur,
              'format' => $donnees['format'],
              'designation' => $donnees['designation'],
              'type' => $donnees['type'],
              'poids' => (float)$poids,
              'prix' => (int)$prixTotal
            ]);
          //  $dcc->calculerPrixTotal($prix);
          $pot++;
          $_SESSION['valeurpot'] = $pot; // $pot = 5 // pot => 5 = objet
          $_SESSION['pot'][$pot] = $objetDetail;

      }
    }

  } echo "</table></form><h2><span class='badge badge-light' style='font-size:15'>Prix total de la commande : <span style='color:red'>".$prixTotal."</span>€</span>";
        echo "<form method='post' action='detail_commande.php' role=''><button type='submit' name='confirmer' class='btn btn-primary'/>Valider mes choix</button></form>
        <form method='post' action='accueil.php' role=''><button type='submit' name='valider' class='btn btn-primary'/>Annuler la commande et revenir a l'accueil</button></form>
             </section>";
  // On vérifie qu'au moins un article est commandée
  if($pot == 0){
    header('Location:accueil.php?erreur');
  }
  //var_dump($prixTotal,$poidsTotal,$quantiteTotal,$id,$valeur);
  // on crée l'objet de la commande
  $data = ([
    'quantiteTotale' => (int)$quantiteTotal,
    'idClient' => (int)$id,
    'prix' => (int)$prixTotal,
    'poidsTotal' => (float)$poidsTotal,
    'statut' => 'En cours'
  ]);
  // On met les valeurs de la commande en session
  $_SESSION['data'] = $data;
  $_SESSION['idCom'] = $co->getId();

}
else{    ?>
  <section class="container login-form">
    <h2> Nouvelle commande </h2>
      <section>
<?php
echo $entete;
  // Sinon on affiche le formulaire de commande
            $i=0;
            // Génere un tableau avec tous les articles
            foreach ($art as $donnees){
              if(!$donnees['quantite_stock'] <= 0){
                $text= str_replace (' ','',$donnees['id']);
              //   var_dump($text);
                  echo "<tr>";
                    echo "<td scope='row'>".$donnees['designation']."</td>".
                        "<td><input type='user' name='quantiteTot-".$text."' class='form-control' value='0'/></td>".
                        "<td scope='row'>".$donnees['type']."</td>".
                        "<td scope='row'>".$donnees['format']."</td>".
                        "<td scope='row'>".$donnees['prix_unitaire']."€</td>";
                    echo "</tr>";

              }$i++;
            }
            // on recupère le nombre de boucle qu'on a executée
            $_SESSION['i']=$i;?>
          </tbody>
        </table>
        <button type="submit" name="terminer" class="btn btn-primary btn-block">Passer la commande</button>
      </form>
    </section>
      <a href='accueil.php' name="annuler" class="btn btn-link">Annuler</a>
  </section>
<?php }
