<?php
require_once './config.php';
require_once './lib/httpful.phar';

class PxRestApiWrapper
{
    private static $INSTANCE;

    private $webserviceUrl;
    private $webservicePassword;
    private $pxUser;
    private $pxPassword;
    private $pxDbName;
    private $pxModule;


    /**
     * Konstruktor
     */
    private function __construct()
    {
        // Übernehme die Werte aus der Config-Datei in die Instanzvariabeln
        $this->webserviceUrl = Config::get("WebserviceUrl");
        $this->webservicePassword = Config::get("WebservicePassword");
        $this->pxUser = Config::get("PxUser");
        $this->pxPassword = Config::get("PxPassword");
        $this->pxDbName = Config::get("DbName");
        $this->pxModule = Config::get("PxModule");
        // Session-Variabel initialisieren falls sie nicht existiert
        if (empty($_SESSION["pxSessionId"])) {
            $_SESSION["pxSessionId"] = "";
        }
    }

    /**
     * Funktion getInstance
     * @return: liefert die einzige Instanz des PxRestApiWrappers zurück
     */
    public static function getInstance() : PxRestApiWrapper
    {
        if (self::$INSTANCE === null) {
            self::$INSTANCE = new PxRestApiWrapper();
        }
        return self::$INSTANCE;
    }

    /**
     * Funktion Get
     * Macht einen HTTP-GET Aufruf auf die PROFFIX REST API um Daten zu LESEN
     * @param endpointWithParams: Endpunkt plus eventuelle globale Query-Parameter
     * (Query-Parameter sind zum Beispiel "filter", "sort", "offset" und "limit")
     * @return: Response
     */
    public function Get($endpointWithParams) : \Httpful\Response
    {
        $response = \Httpful\Request::get($this->webserviceUrl . $endpointWithParams)
            ->expectsJson()
            ->addHeader("PxSessionId", $_SESSION["pxSessionId"])
            ->send();
        $_SESSION["pxSessionId"] = $response->headers["pxsessionid"];

        // Einloggen, falls nicht eingeloggt
        if ($response->code == 401 && !($response->request->method == "POST" && $response->request->uri == $this->webserviceUrl . "/PRO/Login")) {
            $responseLogin = $this->Post("/PRO/Login", json_encode(array(
                "Benutzer" => $this->pxUser,
                "Passwort" => $this->pxPassword,
                "Datenbank" => array("Name" => $this->pxDbName),
                "Module" => $this->pxModule
            )), true);
            if ($responseLogin->code === 201) {

                // Ursprünglichen Request nochmals ausführen
                $response = $this->Get($endpointWithParams);
            }
        }
        return $response;
    }

    /**
     * Funktion Post
     * Macht einen HTTP-POST Aufruf zur PROFFIX REST API um Daten zu SCHREIBEN
     * @param endpointWithParams: Endpunkt plus eventuelle globale Query-Parameter
     * (Query-Parameter sind zum Beispiel "filter", "sort", "offset" und "limit")
     * @param body: Im Body werden die zu schreibenden Werte im JSON-Format mitgesendet
     * @return: Response
     */
    public function Post($endpointWithParams, $body) : \Httpful\Response
    {
        $response = \Httpful\Request::post($this->webserviceUrl . $endpointWithParams)
            ->expectsJson()
            ->addHeader("pxsessionid", $_SESSION["pxSessionId"])
            ->sendsJson()
            ->body($body)
            ->send();

        $_SESSION["pxSessionId"] = $response->headers["pxsessionid"];
        return $response;
    }
}