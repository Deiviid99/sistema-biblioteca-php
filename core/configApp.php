<?php
//DECLARACION DE CONSTANTES PARA CONEXION DE BASE
const DB_SERVER = "localhost";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_NAME = "biblioteca_publica";
const DB_CHARACTER = "SET CHARACTER SET utf8";
const DB_SG = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;
//SOLO SE CONFIGURA UNA VEZ PARA LA ENCRIPTACION DE VALORES POR LA URL
const METHOD = "AES-256-CBC";
const SECRET_KEY = '$%B_P.20%$';
const SECRET_IV = '15041308';
