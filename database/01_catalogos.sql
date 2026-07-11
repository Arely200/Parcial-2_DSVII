-- =========================================================
-- iTECH Contrataciones - Script 1: Base de datos y catálogos
-- =========================================================

CREATE DATABASE IF NOT EXISTS itech_contrataciones
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE itech_contrataciones;

-- -------------------------------------------
-- Catálogo: Tipos de planilla
-- -------------------------------------------
CREATE TABLE cat_tipos_planilla (
    id_tipo_planilla INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_tipos_planilla (nombre) VALUES
    ('Permanente'),
    ('Eventual'),
    ('Interino');

-- -------------------------------------------
-- Catálogo: Ocupaciones
-- -------------------------------------------
CREATE TABLE cat_ocupaciones (
    id_ocupacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_ocupaciones (nombre) VALUES
    ('Secretaria'),
    ('Albañil'),
    ('Ingeniero'),
    ('Supervisor'),
    ('Chofer'),
    ('Administrador');

-- -------------------------------------------
-- Catálogo: Rutas
-- -------------------------------------------
CREATE TABLE cat_rutas (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_rutas (nombre) VALUES
    ('Panamá Este'),
    ('Panamá Oeste'),
    ('Panamá Norte');

-- -------------------------------------------
-- Catálogo: Tipos de sangre
-- -------------------------------------------
CREATE TABLE cat_tipos_sangre (
    id_tipo_sangre INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(5) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_tipos_sangre (nombre) VALUES
    ('A+'), ('A-'), ('B+'), ('B-'),
    ('AB+'), ('AB-'), ('O+'), ('O-');
