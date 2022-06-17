-- @author Felix Huber
CREATE TRIGGER tr_lagerbestand_buchen
    AFTER INSERT
    ON bestellposition
    FOR EACH ROW
BEGIN
    declare anzahl int(11);

    -- aktuellen Lagerbestand des zu entnehmenden Getränks beziehen
    select f.lagerbestand
    into anzahl
    from fuehrt f,
         bestellung b
    where f.getraenkename = new.getraenkename
      and f.hersteller = new.hersteller
      and b.bestellnr = new.bestellnr
      and b.marktid = f.marktid;

    -- schauen ob zu viel gewünscht wird
    IF new.anzahl > anzahl
    THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Die gewünschte Anzahl ist höher als der Lagerbestand';
    end if;

    -- Lagerbestand verringern
    UPDATE fuehrt f, bestellung b
    set f.lagerbestand = f.lagerbestand - new.anzahl
    where f.getraenkename = new.getraenkename
      and f.hersteller = new.hersteller
      and b.bestellnr = new.bestellnr
      and b.marktid = f.marktid;
END;