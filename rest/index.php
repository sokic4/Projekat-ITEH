<?php
require 'flight/Flight.php';
require 'jsonindent.php';
Flight::register('db', 'Database', array('seminarski'));
$json_podaci = file_get_contents("php://input");
Flight::set('json_podaci', $json_podaci);

Flight::route('/', function () {
    echo 'hello world!';
});

// Vracanje svih reg tablica
Flight::route('GET /tablice.json', function () {
    header("Content-Type: application/json; charset=utf-8");
    $db = Flight::db();
    $db->selectTablice();
    $niz = array();
    $i=0;
    while ($red=$db->getResult()->fetch_object()) {
        $niz[$i]["id"] = $red->id;
        $niz[$i]["tablica"] = $red->tablica;
        $niz[$i]["datum"] = $red->datum;
        $niz[$i]["cena"] = $red->cena;
        $niz[$i]["vlasnik"] = $red->vlasnik;
        $niz[$i]["kontrolor"] = $red->kontrolor;
        $niz[$i]["marka"] = $red->marka;
        $niz[$i]["model"] = $red->model;
        $i++;
    }
    $json_niz = json_encode($niz, JSON_UNESCAPED_UNICODE);
    echo indent($json_niz);
    return false;
});

// Vracanje jedne reg tablice
Flight::route('GET /tablice/@id.json', function ($id) {
    header("Content-Type: application/json; charset=utf-8");
    $db = Flight::db();
    $db->selectTablice($id);
    $red = $db->getResult()->fetch_object();
    $json_niz = json_encode($red, JSON_UNESCAPED_UNICODE);
    echo indent($json_niz);
    return false;
});

// Kreiranje reg tablice
Flight::route('POST /tablice', function () {
    header("Content-Type: application/json; charset=utf-8");
    $db = Flight::db();
    $podaci_json = Flight::get("json_podaci");
    $podaci = json_decode($podaci_json);
    if ($podaci == null) {
        $odgovor["poruka"] = "Niste prosledili podatke";
        $json_odgovor = json_encode($odgovor);
        echo $json_odgovor;
        return false;
    } else {
        if (!property_exists($podaci, 'idVlasnik')||
            !property_exists($podaci, 'idKontrolor')||
            !property_exists($podaci, 'idVozilo')||
            !property_exists($podaci, 'cena')||
            !property_exists($podaci, 'tablica')) {
            $odgovor["poruka"] = "Niste prosledili korektne podatke";
            $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
        } else {
            if ($db->insertTablice($podaci->idVlasnik, $podaci->idKontrolor, $podaci->idVozilo, $podaci->tablica, $podaci->cena)) {
                $odgovor["poruka"] = "Reg tablica je uspe??no uba??ena";
                $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
                echo $json_odgovor;
                return false;
            } else {
                $odgovor["poruka"] = "Do??lo je do gre??ke pri ubacivanju reg tablice";
                $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
                echo $json_odgovor;
                return false;
            }
        }
    }
});

// Izmena reg tablice
Flight::route('PUT /tablice/@id', function ($id) {
    header("Content-Type: application/json; charset=utf-8");
    $db = Flight::db();
    $podaci_json = Flight::get("json_podaci");
    $podaci = json_decode($podaci_json);
    if ($podaci == null) {
        $odgovor["poruka"] = "Niste prosledili podatke";
        $json_odgovor = json_encode($odgovor);
        echo $json_odgovor;
    } else {
        if (!property_exists($podaci, 'tablica')) {
            $odgovor["poruka"] = "Niste prosledili korektne podatke";
            $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
            echo $json_odgovor;
            return false;
        } else {
            if ($db->updateTablice($id, $podaci->tablica)) {
                $odgovor["poruka"] = "Reg tablica je uspe??no izmenjena";
                $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
                echo $json_odgovor;
                return false;
            } else {
                $odgovor["poruka"] = "Do??lo je do gre??ke pri izmeni reg tablice";
                $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
                echo $json_odgovor;
                return false;
            }
        }
    }
});

// Brisanje reg tablica
Flight::route('DELETE /tablice/@id', function ($id) {
    header("Content-Type: application/json; charset=utf-8");
    $db = Flight::db();
    if ($db->deleteTablice($id)) {
        $odgovor["poruka"] = "Reg tablica je uspe??no izbrisana";
        $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
        echo $json_odgovor;
        return false;
    } else {
        $odgovor["poruka"] = "Do??lo je do gre??ke prilikom brisanja reg tablice";
        $json_odgovor = json_encode($odgovor, JSON_UNESCAPED_UNICODE);
        echo $json_odgovor;
        return false;
    }
});


Flight::start();
