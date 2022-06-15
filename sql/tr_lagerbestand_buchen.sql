CREATE TRIGGER lagerbestand_buchen AFTER INSERT ON bestellposition  FOR EACH ROW
BEGIN

    UPDATE fuehrt f, bestellung b set f.lagerbestand =  f.lagerbestand- new.anzahl where f.getraenkename= new.getraenkename
                                                                                     and f.hersteller = new.hersteller and b.bestellnr = new.bestellnr and b.marktid = f.marktid;
END;