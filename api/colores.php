<?php
include_once('colores_class.php');

$color = new Colores(
    $_SERVER['PHP_AUTH_USER'],
    $_SERVER['PHP_AUTH_PW'],
    $_SERVER['REQUEST_METHOD']
);