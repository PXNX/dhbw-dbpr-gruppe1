<?php


include "db.inc.php";

class Auswertung
{
    private $gesamtumsatz;
    private $groesste;
    private $startdatum;
    private $kategorie;
    private $marktid;
    private $standardabweichung;
    private $median;
    private $lineare_regression;


    /**
     * Klassenattribute mit Parametern und Berechnungsergebnissen initialisieren.
     * @author Felix Huber
     */
    public function __construct($marktid, $startdatum, $kategorie)
    {
        $this->marktid = $marktid;
        $this->startdatum = $startdatum;
        $this->kategorie = $kategorie;

        $this->gesamtumsatz = $this->berechne_gesamtumsatz();
        $this->groesste = $this->berechne_groesste();
        $this->standardabweichung = $this->berechne_standardabweichung();
        $this->median = $this->berechne_median();
        $this->lineare_regression = $this->berechne_lineare_regression();
    }

    /**
     * Stored Procedure zur Berechnung des Gesamtumsatzes je Woche aufrufen.
     * @author Felix Huber
     */
    private function berechne_gesamtumsatz(): array
    {
        include 'db.inc.php';
        $query = $db->prepare("call sp_gesamt(:kategorie, :startdatum, :marktid);");
        $query->execute([
            'kategorie' => $this->kategorie,
            'startdatum' => $this->startdatum,
            'marktid' => $this->marktid
        ]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        //var_dump($result);
        return $result;
    }

    /**
     * Stored Procedure zur Berechnung des Umsatzes der größten Bestellung je Woche aufrufen.
     * @author Felix Huber
     */
    private function berechne_groesste(): array
    {
        include "db.inc.php";
        $query = $db->prepare("call sp_groesste(:kategorie, :startdatum, :marktid); ");
        $query->execute([
            'kategorie' => $this->kategorie,
            'startdatum' => $this->startdatum,
            "marktid" => $this->marktid
        ]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        //var_dump($result);
        return $result;
    }

    /**
     * Stored Procedure zur Berechnung der Standardabweichung der Umsätze pro Woche.
     * @author Marcel Bitschi
     */
    private function berechne_standardabweichung(): array
    {
        include "db.inc.php";
        $query = $db->prepare("call sp_standardabweichung(:kategorie, :startdatum, :marktid); ");
        $query->execute([
            'kategorie' => $this->kategorie,
            'startdatum' => $this->startdatum,
            "marktid" => $this->marktid
        ]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Stored Procedure zur Berechnung des Medians aller Umsätze je Woche aufrufen.
     * @author Felix Huber
     */
    private function berechne_median(): array
    {
        include "db.inc.php";
        $query = $db->prepare("call sp_median(:kategorie, :startdatum, :marktid); ");
        $query->execute([
            'kategorie' => $this->kategorie,
            'startdatum' => $this->startdatum,
            "marktid" => $this->marktid
        ]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        //var_dump($result);
        return $result;
    }

    /**
     *  Regressionsfunktion soll für aktuelle und folgende Woche Umsatz vorhersagen.
     * @author Patricia Schäle
     */
    function berechne_lineare_regression(): array
    {
        foreach ($this->getGesamtumsatz() as $row) {
            $daten[] = date('W', strtotime($row["start_date"]));
            $umsaetze[] = $row["total"];
        }

        $xWerte = $daten;
        $yWerte = $umsaetze;

        if (count($xWerte) !== count($yWerte)) {
            echo 'Es ist ein Fehler aufgetreten.';
        }

        //Summen berechnen
        $summe_x = 0;
        $summe_y = 0;
        $summe_xx = 0;
        $summe_xy = 0;

        $count = count($xWerte);
        for ($i = 0; $i < $count; $i++) {
            $summe_x += $xWerte[$i];
            $summe_y += $yWerte[$i];
            $summe_xx += $xWerte[$i] * $xWerte[$i];
            $summe_xy += $xWerte[$i] * $yWerte[$i];
        }

        //Steigung and achsenabschnitt(Verschiebung)
        // n = Anzahl
        // S = Summe
        //steigung = (nSxy - SxSy) / (nSxx - SxSx)
        $steigung = ($count * $summe_xy - $summe_x * $summe_y) / ($count * $summe_xx - $summe_x * $summe_x);

        //Achsenabschnitt
        $achsenabschnitt = ($summe_y / $count) - ($steigung * $summe_x) / $count;

        $dieseWoche = date('W');
        $vorhersage1 = (($dieseWoche * $steigung) + $achsenabschnitt);
        $naechsteWoche = date('W', strtotime('+1 week'));
        $vorhersage2 = (($naechsteWoche * $steigung) + $achsenabschnitt);
        var_dump($vorhersage2);

        return [
            $dieseWoche => number_format($vorhersage1, 2, '.', ','),
            $naechsteWoche => number_format($vorhersage2, 2, '.', ','),
        ];

    }

    /**
     * Getter für das Ergebnis der Berechnung des Gesamtumsatzes.
     * @author Felix Huber
     */
    function getGesamtumsatz(): array
    {
        return $this->gesamtumsatz;
    }

    /**
     * Getter für das Ergebnis der Berechnung der größten Bestellung.
     * @author Felix Huber
     */
    function getGroesste(): array
    {
        return $this->groesste;
    }

    /**
     * Getter für das Ergebnis der Berechnung des Medians.
     * @author Felix Huber
     */
    function getMedian(): array
    {
        return $this->median;
    }

    /**
     * Getter für das Ergebnis der Berechnung der Standardabweichung.
     * @author Marcel Bitschi
     */
    function getStandardabweichung(): array
    {
        return $this->standardabweichung;
    }

    /**
     * Getter für das Ergebnis der Berechnung der linearen Regression.
     * @author Marcel Bitschi
     */
    function getLineareRegression(): array
    {
        return $this->lineare_regression;
    }
}