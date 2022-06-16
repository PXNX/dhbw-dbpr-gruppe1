<?php
include "db.inc.php";

class Auswertung
{
    private $gesamtumsatz;
    private $bestellung;
    private $startdatum;
    private $kategorie;
    private $marktid;
    private $kalenderWochen;
    private $betrachtungszeitraumRegression;
    private $wochenUmsaetze;
    private $standardabweichungen;
    // private $umsatzFolgewoche;

    /** @author Felix Huber */
    public function __construct($marktid, $startdatum, $kategorie)
    {
        $this->gesamtumsatz = 0.0;
        $this->bestellung = 0.0;
        $this->standardabweichungen = 0.0;
        $this->median = 0.0;

        $this->gesamtumsatz_berchnen($marktid, $startdatum, $kategorie);

        $this->marktid = $marktid;
        $this->startdatum = $startdatum;
        $this->kategorie = $kategorie;
    }

    /** @author Marcel Bitschi */
    public function setWochenUmsaetze($connection, $kalenderWochen)
    {
        $marktid = $this->marktid;
        $kategorie = $this->kategorie;
        $wochenUmsaetze = [];
        $stmt = $connection->prepare("SELECT SUM(bp.anzahl * g.preis) as Umsatz 
			from bestellposition bp, getraenk g, bestellung b 
			where bp.bestellnummer = b.bestellnr 
			AND g.hersteller = bp.hersteller 
			AND g.getraenkename = bp.getraenkename 
			AND b.marktid = ?
			AND g.kategorie like ?
			AND b.bestelldatum BETWEEN ?
		    AND ?
			group by b.bestellnr;");
        foreach ($kalenderWochen as $key => $value) {
            $stmt->execute([$marktid, $kategorie, $value['startzeitpunkt'], $value['endzeitpunkt']]);

            $ergebnis = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $umsatzArray = [];
            foreach ($ergebnis as $umsatz) {
                $umsatzArray[] = $umsatz['Umsatz'];
            }
            $wochenUmsaetze[$key] = $umsatzArray;
        }
        return $wochenUmsaetze;
    }

    public function getWochen()
    {
        return $this->kalenderWochen;
    }

    /** @author Marcel Bitschi */
    public function getWochenumsatz()
    {
        $kalenderWochen = $this->kalenderWochen;
        $umsaetze = $this->wochenUmsaetze;
        $this->wochenUmsatzSummen = $this->umsatzSummenBerechnen($kalenderWochen, $umsaetze);
        return $this->wochenUmsatzSummen;
    }

    /** @author Marcel Bitschi */
    private function umsatzSummenBerechnen($kalenderWochen, $umsaetze)
    {
        $wochenUmsatzSummen = [];
        foreach ($kalenderWochen as $key => $value) {
            $umsatzsumme = 0;
            if (count($umsaetze[$key])) {
                foreach ($umsaetze[$key] as $umsatz) {
                    $umsatzsumme += $umsatz;
                }
            }
            $wochenUmsatzSummen[$key] = $umsatzsumme;
        }

        return $wochenUmsatzSummen;
    }

    /** @author Marcel Bitschi */
    public function getStandardabweichung()
    {
        $kalenderWochen = $this->kalenderWochen;
        $umsaetze = $this->wochenUmsaetze;
        $this->standardabweichungen = $this->standardabweichungBerechnung($kalenderWochen, $umsaetze);
        return $this->standardabweichungen;
    }

    /** @author Marcel Bitschi */
    private function standardabweichungBerechnung($kalenderWochen, $umsaetze)
    {
        $standardabweichungen = [];
        if (is_array($kalenderWochen) || is_object($kalenderWochen)) {
            foreach ($kalenderWochen as $key => $value) {
                $standardabweichung = 0;
                $umsatzsumme = 0;
                $umsatzQuadratsumme = 0;
                if (count($umsaetze[$key])) {
                    $count = count($umsaetze[$key]);
                    foreach ($umsaetze[$key] as $umsatz) {
                        $umsatzsumme += $umsatz;
                        $umsatzQuadratsumme += $umsatz ** 2;
                    }

                    $arithmetischesMittel = $umsatzsumme / $count;

                    $varianz = 1 / $count * $umsatzQuadratsumme - ($arithmetischesMittel ** 2);
                    $standardabweichung = sqrt($varianz);
                }
                $standardabweichungen[$key] = $standardabweichung;
            }
        }
        return $standardabweichungen;
    }

    function calculateKennzahlen(string $start_datum, string $kategorie)
    {

    }

    function getGesamtumsatz(): float
    {
        return $this->gesamtumsatz;
    }

    function getBestellung(): float
    {
        return $this->bestellung;
    }

    function getMedian(): float
    {
        return $this->median;
    }


  
    //Regressionsfunktion
    //Eingabe soll für aktuelle und folgende Woche Umsatz vorhersagen
    function regression($x)
    {
        $aktuellesDatum=$x;
        $kw = date("W", $aktuellesDatum);
        $kw2 = $kw -> addWeek(1);

        //Daten x Werte (Zeitwerte)
        foreach($kalenderWochen as $key => $woche){
            $xWerte[] = $woche[$key];
        }

        //Daten y Werte  
        foreach($wochenUmsaetze as $u){
            $yWerte[] = $u;
        }

        //Mittelwert x und y Werte
        $xMittelwert = arithmetischesMittel($xWerte[]);
        $yMittelwert = arithmetischesMittel($yWerte[]);

        //Abweichung von x und y vom Mittelwert 
        $xAbweichung[] = $xWerte[] - $xMittelwert;
        $yAbweichung[] = $yWerte[] - $yMittelwert;

        //Quadrierte Abweichung von x
        $xQuadriert[] = pow($xAbweichung, 2);

        //Abweichung x * Abweichung y -> (xi - ∅x) × (yi - ∅y)
        $xyAbweichung[] = $xAbweichung[] * $yAbweichung[];

        //Steigung berechnen -> Nun wird die Summe der multiplizierten Abweichungen 
        //durch die Summe der quadrierten Abweichungen von x geteilt -> β = ∑ [(xi - ∅x) × (yi - ∅y)] / ∑(xi - ∅x)2

        foreach($xQuadriert as $quad){
            $sumQuadriert += $quad;
        } 

        foreach($xyAbweichung as $xyab){
            $sumAbweichung += $xyab;
        }

        $steigung = $sumQuadriert / $sumAbweichung;

        //Achsenabschnitt berechnen -> α = ∅y - β × ∅x
        $achsenabschnitt = $yMittelwert - $yMittelwert*$xMittelwert;
        

        //Regressionsgerade -> yi = α + β × xi
        $umsatz = $achsenabschnitt + ($x * $steigung);

        //Regression Folgewoche
        $umsatz2 = $achsenabschnitt + ($x * $steigung);

        return echo' '$umsatz' '$umsatz2'';
        
    }


    /** @author Patricia Schäle */
    function arithmetischesMittel($werte){
        if (count($werte)) {
            $count = count($werte);
            foreach ($werte as $w) {
                $summe += $w;
            }
        return $arithmetischesMittel = $summe / $count;
    }
    }




    /** @author Marcel Bitschi */
    private function setKalenderWochen($startdatum)
    {
        date_default_timezone_set('Europe/Berlin');
        $startzeitpunkt = $startdatum;

        $kalenderWochen = [];
        do {
            $kw = date("W Y", $startzeitpunkt);
            $endzeitpunkt = $this->letzterWochentag($startzeitpunkt);
            $startzeitpunkt_formatiert = date("Y-m-d H:i:s", $startzeitpunkt);
            $endzeitpunkt_formatiert = date("Y-m-d H:i:s", $endzeitpunkt);


            $kalenderWochen[$kw]['startzeitpunkt'] = $startzeitpunkt_formatiert;
            $kalenderWochen[$kw]['endzeitpunkt'] = $endzeitpunkt_formatiert;


            $startzeitpunkt = $endzeitpunkt + 1;
            $endzeitpunkt = $this->letzterWochentag($startzeitpunkt);
        } while ($startzeitpunkt < time());

        return $kalenderWochen;
    }

    private function gesamtumsatz_berchnen($marktid, $startdatum, $kategorie)
    {
        include "db.inc.php";
        $query = $db->prepare("call sp_gesamt(:kategorie, :startdatum); "); // :marktid
        $result = $query->execute([<
            ':kategorie' => $kategorie,
            ':startdatum' => $startdatum,

            ]);  //':marktid' => $_SESSION['marktid']
    }


}
