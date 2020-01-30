<?php

include_once("init.php");

if ($_SESSION['level'] == 0 || $_SESSION['level'] > 2) {
    die();
}
$handle = $config['dbo']->prepare('SELECT * FROM lore ORDER BY pc, title');
$handle->execute();
$lore = $handle->fetchAll(\PDO::FETCH_ASSOC);

if ($_SESSION['level'] > 1) {
    if ($_POST['action'] == 'deleteLore') {
        $handle = $config['dbo']->prepare('DELETE FROM lore WHERE id = ?');
        $handle->bindValue(1, $_POST['resource_id']);
        $handle->execute();
        header("Location: ?");
        die();
    } elseif ($_POST['action'] == 'newLore') {
        $handle = $config['dbo']->prepare('INSERT INTO lore (title, pc, lore) VALUES (?, ?, ?)');
        $handle->bindValue(1, $_POST['title']);
        $handle->bindValue(2, $_POST['pc']);
        $handle->bindValue(3, $_POST['lore']);
        $handle->execute();
        header("Location: ?");
        die();
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<h1 class="mt-2">Lore Book</h1>
<?php

if ($_SESSION['level'] > 1) {
    ?>
    <div class="d-flex mt-3 mb-3">
        <div class="btn-group flex-fill" role="group" aria-label="Basic example">
            <a href="?action=newLore" class="btn btn-warning">Create New Lore</a>
            <a href="?action=deleteLore" class="btn btn-warning">Delete Lore</a>
        </div>
    </div>
    <?php

    if ($_GET['action'] == 'newLore') {
        ?>
        <div class="pl-4 pr-4 mb-4">
            <form method="post">
                <div class="form-group">
                    <label for="newLoreTitle">Title</label>
                    <input name="title" type="text" class="form-control" id="newLoreName" aria-describedby="emailHelp" placeholder="Trip To The Zoo" required>
                </div>
                <div class="form-group">
                    <label for="newLorePC">Pledge Class</label>
                    <input name="pc" type="text" class="form-control" id="newResourcePC" aria-describedby="emailHelp" placeholder="Fall 2010" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Lore</label>
                    <textarea name="lore" class="form-control" id="exampleFormControlTextarea1" rows="5" required></textarea>
                </div>
                <input type="hidden" name="action" value="newLore">
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
        <?php
    } elseif ($_GET['action'] == 'deleteLore') {
        ?>
        <div class="pl-4 pr-4 mb-4">
            <form method="post">
                <div class="form-group">
                    <label for="deleteLoreLore">Resource</label>
                    <select name="resource_id" required id="deleteLoreLore">
                        <?php
                        foreach ($lore as $story) {
                            echo "<option value='" . $story['id'] . "'>" . $story['pc'] . ': ' . $story['title'] . "</option>";
                        } ?>
                    </select>
                </div>
                <input type="hidden" name="action" value="deleteLore">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
        <?php
    }
}

echo "</br>";

if (count($lore) == 0) {
    echo "<h3>No Lore Found</h3>";
} else {
    ?>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th style="width: 10%;">Pledge Class</th>
                <th style="width: 20%;">Title</th>
                <th style="width: 70%;">Story</th>
            </tr>
        </thead>
    <?php
    foreach ($lore as $story) {
        echo "<tr>";
        echo "<td>" . $story['pc'] . "</td>";
        echo "<td>" . $story['title'] . "</td>";
        echo "<td>" . nl2br($story['lore']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script></body>
</html>
