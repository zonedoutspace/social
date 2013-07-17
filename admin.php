<?php
    session_start();
    require 'database_connect.php';
    if (! isset($_SESSION['userType'])) {
        header('Location: ./login.php');
    }
    elseif ($_SESSION['userType'] != 'admin') {
        header('Location: ./index.php');
    }
    else
    {
        $message = NULL;
        if (isset($_FILES['file']))
        {
            move_uploaded_file($_FILES['file']['tmp_name'], "./files/{$_FILES['file']['name']}");
            $linkToFile = "<a href=\"./files/{$_FILES['file']['name']}\">Link to " . $_FILES['file']['name'] ."</a>";
            $message = "File uploaded successfully. $linkToFile";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
    </head>
    <body>
        <?php include 'header.php'; ?>

        <div class="container" style="position: relative; top:40px;">
            <div class="row-fluid">
                <div class="span4 well">
                    <h2>Upload a file</h2>
                    <?php
                        if ($message != NULL)
                            echo $message . "<br />";
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="file" name="file" /><br />
                        <input class="btn btn-primary" type="submit" value="Submit" />
                    </form>
                </div>
                <div class="span4 well">
                </div>
                <div class="span4 well">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4 well">
                    <h2>Upload a file</h2>
                    <?php
                        if ($message != NULL)
                            echo $message . "<br />";
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="file" name="file" /><br />
                        <input class="btn btn-primary" type="submit" value="Submit" />
                    </form>
                </div>
                <div class="span4 well">

                      <?php
                            $query_string = sprintf("SELECT * FROM users ORDER BY id DESC");
                            $result = mysql_query($query_string) or die(mysql_error());
                            $userRow = mysql_fetch_assoc($result);

                            echo "<select class=\"input-large   \">";
                            while("$userRow")
                            {
                                $un = $userRow['username'];
                                echo "<option value=\"$un\"/>$un</option>";
                                $userRow = mysql_fetch_assoc($result);
                            }
                            echo "</select>";
                       ?>
                       <a href="admin.php?delete=" class="btn btn-small">Delete</a>
                       <a href="admin.php?ban=" class="btn btn-small">Ban</a>

                </div>
                <div class="span4 well">
                </div>
            </div>
        </div>
    <?php include("footer.php"); ?>
    </body>
</html>
