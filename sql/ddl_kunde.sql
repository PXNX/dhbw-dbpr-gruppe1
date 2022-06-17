create table kunde
(
    mailadresse    varchar(60)  not null
        primary key,
    kundenname     varchar(40)  null,
    -- Laut https://www.php.net/password_hash können Passwort-Hashes eine Länge > 60 Zeichen einnehmen. 255 Sei eine gute Wahl.
    kundenkennwort varchar(255) null,
    hausnummer     varchar(10)  not null,
    strassenname   varchar(80)  not null,
    plz            char(5)      not null,
    ort            varchar(80)  not null
);