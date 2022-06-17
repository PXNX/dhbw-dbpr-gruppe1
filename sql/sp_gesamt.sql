DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gesamt`(p_kategorie varchar(30), p_start_date date, p_marktid int(11))
begin


    declare start_date date default p_start_date;
    declare end_date date default date_add(start_date, interval 1 week);

    drop TEMPORARY table if exists temp_res;
    create temporary table temp_res
    (
        start_date date primary key,
        total      DECIMAL(7, 2)
    );

    l1:
    while start_date <= current_date
        do

            insert into temp_res
            Select start_date, sum(einzel_umsatz.total)
            from (SELECT g.preis * p.anzahl as total
                  from bestellposition p,
                       bestellung b,
                       getraenk g
                  where b.marktid = p_marktid
                    and p.bestellnr = b.bestellnr
                    and p.getraenkename = g.getraenkename
                    and p.hersteller = g.hersteller
                    and g.kategorie LIKE coalesce(p_kategorie,'%')
                    and b.bestelldatum between start_date and end_date) as einzel_umsatz;

            set start_date = end_date;
            set end_date = date_add(end_date, interval 1 week);

        end while l1;


    select * from temp_res;
END$$
DELIMITER ;