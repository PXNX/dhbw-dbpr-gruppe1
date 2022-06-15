DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `bestandsanpassung`(IN `param_market` VARCHAR(50), 
IN `param_hersteller` VARCHAR(50), IN `param_name` VARCHAR(50), IN `param_bestand` INT)
BEGIN
	DECLARE anzahl INT DEFAULT 0;
    SELECT COUNT(*) INTO anzahl FROM fuehrt WHERE hersteller = param_hersteller 
    AND getraenkename = param_name AND marktid = param_market;
    IF (anzahl = 1) THEN
    	UPDATE fuehrt SET lagerbestand = param_bestand WHERE hersteller = param_hersteller 
        AND getraenkename = param_name AND marktid = param_market;
    ELSE
    	INSERT INTO fuehrt (hersteller, getraenkename, lagerbestand, marktid) 
        VALUES (param_hersteller, param_name, param_bestand, param_market);
    END IF;

END$$
DELIMITER ;