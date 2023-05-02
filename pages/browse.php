<!DOCTYPE html>
<html>

<head>
    <title>TTB - Browse</title>
    <link href="../resources/css/style.css" rel="stylesheet" type="text/css" media="all">
    <link rel="icon" href="../resources/images/other/icon.png">
</head>
<script src="../resources/js/jquery-1.4.3.min.js"></script>
<script>
    function generateInternals(fn) {
        alert(fn);
        var obj = JSON.parse(fn);
        var box = document.createElement("div");
        box.innerHTML = "<h2>" + obj.name + "'s Deck</h2><p>" + obj.description + "</p><button class='deckButton' href='" + filePath + "'>Download</button>"
    }

    function validate(formObj) {

        if (formObj.file.length == 0) {
            alert("Please upload a file!");
            formObj.file.focus();
            return false;
        }

        if (formObj.description.value == "") {
            alert("Please fill in a discription!");
            formObj.lastName.focus();
            return false;
        }

        return true;
    }
</script>

<body>
    <header>
        <a href="../index.html">
            <img src="../resources/images/other/logo.png">
        </a>
        <span>
        <h2><a href="../pages/editor.html">Build Deck</a> | <a href="../pages/browse.php" class="selected">Browse + Share Decks</a> | <a href="../pages/FAQ.html">FAQ</a> | <a href="../pages/tutorial.html">Tutorial</a></h2>
        </span>
    </header>
    <center>

        <?php
        $dbOk = false;

        @$db = new mysqli('localhost', 'root', 'FuckThis1', 'final');

        if ($db->connect_error) {
            echo '<script>alert("Could Not Connect To Database.")</script>';
        } else {
            $dbOk = true;
        }

        $havePost = isset($_POST["save"]);

        $errors = '';
        if ($havePost) {

            if ($_FILES["file"]["type"] != 'application/json') {
                $errors .= '<p>ERROR: Must upload a JSON file.</p>';
            }


            if ($errors != '') {
                echo $errors;
            } else {
                if ($dbOk) {
                    $target = '../resources/uploads/' . $_FILES['file']['name'];
                    $nameTXT = basename($target, ".json");

                    if (file_exists($target)) {
                        echo '<p>ERROR: This file exists in the database, please rename your file and try again.</p>';
                    } else {
                        move_uploaded_file($_FILES['file']['tmp_name'], $target);

                        $likes = 0;

                        $insQuery = "INSERT INTO decks (`filename`,`likes`) VALUES(?,?)";
                        $statement = $db->prepare($insQuery);
                        $statement->bind_param("si", $nameTXT, $likes);
                        $statement->execute();

                        $statement->close();
                    }


                }
            }
        }
        ?>
        <h3>Share Your Deck</h3>
        <form id="upForm" name="upForm" enctype="multipart/form-data" action="browse.php" method="post"
            onsubmit="return validate(this);">
            <label class="field" for="file">Deck To Share:</label>
            <input id="file" name="file" type="file" value="import" accept="application/JSON" />
            <input type="submit" value="Share Your Deck!" id="save" name="save" />
        </form>

        <div class="browseBox">

            <?php
            // Include the database configuration file
            $db = new mysqli('localhost', 'root', 'FuckThis1', 'final');

            // Check connection
            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }

            // Get images from the database
            $query = $db->query("SELECT * FROM decks ORDER BY deckid DESC");

            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    $fileURL = '../resources/uploads/' . $row["filename"] . '.json';
                    $curJSON = file_get_contents($fileURL);
                    $decoded_json = json_decode($curJSON, true);
                    if($decoded_json["name"] == '' || $decoded_json["description"] == ''){
                        $id = $row[0];
                        $insQuery = "DELETE FROM decks WHERE deckid = ?";
                        $statement = $db->prepare($insQuery);
                        $statement->bind_param("i", $id);
                        $statement->execute();
                        $statement->close();
                    } else{
                        echo "<div class='deckBox'><h2>" . $decoded_json["name"] . "</h2><p>" . $decoded_json["description"] . "</p><a href='" . $fileURL . "' download='" . $row["filename"] . "'><button class='deckButton'>Download</button></a></div>";
                    }
                }
            } ?>

        </div>
    </center>
</body>

</html>