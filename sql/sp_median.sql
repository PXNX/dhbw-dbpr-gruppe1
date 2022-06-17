-- @author Felix Huber
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_median`(p_kategorie varchar(30), p_start_date date, p_marktid int(11))
begin

    declare start_date date default p_start_date;
    DECLARE entry_amount int;
    declare end_date date default date_add(start_date, interval 1 week);

    -- temporäre Tabelle zur Speicherung der Zwischenergebnisse
    drop TEMPORARY table if exists temp_res;
    create temporary table temp_res
    (
        start_date date primary key,
        bestellnr  int(11),
        total      DECIMAL(13, 2)
    );


    -- hiermit werden die Wochen durchlaufen
    l1:
    while start_date <= current_date
        do

        -- Anzahl der Umsätze je Woche halbieren und aufrunden wie von Herr Jeske beigebracht
            set entry_amount := (SELECT CEIL(COUNT(*) / 2)

                                 FROM bestellposition p,
                                      bestellung b,
                                      getraenk g
                                 where b.marktid = p_marktid
                                   and p.bestellnr = b.bestellnr
                                   and p.getraenkename = g.getraenkename
                                   and p.hersteller = g.hersteller
                                   and b.bestelldatum between start_date and end_date
                                   and g.kategorie LIKE coalesce(p_kategorie,'%'));

            insert into temp_res
            -- der höchste Umsatz ist hierbei Median
            SELECT start_date, bestellnr, max(half.total)
            -- die Hälfte der geordneten Umsätze einer Woche nehmen
            FROM (SELECT b.bestellnr, sum(g.preis * p.anzahl) as total
                  from bestellposition p,
                       bestellung b,
                       getraenk g
                  where b.marktid = p_marktid
                    and p.bestellnr = b.bestellnr
                    and p.getraenkename = g.getraenkename
                    and p.hersteller = g.hersteller
                    and g.kategorie LIKE coalesce(p_kategorie,'%')
                    and b.bestelldatum between start_date and end_date
                  group by p.bestellnr
                  order by total
                  limit entry_amount) as half;

            -- eine Woche in die Zukunft schreiten
            set start_date = end_date;
            set end_date = date_add(end_date, interval 1 week);

        end while l1;

-- Ergebnisse selektieren damit sie ausgegeben werden
    select * from temp_res;


end$$
DELIMITER ;