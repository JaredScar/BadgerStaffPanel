<?php
session_start();
require_once 'functions.php';
$sql = getSQL();
$_SESSION['players-page'] = 0;
checkSessionTimer();
if (is_logged_in()) {
    if (is_staff()) {
        setLastPage("players.php");
        ?>
        <html>
        <head>
            <title>BadgerStaffPanel v1.0 ALPHA</title>
            <link rel="stylesheet" href="css/webReset.css" />
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="css/playersStyle.css" />
            <script src="js/functions.js" rel="script"></script>
        </head>
        <body id="background">
            <div class="row contain">
                <?php require_once 'sidebar.php'; ?>
                <div class="content-contain">
                    <div class="header">Players</div>
                    <div id="content-players">
                        <div id="search-bar">
                            <input type="search" placeholder="Badger" id="players-searchbar" onkeyup="getAutoCompleteKeyup();" />
                            <button onclick="getAutoComplete();">Search</button>
                        </div>
                        <table class="table table-striped" id="players">
                            <thead>
                            <tr>
                                <th scope="col">User ID</th>
                                <th scope="col">Last Player Name</th>
                                <th scope="col">Discord</th>
                                <th scope="col">Steam</th>
                                <th scope="col">Notes</th>
                                <th scope="col">Warns</th>
                                <th scope="col">Kicks</th>
                                <th scope="col">Tempbans</th>
                                <th scope="col">Bans</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $stmt = $sql->prepare("SELECT ID, lastPlayerName, steamID, discord FROM Users LIMIT 0, 20;");
                            $stmt->execute();
                            $res = $stmt->get_result();
                            while ($row = $res->fetch_assoc()) {
                                echo '<tr>';
                                $userID = $row['ID'];
                                $discord = str_replace("discord:", "", $row['discord']);
                                $steamID = bchexdec(str_replace("steam:", "", $row['steamID']));
                                $steamLink = 'https://steamcommunity.com/profiles/' . $steamID;
                                $playerName = $row['lastPlayerName'];

                                echo '<td><a href="viewUser.php?ID=' . $userID . '">' . $userID . '</a></td>';
                                echo '<td>' . $playerName . '</td>';
                                echo '<td>' . $discord . '</td>';
                                echo '<td><a href="' . $steamLink . '" target="_blank">' . $steamLink . '</a></td>';

                                $tables = ['Notes', 'Warns', 'Kicks', 'Tempbans', 'Bans'];
                                foreach ($tables as $table) {
                                    $statem = $sql->prepare("SELECT COUNT(*) AS total FROM " . $table . " WHERE User_ID = " . $userID . ";");
                                    $statem->execute();
                                    $resul = $statem->get_result();
                                    $count = $resul->fetch_assoc()['total'];
                                    echo '<td>' . $count . '</td>';
                                }
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                        <button id="load-more" onclick="loadMoreUsers();">Load more users</button>
                        <img id="load-gif" src="css/img/loading-gif.gif" />
                    </div>
                </div>
            </div>
        </body>
        <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        </html>
        <?php
    } else {
        // Take them to their profile page
    }
} else {
    // Take them to index
    header("Location: index.php");
}
?>
