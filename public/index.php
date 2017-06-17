<?php

include "../loader.php";

if (isset($_GET["Title"]))
{
    $file = \Shared\FileSystem\File::open("../data/dbc/enUS/CharTitles.db2");
    $db2 = new \Game\DB2\CharTitles($file);
    $db2->load();

    $searcher = new \Shared\DB2\DB2Searcher($db2);
    $result = $searcher->search($_GET["Title"]);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>DB2 Searcher</title>
    </head>
    <body>

    <h1>CharTitles</h1>

    <form action="index.php" method="get">
        <input type="text" name="Title" />
        <input type="submit" />
    </form>

    <br><br><br>

    <?php

    if (isset($result))
    {
        echo "<table>";
        foreach ($result as $data)
        {
            echo "<tr> <td> " . $data->getId() . " </td> <td> " . $data->getField(0) . " </td> <td> " . $data->getField(1) . " </td> <td> " . $data->getField(2) . " </td> <td> " . $data->getField(3) . " </td> </tr> ";
        }
        echo "</table>";
    }

    ?>

    </body>
</html>