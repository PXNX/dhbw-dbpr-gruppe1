create table bestellposition
(
    positionsnr   int         not null,
    anzahl        int         not null,
    bestellnr     int         not null,
    getraenkename varchar(40) null,
    hersteller    varchar(60) null,
    primary key (positionsnr, bestellnr),
    constraint bestellposition_ibfk_1
        foreign key (bestellnr) references bestellung (bestellnr),
    constraint bestellposition_ibfk_2
        foreign key (getraenkename, hersteller) references getraenk (getraenkename, hersteller)
);

create index bestellnr
    on bestellposition (bestellnr);

create index getreankename
    on bestellposition (getraenkename, hersteller);
