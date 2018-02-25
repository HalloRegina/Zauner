<?php
/**
 * Created by PhpStorm.
 * User: Michael Zauner
 * Date: 22.01.2018
 * Beschreibung: DB Verbindung
 */

$server = 'localhost';
$user = 'root';
$pwd = '';
$db = 'theater';
try
{
    $con = new PDO('mysql:host='.$server.';dbname='.$db.';charset=utf8',$user, $pwd);
}
catch (Exception $e)
{
    switch($e->getCode())
    {
        case 1049:
            echo 'Datenbank ' .$db.' nicht vorhanden!';
            break;
        default:
            echo $e->getMessage();
    }
}
