<?php

// Création de la class PDF
class PDF extends FPDF {
    // Header
    function Header() {
        // Logo
      //  $this->Image('../assets/images/logo_accueil.png',10,12,40);
        // Bannière
        $this->Image('../assets/images/banniere.jpg',10,12,190);
        // Saut de ligne
        $this->Ln(20);
    }
    // Footer
    function Footer() {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        // Adresse
      //  $this->Cell(196,5,'Mes coordonnees - +32(0)56588923',0,0,'C');
    }


}
