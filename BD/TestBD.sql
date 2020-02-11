--
-- ER/Studio 8.0 SQL Code Generation
-- Company :      DEVRAM
-- Project :      BD Test.DM1
-- Author :       Sensei-Ramón
--
-- Date Created : Monday, February 10, 2020 18:12:46
-- Target DBMS : MySQL 5.x
--

-- 
-- TABLE: datosfiscales 
--

CREATE TABLE datosfiscales(
    kcvedatosfiscales    INT             AUTO_INCREMENT,
    orfc                 VARCHAR(15),
    orazonsocial         VARCHAR(300),
    rcveestado           INT,
    rcvemunicipio        INT,
    odireccion           VARCHAR(600),
    ocolonia             VARCHAR(100),
    ocodigopostal        VARCHAR(5),
    oemail               VARCHAR(120),
    istatus              VARCHAR(1),
    ifecins              DATETIME,
    iusrins              VARCHAR(45),
    ifecmod              DATETIME,
    iusrmod              VARCHAR(45),
    PRIMARY KEY (kcvedatosfiscales)
)ENGINE=INNODB
;



-- 
-- TABLE: estados 
--

CREATE TABLE estados(
    kcveestado    INT             AUTO_INCREMENT,
    onombre       VARCHAR(150),
    istatus       VARCHAR(1),
    ifecins       DATETIME,
    iusrins       VARCHAR(45),
    ifecmod       DATETIME,
    iusrmod       VARCHAR(45),
    PRIMARY KEY (kcveestado)
)ENGINE=INNODB
;



-- 
-- TABLE: municipio 
--

CREATE TABLE municipio(
    kcvemunicipio    INT             AUTO_INCREMENT,
    kcveestado       INT             NOT NULL,
    onombre          VARCHAR(200),
    istatus          VARCHAR(1),
    ifecins          DATETIME,
    iusrins          VARCHAR(45),
    ifecmod          DATETIME,
    iusrmod          VARCHAR(45),
    PRIMARY KEY (kcvemunicipio)
)ENGINE=INNODB
;



-- 
-- TABLE: producto 
--

CREATE TABLE producto(
    kcveproducto      INT               AUTO_INCREMENT,
    onombre           VARCHAR(200),
    oclave            VARCHAR(45),
    ocostounitario    DECIMAL(10, 2),
    istatus           VARCHAR(1),
    ifecins           DATETIME,
    iusrins           VARCHAR(45),
    ifecmod           DATETIME,
    iusrmod           VARCHAR(45),
    PRIMARY KEY (kcveproducto)
)ENGINE=INNODB
;



-- 
-- TABLE: municipio 
--

ALTER TABLE municipio ADD CONSTRAINT Refestados1 
    FOREIGN KEY (kcveestado)
    REFERENCES estados(kcveestado)
;


