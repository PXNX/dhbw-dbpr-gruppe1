-- @author Felix Huber
DELIMITER $$
CREATE
    DEFINER = `root`@`localhost` PROCEDURE `sp_gesamt`(p_kategorie varchar(30), p_start_date date, p_marktid int(11))
begin


    declare start_date date default p_start_date;
    declare end_date date default date_add(start_date, interval 1 week);

    -- temporäre Tabelle zur Speicherung der Zwischenergebnisse
    drop TEMPORARY table if exists temp_res;
    create temporary table temp_res
    (
        start_date date primary key,
        total      DECIMAL(13, 2)
    );

    -- hiermit werden die Wochen durchlaufen
    l1:
    while start_date <= current_date
        do

            insert into temp_res
                -- aufsummieren der Umsätze
            Select start_date,
                   sum(einzel_umsatz.total)
                   -- die Umsätze einer Woche nehmen
            from (SELECT g.preis * p.anzahl as total
                  from bestellposition p,
                       bestellung b,
                       getraenk g
                  where b.marktid = p_marktid
                    and p.bestellnr = b.bestellnr
                    and p.getraenkename = g.getraenkename
                    and p.hersteller = g.hersteller
                    and g.kategorie LIKE coalesce(p_kategorie, '%')
                    and b.bestelldatum between start_date and end_date) as einzel_umsatz;

            -- eine Woche in die Zukunft schreiten
            set start_date = end_date;
            set end_date = date_add(end_date, interval 1 week);

        end while l1;

-- Ergebnisse selektieren damit sie ausgegeben werden
    select * from temp_res;
END$$
DELIMITER ;