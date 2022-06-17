DELIMITER $$
CREATE
    DEFINER = `root`@`localhost` PROCEDURE `bestandsanpassung`(IN `p_market` VARCHAR(50),
                                                               IN `p_hersteller` VARCHAR(50),
                                                               IN `p_name` VARCHAR(50), IN `p_bestand` INT)
BEGIN
    DECLARE anzahl INT DEFAULT 0;
    SELECT COUNT(*)
    INTO anzahl
    FROM fuehrt
    WHERE hersteller = p_hersteller
      AND getraenkename = p_name
      AND marktid = p_market;
    IF (anzahl = 1) THEN
        UPDATE fuehrt
        SET lagerbestand = p_bestand
        WHERE hersteller = p_hersteller
          AND getraenkename = p_name
          AND marktid = p_market;
    ELSE
        INSERT INTO fuehrt (hersteller, getraenkename, lagerbestand, marktid)
        VALUES (p_hersteller, p_name, p_bestand, p_market);
    END IF;

END$$
DELIMITER ;