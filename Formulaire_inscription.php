<?php require "connect/connect.php";
require "connect/header.php";
$m = new UserManager($bdd);
?>
<body>
	<section class="container login-form">
		<section>
			<form method="post" action="" role="inscription">
        <h1> Inscription </h1>
				<div class="form-group">
					<input type="user" name="nom" required class="form-control" placeholder="Nom" />
				</div>
        <div class="form-group">
					<input type="user" name="prenom" required class="form-control" placeholder="Prenom" />
				</div>
        <div class="form-group">
          <u>Date de naissance </u>
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
					<input type="user" name="societe" class="form-control" placeholder="Votre societe" />
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
			<a href='index.php' name="annuler" class="btn btn-link">Annuler</a>
	</section>
</body>
</html>
<?php
if(isset($_POST['inscription'])){
	if ((htmlspecialchars($_POST['psw1']) == htmlspecialchars($_POST['psw2']))){
			// On crée un tableau avec tous les champs
			$donnees = ([
				"nom" => utf8_decode(htmlspecialchars($_POST['nom'])),
				"prenom" => utf8_decode(htmlspecialchars($_POST['prenom'])),
				"dateNaiss" => $_POST['dateNaiss'],
				"adresse" => utf8_decode(htmlspecialchars($_POST['adresse'])),
				"codePostal" => (int)$_POST['codePostal'],
				"ville" => utf8_decode(htmlspecialchars($_POST['ville'])),
				"pays" => utf8_decode(htmlspecialchars($_POST['pays'])),
				"mail" => utf8_decode(htmlspecialchars($_POST['mail'])),
				"telephone" => (int)$_POST['telephone'],
				"societe" => utf8_decode(htmlspecialchars($_POST['societe'])),
				"login" => trim(htmlspecialchars($_POST['login'])),
				"psw" => trim(htmlspecialchars($_POST['psw1'])),
				"type" => 'Client'
			]);
			$client = new User($donnees);
		//	var_dump($donnees);
			// si le nom d'utilisateur n'existe pas
		if(!$m->getUser($client->login())){
				// On envoi ce tableau a la méthode "ajouter"
			//	var_dump($client);
				if($m->add($client)){
					header('Location:index.php?succes');
				}else{
					echo "<div class='alert alert-danger' role='alert'>
					Une erreur s'est produite, veuillez vérifier que les champs sont remplis correctement.</div>";
				}

		}else{
			echo "<div class='alert alert-danger' role='alert'>
			Une erreur s'est produite, le nom d'utilisateur existe deja et/ou est éronné.</div>";
		}
	}
	else{
		echo "<div class='alert alert-danger' role='alert'>Les mots de passes ne correspondent pas.</div>";
	}
}
 ?>
