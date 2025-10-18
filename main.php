<!doctype html>
<html lang="de">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="container text-center">
        <div class="row">
            <div class="col">
                <h1>Einpflege von Daten</h1>
                <form id="neuer_eintrag" method="POST">
                    <div class="mb-3">
                        <label for="datum" class="form-label">Datum</label>
                        <input type="date" class="form-control" id="datum">
                    </div>
                    <div class="mb-3">
                        <label for="inhalt" class="form-label">Inhalt</label>
                        <textarea class="form-control" id="inhalt" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-outline-primary mb-3">Neuen Eintrag anlegen</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h1>Titel der Tabelle</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-striped" id="testTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Datum</th>
                            <th>Inhalt</th>
                            <th class="col-2">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once('db.php'); 
                            try {  
                                $db = DB::connectDB();
                                // Vorbereitung
                                $query = $db->prepare('SELECT * FROM testtabelle;');
                                // Ausführung
                                $query->execute();
                                $res = $query->fetchAll();
                                
                                foreach ($res as $entry) {
                                        print('<tr id="'.$entry["id"].'">');
                                        print('<td>'.$entry["id"].'</td>'); 
                                        print('<td>'.$entry["datum"].'</td>'); 
                                        print('<td>'.$entry["inhalt"].'</td>');
                                        print('<td>
                                            <button type="button" class="btn btn-outline-primary edit" data-bs-toggle="modal" data-bs-target="#editModal">Bearbeiten</button>
                                            <button type="button" class="btn btn-outline-danger delete">Löschen</button>
                                        </td>');
                                        print('</tr>');
                                }
                            } catch(PDOException $ex) {
                                error_log("Error: main.php.(".date("F j, Y, g:i a").") ".$ex."\r\n", 3, "logs/db-error.txt");
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Bearbeiten von Datensatz</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit_eintrag" method="POST">
                        <div class="mb-3">
                            <label for="edit_datum" class="form-label">Datum</label>
                            <input type="date" class="form-control" id="edit_datum">
                        </div>
                        <div class="mb-3">
                            <label for="edit_inhalt" class="form-label">Inhalt</label>
                            <textarea class="form-control" id="edit_inhalt" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary save_modal" data-bs-dismiss="modal">Speichern</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            // alert("Jquery läuft");
            $('#neuer_eintrag').submit(function (event) {
                event.preventDefault();
                $.ajax({
                    url: "create-eintrag.php",
                    method: "POST",
                    data: {
                        datum: $('#datum').val(),
                        inhalt: $('#inhalt').val().trim()
                    },
                    success: function (res) {
                        // let resJSON = JSON.parse(res);
                        let resJSON = res;
                        // console.log(resJSON);
                        if (resJSON.status !== "error") {
                            $('#testTable tbody').find('tr:last').after('<tr id="' + resJSON.ID + '">'
                                + '<td>' + resJSON.ID + '</td>'
                                + '<td>' + resJSON.Datum + '</td>'
                                + '<td>' + resJSON.Inhalt + '</td>'
                                + '<td>'
                                + '<button type="button" class="btn btn-outline-primary edit" data-bs-toggle="modal" data-bs-target="#editModal">Bearbeiten</button>'
                                + '<button type="button" class="btn btn-outline-danger delete">Löschen</button>'
                                + '</td>'
                                + '</tr>'
                            );
                            
                            // Felder initialisieren
                            $('#datum').val('');
                            $('#inhalt').val('');

                        } else {
                            alert('Fehler beim Einfügen');
                        }
                    },
                    error: function (err) {
                        alert("An error occured: " + err.status + " " + err.statusText);
                    },
                });
            }) // Neuer Eintrag

            $('#testTable tbody').on('click', 'button.delete', function (event) {
                let id = $(this).closest('tr').attr('id');
                // alert("Hier wird nichts gelöscht ("+id+")!");
                event.preventDefault();
                $.ajax({
                    url: "delete-eintrag.php",
                    method: "POST",
                    data: {
                        id: id,
                    },
                    success: function (res) {
                        if (res.status !== "error") {
                            $('#' + id).remove();
                        } else {
                            alert("Fehler beim Löschen");
                        }
                    },
                    error: function (err) {
                        alert("An error occured: " + err.status + " " + err.statusText);
                    },

                });
            }); // delete

            // Modal ID
            let modalID = "";

            // Show Edit Modal
            $('#testTable tbody').on('click', 'button.edit', function (event) {
                id = $(this).closest('tr').attr('id');
                modalID = id;

                // Inhalte
                $('#edit_datum').val( $('#'+id+' td:eq(1)').text() );
                $('#edit_inhalt').val( $('#'+id+' td:eq(2)').text() );
            }) // show edit modal

            $('#editModal .save_modal').on('click', function (event) {
                console.log(modalID);
                $.ajax({
                    url: "edit-eintrag.php",
                    method: "POST",
                    data: {
                        id: modalID,
                        datum: $('#edit_datum').val(),
                        inhalt: $('#edit_inhalt').val().trim()
                    },
                    success: function (res) {
                        // let resJSON = JSON.parse(res);
                        let resJSON = res;
                        // console.log(resJSON);
                        if (resJSON.status !== "error") {
                            $('#'+modalID+" td:eq(1)").text( $('#edit_datum').val() );
                            $('#'+modalID+" td:eq(2)").text( $('#edit_inhalt').val() );
                        } else {
                            alert('Fehler beim Einfügen');
                        }
                    },
                    error: function (err) {
                        alert("Fehler beim Editieren: " + err.status + " " + err.statusText);
                    }, // AJAX
                })    
            }) // Edit
        })
    </script>
</body>

</html> 