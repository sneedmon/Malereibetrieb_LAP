<?php 
require "includes/conn.inc.php";

// IF NO POST DATA IS SENT SET KEYS TO EMPTY STRING
// PREVENTS FROM GETTING UNDEFINED ARRAY KEY ERROR IF PAGE GETS LOADED WITHOUT POST DATA
if(count($_POST)==0) {
    $_POST["NN_MA"] = "";
    $_POST["VN_MA"] = "";
    $_POST["NN_KD"] = "";
    $_POST["VN_KD"] = "";
}
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Mitarbeiter</title>
    </head>
    <body>
    <form method="post">
        <fieldset>
            <legend>Mitarbeiter</legend>
            <label>
                Nachname:
                <!-- OUTPUT SEARCH VALUE AFTER FILTER BUTTON GETS CLICKED -->
                <input type="text" name="NN_MA" value="<?php echo($_POST["NN_MA"]); ?>">  
            </label>
            <label>
                Vorname:
                <input type="text" name="VN_MA" value="<?php echo($_POST["VN_MA"]); ?>">
            </label>
        </fieldset>
        <fieldset>
            <legend>Kunde</legend>
            <label>
                Nachname:
                <input type="text" name="NN_KD" value="<?php echo($_POST["NN_KD"]); ?>">
            </label>
            <label>
                Vorname:
                <input type="text" name="VN_KD" value="<?php echo($_POST["VN_KD"]); ?>">
            </label>
        </fieldset>
        <button type="submit">Filtern</button>
    </form>
        <?php
            echo "<ul>";
            // CREATE WHERE CLAUSE FILTER
            $where = "";
            $arr_W = [];
            if (count($_POST) > 0) {
                if(strlen($_POST["NN_MA"]) > 0) {
                    $arr_W[] = "tbl_mitarbeiter.Nachname='" . $_POST["NN_MA"] . "'";
                }
                if(strlen($_POST["VN_MA"]) > 0) {
                    $arr_W[] = "tbl_mitarbeiter.Vorname='" . $_POST["VN_MA"] . "'";
                }
                if (count($arr_W) > 0 ) {
                    $where = " 
                        WHERE " . implode(" AND ", $arr_W) . "
                    ";
                }
            }
            $mainSql = "
                SELECT * FROM tbl_mitarbeiter " . $where . "ORDER BY NACHNAME ASC, VORNAME ASC
            ";
            $conn = openConn();
            $workerList = $conn->query($mainSql) or die ("Fehler in der query " . $conn->error);
            while ($worker = $workerList->fetch_object()) {
                echo "<li>";
                    echo $worker->Vorname . " ";
                    echo $worker->Nachname . " ";
                    // CREATE WHERE CLAUSE FOR
                    // FIDMitarbeiter IS DEFAULT IN ARRAY
                    $arr_W = ["FIDMitarbeiter=" . $worker->IDMitarbeiter];
                    if(count($_POST)>0) {
                        if(strlen($_POST["NN_KD"]) > 0) {
                            $arr_W[] = "tbl_kunden.Nachname='" . $_POST["NN_KD"] . "'";
                        }
                        if(strlen($_POST["VN_KD"]) > 0) {
                            $arr_W[] = "tbl_kunden.Vorname='" . $_POST["VN_KD"] . "'";
                        }
                    }
                    $sql = "
                        SELECT 
                            tbl_einsatz.Startzeitpunkt,
                            tbl_einsatz.Endzeitpunkt,
                            tbl_kunden.Vorname,
                            tbl_kunden.Nachname,
                            tbl_kunden.Adresse,
                            tbl_kunden.PLZ,
                            tbl_kunden.Ort
                        FROM tbl_einsatz
                        LEFT JOIN tbl_kunden ON tbl_kunden.IDKunde=tbl_einsatz.FIDKunde 
                        WHERE " . implode(" AND " ,$arr_W) .
                        " ORDER BY tbl_einsatz.Startzeitpunkt ASC
                    ";
                    $einsätze = $conn->query($sql) or die ("Fehler in der query " . $conn->error);
                    while ($einsatz = $einsätze->fetch_object()) {
                        echo "<ul>";
                            echo "<li>";
                                echo $einsatz->Startzeitpunkt . " bis ";
                                echo $einsatz->Endzeitpunkt;
                                echo " - ";
                                echo $einsatz->Vorname .  " ";
                                echo $einsatz->Nachname . " (";
                                echo $einsatz->Adresse . " / ";
                                echo $einsatz->PLZ . " ";
                                echo $einsatz->Ort . ")";
                            echo "</li>";
                        echo "</ul>";
                    }
                echo "</li>";
            }
            echo "</ul>";
        ?>
    </body>
</html>

