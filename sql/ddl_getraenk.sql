create table getraenk
(
    getraenkename varchar(40)                                                      not null,
    hersteller    varchar(60)                                                      not null,
    preis         decimal(7, 2)                                                    null,
    kategorie     enum ('Wasser', 'Saft', 'Limonade', 'Wein', 'Bier', 'Sonstiges') null,
    primary key (getraenkename, hersteller)
);
