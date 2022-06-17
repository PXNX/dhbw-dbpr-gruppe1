<?php


include "db.inc.php";

class Auswertung
{
    private $gesamtumsatz;
    private $groesste;
    private $startdatum;
    private $kategorie;
    private $marktid;
    // private $kalenderWochen;
    // private $betrachtungszeitraumRegression;
    // private $wochenUmsaetze;
    // private $standardabweichungen;
    // private $umsatzFolgewoche;


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
        $this->standardabweichungen = $this->berechne_standardabweichung();
        $this->median = $this->berechne_median();
    }

    /**
     * Stored Procedure zur Berechnung des Gesamtumsatzes je Woche aufrufen.
     * @author Felix Huber
     */
    public function berechne_gesamtumsatz(): array
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
    public function berechne_groesste(): array
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
     * Stored Procedure zur Berechnung des Medians aller Umsätze je Woche aufrufen.
     * @author Felix Huber
     */
    public function berechne_median(): array
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
     * Stored Procedure zur Berechnung der Standardabweichung der Umsätze pro Woche.
     * @author Marcel Bitschi
     */
    public function berechne_standardabweichung(): array
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

    function calculateKennzahlen(string $start_datum, string $kategorie)
    {

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
        return $this->standardabweichungen;
    }


    /*
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

          return  echo' '$umsatz' '$umsatz2'';

      }
  */


    /** @author Patricia Schäle */
    function arithmetischesMittel($werte)
    {
        if (count($werte)) {
            $count = count($werte);
            foreach ($werte as $w) {
                $summe += $w;
            }
            return $arithmetischesMittel = $summe / $count;
        }
    }

}
