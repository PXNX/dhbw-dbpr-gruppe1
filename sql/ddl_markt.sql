create table markt
(
    marktid       int          not null
        primary key,
    marktname     varchar(50)  not null,
    -- Laut https://www.php.net/password_hash können Passwort-Hashes eine Länge > 60 Zeichen einnehmen. 255 Sei eine gute Wahl.
    marktkennwort varchar(255) not null
);