
USE itech_contrataciones;

-- -------------------------------------------
-- Tabla: Colaboradores (ADAPTADA)
-- -------------------------------------------
DROP TABLE IF EXISTS colaboradores;
CREATE TABLE colaboradores (
    id_colaborador INT AUTO_INCREMENT PRIMARY KEY,
    identidad VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(60) NOT NULL,
    apellido VARCHAR(60) NOT NULL,
    edad TINYINT UNSIGNED NOT NULL,
    id_tipo_sangre INT NOT NULL,
    id_sexo INT NOT NULL,  -- Cambio: ahora usa id de cat_sexo
    nacionalidad VARCHAR(50) NOT NULL,
    id_ruta INT NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    celular VARCHAR(9) NOT NULL,
    id_estado_civil INT DEFAULT 1, -- Nuevo campo
    fecha_registro DATE NOT NULL DEFAULT (CURRENT_DATE),

    CONSTRAINT chk_edad CHECK (edad BETWEEN 18 AND 99),
    CONSTRAINT chk_correo CHECK (correo LIKE '%_@_%._%'),
    CONSTRAINT chk_celular CHECK (celular REGEXP '^6[0-9]{3}-[0-9]{4}$'),

    CONSTRAINT fk_colab_tipo_sangre FOREIGN KEY (id_tipo_sangre)
        REFERENCES tiposangre(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_colab_sexo FOREIGN KEY (id_sexo)
        REFERENCES cat_sexo(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_colab_ruta FOREIGN KEY (id_ruta)
        REFERENCES cat_rutas(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_colab_estado_civil FOREIGN KEY (id_estado_civil)
        REFERENCES cat_estadocivil(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------
-- Tabla: Perfiles Laborales (ADAPTADA)
-- -------------------------------------------
DROP TABLE IF EXISTS perfiles_laborales;
CREATE TABLE perfiles_laborales (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    codigo_empleado INT NOT NULL,
    id_ocupacion INT NOT NULL,
    id_tipo_empleado INT NOT NULL,  -- Cambio: ahora usa cat_tipoempleado
    salario DECIMAL(10,2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL,
    cargo_activo TINYINT(1) NOT NULL DEFAULT 1,
    empleado_activo TINYINT(1) NOT NULL DEFAULT 1,
    id_motivo_terminacion INT NULL,  -- Nuevo campo
    motivo_baja VARCHAR(255) NULL,
    firma_digital TEXT NULL,

    CONSTRAINT chk_salario CHECK (salario > 0),

    CONSTRAINT fk_perfil_colaborador FOREIGN KEY (codigo_empleado)
        REFERENCES colaboradores(id_colaborador)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_perfil_ocupacion FOREIGN KEY (id_ocupacion)
        REFERENCES cat_ocupaciones(C_OCUP)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_perfil_tipo_empleado FOREIGN KEY (id_tipo_empleado)
        REFERENCES cat_tipoempleado(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_perfil_motivo_terminacion FOREIGN KEY (id_motivo_terminacion)
        REFERENCES cat_motivos_terminacion(C_TERMINACION)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Índice para acelerar la búsqueda del cargo activo por colaborador
CREATE INDEX idx_perfil_activo ON perfiles_laborales (codigo_empleado, cargo_activo);