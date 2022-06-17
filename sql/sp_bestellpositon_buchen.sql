/*
@author Patricia Schäle

Allgemeine Erläuterung:
Stored Procedure dient dazu die Bestellpositionen der Kundenbestellung anzulegen.

*/
DELIMITER $$
CREATE
    DEFINER = `root`@`localhost` PROCEDURE `bestellposition_buchen`(IN `p_bestellnr` INT(11),
                                                                    IN `p_positionsnr` INT(11), IN `p_anzahl` INT(11),
                                                                    IN `p_getraenkename` VARCHAR(40),
                                                                    IN `p_hersteller` VARCHAR(60))
BEGIN
    DECLARE marktid int(11);
    DECLARE current_amount int(11);
    SELECT b.marktid INTO marktid from bestellung b where b.bestellnr = p_bestellnr;
    SELECT f.lagerbestand
    INTO current_amount
    FROM fuehrt f
    where f.getraenkename = p_getraenkename
      and f.hersteller = p_hersteller
      and f.marktid = marktid;

    if p_anzahl > current_amount THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Die gewünschte Anzahl ist höher als der Lagerbestand';
    end if;

    INSERT INTO bestellposition(anzahl, bestellnr, getraenkename, hersteller, positionsnr)
    VALUES (p_anzahl, p_bestellnr, p_getraenkename, p_hersteller, p_positionsnr);

END$$
DELIMITER ;