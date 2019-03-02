<?php
require "connect/connect.php";
require "connect/header.php";
session_start();
$id=$_SESSION["id"];
$em = new UserManager($bdd);
//var_dump ($em->getClient($id));
$enCours= $em->getUser($id);
//var_dump($enCours);
if(isset($_POST['update'])){
  if ((htmlspecialchars($_POST['psw1']) == htmlspecialchars($_POST['psw2']))){
    $psw = trim(htmlspecialchars($_POST['psw1']));
      // On crée un tableau avec tous les champs
      $donnees = ([
        "id" => $_POST['id'],
        "nom" => trim(htmlspecialchars($_POST['nom'])),
        "prenom" => trim(htmlspecialchars($_POST['prenom'])),
        "dateNaiss" => $_POST['dateNaiss'],
        "adresse" => trim(htmlspecialchars($_POST['adresse'])),
        "codePostal" => (int)$_POST['codePostal'],
        "ville" => trim($_POST['ville']),
        "pays" => trim($_POST['pays']),
        "mail" => trim(htmlspecialchars($_POST['mail'])),
        "telephone" => (int)$_POST['telephone'],
        "societe" => trim(htmlspecialchars($_POST['societe'])),
        "login" => trim(htmlspecialchars($_POST['login'])),
        "psw" => $psw,
        "type" => ucfirst(trim(htmlspecialchars($_POST['type'])))
      ]);
      $detail = new User($donnees);
     // var_dump($detail);exit();
        // On envoi ce tableau a la méthode "mettre a jours"
        if(!$em->updateUser($detail)){
          header('Location:accueil.php?succes');
        }else{
          echo "<div class='alert alert-danger' role='alert'>
          Une erreur s'est produite, veuillez vérifier que tous les champs sont remplis correctement. En cas d'échec à nouveau, veuillez contacter l'administrateur.</div>";
        }
  }
}elseif($enCours['type'] == 'Admin'){
  // Si on modifie un utilisateur
  if(isset($_POST['Modifier'])){
    // Si on a l'id de l'utilisateur a modifier dans l'url alors on modifie celui la
    if(isset($_GET['id'])){
      $id = $_GET['id'];
    }
    $enCours= $em->getUser($id);
    // Formulaire pour modifier l'utilisateur
    ?><body>
      <section class="container login-form">
        <section>
          <form method="post" action="infosUser.php" role="update">
            <h1> Informations personnels </h1>
            <div class="form-group">
              <input type="user" name="nom" required class="form-control" value="<?php echo $enCours['nom'];?>" />
            </div>
            <div class="form-group">
              <input type="user" name="prenom" required class="form-control" value="<?php echo $enCours['prenom'];?>" />
            </div>
            <div class="form-group">
              <u>Date de naissance </u>
              <input type="date" name="dateNaiss" required class="form-control" value="<?php echo $enCours['dateNaiss'];?>" />
            </div>
            <div class="form-group">
              <input type="text" name="adresse" required class="form-control" placeholder="Adresse" value="<?php echo $enCours['adresse'];?>"/>
            </div>
            <div class="form-group">
              <input type="text" name="codePostal" required class="form-control" placeholder="Code postal" value="<?php echo $enCours['codePostal'];?>"/>
            </div>
            <div class="form-group">
              <input type="text" name="ville" required class="form-control" placeholder="Ville" value="<?php echo $enCours['ville'];?>"/>
            </div>
            <div class="form-group">
              <input type="text" name="pays" required class="form-control" placeholder="Pays" value="<?php echo $enCours['pays'];?>"/>
            </div>
            <div class="form-group">
              <input type="user" name="telephone" required class="form-control" maxlength="10" placeholder="Numéro de téléphone" value="<?php echo $enCours['telephone'];?>"/>
            </div>
            <div class="form-group">
              <input type="user" name="mail" required class="form-control" placeholder="Adresse mail" value="<?php echo $enCours['mail'];?>"/>
            </div>
            <div class="form-group">
              <input type="user" name="societe" class="form-control" placeholder="Societe" value="<?php echo $enCours['societe'];?>"/>
            </div>
            <div class="form-group">
              <input type="user" name="type" required class="form-control" maxlength="10" placeholder="type" value="<?php echo $enCours['type'];?>"/>
            </div>
            <div class="form-group">
              <input type="user" name="login" required class="form-control" placeholder="Nom d'utilisateur" value="<?php echo $enCours['login'];?>" />
            </div>
            <div class="form-group">
              <input type="password" name="psw1" required class="form-control" placeholder="Mot de passe" />
              <span class="glyphicon glyphicon-lock"></span>
            </div>
            <div class="form-group">
              <input type="password" name="psw2" required class="form-control" placeholder="Mot de passe" />
              <span class="glyphicon glyphicon-lock"></span>
            </div>
             <input type="hidden" name="id" required class="form-control" value="<?php echo $id;?>" />
            <button type="submit" name="update" class="btn btn-primary btn-block">Modifier</button>
          </form>
      <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    </body><?php

  }

// Confirmation de la suppression d'utilisateur
  elseif(isset($_POST['ConfirmSupp'])){
    //var_dump($_GET['id']);
    $id = $_GET['id'];
    if($em->delete((int)$id)){
      header('Location:infosUser.php');
    }

  }

  elseif(isset($_POST['Supprimer'])){
    //var_dump($_GET['id']);
    $id = $_GET['id'];
    echo "<div class='alert alert-danger' role='alert' style='text-align:center'>Etes vous sur de vouloir supprimer cet utilisateur?</div>";
    echo '</form><form method="post" action="infosUser.php?&id='.$id.'" role="suppression"><td><button type="submit" name="ConfirmSupp" class="btn btn-danger btn-block">Oui</button></form>';
    echo '</form><form method="post" action="infosUser.php" role="annuler"><td><button type="submit" class="btn btn-primary btn-block">Non</button></form>';
    //var_dump($_SESSION['type']);
  }

  // Créer un nouvel utilisateur
  elseif(isset($_POST['newUser'])){
      $role = $em->getAllUser();
      ?>
      <body>
        <section class="container login-form">
          <section>
          </form><form method="post" action="index.php" role="inscription">
              <h1> Inscription </h1>
              <div class="form-group">
                <input type="user" name="nom" required class="form-control" placeholder="Nom" />
              </div>
              <div class="form-group">
                <input type="user" name="prenom" required class="form-control" placeholder="Prenom" />
              </div>
              <div class="form-group">
                <b>Date de naissance </b>
                <input type="date" name="dateNaiss" required class="form-control" />
              </div>
              <div class="form-group">
                <input type="text" name="adresse" required class="form-control" placeholder="Votre adresse" />
              </div>
              <div class="form-group">
                <input type="text" name="codePostal" required class="form-control" maxlength="5" placeholder="Votre code postal" />
              </div>
              <div class="form-group">
                <input type="text" name="ville" required class="form-control" placeholder="Votre ville" />
              </div>
              <div class="form-group">
                <input type="text" name="pays" required class="form-control" placeholder="Votre pays" />
              </div>
              <div class="form-group">
                <input type="user" name="telephone" required class="form-control" maxlength="10" placeholder="Votre numéro de téléphone" />
              </div>
              <div class="form-group">
                <input type="user" name="mail" required class="form-control" placeholder="Votre adresse mail" />
              </div>
              <div class="form-group">
                <b>Role de l'employes :</b>
                  <SELECT name="role" size="1">
                    <option valeur="Preparateur" selected>Preparateur</option>
                    <option valeur="Administrateur">Administrateur</option>
                  </SELECT>
              </div>
              <div class="form-group">
                <input type="user" name="login" required class="form-control" placeholder="Votre nom d'utilisateur" />
              </div>
              <div class="form-group">
                <input type="password" name="psw1" required class="form-control" placeholder="Votre mot de passe" />
              </div>
              <div class="form-group">
                <input type="password" name="psw2" required class="form-control" placeholder="Votre mot de passe" />
              </div>
              <button type="submit" name="inscription" class="btn btn-primary btn-block">Inscription</button>
            </form>
          </section>
            <a href='accueil.php' name="annuler" class="btn btn-link">Annuler</a>
        </section>
      </body>
      </html>
      <?php
  }
  // Consulter la liste des utilisateurs
  elseif((isset($_POST['users']))OR(isset($_GET['trie']))OR(isset($_GET['trieRole']))OR(isset($_GET['trieNom']))OR(isset($_GET['triePrenom']))){
    ?>
    <section class="container login-form">
      <section>
        <table class="table table-dark table-striped" >
          <h2>Listes des utilisateurs</h2>
          <thead>
            <tr>
              <th scope="col"><form method="post" action="infosUser.php?trieNom" role="user"><button type="submit" name="trie" class="btn btn-link btn-block">Nom</button></form></th>
              <th scope="col"><form method="post" action="infosUser.php?triePrenom" role="user"><button type="submit" name="trie" class="btn btn-link btn-block">Prenom</button></form></th>
              <th scope="col"><form method="post" action="infosUser.php?trie" role="user"><button type="submit" name="trie" class="btn btn-link btn-block">Type</button></form></th>
              <th scope="col"><form method="post" action="infosUser.php?trieRole" role="user"><button type="submit" name="trieRole" class="btn btn-link btn-block">Role</button><form></th>
              <th scope="col" colspan="4" style="text-align:center">Action</th>
            </tr>
          </thead><?php

    if((isset($_GET['trie']))OR(isset($_GET['trieRole']))OR(isset($_GET['triePrenom']))OR(isset($_GET['trieNom']))OR(isset($_GET['trieType']))){
      $Users = $em->getAllUser();

      if((isset($_GET['trie']))&&((!isset($_SESSION['valeurType'])) OR ($_SESSION['valeurType'] == 2))){
        array_multisort( array_column($Users, "type"), SORT_ASC, $Users );
        $_SESSION['valeurType'] = 1;
      }elseif((isset($_GET['trie']))&&($_SESSION['valeurType'] == 1)){
          array_multisort( array_column($Users, "type"), SORT_DESC, $Users );
          $_SESSION['valeurType'] = 2;
      }
      if((isset($_GET['trieRole']))&&((!isset($_SESSION['valeurRole'])) OR ($_SESSION['valeurRole'] == 2))){
        array_multisort( array_column($Users, "role"), SORT_DESC, $Users );
        $_SESSION['valeurRole'] = 1;
      }elseif((isset($_GET['trieRole']))&&($_SESSION['valeurRole'] == 1)){
        array_multisort( array_column($Users, "role"), SORT_ASC, $Users );
        $_SESSION['valeurRole'] = 2;
      }
      if((isset($_GET['trieNom']))&&((!isset($_SESSION['valeurNom'])) OR ($_SESSION['valeurNom'] == 2))){
        array_multisort( array_column($Users, "nom"), SORT_DESC, $Users );
        $_SESSION['valeurNom'] = 1;
      }elseif((isset($_GET['trieNom']))&&($_SESSION['valeurNom'] == 1)){
        array_multisort( array_column($Users, "nom"), SORT_ASC, $Users );
        $_SESSION['valeurNom'] = 2;
      }
      if((isset($_GET['triePrenom']))&&((!isset($_SESSION['valeurPrenom'])) OR ($_SESSION['valeurPrenom'] == 2))){
        array_multisort( array_column($Users, "prenom"), SORT_DESC, $Users );
        $_SESSION['valeurPrenom'] = 1;
      }elseif((isset($_GET['triePrenom']))&&($_SESSION['valeurPrenom'] == 1)){
        array_multisort( array_column($Users, "prenom"), SORT_ASC, $Users );
        $_SESSION['valeurPrenom'] = 2;
      }

      //var_dump($Users['type']);
      foreach($Users as $us){
        echo "<tr>";
        echo "<td scope='col'>".$us['nom']."</td>";
        echo "<td scope='col'>".$us['prenom']."</td>";
        echo "<td scope='col'>".$us['type']."</td>";
        echo "<td scope='col'>".$us['role']."</td>";
        echo '<td scope="col"></form><form method="post" action="infosUser.php?id='.$us['id'].'" role="user"><td><button type="submit" name="Supprimer" class="btn btn-danger btn-block">Supprimer</button></form></td>';
        echo '<td scope="col"></form><form method="post" action="infosUser.php?id='.$us['id'].'" role="user"><td><button type="submit" name="Modifier" class="btn btn-primary btn-block">Modifier</button></form></td>';
        echo "</tr>";
      }

    }
    else{
    $Users = $em->getAllUser();
    // tableau des utilisateurs avec la fonction " modifier " / " supprimer "
    ?>

      <?php foreach($Users as $us){
        echo "<tr>";
        echo "<td scope='col'>".$us['nom']."</td>";
        echo "<td scope='col'>".$us['prenom']."</td>";
        echo "<td scope='col'>".$us['type']."</td>";
        echo "<td scope='col'>".$us['role']."</td>";
        echo '<td scope="col"></form><form method="post" action="infosUser.php?id='.$us['id'].'" role="user"><td><button type="submit" name="Supprimer" class="btn btn-danger btn-block">Supprimer</button></form></td>';
        echo '<td scope="col"></form><form method="post" action="infosUser.php?id='.$us['id'].'" role="user"><td><button type="submit" name="Modifier" class="btn btn-primary btn-block">Modifier</button></form></td>';
        echo "</tr>";
      }
      ?></table>
  <?php
    }
  }
  else{
    ?><body>
      <form method="post" action="" role="login">
      </form>
        <section class="container login-form">
          <section>
            <form method="post" action="" role="login">
              <button type="submit" name="newUser" class="btn btn-primary">Ajouter un employé</button>
              <button type="submit" name="users" class="btn btn-primary">Consulter la liste des utilisateurs</button>
            </form>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
    </html><?php
  }

}
else{
  // informations personnel du client
    ?><body>
    	<section class="container login-form">
    		<section>
    			<form method="post" action="" role="update">
            <h1> Informations personnels </h1>
    				<div class="form-group">
    					<input type="user" name="nom" required class="form-control" value="<?php echo $enCours['nom'];?>" />
    				</div>
            <div class="form-group">
    					<input type="user" name="prenom" required class="form-control" value="<?php echo $enCours['prenom'];?>" />
    				</div>
            <div class="form-group">
              <u>Date de naissance </u>
    					<input type="date" name="dateNaiss" required class="form-control" value="<?php echo $enCours['dateNaiss'];?>" />
    				</div>
            <div class="form-group">
    					<input type="text" name="adresse" required class="form-control" placeholder="Votre adresse" value="<?php echo $enCours['adresse'];?>"/>
    				</div>
            <div class="form-group">
              <input type="text" name="codePostal" required class="form-control" placeholder="Votre code postal" value="<?php echo $enCours['codePostal'];?>"/>
            </div>
            <div class="form-group">
              <input type="text" name="ville" required class="form-control" placeholder="Votre ville" value="<?php echo $enCours['ville'];?>"/>
            </div>
            <div class="form-group">
              <input type="text" name="pays" required class="form-control" placeholder="Votre pays" value="<?php echo $enCours['pays'];?>"/>
            </div>
            <div class="form-group">
    					<input type="user" name="telephone" required class="form-control" maxlength="10" placeholder="Votre numéro de téléphone" value="<?php echo $enCours['telephone'];?>"/>
    				</div>
            <div class="form-group">
    					<input type="user" name="mail" required class="form-control" placeholder="Votre adresse mail" value="<?php echo $enCours['mail'];?>"/>
    				</div>
            <div class="form-group">
    					<input type="user" name="societe" required class="form-control" placeholder="Votre societe" value="<?php echo $enCours['societe'];?>"/>
    				</div>
            <div class="form-group">
    					<input type="user" name="login" required class="form-control" placeholder="Votre nom d'utilisateur" value="<?php echo $enCours['login'];?>" />
    				</div>
            <div class="form-group">
              <input type="password" name="psw1" required class="form-control" placeholder="Votre mot de passe" />
              <span class="glyphicon glyphicon-lock"></span>
            </div>
    				<div class="form-group">
    					<input type="password" name="psw2" required class="form-control" placeholder="Votre mot de passe" />
    					<span class="glyphicon glyphicon-lock"></span>
    				</div>

    				<button type="submit" name="update" class="btn btn-primary btn-block">Modifier</button>
    			</form>

<?php
}?>
</table>
</section>
  <a href='accueil.php' name="annuler" class="btn btn-link">Retour Accueil</a>
</section>
