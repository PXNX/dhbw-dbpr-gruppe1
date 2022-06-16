create table kunde
(
    mailadresse    varchar(60)  not null
        primary key,
    kundenname     varchar(40)  null,
    kundenadresse  varchar(40)  null,
    kundenkennwort varchar(255) null
-- passwort sollte laut php so lange sein, sofern ich mich korrekt erinnere
);