<?php
    header('Content-type: application/json; charset=utf-8');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // datum: "2025-10-02",
        // inhalt: "Das ist ein AJAC Test."
        if (empty($_POST["datum"]) || empty($_POST["inhalt"]) || empty($_POST["id"])){
            print("Fehler! Feld fehlt");
            exit();
        }
        $datum = $_POST["datum"];
        $inhalt = $_POST["inhalt"];
        $id = $_POST["id"];

        try {
            require_once('db.php');
            $db = DB::connectDB();
            $query = $db->prepare('UPDATE testtabelle SET datum = :datum, inhalt = :inhalt WHERE id = :id');
            $query->bindParam(":datum", $datum, PDO::PARAM_STR);
            $query->bindParam(":inhalt", $inhalt, PDO::PARAM_STR);
            $query->bindParam(":id", $inhalt, PDO::PARAM_INT);
            $query->execute();
           
        } catch (PDOException $ex) {
            //throw $th;
            error_log("Write error(".date("F j, Y, g:i a").") ".$ex."\r\n", 3, "logs/db-error.txt");
            print(json_encode(array("status" => "error")));
            exit();
        };
        // Optionale Felder ?? wenn Feld leer, dann ...
        // $inhalt = $_POST['inhalt'] ?? "";
        // print($datum." ".$inhalt);

        echo json_encode(
            array(
                "status" => "success",
                "ID" => $id,
            ));
        exit();    
    } else {
        print("Error, kein POST");
        exit();
    }
?>