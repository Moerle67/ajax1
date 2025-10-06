<?php
    header('Content-type: application/json; charset=utf-8');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // datum: "2025-10-02",
        // inhalt: "Das ist ein AJAC Test."

        if (empty($_POST["id"])) {
            print("Fehler! Feld fehlt");
            exit();
        }
        $id = $_POST["id"];

        try {
            require_once('db.php');
            $db = DB::connectDB();
            $query = $db->prepare('DELETE FROM testtabelle WHERE id = :id');
            $query->bindParam(":id", $id, PDO::PARAM_INT);

            $query->execute();

        } catch (PDOException $ex) {
            //throw $th;
            error_log("Delete error(".date("F j, Y, g:i a").") ".$ex."\r\n", 3, "logs/db-error.txt");
            print(json_encode(array("status" => "error")));
            exit();
        };
        // Optionale Felder ?? wenn Feld leer, dann ...
        // $inhalt = $_POST['inhalt'] ?? "";
        // print($datum." ".$inhalt);

       echo json_encode(
        array(
            "status" => "succes",
        ));
        exit();    
    } else {
        print("Error, kein POST");
        exit();
    }
?>