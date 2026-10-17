<?php
session_start();

if (!isset($_SESSION['reservation_data'])) {
    die("Votre reçu n'est pas disponible ou déjà téléchargé.");
}

$_SESSION['reservation_data_backup'] = $_SESSION['reservation_data'];


require_once('../../libs/tcpdf/tcpdf.php');


$reservation = $_SESSION['reservation_data'];

// Create half-page PDF (A5 landscape half = 105mm x 148mm)
$pdf = new TCPDF('P', 'mm', array(105, 148), true, 'UTF-8', false);

// Document settings
$pdf->SetCreator('AGHARAS');
$pdf->SetAuthor('AGHARAS');
$pdf->SetTitle('Reçu #'.$reservation['reservation_id']);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(10, 5, 10); // Tight margins
$pdf->SetAutoPageBreak(false);

// Add page
$pdf->AddPage();





// HTML content with perfect half-page layout
$html = '
<style>
    .header { 
    text-transform: uppercase;
        text-align: center; 
        margin: 0 0 2mm 0;
        line-height: 1;
       font-size: 8pt;
       font-weight: bold;
        
    }
    .logo {
        height: 25mm;
    }
    .company {
        font-size: 14pt;
        font-weight: bold;
        color: #d4b221;
        margin: 1mm 0;
    }
    .receipt-title {
        font-size: 11pt;
        margin: 0 0 3mm 0;
    }
    .details-table {
        
        

        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
        line-height: 1.3;
    }
            .details-table hr {
        border: 1px solid rgb(242, 241, 236);
        color: white;
}
    .details-table td {
        padding: 1.5mm 0;
        vertical-align: top;
      margin-bottom: 20px;
    }
    .section-title {
        font-weight: bold;
        font-size: 10pt;
        margin: 2mm 0 1mm 0;
                text-decoration: underline solid #d4b221;
        padding-bottom: 1mm;
    }
    .footer {
        text-align: center;
        
        font-style: italic;
        font-size: 6pt;
        text-decoration: underline;
        margin-top: 6mm;
        color: #555;
    }
</style>

<div class="header">
   <!-- <div><img src="images/LogoFv.png" class="logo" /></div>-->
    <div class="company">AGHARAS</div>
    <div class="receipt-title">Reçu de réservation</div>
</div>

<table class="details-table">
    <tr>
        <td colspan="2"><div class="section-title">Détails de la réservation:</div></td>
    </tr>
    <tr>
        <td width="45%"><strong>Référence:</strong></td>
        <td>#'.$reservation['reservation_id'].'</td>
    </tr>
    <tr>
        <td><strong>Date de réservation:</strong></td>
        <td>'.date('d/m/Y H:i', strtotime($reservation['date_reservation'])).'</td>
    </tr>
    <tr>
        <td><strong>Passager:</strong></td>
        <td>'.htmlspecialchars($reservation['nom']).' '.htmlspecialchars($reservation['prenom']).'</td>
    </tr>
    <tr>
        <td><strong>Email:</strong></td>
        <td>'.htmlspecialchars($reservation['email']).'</td>
    </tr>
    <tr>
        <td><strong>Téléphone:</strong></td>
        <td>'.htmlspecialchars($reservation['telephone']).'</td>
    </tr>
    <tr>
        <td><strong  style=" margin-bottom:15px;">Places réservées:</strong></td>
        <td>'.htmlspecialchars($reservation['nombre_places']).'</td>
    </tr>
    <hr>
    <tr>
        <td colspan="2"><div class="section-title">Détails du trajet:</div></td>
    </tr>
    <tr>
        <td><strong>Départ:</strong></td>
        <td>'.htmlspecialchars($reservation['depart']).'</td>
    </tr>
    <tr>
        <td><strong>Arrivée:</strong></td>
        <td>'.htmlspecialchars($reservation['arrive']).'</td>
    </tr>
    <tr>
        <td><strong>Date du trajet:</strong></td>
        <td>'.date('d/m/Y', strtotime($reservation['date_trajet'])).'</td>
    </tr>
    <tr>
        <td><strong>Heure de départ:</strong></td>
        <td>'.htmlspecialchars($reservation['heure_depart']).'</td>
    </tr>
    <tr>
        <td><strong>Prix total:</strong></td>
        <td>'.htmlspecialchars($reservation['prix'] * $reservation['nombre_places']).' DH</td>
    </tr>
</table>

<div class="footer">
    Merci d\'utiliser AGHARAS pour vos déplacements
</div>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('recu_agharas_'.$reservation['reservation_id'].'.pdf', 'D');
// unset($_SESSION['reservation_data']);
?>