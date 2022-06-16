create table fuehrt
(
    lagerbestand  int         not null,
    marktid       int         not null,
    getraenkename varchar(40) not null,
    hersteller    varchar(60) not null,
    primary key (hersteller, getraenkename, marktid)
);

