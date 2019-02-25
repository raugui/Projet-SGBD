<?php
session_start();
require('../connect/connect.php');
// Id utilisateur
$idUser=$_SESSION["id"];
$idCommande = $_GET['idCom'];

$em = new UserManager($bdd);
$employes = $em->getUser((int)$idUser);

$co = new CommandesManager($bdd);
// on recupère l'id de la commande
$commande = $co->getCommande($idCommande);
// on recupère l'id du client via la commande
$idClient = $commande['idClient'];
// on recupère les informations du client
$client = $co->getNomSocieteClientId($idCommande,$idClient);

$dc = new DetailCommandesManager($bdd);
$det_com = $dc->getListId($idCommande);

// Construction du document pdf
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$pdf->Text(90,8,utf8_decode(strtoupper('Commande')));
// Infos de la commande calées à gauche
$pdf->setFontSize(10);
$pdf->Text(12,103,utf8_decode('Date de préparation : '.date('m-d-y')));
$pdf->Text(12,108,utf8_decode('Numéro de commande : '.$client['idCom']));

// Infos du préparateur
$pdf->setFontSize(15);
$pdf->Text(60,90,utf8_decode('Préparateur : ').' '.$employes['nom'].' '.$employes['prenom']);

// Infos du client calées à droite
$pdf->setFontSize(10);
$pdf->Text(120,103,utf8_decode(('Client : ').' '.strtoupper($client['societe'])));
$pdf->Text(120,108,utf8_decode(('Adresse : ').' '.$client['adresse']));

// Position de l'entête à 10mm des infos (108 + 10)
$position_entete = 118;

function entete_table($position_entete){
    global $pdf;
    $pdf->SetDrawColor(183); // Couleur du fond
    $pdf->SetFillColor(221); // Couleur des filets
    $pdf->SetTextColor(0); // Couleur du texte
    $pdf->SetY($position_entete);
    $pdf->SetX(8);
    $pdf->Cell(148,8,utf8_decode('Désignation'),1,0,'L',1);
    $pdf->SetX(156);
    $pdf->Cell(20,8,utf8_decode('Quantité'),1,0,'C',1);
    $pdf->SetX(176);
    $pdf->Cell(24,8,utf8_decode('Format'),1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
}
entete_table($position_entete);


// Liste des détails
$position_detail = 126; // Position à 8mm de l'entête

// Les données de la commande :
foreach ($det_com as $row2) {
    $pdf->SetY($position_detail);
    $pdf->SetX(8);
    $pdf->MultiCell(148,8,utf8_decode($row2['designation']),1,'L');
    $pdf->SetY($position_detail);
    $pdf->SetX(156);
    $pdf->MultiCell(20,8,$row2['quantite'],1,'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(176);
    $pdf->MultiCell(24,8,$row2['format'],1,'R');
    $position_detail += 8;
}

// Afficher articles total :
//$pdf->Text(150,258,utf8_decode("Quantité totale d'article : ".' '.$client['quantiteTotale']));
$pdf->Text(150,258,utf8_decode("Quantité totale d'article".((($client['quantiteTotale']) > 1) ? 's' : '')." : ".' '.$client['quantiteTotale']));
// // cadre titre des colonnes
// $pdf->Line(5, 105, 205, 105);
// // les traits verticaux colonnes
// $pdf->Line(145, 95, 145, 213);
// $pdf->Line(158, 95, 158, 213);
// $pdf->Line(176, 95, 176, 213);
// $pdf->Line(187, 95, 187, 213);
// // titre colonne
// $pdf->SetXY( 145, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 13, 8, "Quantite", 0, 0, 'C');
// $pdf->SetXY( 1, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 100, 8, "Designation", 0, 0, 'C');
// $pdf->SetXY( 156, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "Type", 0, 0, 'C');
// $pdf->SetXY( 177, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, "Format", 0, 0, 'C');
$pdf->Output();
