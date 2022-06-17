-- @autor Marcel Bitschi
DELIMITER $$
CREATE
    DEFINER = `root`@`localhost` PROCEDURE `sp_bestandsanpassung`(p_market VARCHAR(50),
                                                                  p_hersteller VARCHAR(50),
                                                                  p_name VARCHAR(50), p_bestand INT)
BEGIN
    DECLARE anzahl INT DEFAULT 0;
    SELECT COUNT(*)
    INTO anzahl
    FROM fuehrt
    WHERE hersteller = p_hersteller
      AND getraenkename = p_name
      AND marktid = p_market;
-- Wenn ein Bestand vorhanden ist wird dieser geupdated
    IF
        (anzahl = 1)
    THEN
        UPDATE fuehrt
        SET lagerbestand = p_bestand
        WHERE hersteller = p_hersteller
          AND getraenkename = p_name
          AND marktid = p_market;
-- Falls kein Bestand zu diesem Getränk vorhanden ist, wird es hier angelegt
    ELSE
        INSERT INTO fuehrt (hersteller, getraenkename, lagerbestand, marktid)
        VALUES (p_hersteller, p_name, p_bestand, p_market);
    END IF;
end$$
DELIMITER ;