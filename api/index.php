<?php
require_once 'lib/flight/flight/Flight.php'; // Verwendung von FlightPHP https://github.com/mikecao/flight
require_once './config.php';
require_once './PxRestApiWrapper.class.php';

// PHP Session starten
session_start();

/**
 * CORS-Header setzen damit im Browser keine Sicherheitswarnung ausgelöst wird
 */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Location');
Flight::route("OPTIONS *", function () {
    Flight::halt(200);
});

/**
 * Routen
 */
Flight::route('/Mitarbeiterliste', function () {
    Flight::json(PxRestApiWrapper::getInstance()->Get("/PRO/Mitarbeiter?fields=MitarbeiterNr,Name")->body);
});

Flight::route('/MitarbeiterStempelstatus/@MitarbeiterNr', function ($MitarbeiterNr) {
    Flight::json(PxRestApiWrapper::getInstance()->Get("/ZEI/Stempel?fields=Eingestempelt&mitarbeiter=" . $MitarbeiterNr)->body);
});

/**
 * Server Start
 */
Flight::start();