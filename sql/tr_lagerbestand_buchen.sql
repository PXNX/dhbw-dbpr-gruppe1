CREATE TRIGGER lagerbestand_buchen
    AFTER INSERT
    ON bestellposition
    FOR EACH ROW
BEGIN
    declare anzahl int(11);

    select f.lagerbestand
    into anzahl
    from fuehrt f,
         bestellung b
    where f.getraenkename = new.getraenkename
      and f.hersteller = new.hersteller
      and b.bestellnr = new.bestellnr
      and b.marktid = f.marktid;

    IF new.anzahl > anzahl
    THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Die gewünschte Anzahl ist höher als der Lagerbestand';
    end if;

    UPDATE fuehrt f, bestellung b
    set f.lagerbestand = f.lagerbestand - new.anzahl
    where f.getraenkename = new.getraenkename
      and f.hersteller = new.hersteller
      and b.bestellnr = new.bestellnr
      and b.marktid = f.marktid;
END;