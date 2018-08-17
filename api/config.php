<?php

class Config
{

    /**
     * Konfigurations-Array
     * Beispiel PROFFIX REST API auf unserer Online-Demo
     * http://www.proffix.net/entwickler/restapi/handbuch
     */
    private static $CONFIG = array(
        "WebserviceUrl" => "https://remote.proffix.net:11011/pxapi/v2",
        "WebservicePassword" => "16378f3e3bc8051435694595cbd222219d1ca7f9bddf649b9a0c819a77bb5e50",
        "PxUser" => "Gast",
        "PxPassword" => "16ec7cb001be0525f9af1a96fd5ea26466b2e75ef3e96e881bcb7149cd7598da",
        "DbName" => "DEMODB",
        "PxModule" => array("ZEI")
    );

    /**
     * Funktion get
     * @param configname: Key welcher aus dem Konfigurations-Array zurückgeliefert werden soll
     * @return: Liefert einen Datensatz aus dem Konfigurations-Array zurück
     */
    public static function get($configname)
    {
        if (array_key_exists($configname, self::$CONFIG)) {
            return self::$CONFIG[$configname];
        } else {
            throw new Exception("Configname doesn't exist");
        }
    }
}
