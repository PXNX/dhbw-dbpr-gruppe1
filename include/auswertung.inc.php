<?php class Auswertung{

    private  $gesamtumsatz;
    private $bestellung;
private $standardabweichung;
private $median;

public function __construct(){
    $this->gesamtumsatz=0.0;
    $this->bestellung=0.0;
    $this->standardabweichung=0.0;
    $this->median=0.0;
}

    function calculateKennzahlen( string $start_datum, string $kategorie){

    }

    function getGesamtumsatz(): float{
        return $this->gesamtumsatz;
    }

    function getBestellung(): float{
        return $this->bestellung;
    }

    function getStandardabweichung(): float{
        return $this->standardabweichung;
    }

    function getMedian(): float{
        return $this->median;
    }
}

?>