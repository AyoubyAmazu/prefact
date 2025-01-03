<?php
function affiche_entete_travaux($fact, $idtrav, $idtrav_select, $site) {
    global $host, $user, $pass;

    // Initialize variables
    $total_fact = 0;
    $total_trav = 0;

    try {
        // Establish PDO connection
        $dsn = "mysql:host=$host;dbname=z_fact";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Function to execute a query and return the result
        function execute_query($pdo, $query, $params = []) {
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        }

        // Calculate the total cost of travaux
        $select_total_trav = "SELECT * FROM prestations 
                              WHERE IdFact = :fact AND IdDetail IN (
                                  SELECT IdDetail FROM detail WHERE IdFact = :fact AND IdTrav = :idtrav
                              )";
        $stmt = execute_query($pdo, $select_total_trav, ['fact' => $fact, 'idtrav' => $idtrav]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_trav += $row['Cout'];
        }

        // Check and update travaux_detail table
        $select_total_trav = "SELECT * FROM travaux_detail WHERE IdFact = :fact AND IdTrav = :idtrav ORDER BY IdTrav ASC";
        $stmt = execute_query($pdo, $select_total_trav, ['fact' => $fact, 'idtrav' => $idtrav]);

        if ($stmt->rowCount() == 0) {
            // Insert new entry if not found
            $insert_query = "INSERT INTO travaux_detail (IdFact, IdTrav, Total) VALUES (:fact, :idtrav, :total_trav)";
            execute_query($pdo, $insert_query, ['fact' => $fact, 'idtrav' => $idtrav, 'total_trav' => $total_trav]);
        } else if ($stmt->rowCount() == 2) {
            // Handle duplicate entries
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $idtravdet = $row['IdTravDet'];
            $total1 = $row['Total'];
            $total_fact1 = $row['Total_facture'];
            $remise1 = $row['Remise'];
            $acompte1 = $row['Acompte'];

            // Delete the duplicate entry
            $delete_query = "DELETE FROM travaux_detail WHERE IdTravDet = :idtravdet";
            execute_query($pdo, $delete_query, ['idtravdet' => $idtravdet]);

            // Recalculate total for the remaining record
            $total_trav += $total1;

            // Update total_facture
            if ($idtrav == 17) {
                $select_commis = "SELECT * FROM travaux_detail WHERE IdFact = :fact AND IdTrav = 1 LIMIT 1";
                $stmt_commis = execute_query($pdo, $select_commis, ['fact' => $fact]);
                $total_fact = 0;
                if ($stmt_commis->rowCount() > 0) {
                    $row_commis = $stmt_commis->fetch(PDO::FETCH_ASSOC);
                    $total_fact = $row_commis['Total_facture'] == 0 ? $row_commis['Total'] / 200 : $row_commis['Total_facture'] / 200;
                }
            } else {
                $total_fact = $total_fact1 + $total_trav;
            }

            $remise = $remise1 + $row['Remise'];
            $acompte = $acompte1 + $row['Acompte'];

            // Update the total in travaux_detail
            $update_query = "UPDATE travaux_detail SET Total = :total_trav, Total_facture = :total_fact, Remise = :remise, Acompte = :acompte WHERE IdTravDet = :idtravdet";
            execute_query($pdo, $update_query, [
                'total_trav' => $total_trav, 
                'total_fact' => $total_fact, 
                'remise' => $remise, 
                'acompte' => $acompte, 
                'idtravdet' => $idtravdet
            ]);
        } else {
            // Update total if no duplicates
            if ($idtrav == 17) {
                $select_commis = "SELECT * FROM travaux_detail WHERE IdFact = :fact AND IdTrav = 1 LIMIT 1";
                $stmt_commis = execute_query($pdo, $select_commis, ['fact' => $fact]);
                $total_fact = 0;
                if ($stmt_commis->rowCount() > 0) {
                    $row_commis = $stmt_commis->fetch(PDO::FETCH_ASSOC);
                    $total_fact = $row_commis['Total_facture'] == 0 ? $row_commis['Total'] / 200 : $row_commis['Total_facture'] / 200;
                }

                $update_query = "UPDATE travaux_detail SET Total_facture = :total_fact WHERE IdFact = :fact AND IdTrav = :idtrav";
                execute_query($pdo, $update_query, ['total_fact' => $total_fact, 'fact' => $fact, 'idtrav' => $idtrav]);
            } else {
                $update_query = "UPDATE travaux_detail SET Total = :total_trav WHERE IdFact = :fact AND IdTrav = :idtrav";
                execute_query($pdo, $update_query, ['total_trav' => $total_trav, 'fact' => $fact, 'idtrav' => $idtrav]);
            }
        }

        // Display travaux options
        echo '<legend><table><tr><td><i><select name="' . $idtrav_select . '" id="' . $idtrav_select . '" onchange="changement_travaux(' . $idtrav . ', \'' . $idtrav_select . '\', ' . $fact . ');">';

        $select_travaux = "SELECT * FROM divers_travaux WHERE IdDivers NOT IN (1, 13, 19, 27, 41, 42) ORDER BY Classer ASC";
        if ($site == 'Audit' || $site == 'Montauban') {
            $select_travaux = "SELECT * FROM divers_travaux ORDER BY Classer ASC";
        } else if ($site == 'Avocatio') {
            $select_travaux = "SELECT * FROM divers_travaux WHERE IdDivers IN (8, 9, 31, 10, 40) ORDER BY Classer ASC";
        }

        $stmt_travaux = execute_query($pdo, $select_travaux);
        while ($row4 = $stmt_travaux->fetch(PDO::FETCH_ASSOC)) {
            $selected = $row4['IdDivers'] == $idtrav ? 'selected' : '';
            echo '<option ' . $selected . ' value="' . $row4['IdDivers'] . '">' . $row4['Description'] . '</option>';
        }

        echo '</select></td><td>';

        // Display total values
        echo '<input type="hidden" id="total_fact_theorique' . $idtrav . '" value="' . $total_trav . '"/>';
        $readonly = ($idtrav == 10 || $idtrav == 40) ? 'readonly onclick="verif_travaux(' . $idtrav . ');"' : '';
        $total_fact_display = number_format($total_fact, 2, ',', '');
        echo '<font size="5px" color="green"><big> ___________________________________________ </big></font><b>Total Général = ' . number_format($total_trav, 2, ',', '') . ' / Total Facturé = <input class="total" id="total_fact' . $idtrav . '" type="text" name="totaux_part" ' . $readonly . ' size="7" value="' . $total_fact_display . '" onFocus="this.select();" onBlur="javascript:this.value=totalcategorie(' . $idtrav . ', ' . $fact . ', this.id);"/>';
        echo '</i></td></tr></table></legend>';
        
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}



// Function to establish database connection
function connectToDatabase($db_name) {
    global $host, $user, $pass;

    $link = mysql_connect($host, $user, $pass) or die('Connection error: ' . mysql_error());
    mysql_select_db($db_name, $link) or die('Database error: ' . mysql_error());

    return $link;
}

// Fetch collaborators data
function fetchCollaborators() {
    $collaborators = array();
    $link = connectToDatabase('collab');

    $query = "SELECT COL_CODE, COL_NOM, COL_PRENOM, FONC_CODE, COL_EMAIL, SOC_CODE FROM collab ORDER BY COL_CODE ASC";
    $result = mysql_query($query, $link) or die(mysql_error());

    while ($row = mysql_fetch_array($result)) {
        $collaborators[$row['COL_CODE']] = [
            'name' => addslashes($row['COL_NOM']) . ' ' . addslashes($row['COL_PRENOM']),
            'email' => $row['COL_EMAIL'],
            'soc_code' => $row['SOC_CODE'],
            'role' => ($row['FONC_CODE'] == 'STAGIAIRE') ? 'STAGIAIRE' : ''
        ];
    }

    return $collaborators;
}

// Fetch invoice details
function fetchInvoiceDetails($fact) {
    $link = connectToDatabase('z_fact');
    $query = "
        (SELECT * FROM detail WHERE IdFact = '$fact' AND (IdTrav < 27 OR IdTrav >= 33))
        UNION
        (SELECT * FROM detail WHERE IdFact = '$fact' AND (IdTrav BETWEEN 27 AND 32))
        ORDER BY IdTrav, IdDetail ASC";
    $result = mysql_query($query, $link) or die(mysql_error());

    return $result;
}

// Display header for each 'travaux' based on IdTrav
function displayTravauxHeader($fact, $idTrav, $site) {
    $travauxHeaders = [
        1 => 'id_travaux1', 2 => 'id_travaux2', 3 => 'id_travaux3', 4 => 'id_travaux4',
        5 => 'id_travaux5', 6 => 'id_travaux6', 7 => 'id_travaux7', 8 => 'id_travaux8',
        9 => 'id_travaux9', 10 => 'id_travaux10', 12 => 'id_travaux12', 13 => 'id_travaux13',
        14 => 'id_travaux14', 15 => 'id_travaux15', 16 => 'id_travaux16', 17 => 'id_travaux17',
        18 => 'id_travaux18', 19 => 'id_travaux19', 20 => 'id_travaux20', 21 => 'id_travaux21',
        22 => 'id_travaux22', 23 => 'id_travaux23', 24 => 'id_travaux24', 25 => 'id_travaux25',
        26 => 'id_travaux26', 27 => 'id_travaux27', 28 => 'id_travaux28', 29 => 'id_travaux29',
        30 => 'id_travaux30', 31 => 'id_travaux31', 32 => 'id_travaux32', 33 => 'id_travaux33',
        34 => 'id_travaux34', 35 => 'id_travaux35', 36 => 'id_travaux36', 37 => 'id_travaux37',
        38 => 'id_travaux38', 39 => 'id_travaux39', 40 => 'id_travaux40', 41 => 'id_travaux41',
        42 => 'id_travaux42'
    ];

    if (isset($travauxHeaders[$idTrav])) {
        affiche_entete_travaux($fact, $idTrav, $travauxHeaders[$idTrav], $site);
    }
}

// Display the invoice details
// Function to establish database connection
function connectToDatabase($db_name) {
    global $host, $user, $pass;

    $link = mysql_connect($host, $user, $pass) or die('Connection error: ' . mysql_error());
    mysql_select_db($db_name, $link) or die('Database error: ' . mysql_error());

    return $link;
}

// Fetch collaborators data
function fetchCollaborators() {
    $collaborators = array();
    $link = connectToDatabase('collab');

    $query = "SELECT COL_CODE, COL_NOM, COL_PRENOM, FONC_CODE, COL_EMAIL, SOC_CODE FROM collab ORDER BY COL_CODE ASC";
    $result = mysql_query($query, $link) or die(mysql_error());

    while ($row = mysql_fetch_array($result)) {
        $collaborators[$row['COL_CODE']] = [
            'name' => addslashes($row['COL_NOM']) . ' ' . addslashes($row['COL_PRENOM']),
            'email' => $row['COL_EMAIL'],
            'soc_code' => $row['SOC_CODE'],
            'role' => ($row['FONC_CODE'] == 'STAGIAIRE') ? 'STAGIAIRE' : ''
        ];
    }

    return $collaborators;
}

// Fetch invoice details
function fetchInvoiceDetails($fact) {
    $link = connectToDatabase('z_fact');
    $query = "
        (SELECT * FROM detail WHERE IdFact = '$fact' AND (IdTrav < 27 OR IdTrav >= 33))
        UNION
        (SELECT * FROM detail WHERE IdFact = '$fact' AND (IdTrav BETWEEN 27 AND 32))
        ORDER BY IdTrav, IdDetail ASC";
    $result = mysql_query($query, link_identifier: $link) or die(mysql_error());

    return $result;
}

// Display header for each 'travaux' based on IdTrav
function displayTravauxHeader($fact, $idTrav, $site) {
    $travauxHeaders = [
        1 => 'id_travaux1', 2 => 'id_travaux2', 3 => 'id_travaux3', 4 => 'id_travaux4',
        5 => 'id_travaux5', 6 => 'id_travaux6', 7 => 'id_travaux7', 8 => 'id_travaux8',
        9 => 'id_travaux9', 10 => 'id_travaux10', 12 => 'id_travaux12', 13 => 'id_travaux13',
        14 => 'id_travaux14', 15 => 'id_travaux15', 16 => 'id_travaux16', 17 => 'id_travaux17',
        18 => 'id_travaux18', 19 => 'id_travaux19', 20 => 'id_travaux20', 21 => 'id_travaux21',
        22 => 'id_travaux22', 23 => 'id_travaux23', 24 => 'id_travaux24', 25 => 'id_travaux25',
        26 => 'id_travaux26', 27 => 'id_travaux27', 28 => 'id_travaux28', 29 => 'id_travaux29',
        30 => 'id_travaux30', 31 => 'id_travaux31', 32 => 'id_travaux32', 33 => 'id_travaux33',
        34 => 'id_travaux34', 35 => 'id_travaux35', 36 => 'id_travaux36', 37 => 'id_travaux37',
        38 => 'id_travaux38', 39 => 'id_travaux39', 40 => 'id_travaux40', 41 => 'id_travaux41',
        42 => 'id_travaux42'
    ];

    if (isset($travauxHeaders[$idTrav])) {
        affiche_entete_travaux($fact, $idTrav, $travauxHeaders[$idTrav], $site);
    }
}

// Display the invoice details
function afficher_fact($cat_actuelle, $fact, $site, $cpt_inter, $social, $utilise, $tri, $tab_titre, $code_dos, $recup_modele) {
    // Fetch collaborator data
    $collaborators = fetchCollaborators();
    
    // Fetch invoice details
    $result = fetchInvoiceDetails($fact);

    $cat_actuelle = 0;
    $total_trav = 0;
    $remise = 0;
    $acompte = 0;

    $date_deb_base = $_SESSION['date_deb_social'];
    $date_fin_base = $_SESSION['date_fin_social'];

    while ($row = mysql_fetch_array($result)) {
        if ($cat_actuelle == 0) {
            if ($row['IdTrav'] != 11) {
                echo '<fieldset class="travaux">';
            }

            // Display travaux header based on IdTrav
            displayTravauxHeader($fact, $row['IdTrav'], $site);
            $cat_actuelle = $row['IdTrav'];

            if ($row['IdTrav'] != 11) {
                echo '<table class="table_total">';
                zone_remise_acompte($cat_actuelle, $fact, $cpt_inter);
            }
        } elseif ($cat_actuelle != $row['IdTrav']) {
            if ($row['IdTrav'] != 11) {
                echo '</table>';
            }

            bouton_ajout_partie($social, $utilise, $cat_actuelle, $fact, $recup_modele);
            echo '<a href="./affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Utilise=' . $utilise . '&Supprime=' . $cat_actuelle . '"><img src="../Images/moins.jpg" border="0"></a>';
            echo '</fieldset>';

            if ($row['IdTrav'] != 11) {
                echo '<fieldset class="travaux">';
            }

            // Display travaux header based on IdTrav
            displayTravauxHeader($fact, $row['IdTrav'], $site);
            $cat_actuelle = $row['IdTrav'];

            echo '<table class="table_total">';
            zone_remise_acompte($cat_actuelle, $fact, $cpt_inter);
        }
    }
    $total_trav = 0;
}
// Retrieve the prestations for this IdDetail for the title and totals based on the chosen sorting order
if ($tri == 'Collab') {
    $select_total = "SELECT * FROM prestations WHERE IdFact = '$fact' AND IdDetail = '" . $row['IdDetail'] . "' ORDER BY Collab ASC, DatePrestBase ASC";
} elseif ($tri == 'Prest') {
    $select_total = "SELECT * FROM prestations WHERE IdFact = '$fact' AND IdDetail = '" . $row['IdDetail'] . "' ORDER BY CodePrest, DatePrestBase ASC";
} else {
    $select_total = "SELECT * FROM prestations WHERE IdFact = '$fact' AND IdDetail = '" . $row['IdDetail'] . "' ORDER BY IdPrest ASC";
}

$result_total = mysql_query($select_total, $link) or die(mysql_error() . "\n" . $select_total);

// Check if it's not an "Abonnement" or "Acompte" category
if ($cat_actuelle != 11 && $cat_actuelle != 32) {
    // Display row for section title and totals
    echo '<tr>';
    echo '<td class="table_total"></td>';

    // Display title for the subsection
    $title_value = ($tab_titre[$i] == "-") ? '' : htmlentities($tab_titre[$i]);
    echo '<td class="table_total" colspan="2" align="left">';
    echo '<a href="./affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Utilise=' . $utilise . '&Suppr=oui&IdDet=' . $row['IdDetail'] . '"><img src="../Images/supp2.jpg" border="0"></a>';
    echo '<input class="titre" type="text" id="' . $row['IdDetail'] . '" name="titre_part" size="140" value="' . $title_value . '" onBlur="javascript:this.value=testchamps(' . $row['IdDetail'] . ', ' . $fact . ');"/></td>';
    echo '<td class="table_total"></td><td class="table_total"></td><td class="table_total"></td>';
    echo '</tr>';
}

// Display table for time entries
echo '<tr>';
echo '<td class="table_total"></td><td class="table_total"></td>';
echo '<td class="table_total" colspan="2" align="left"><table class="table_detail" width="90%">';

if ($cat_actuelle != 11 && $cat_actuelle != 32) {
    echo '<tr>';
    $abo = 0;
    $somme_cout = 0;
    
    // Calculate totals for the subsection and handle subscription calculations
    while ($row2 = mysql_fetch_array($result_total)) {
        if (($row2['CodePrest'] >= 900 && $row2['CodePrest'] <= 999) || in_array($row2['CodePrest'], $presta_abon)) {
            $abo += $row2['Cout'];
        }
        $somme_cout += $row2['Cout'];
    }

    $readonly = (($cat_actuelle == 10 || $cat_actuelle == 40) && $somme_cout != 0) ? "readonly" : "";

    if ($tab_total[$i] != 0 && $tab_total[$i] != $abo) {
        // If there's an existing total and it's different from the subscription amount, show the previously retrieved amount
        $id = $row['IdDetail'] - 100;
        echo '<input type="hidden" id="sous_total_trav' . $id . '" value="' . $cat_actuelle . '"/>';
        
        // Display arrow to show/hide time entries
        if ($row['Masquer'] == 0) {
            echo '<td class="table_detail" colspan="2"><a title="Masquer les lignes de temps" href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Utilise=' . $utilise . '&Afficher=non&IdDet=' . $row['IdDetail'] . '&Id=ok&Scroll=\' + document.documentElement.scrollTop;"><img src="../Images/fleche-haut.jpg" border="0"></a></td>';
        } else {
            echo '<td class="table_detail" colspan="2"><a title="Afficher les lignes de temps" href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Utilise=' . $utilise . '&Afficher=oui&IdDet=' . $row['IdDetail'] . '&Id=ok&Scroll=\' + document.documentElement.scrollTop;"><img src="../Images/fleche-bas.jpg" border="0"></a></td>';
        }
        
        echo '<td class="table_detail" align="center"><a href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Code=' . $code_dos . '&Utilise=' . $utilise . '&Tri=Collab&Id=ok&Scroll=\' + document.documentElement.scrollTop;">Collab</a></td>';
        echo '<td class="table_detail" align="center"><a href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Code=' . $code_dos . '&Utilise=' . $utilise . '&Tri=Prest&Id=ok&Scroll=\' + document.documentElement.scrollTop;">Prest</a></td>';
        echo '<td class="table_detail" colspan="5" align="right">Montant Général = ' . number_format($tab_total[$i], 2, ',', '') . '  /  Montant Facturé = <input class="total" type="text" id="sous_total' . $id . '" style="background-color:#D6FDD5;" name="total_part" size="7" value="' . number_format($row['Total_facture'], 2, ',', '') . '" ' . $readonly . ' onFocus="this.select();" onBlur="javascript:this.value=totalchamps(' . $id . ', ' . $fact . ', this.id, ' . $cat_actuelle . ');"/></td>';
        $id = $row['IdDetail'] + 100;
    } else {
        // If there are subscriptions or the value is 0, display the calculated total above
        $id = $row['IdDetail'] - 100;
        echo '<input type="hidden" id="sous_total_trav' . $id . '" value="' . $cat_actuelle . '"/>';
        
        // Display arrow to show/hide time entries
        if ($row['Masquer'] == 0) {
            echo '<td class="table_detail" colspan="2"><a title="Masquer les lignes de temps" href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Utilise=' . $utilise . '&Afficher=non&IdDet=' . $row['IdDetail'] . '&Id=ok&Scroll=\' + document.documentElement.scrollTop;"><img src="../Images/fleche-haut.jpg" border="0"></a></td>';
        } else {
            echo '<td class="table_detail" colspan="2"><a title="Afficher les lignes de temps" href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Utilise=' . $utilise . '&Afficher=oui&IdDet=' . $row['IdDetail'] . '&Id=ok&Scroll=\' + document.documentElement.scrollTop;"><img src="../Images/fleche-bas.jpg" border="0"></a></td>';
        }
        
        echo '<td class="table_detail" align="center"><a href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Code=' . $code_dos . '&Utilise=' . $utilise . '&Tri=Collab&Id=ok&Scroll=\' + document.documentElement.scrollTop;">Collab</a></td>';
        echo '<td class="table_detail" align="center"><a href="javascript:location.href=\'affiche_fact.php?Social=' . $social . '&Recup_modele=' . $recup_modele . '&IdFact=' . $fact . '&Code=' . $code_dos . '&Utilise=' . $utilise . '&Tri=Prest&Id=ok&Scroll=\' + document.documentElement.scrollTop;">Prest</a></td>';
        echo '<td class="table_detail" colspan="5" align="right">Montant Général = ' . number_format($somme_cout, 2, ',', '') . '  /  Montant Facturé = <input class="total" type="text" id="sous_total' . $id . '" style="background-color:#D6FDD5;" name="total_part" size="7" value="' . number_format($row['Total_facture'], 2, ',', '') . '" ' . $readonly . ' onFocus="this.select();" onBlur="javascript:this.value=totalchamps(' . $id . ', ' . $fact . ', this.id, ' . $cat_actuelle . ');"/></td>';
        $id = $row['IdDetail'] + 100;
    }
    echo '</tr>';
}
echo '</table></td><td class="table_total"></td>';
echo '</tr>';
