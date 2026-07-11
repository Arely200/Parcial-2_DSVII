<?php
/**
 * EJECUTAR UNA SOLA VEZ desde el navegador o consola antes de usar el
 * sistema: genera el par de llaves OpenSSL para firmar/verificar los
 * perfiles laborales.
 *
 * Uso: http://localhost/itech_contrataciones/setup_llaves.php
 */
require_once __DIR__ . '/helpers/FirmaDigital.php';

FirmaDigital::generarLlaves();

echo "Llaves generadas correctamente en la carpeta /keys.<br>";
echo "IMPORTANTE: borra o comenta este archivo después de usarlo, y nunca subas keys/private_key.pem al repositorio.";
