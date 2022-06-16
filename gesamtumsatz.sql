DELIMITER $$
CREATE
DEFINER
=
root
`@`localhost PROCEDURE auswertung_umsatz(IN input_date DATE, IN input_kategorie VARCHAR(50))
BEGIN declare start_date date default input_date;
declare end_date date default date_add(input_date, interval 1 week);
DECLARE umsatz DECIMAL(7,2) DEFAULT 0.0;
DECLARE done INT DEFAULT FALSE;
DECLARE CONTINUE
HANDLER FOR NOT FOUND SET done = TRUE;


DECLARE cur_week CURSOR FOR
SELECT sum(g.preis)
from bestellposition p,
     bestellung b,
     getraenk g
where p.bestellnr = b.bestellnr
  and p.getreankename = g.getraenkename
  and p.hersteller = g.hersteller
  and g.kategorie LIKE COALESCE(input_kategorie, '%')
  and b.bestelldatum between start_date and end_date;


CREATE TEMPORARY TABLE total_umsatz
(
    week   date primary key,
    umsatz decimal(7, 2)
);

call printf('Counting products that have missing short description');

all_weeks: while end_date <= current_date
do
    OPEN cur_week;
loop_week: LOOP
    FETCH cur_week INTO umsatz;
INSERT INTO total_umsatz
VALUES (start_date, umsatz);
IF done THEN
      LEAVE loop_week;
END IF;

END loop loop_week;

CLOSE  cur_week;

set start_date = end_date;
set end_date = date_add(end_date, interval 1 week);

end while all_weeks;
END$$
DELIMITER ;