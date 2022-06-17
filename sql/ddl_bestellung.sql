create table bestellung
(
    bestellnr    int auto_increment not null
        primary key,
    bestelldatum date        null,
    marktid      int         null,
    mailadresse  varchar(60) null,
    constraint bestellung_ibfk_1
        foreign key (marktid) references markt (marktid),
    constraint bestellung_ibfk_2
        foreign key (mailadresse) references kunde (mailadresse)
);

create index mailaddresse
    on bestellung (mailadresse);

create index marktid
    on bestellung (marktid);

