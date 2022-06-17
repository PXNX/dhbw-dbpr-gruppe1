create table fuehrt
(
    lagerbestand  int         not null,
    marktid       int         not null,
    getraenkename varchar(40) not null,
    hersteller    varchar(60) not null,
    constraint fuehrt_ibfk_1
        foreign key (marktid) references markt (marktid),
    constraint fuehrt_ibfk_2
        foreign key (getraenkename) references getraenk (getraenkename),
    constraint fuehrt_ibfk_3
        foreign key (hersteller) references getraenk (hersteller),
    primary key (hersteller, getraenkename, marktid)
);

