<?php
require_once 'functions.php';
$sql = getSQL();
checkSessionTimer();
$_SESSION['logs-page'] = 0;
if (is_logged_in()) {
    if (is_staff()) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>BadgerStaffPanel (Logs)</title>
            <link rel="stylesheet" href="css/webReset.css"/>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
                  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
                  crossorigin="anonymous"/>
            <link rel="stylesheet" href="css/style.css"/>
            <link rel="stylesheet" href="css/accountStyle.css"/>
            <link rel="stylesheet" href="css/viewStyle.css"/>
            <link rel="stylesheet" href="css/playersStyle.css" />
            <link rel="stylesheet" href="css/logsStyle.css" />
            <script src="js/functions.js" rel="script"></script>
        </head>
    <body id="background">
    <div class="row contain">
        <?php require_once 'sidebar.php' ?>
        <div class="content-contain">
            <div class="header">Logs</div>
                <div id="content-view">
                    <div id="tables-contain">
                        <table class="table table-striped" id="logs">
                            <thead>
                            <th>Action</th>
                            <th>Punish ID</th>
                            <th>Punishment Type</th>
                            <th>Data</th>
                            <th>Issued By</th>
                            <th>Punished User</th>
                            <th>Issued On</th>
                            </thead>
                            <tbody>
                            <?php
                            $stmt = $sql->prepare("SELECT * FROM `Logger` ORDER BY Punish_ID DESC LIMIT 0, 15;");
                            $stmt->execute();
                            $res = $stmt->get_result();
                            while ($row = $res->fetch_assoc()) {
                                $pid = $row['Punish_ID'];
                                $ptype = $row['Punish_Type'];
                                $punisherSteam = $row['Punished_By_steamID'];
                                $punisherUID = getUserIDFromSteamID($punisherSteam);
                                $punishedUID = $row['ID_Punished'];
                                $data = $row['Data'];
                                $date = $row['Action_Date'];
                                $action = $row['Action'];
                                if ($action == 'Add') {
                                    $action = 'Issued';
                                } elseif ($action == "Remove") {
                                    $action = 'Revoked';
                                }
                                $steamID = bchexdec(str_replace("steam:", "", $punisherSteam));
                                echo '<tr>';
                                echo '<td><span class="' . $action . '">' . $action . '</span></td>';
                                echo '<td>' . $pid . '</td>';
                                echo '<td>' . $ptype . '</td>';
                                echo '<td><textarea disabled style="resize: none;">' . str_replace(" || ", "\n", $data) . '</textarea></td>';
                                $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=419B129CA3FE72C01B2982B6A305CE0F&steamids=' . $steamID);
                                $steamData = json_decode($steamAPI)->response->players[0];
                                echo '<td><a href="viewUser.php?ID=' . $punisherUID .'" target="_self">' . $steamData->personaname . '</a></td>';
                                echo '<td><a href="viewUser.php?ID=' . $punishedUID . '" target="_self">' . $punishedUID . '</a></td>';
                                echo '<td>' . $date . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                        <button id="load-more-logs" onclick="loadMoreLogs();">Load more logs</button>
                        <img id="load-gif" src="css/img/loading-gif.gif" />
                    </div> <!-- End tables-contain -->
                </div>
            </div> <!-- End content-view -->
        </div> <!-- End content-contain -->
    </body>
        <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        </html>
        <?php
    } else {
        header("Location: account.php");
    }
} else {
    header("Location: index.php");
}