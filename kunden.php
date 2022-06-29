<?php
    require "includes/conn.inc.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kunden</title>
</head>
<body>
    <h1>Kundenauswertung</h1>
    <ul>
        <?php
            // QUERY tbl_kunden AND DISPLAY THEM AS <li>
            $conn = openConn();
            $sql = "
                SELECT * FROM tbl_kunden ORDER BY NACHNAME ASC, VORNAME ASC
            ";
            $kunden = $conn->query($sql);
            while ($kunde = $kunden->fetch_object()) {
                echo "<li>";
                    echo $kunde->Nachname . " ";
                    echo $kunde->Vorname . " ";
                    // CREATE SUB LIST, GET DATA FROM tbl_einsatz, DISPLAY DATA IN <li>
                    echo "<ul>";
                        $sql = "
                            SELECT
                                SUM(TIMESTAMPDIFF(MINUTE,Startzeitpunkt,Endzeitpunkt)) AS sumMinuten,
                                MIN(Startzeitpunkt) AS von,
                                MAX(Endzeitpunkt) AS bis
                            FROM tbl_einsatz
                            WHERE(
                                FIDKunde=" . $kunde->IDKunde . "
                            )
                        ";
                        $zeiten = $conn->query($sql) or die ("FEHLER IN DER QUERY " . $conn->error);
                        while ($zeit = $zeiten->fetch_object()) {
                            $stundenSatz = 60;
                            echo "<li>";
                                echo "Geleistete Stunden: " . $zeit->sumMinuten / 60 . "h.";
                            echo "</li>";
                            echo "<li>";
                                echo "Kosten: " . $zeit->sumMinuten / 60 * $stundenSatz . "€";
                            echo "</li>";
                            echo "<li>";
                                echo "Gearbeitet von: " . $zeit->von . " bis " . $zeit->bis;
                            echo "</li>";
                        }
                    echo "</ul>";
                echo "</li>";
            }
        ?>
    </ul>
</body>
</html>