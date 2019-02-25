<?php
require ('connect/connect.php');
require "connect/header.php";
session_start();
// Récupère l'id de l'utilisateur dans la session
$id=$_SESSION["id"];
$em = new UserManager($bdd);

$enCours= $em->getUser($id);
// On verifie quel type d'utilisateur
if($_SESSION['type']=='Employes'){
//  var_dump($enCours);
  // informations personnel du client
  if(isset($_POST['commandesEnCours'])){
  header('Location: commandes_effectues.php?enCours');
  }

  // Consulter les commandes
  elseif(isset($_POST['newCommandeFini'])){
    header('Location: commandes_effectues.php?terminee');
  }
  // Créer une commande
  elseif(isset($_POST['stock'])){
    header('Location:stock.php');
  }elseif(isset($_POST['deco'])){
    session_destroy();
    header('Location:index.php');
  }

  // Si aucune action , afficher l'accueil
  else{
  ?>
  <body>
    <form method="post" role="login">
      <h4 align="right">Bonjour <?php echo $enCours['nom'].' '.$enCours['prenom']?>
      </br><button name="deco" align="right" class="btn btn-info">Deconnexion</button></h4>
    </form>
    	<section class="container login-form">
    		<section>
          <form method="post" role="login">
            <button type="submit" name="commandesEnCours" class="btn btn-primary" >Consulter les commandes en cours</button></br>
            <button type="submit" name="newCommandeFini" class="btn btn-primary">Consulter les commandes terminées</button>
            <button type="submit" name="stock" class="btn btn-primary">Consulter le stock</button>
          </form>
    		</section>
    	</section>
    	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>
  </html>
  <?php
  }
}elseif($_SESSION['type'] == 'Client'){
//  var_dump($enCours);
  // si un utilisateur se déconnecte, on détruit la session
  if(isset($_POST['deco'])){
    session_destroy();
    header('Location:index.php');
  }

  // informations personnel du client
  if(isset($_POST['infos'])){
  header('Location: infosUser.php');
  }

  // Consulter les commandes terminée
  elseif(isset($_POST['commandest'])){
    header('Location: commandes_effectues.php?terminee');
  }

  // Consulter les commandes
  elseif(isset($_POST['commandes'])){
    header('Location: commandes_effectues.php');
  }
  // Créer une commande
  elseif(isset($_POST['newCommande'])){
    header('Location:formulaire_commande.php');
  }

  // Si aucune action , afficher l'accueil
  else{
  ?>
  <body>
    <form method="post" role="login">
      <h4 align="right">Bonjour <?php echo $enCours['nom'].' '.$enCours['prenom']?>
      </br><button name="deco" align="right" class="btn btn-info">Deconnexion</button></h4>
    </form>
    	<section class="container login-form">
    		<section>
          <form method="post" action="" role="login">
            <button type="submit" name="commandes" class="btn btn-primary" >Consulter mes commandes en cours</button></br>
            <button type="submit" name="commandest" class="btn btn-primary" >Historique de mes commandes terminées</button></br>
            <button type="submit" name="newCommande" class="btn btn-primary">Effectuer une commande</button>
            <button type="submit" name="infos" class="btn btn-primary">Consulter mes informations personnelles</button>
          </form>
    		</section>
    	</section>
    	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>
  </html>
  <?php }
  if(isset($_GET['succes'])){
  	echo "<div class='alert alert-success' role='alert'>
  	Vos modifications ont bien été enregistrées.</div>";
  }
  if(isset($_GET['erreur'])){
    echo "<div class='alert alert-danger' role='alert'>
  	Vous avez commandez 0 articles. Votre commande n'est donc pas prise en compte. Veuillez retourner sur le formulaire si vous voulez commander.</div>";
  }
}elseif($_SESSION['type'] == 'Admin'){
  // si un utilisateur se déconnecte, on détruit la session
  if(isset($_POST['deco'])){
    session_destroy();
    header('Location:index.php');
  }

  // informations personnel de l'admin
  if(isset($_POST['infos'])){
  header('Location: infosUser.php?infoPerso');
  }

  // Consulter les commandes terminée
  elseif(isset($_POST['commandest'])){
    header('Location: commandes_effectues.php?terminee');
  }

  // Consulter les commandes en cours
  elseif(isset($_POST['commandes'])){
    header('Location: commandes_effectues.php?enCours');
  }

  // Consulter le stock
  elseif(isset($_POST['stock'])){
    header('Location:stock.php');
  }

  // Consulter la liste des utilisateurs
  elseif(isset($_POST['users'])){
    header('Location:infosUser.php');
  }

  else{
  ?>
  <body>
    <form method="post" action="" role="login">
      <h4 align="right">Bonjour <?php echo $enCours['nom'].' '.$enCours['prenom']?>
      </br><button name="deco" align="right" class="btn btn-info">Deconnexion</button></h4>
    </form>
    	<section class="container login-form">
    		<section>
          <form method="post" action="infosUser.php" role="login">
            <button type="submit" name="Modifier" class="btn btn-primary">Consulter mes informations personnelles</button>
          </form><form method="post" action="" role="login">
            <button type="submit" name="commandes" class="btn btn-primary" >Consulter les commandes en cours</button></br>
            <button type="submit" name="commandest" class="btn btn-primary">Consulter les commandes terminées</button>
            <button type="submit" name="stock" class="btn btn-primary">Consulter le stock</button>
            <button type="submit" name="users" class="btn btn-primary">Gérer les utilisateurs</button>
          </form>
    		</section>
    	</section>
    	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>
  </html>


<?php }
}
