<?php

// Function to establish database connection
function get_db_connection($host, $user, $pass, $db_name) {
    $link = mysqli_connect($host, $user, $pass, $db_name);
    if (!$link) {
        die('Connection error: ' . mysqli_connect_error());
    }
    return $link;
}

// Function to fetch data from database
function fetch_data($link, $query) {
    $result = mysqli_query($link, $query);
    if (!$result) {
        die('Query error: ' . mysqli_error($link));
    }
    return $result;
}

// Function to format the date
function format_date($date) {
    if ($date === '0') {
        return '-';
    }
    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    $day = substr($date, 6, 2);
    return "$day/$month/$year";
}

// Refactor logic for getting collaborator details
function get_collaborators($link, $idFact) {
    $collab = '';
    $query = "SELECT CodeCollab FROM collab WHERE IdFact = $idFact ORDER BY IdCollab ASC";
    $result = fetch_data($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        if (!empty($row['CodeCollab']) && $row['CodeCollab'] !== 'undefined') {
            if (empty($collab)) {
                $collab = strtoupper($row['CodeCollab']);
            }
        }
    }
    return $collab;
}

// Refactor logic for getting dates
function get_due_dates($row) {
    $due_dates = '';
    if ($row['Provision'] == 1) {
        $nb_ech = $row['NbEcheProv'];
        $liste_date_ech = $row['DateEchProv'];
        if ($nb_ech > 0) {
            $dates = [];
            $choix_date = '';
            $i = 0;
            $cpt = 1;
            while ($liste_date_ech[$i] != '*') {
                if ($liste_date_ech[$i] == '-') {
                    if ($i > 0) {
                        $dates[$cpt] = $choix_date;
                        $cpt++;
                        $choix_date = '';
                    }
                    $i++;
                }
                $choix_date .= $liste_date_ech[$i];
                $i++;
            }
            $dates[$cpt] = $choix_date;
            $due_dates = implode('-', $dates);
        }
    }
    return $due_dates;
}

// Main processing logic
function process_invoices($host, $user, $pass, $db_name, $presta_debours) {
    $link = get_db_connection($host, $user, $pass, $db_name);
    $select_fact = "SELECT * FROM invoices";  // Adjust this query based on your requirements
    $result = fetch_data($link, $select_fact);
    
    $recap = []; // Use associative arrays for better structure
    while ($row = mysqli_fetch_array($result)) {
        $montant = 0;
        $debours = '-';
        $fae = ($row['FAE'] == 1) ? 'FAE ' : '';
        
        // Get total for travel-related prestations
        $select_total_trav = "SELECT * FROM prestations WHERE IdFact = '{$row['IdFact']}' AND IdDetail IN (SELECT IdDetail FROM detail WHERE IdFact = '{$row['IdFact']}')";
        $result_total_trav = fetch_data($link, $select_total_trav);
        while ($row2 = mysqli_fetch_array($result_total_trav)) {
            if (in_array($row2['CodePrest'], $presta_debours)) {
                $debours = 'Oui';
            }
        }
        
        // Get collaborator information
        $collab = get_collaborators($link, $row['IdFact']);
        
        // Get due dates if provision is 1
        $affiche_date_ech = get_due_dates($row);
        
        // Format dates for display
        $dte1 = format_date($row['DateFacture'] == 0 ? $row['Date'] : $row['DateFacture']);
        $dte2 = format_date($row['DateInsertDIA']);
        $dte3 = format_date($row['DateValidationRD']);
        
        // Prepare recap data
        $recap[] = [
            'rd' => $row['Code'],
            'collab' => $collab,
            'code' => $row['Code'],
            'nom' => $row['Dossier'],
            'date' => $dte1,
            'date_dia' => $dte2,
            'date_valide_rd' => $dte3,
            'adresse' => $row['Adresse1'],
            'modalite' => $fae . $row['Modalite1'],
            'id' => $row['IdFact'],
            'encours' => $row['EnCours'],
            'prov' => $row['Provision'],
            'montant' => $row['MontantFact'],
            'debours' => $debours,
            'impression' => $row['FactImprimee'],
            'verrou' => $row['BlocageFacture'],
            'comment' => $row['CommentairesFacture'],
            'comment_ajout' => $row['CommentairesAjoutRecap'],
            'affiche_date' => $dte1
        ];
    }

    // Storing recap data in session
    $_SESSION['recap_data'] = $recap;
    echo '</table>';
    
    return $recap; // Returning recap for potential further processing
}

// Execute function with example parameters
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'your_database_name';
$presta_debours = ['presta1', 'presta2'];  // Example prestation codes

$recap_data = process_invoices($host, $user, $pass, $db_name, $presta_debours);

?>
