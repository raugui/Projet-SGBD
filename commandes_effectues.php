<?php
require "connect/connect.php";
session_start();
require "connect/header.php";
// On garde l'id de l'utilisateur connecté
$id = $_SESSION["id"];
// on garde le type d'utilisateur connecté
$type = $_SESSION['type'];
// La quantité totale d'éléments dans le tableau
$co = new CommandesManager($bdd);
$user = new UserManager($bdd);
$art = new ArticlesManager($bdd);
$dc = new DetailCommandesManager($bdd);
//var_dump($id);
//var_dump($listes);
$TypeUser = $user->getUser($id);

$PrixTotalDeLaCommande = 'Prix total de la commande';
$QuantiteTotaleArticle = 'Quantite totale darticles';
$PoidsTotalCommande = 'Poids total de la commande';
//var_dump($co->getListId($id));
// Si suppression commande en cours
if (isset($_POST['deleteC'])) {
    //var_dump($_GET['id']);
    $id = $_GET['id'];
    echo "<div class='alert alert-danger' role='alert' style='text-align:center'>Etes vous sur de vouloir supprimer cette commande?</div>";
    echo '</form><form method="post" action="commandes_effectues.php?enCours&id=' . $id . '" role="suppression"><td><button type="submit" name="ConfirmDeleteC" class="btn btn-danger btn-block">Oui</button></form>';
    echo '</form><form method="post" action="commandes_effectues.php?enCours" role="commandes"><td><button type="submit" class="btn btn-primary btn-block">Non</button></form>';
    exit();
}// Si suppression commande terminee
if (isset($_POST['deleteT'])) {
    $id = $_GET['id'];
    echo "<div class='alert alert-danger' role='alert' style='text-align:center'>Etes vous sur de vouloir supprimer cette commande?</div>";
    echo '</form><form method="post" action="commandes_effectues.php?terminee&id=' . $id . '" role="suppression"><td><button type="submit" name="ConfirmDeleteT" class="btn btn-danger btn-block">Oui</button></form>';
    echo '</form><form method="post" action="commandes_effectues.php?terminee" role="commandes"><td><button type="submit" class="btn btn-primary btn-block">Non</button></form>';
    exit();
// Commande terminée, confirmation de suppression
}if (isset($_POST['ConfirmDeleteT'])) {
    $idCom = $_GET['id']; 
    $co->delete((int) $idCom);
    header('Location:commandes_effectues.php?terminee');
}
// Commande en cours, confirmation de suppression
if (isset($_POST['ConfirmDeleteC'])) {
    $idCom = $_GET['id'];
    $com = $co->getCommande($idCom);
    $detailCom = $dc->getListId($idCom);
    foreach ($detailCom as $detc){
        // on ajoute en stock les produits annulés
        $art->restock($detc['quantite'], $detc['idArt']);
    } 
    $co->delete((int) $idCom);
    header('Location:commandes_effectues.php?enCours');
}
?>
<section class="container login-form">
    <section>
        <form method="post" action="accueil.php" role="commandes">
            <?php if ($TypeUser['type'] == 'Client'): echo '<h1> Vos commandes </h1>';
            endif ?>
            <?php if ($TypeUser['type'] == 'Employes' OR $TypeUser['type'] == 'Admin'): echo '<h1> Les Commandes </h1>';
            endif ?>
            <table class="table table-dark table-striped" >
                <thead>
                    <tr>
                        <th scope="col">Ordre</th>
                        <th scope="col">
                        <?php if((isset($_GET['terminee']))&&($TypeUser['type']== 'Admin')){
                          echo '</form><form method="post" action="commandes_effectues.php?terminee&trieArt" role="user">';
                          echo $QuantiteTotaleArticle;
                          echo '<button type="submit" name="trieArt" class="btn btn-link btn-block"></button></form>';
                        }else{
                          echo $QuantiteTotaleArticle;
                        }?>
                      </th>

                        <th scope="col">Statut de la commande</th>

                        <?php if (($TypeUser['type'] == 'Client') OR ( $TypeUser['type'] == 'Admin')){
                          echo '<th scope="col">';
                          if((isset($_GET['terminee']))&&($TypeUser['type']== 'Admin')){
                            echo '</form><form method="post" action="commandes_effectues.php?terminee&triePrix" role="user"><button type="submit"
                            name="triePrix" class="btn btn-link btn-block">';
                            echo $PrixTotalDeLaCommande;
                            echo '</button></form>';
                          }
                          else{
                            echo $PrixTotalDeLaCommande;
                          }
                          echo '</th>';
                          } ?>


                        <?php if (($TypeUser['type'] == 'Client') OR ( $TypeUser['type'] == 'Admin')){
                          echo '<th scope="col">';
                          if((isset($_GET['terminee']))&&($TypeUser['type']== 'Admin')){
                            echo '</form><form method="post" action="commandes_effectues.php?terminee&triePoids" role="user">
                            <button type="submit" name="triePoids" class="btn btn-link btn-block">';
                            echo $PoidsTotalCommande;
                            echo '</button></form>';
                          }else{
                            echo $PoidsTotalCommande;
                          }
                          echo '</th>';
                        }
                        /* ------------------------------------------------------- */
                        /* -----------------------CLIENT-------------------------- */
                        /* ------------------------------------------------------- */
                        if ($TypeUser['type'] == 'Client') {
                            // Récupère la liste des commandes de l'utilisateur
                            $listes = $co->getListId($id);
                            if (!$listes) {
                                echo "<div class='alert alert-danger' role='alert'>Vous n'avez pas de commandes.</div>";
                            } else {
                                ?>            <th scope="col">Détail de la commande</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // On initialise un compteur pour afficher le numéro d'ordre dans le tableau
                            $cpt = 1;
                            if (isset($_GET['terminee'])) {
                                // Génere un tableau avec tous les articles
                                foreach ($listes as $donnees) {
                                    $nCl = $co->getNomSocieteClientId($donnees['id'], $donnees['idClient']);
                                    //var_dump($donnees);
                                    if ($donnees['statut'] == 'terminee') {
                                        //    var_dump($donnees);
                                        echo "<tr>";
                                        echo "<td scope='row'>" . $cpt . "</td>";
                                        echo "<td scope='row'>" . (int) $donnees['quantiteTotale'] . "</td>" .
                                        "<td scope='row'>" . $donnees['statut'];
                                        echo "</td>" .
                                        "<td scope='row'>" . (int) $donnees['prix'] . "€</td>";
                                        echo "<td scope='row'>" . $donnees['poidsTotal'] . "kg</td>";
                                        echo "<td scope='row'>";
                                        if ((int) $donnees['statut'] == 0) {
                                            echo "<a href='detail_commande.php?id=" . $donnees['id'] . "' name='commande' class='btn btn-link'>Détail</a>";
                                        }
                                        echo "</td></tr>";
                                        $cpt++;
                                    }
                                }if ($cpt == 1) {
                                    echo "<div class='alert alert-danger' role='alert'>Il n'y a pas de commandes terminées.</div>";
                                }
                            } else {
                                // Génere un tableau avec tous les articles
                                foreach ($listes as $donnees) {
                                    if ($donnees['statut'] == 'En cours') {
                                        echo "<tr>";
                                        echo "<td scope='row'>" . $cpt . "</td>";
                                        echo "<td scope='row'>" . (int) $donnees['quantiteTotale'] . "</td>" .
                                        "<td scope='row'>" . $donnees['statut'];
                                        echo "</td>" .
                                        "<td scope='row'>" . (int) $donnees['prix'] . "€</td>";
                                        echo "<td scope='row'>" . $donnees['poidsTotal'] . "kg</td>";
                                        echo "<td scope='row'>";
                                        //var_dump($donnees['id']);
                                        if ((int) $donnees['statut'] == 0) {
                                            echo "<a href='detail_commande.php?id=" . $donnees['id'] . "' name='commande' class='btn btn-link'>Détail</a>";
                                        }
                                        echo "</td></tr>";
                                        $cpt++;
                                    }
                                }
                            }
                        }
                    }

                    /* ------------------------------------------------------- */
                    /* ---------------------EMPLOYES-------------------------- */
                    /* ------------------------------------------------------- */
                    elseif ($TypeUser['type'] == 'Employes') {
                        // On initialise un compteur pour afficher le numéro d'ordre dans le tableau
                        $cpt = 1;
                        // on récupère la liste de commandes
                        $commandes = $co->getList();
                        if (!$commandes) {
                            echo "<div class='alert alert-danger' role='alert'>Il n'y a pas de commandes.</div>";
                        } elseif (isset($_GET['enCours'])) {
                            ?>            <th scope="col">Détail de la commande</th>
                        <th scope="col">Nom du client</th>
                        </tr>
                        </thead> <?php
                        // Génere un tableau avec tous les articles
                        foreach ($commandes as $donnees) {
                            $nCl = $co->getNomSocieteClientId($donnees['id'], $donnees['idClient']);
                            $_SESSION['idCom'] = $donnees['id'];
                            if ($donnees['statut'] == 'En cours') {
                                //  var_dump($donnees);
                                echo "<tr>";
                                echo "<td scope='row'>" . $cpt . "</td>";
                                echo "<td scope='row'>" . (int) $donnees['quantiteTotale'] . "</td>" .
                                "<td scope='row'>";
                                if ((int) $donnees['statut'] == 'En cours') {
                                    $message = 'En cours de préparation';
                                    echo $message;
                                }
                                echo "<td scope='row'>" . $donnees['poidsTotal'] . "kg</td>";
                                echo "<td scope='row'>";
                                if ((int) $donnees['statut'] == 0) {
                                    echo "<a href='detail_commande.php?id=" . $donnees['id'] . "' name='commande' class='btn btn-link'>Détail</a>";
                                }
                                echo "<td scope='row'>" . $nCl['societe'];
                                echo "</td></tr>";
                                $cpt++;
                            }
                        } if ($cpt == 1) {
                            echo "<div class='alert alert-danger' role='alert'>Il n'y a pas de commandes en cours.</div>";
                        }
                    } elseif (isset($_GET['terminee'])) {
                        ?>            <th scope="col">Nom du client</th>
                        <th scope="col">Nom du préparateur</th>
                        </tr>
                        </thead> <?php
                        // Génere un tableau avec tous les articles
                        foreach ($commandes as $donnees) {
                            $_SESSION['idCom'] = $donnees['id'];
                            $nCl = $co->getNomSocieteClientId($donnees['id'], $donnees['idClient']);
                            //var_dump($donnees);
                            if ($donnees['statut'] == 'terminee') {
                                //  var_dump($nCl['societe']);
                                //  var_dump($donnees);
                                echo "<tr>";
                                echo "<td scope='row'>" . $cpt . "</td>";
                                echo "<td scope='row'>" . (int) $donnees['quantiteTotale'] . "</td>" .
                                "<td scope='row'>";
                                if ((int) $donnees['statut'] == 'terminee') {
                                    $message = 'terminée';
                                    echo $message;
                                }
                                echo "<td scope='row'>" . $donnees['poidsTotal'] . "kg</td>";
                                echo "<td scope='row'>" . $nCl['societe'] . "</td>";
                                echo "<td scope='row'>" . $donnees['Preparateur'];
                                echo "</td></tr>";
                                $cpt++;
                            }
                        }if ($cpt == 1) {
                            echo "<div class='alert alert-danger' role='alert'>Il n'y a pas de commandes terminées.</div>";
                        }
                    }//Fin du Get terminee
                }

                /* ------------------------------------------------------- */
                /* ------------------------ADMIN-------------------------- */
                /* ------------------------------------------------------- */ elseif ($TypeUser['type'] == 'Admin') {
                    // On initialise un compteur pour afficher le numéro d'ordre dans le tableau
                    $cpt = 1;
                    // on récupère la liste de commandes
                    $commandes = $co->getList();
                    if (!$commandes) {
                        echo "<div class='alert alert-danger' role='alert'>Il n'y a pas de commandes.</div>";
                    } elseif (isset($_GET['enCours'])) {
                        ?>            <th scope="col">Détail de la commande</th>
                        <th scope="col">Nom du client</th>
                        <th scope="col">Action</th>
                        </tr>
                        </thead> <?php
                // Génere un tableau avec tous les articles
                foreach ($commandes as $donnees) {
                    $nCl = $co->getNomSocieteClientId($donnees['id'], $donnees['idClient']);
                    $_SESSION['idCom'] = $donnees['id'];
                    if ($donnees['statut'] == 'En cours') {
                        //  var_dump($donnees);
                        echo "<tr>";
                        echo "<td scope='row'>" . $cpt . "</td>";
                        echo "<td scope='row'>" . (int) $donnees['quantiteTotale'] . "</td>" .
                        "<td scope='row'>";
                        if ((int) $donnees['statut'] == 'En cours') {
                            $message = 'En cours de préparation';
                            echo $message;
                        }
                        echo "<td scope='row'>" . $donnees['prix'] . "€</td>";
                        echo "<td scope='row'>" . $donnees['poidsTotal'] . "kg</td>";
                        echo "<td scope='row'>";
                        if ((int) $donnees['statut'] == 0) {
                            echo "<a href='detail_commande.php?id=" . $donnees['id'] . "' name='commande' class='btn btn-link'>Détail</a>";
                        }
                        echo "<td scope='row'>" . $nCl['societe'] . "</td>";
                        echo '</form><form method="post" action="commandes_effectues.php?id=' . $donnees['id'] . '" role="commandes"><td><button type="submit" name="deleteC" class="btn btn-danger btn-block">Supprimer</button></form>';
                        echo "</td></tr>";
                        $cpt++;
                    }
                } if ($cpt == 1) {
                    echo "<div class='alert alert-danger' role='alert'>Il n'y a pas de commandes en cours.</div>";
                }
            } elseif (isset($_GET['terminee'])) {
                        ?>            <th scope="col">Nom du client</th>
                        <th scope="col">Nom du préparateur</th>
                        <th scope="col">Action</th>
                        </tr>
                        </thead> <?php

                // Trie tous les articles par leurs quantitée
                if ((isset($_GET['trieArt']))&&((!isset($_SESSION['valeurArt'])) OR ($_SESSION['valeurArt'] == 2)))  {
                    array_multisort(array_column($commandes, "quantiteTotale"), SORT_DESC, $commandes);
                    $_SESSION['valeurArt'] = 1;
                }elseif ((isset($_GET['trieArt']))&&($_SESSION['valeurArt'] == 1)) {
                    array_multisort(array_column($commandes, "quantiteTotale"), SORT_ASC, $commandes);
                    $_SESSION['valeurArt'] = 2;
                }if ((isset($_GET['triePoids']))&&((!isset($_SESSION['valeurPoids'])) OR ($_SESSION['valeurPoids'] == 2)))  {
                    array_multisort(array_column($commandes, "poidsTotal"), SORT_DESC, $commandes);
                    $_SESSION['valeurPoids'] = 1;
                }elseif ((isset($_GET['triePoids']))&&($_SESSION['valeurPoids'] == 1)) {
                    array_multisort(array_column($commandes, "poidsTotal"), SORT_ASC, $commandes);
                    $_SESSION['valeurPoids'] = 2;
                }if ((isset($_GET['triePrix']))&&((!isset($_SESSION['valeurPrix'])) OR ($_SESSION['valeurPrix'] == 2))) {
                    array_multisort(array_column($commandes, "prix"), SORT_ASC, $commandes);
                    $_SESSION['valeurPrix'] = 1;
                }elseif((isset($_GET['triePrix']))&&($_SESSION['valeurPrix'] == 1)) {
                    array_multisort(array_column($commandes, "prix"), SORT_DESC, $commandes);
                    $_SESSION['valeurPrix'] = 2;
                }
                // Génere un tableau avec tous les articles
                foreach ($commandes as $donnees) {
                    $_SESSION['idCom'] = $donnees['id'];
                    $nCll = $co->getNomSocieteClientId($donnees['id'], $donnees['idClient']);
                    //var_dump($donnees);
                    if ($donnees['statut'] == 'terminee') {
                        //var_dump($commandes);
                        //  var_dump($donnees);
                        echo "<tr>";
                        echo "<td scope='row'>" . $cpt . "</td>";
                        echo "<td scope='row'>" . (int) $donnees['quantiteTotale'] . "</td>" .
                        "<td scope='row'>";
                        if ((int) $donnees['statut'] == 'terminee') {
                            $message = 'terminée';
                            echo $message;
                        }
                        echo "<td scope='row'>" . $donnees['prix'] . "€</td>";
                        echo "<td scope='row'>" . $donnees['poidsTotal'] . "kg</td>";
                        echo "<td scope='row'>" . $nCll['societe'] . "</td>";
                        echo "<td scope='row'>" . $donnees['Preparateur'];
                        echo "</td><td><a href='detail_commande.php?id=" . $donnees['id'] . "' name='commande' class='btn btn-link'>Détail</a>";
                        echo "</td>";
                        echo '</form><form method="post" action="commandes_effectues.php?id=' . $donnees['id'] . '" role="commandes"><td><button type="submit" name="deleteT" class="btn btn-danger btn-block">Supprimer</button></form>';
                        echo "</td></tr>";
                        $cpt++;
                    }
                }if ($cpt == 1) {
                    echo "<div class='alert alert-danger' role='alert'>Il n'y a pas de commandes terminées.</div>";
                }
            }//Fin du Get terminee
        }
                ?>

                </tbody>
            </table>
            <button type="submit" name="retour" class="btn btn-primary btn-block">Retour accueil</button>
        </form>
    </section>
</section>
