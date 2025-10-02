<?php
    header('Content-type: application/json; charset=utf-8');
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // datum: "2025-10-02",
        // inhalt: "Das ist ein AJAC Test."
        if (empty($_POST["datum"]) || empty($_POST["inhalt"])){
            print("Fehler!");
            exit();
        }
        $datum = $_POST["datum"];
        $inhalt = $_POST["inhalt"];

        try {
            require_once('db.php');
            $db = DB::connectDB();
            $query = $db->prepare('INSERT INTO testtabelle (Datum,Inhalt) VALUES(:datum, :inhalt)');
            $query->bindParam(":datum", $datum, PDO::PARAM_STR);
            $query->bindParam(":inhalt", $inhalt, PDO::PARAM_STR);
            $query->execute();
            $lastID = $db->lastInsertId();

        } catch (PDOException $ex) {
            //throw $th;
            error_log("Write error(".date("F j, Y, g:i a").") ".$ex."\r\n", 3, "logs/db-error.txt");
            print(json_encode(array("status" => "error")));
        }
        // Optionale Felder ?? wenn Feld leer, dann ...
        // $inhalt = $_POST['inhalt'] ?? "";
        // print($datum." ".$inhalt);
        print(json_encode(array(
            "Datum" => $datum,
            "Inhalt" => $inhalt)
        ));
        exit();
    }
?>