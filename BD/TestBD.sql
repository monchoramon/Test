--
-- ER/Studio 8.0 SQL Code Generation
-- Company :      DEVRAM
-- Project :      BD Test.DM1
-- Author :       Sensei-Ram�n
--
-- Date Created : Friday, February 14, 2020 23:05:21
-- Target DBMS : MySQL 5.x
--

-- 
-- TABLE: compras 
--

CREATE TABLE compras(
    id_compras           INT            AUTO_INCREMENT,
    cantidad             FLOAT(8, 0)    NOT NULL,
    descuento            FLOAT(8, 0)    NOT NULL,
    iva                  FLOAT(8, 0)    NOT NULL,
    total                FLOAT(8, 0)    NOT NULL,
    kcvedatosfiscales    INT            NOT NULL,
    kcveproducto         INT            NOT NULL,
    PRIMARY KEY (id_compras)
)ENGINE=INNODB
;



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
-- TABLE: otros_datos 
--

CREATE TABLE otros_datos(
    id_otros            INT             AUTO_INCREMENT,
    fecha_expedicion    DATETIME,
    folio               INT,
    forma_pago          VARCHAR(100)    NOT NULL,
    cfdi                VARCHAR(100)    NOT NULL,
    metodo_pago         VARCHAR(10)     NOT NULL,
    n_cuenta            VARCHAR(100)    NOT NULL,
    id_compras          INT             NOT NULL,
    PRIMARY KEY (id_otros)
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
-- TABLE: compras 
--

ALTER TABLE compras ADD CONSTRAINT Refdatosfiscales18 
    FOREIGN KEY (kcvedatosfiscales)
    REFERENCES datosfiscales(kcvedatosfiscales)
;

ALTER TABLE compras ADD CONSTRAINT Refproducto19 
    FOREIGN KEY (kcveproducto)
    REFERENCES producto(kcveproducto)
;


-- 
-- TABLE: municipio 
--

ALTER TABLE municipio ADD CONSTRAINT Refestados1 
    FOREIGN KEY (kcveestado)
    REFERENCES estados(kcveestado)
;


-- 
-- TABLE: otros_datos 
--

ALTER TABLE otros_datos ADD CONSTRAINT Refcompras16 
    FOREIGN KEY (id_compras)
    REFERENCES compras(id_compras)
;


