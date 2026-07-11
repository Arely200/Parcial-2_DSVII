# iTECH Contrataciones

Sistema de gestión de colaboradores y perfiles laborales — Desarrollo de Software VII.


[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql&logoColor=white)](https://mysql.com)
[![OpenSSL](https://img.shields.io/badge/OpenSSL-3.x-721412?style=flat&logo=openssl&logoColor=white)](https://openssl.org)
[![Status](https://img.shields.io/badge/Estado-En%20Desarrollo-yellow)]()


## Estructura del proyecto (MVC)

```
itech_contrataciones/
├── 📁 config/
│ └── Conexion.php # Singleton PDO
├── 📁 controllers/
│ ├── ColaboradorController.php # Controlador de colaboradores
│ └── ReporteController.php # Controlador de reportes
├── 📁 models/
│ ├── Colaborador.php # Modelo de colaboradores
│ ├── PerfilLaboral.php # Modelo de perfiles laborales
│ └── Catalogo.php # Catálogos del sistema
├── 📁 helpers/
│ ├── Validator.php # Validaciones (estáticas)
│ ├── Sanitizer.php # Sanitización (estáticas)
│ └── FirmaDigital.php # Firma/Verificación OpenSSL
├── 📁 views/
│ ├── formulario.php # Registro de colaborador
│ ├── perfil_laboral.php # Registro de perfil laboral
│ ├── reporte.php # Reporte con indicadores
│ └── css/
│ └── estilos.css # Estilos del sistema
├── 📁 keys/ # Llaves OpenSSL (generadas)
├── 📁 database/
│ ├── 01_catalogos.sql # Catálogos del sistema
│ └── 02_principal.sql # Tablas principales
├── 📄 index.php # Página principal
├── 📄 exportar_excel.php # Exportación a Excel
├── 📄 setup_llaves.php # Generador de llaves OpenSSL
├── 📄 actualizar_firmas.php # Regenerador de firmas
├── 📄 comprobar_firmas.php # Verificador de firmas
├── 📄 .gitignore # Archivos ignorados por Git
└── 📄 README.md # Este archivo
```

## Pasos para levantar el proyecto en WAMP

1. Copia la carpeta `itech_contrataciones` dentro de `C:\wamp64\www\`.
2. Abre phpMyAdmin (`http://localhost/phpmyadmin/`) y ejecuta, en este orden:
   - `database/01_catalogos.sql`
   - `database/02_principal.sql`
3. Revisa `config/Conexion.php` y ajusta usuario/clave si tu MySQL no usa `root` sin clave.
4. **Genera las llaves OpenSSL** (solo una vez): entra en el navegador a
   `http://localhost/itech_contrataciones/setup_llaves.php`
   - Si te sale un error de OpenSSL, es el mismo problema de siempre en WAMP:
     revisa que en tu `php.ini` la línea `openssl.cafile` / `openssl.cnf`
     apunte a la ruta correcta (el mismo fix que usaste en Parcial2_DSVII).
5. Entra a `http://localhost/itech_contrataciones/` y ya puedes:
   - Registrar colaboradores
   - Registrar perfiles laborales (con promoción automática)
   - Ver el reporte con validación de integridad (verde = íntegro, rojo = corrupto)
   - Exportar a Excel desde el botón del reporte

## Cómo probar la integridad (verde/rojo)

Para ver el indicador en rojo, entra a phpMyAdmin y modifica manualmente el
campo `salario` de un registro en `perfiles_laborales` sin pasar por el
formulario. Al recargar el reporte, ese registro debe aparecer en rojo
porque la firma ya no coincide con los datos.

## Antes de subir a GitHub

- El archivo `.gitignore` ya excluye `keys/private_key.pem`.
- Verifica con `git status` que la llave privada NO aparezca antes de hacer commit
  (recuerda lo que pasó con el Parcial2).
- Puedes subir `keys/public_key.pem` sin problema.


## Requisitos

| Requisito | Versión |
|-----------|---------|
| **PHP** | 8.0+ (con OpenSSL) |
| **MySQL** | 8.0+ |
| **Apache** | 2.4+ |

### Extensiones PHP requeridas:
- `openssl`
- `pdo_mysql`
- `mbstring`

## Problemas comunes

| Problema | Solución |
|----------|----------|
| Error generando llaves | Activar `extension=openssl` en `php.ini` |
| Firmas corruptas | Ejecutar `actualizar_firmas.php` |
| Llaves no encontradas | Ejecutar `setup_llaves.php` |

## Autor

**Arely Mendoza** 

**© 2024 iTECH Contrataciones**
