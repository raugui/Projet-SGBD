<?php
require "connect/connect.php";
session_start();
require "connect/header.php";
$m = new UserManager($bdd);

if(isset($_POST['inscription'])){
		if ((htmlspecialchars($_POST['psw1']) == htmlspecialchars($_POST['psw2']))){
				// On crée un tableau avec tous les champs
				$role = null;
			//	var_dump($_POST['role']);
				if($_POST['role']):$role = utf8_decode(htmlspecialchars($_POST['role']));endif;
			//	var_dump($role);exit();
				$donnees = ([
					"nom" => utf8_decode(htmlspecialchars($_POST['nom'])),
					"prenom" => utf8_decode(htmlspecialchars($_POST['prenom'])),
					"dateNaiss" => $_POST['dateNaiss'],
					"adresse" => utf8_decode(htmlspecialchars($_POST['adresse'])),
					"codePostal" => (int)$_POST['codePostal'],
					"role" => $role,
					"ville" => utf8_decode(htmlspecialchars($_POST['ville'])),
					"pays" => utf8_decode(htmlspecialchars($_POST['pays'])),
					"mail" => utf8_decode(htmlspecialchars($_POST['mail'])),
					"telephone" => (int)$_POST['telephone'],
					"login" => trim(htmlspecialchars($_POST['login'])),
					"psw" => trim(htmlspecialchars($_POST['psw1'])),
					"type" => 'Employes'
				]);
				$client = new User($donnees);
			//	var_dump($donnees);
				// si le nom d'utilisateur n'existe pas
			if(!$m->getUser($client->login())){
					// On envoi ce tableau a la méthode "ajouter"
				//	var_dump($client);
					if(!$m->add($client)){
						$Type = $m->getUser($_SESSION['id']);
						if($Type['type'] =! 'Administrateur'){
							header('Location:index.php?succes');
						}else{
							header('Location:accueil.php');
						}
					}else{
						echo "<div class='alert alert-danger' role='alert'>
						Une erreur s'est produite.</div>";
					}
			}else{
				echo "<div class='alert alert-danger' role='alert'>
				Une erreur s'est produite, le nom d'utilisateur existe deja.</div>";
			}
		}
		else{
			echo "<div class='alert alert-danger' role='alert'>Les mots de passes ne correspondent pas.</div>";
		}
}
else{
	?>
	<body>
		<section class="container login-form">
			<section>
				<form method="post" action="" role="login">
					<img src="assets/images/880803.png" alt="" class="img-login"/>

					<div class="form-group">
						<input type="user" name="user" required class="form-control" placeholder="Votre nom d'utilisateur" />
						<span class="glyphicon glyphicon-user"></span>
					</div>

					<div class="form-group">
						<input type="password" name="password" required class="form-control" placeholder="Votre mot de passe" />
						<span class="glyphicon glyphicon-lock"></span>
					</div>

					<button type="submit" name="connexion" class="btn btn-primary btn-block">Connexion</button>

					<a href="index.php?oublie">Mot de passe oublié ?</a> ou <a href="Formulaire_inscription.php"><span style="color:green">Inscription client</span></a>
				</form>
			</section>
		</section>
		<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	</body>
	</html>
<?php
}
// Lorsque le bouton connexion est pressé
if(isset($_POST['connexion'])){
	// On crée une instance de la class utilisateur
	$em = new UserManager($bdd);
	//var_dump($_POST);
	/* Si l'utilisateur et le mot de passe introduit correspondent */
	if ($em->exists($_POST['user'],md5($_POST['password']))){
		// On récupère l'id correspondant au nom d'utilisateur et mot de passe
		$id = $em->getId($_POST['user'],md5($_POST['password']));
		// on envoi en session le type d'utilisateur
		$_SESSION['type']=$em->getUser($id)['type'];
			/* Si on est un employes, on verifie dans le choix + la session */
			// on transmet l'id dans l'url de la page suivante
			$_SESSION['id']=$id;
			header ('Location:accueil.php'); // Redirection
			}else{
			echo "<div class='alert alert-danger' role='alert'>
			Identifiant ou mot de passe incorrect. En cas d'échec à nouveau, veuillez contacter l'administrateur.</div>";
			}
}

// si mot de passe oublié
if(isset($_GET['oublie'])){
	echo "<div class='alert alert-danger' role='alert'>
	Veuillez contacter l'administrateur pour vous attribuez un nouveau mot de passe.</div>";
}
if(isset($_GET['succes'])){
	echo "<div class='alert alert-success' role='alert'>
	Votre inscription à été éffectué avec succès.</div>";
}



?>
