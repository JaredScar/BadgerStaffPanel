<?php
session_start();
require_once 'functions.php';
$sql = getSQL();
checkSessionTimer();
$userID = $_GET['ID'];



if (is_logged_in()) {
    if (is_staff()) {
        setLastPage("viewUser.php?ID=" . $userID);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>BadgerStaffPanel v1.0 ALPHA</title>
            <link rel="stylesheet" href="css/webReset.css"/>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
                  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
                  crossorigin="anonymous"/>
            <link rel="stylesheet" href="css/style.css"/>
            <link rel="stylesheet" href="css/viewStyle.css"/>
        </head>
        <body id="background">
        <div class="row contain">
            <?php require_once 'sidebar.php'; ?>
            <div class="content-contain">
                <div class="header">Viewing User: <?php echo $userID; ?></div>
                <div id="content-view">
                    <div id="profile-info">
                        <?php
                        $sql = getSQL();
                        $stmt = $sql->prepare("SELECT steamID, lastPlayerName, discord, live FROM Users WHERE ID = ?");
                        $stmt->bind_param("i", $userID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $steamI = str_replace("steam:", "", $row['steamID']);
                        $steamID = bchexdec($steamI);
                        $discordID = $row['discord'];
                        $live = $row['live'];
						global $steamAPIkey;
                        $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $steamAPIkey . '&steamids=' . $steamID);
                        $steamData = json_decode($steamAPI)->response->players[0];
                        $avatarLink = $steamData->avatarmedium;
                        echo '<span><img src="' . $avatarLink . '" /></span>';
                        echo '<span class="profile-name">' . $row['lastPlayerName'] . '</span>';
                        echo '<span class="profile-data">';
                        $options = [
                            'https' => [
                                'header' => 'Content-Type: application/x-www-form-urlencoded',
                                'method' => 'GET',
                                'content' => null,
                            ],
                            'http' => [
                                'header' => 'Content-Type: application/x-www-form-urlencoded',
                                'method' => 'GET',
                                'content' => null,
                            ],
                        ];
                        require_once 'config.php';
                        global $botToken;
                        global $guildID;
                        $options['http']['content'] = null;
                        $options['https']['content'] = null;
                        $options['https']['method'] = 'GET';
                        $options['http']['method'] = 'GET';
                        $options['https']['header'] = 'Authorization: Bot ' . $botToken;
                        $options['http']['header'] = 'Authorization: Bot ' . $botToken;
                        $context = stream_context_create($options);
                        //var_dump($userID);
                        $result = file_get_contents('https://discordapp.com/api/users/' . str_replace("discord:", "", $discordID), false, $context);
                        $json = json_decode($result);
                        $avatarHash = $json->avatar;
                        $profilePic = 'https://cdn.discordapp.com/avatars/' . str_replace("discord:", "", $discordID) . '/' . $avatarHash . '.png';
                        $profilePicLink = $profilePic;
                        //var_dump($json);
                        $discordName = $json->username . '#' . $json->discriminator;
                        echo '<p><img src="' . $profilePicLink . '" />';
                        echo $discordName . '</p>';
                        $steamLink = $steamData->profileurl;
                        echo '<a href="' . $steamLink . '" target="_blank"><p><img src="css/img/steam-icon.png" />' . $steamLink . '</p></a>';
                        echo '</span>'
                        ?>
                    </div> <!-- End profile-info -->
                    <div id="account-wrapper">
                    <div id="tables-contain">
                        <div id="note-info">
                            <h2>Notes</h2>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Note</th>
                                    <th>Issued by</th>
                                    <th>
                                        <button class="add" onclick="punishUser('note', '<?php echo $userID; ?>');">
                                            Add
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $issuedByField = 'steamIdStaff';
                                $stmt = $sql->prepare("SELECT uid, note, " . $issuedByField . " FROM Notes WHERE " .
                                    "User_ID = ?;");
                                $stmt->bind_param("i", $userID);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['note'] . '</td>';
                                    $steamID = bchexdec(str_replace("steam:", "", $row[$issuedByField]));
                                    if ($steamID != 0) {
										// TODO Replace {KEY} with your Steam API key
                                        $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' .
                                            $steamAPIkey . '&steamids=' . $steamID);
                                        $steamData = json_decode($steamAPI)->response->players[0];
                                        echo '<td>' . $steamData->personaname . '</td>';
                                    }
                                    echo '<td><form action="removePunishment.php" method="post"><button name="uid" value="' . $row['uid'] . '" class="remove">Remove</button>'
                                        . '<input type="hidden" name="punishType" value="note" />' . '</form></td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="warn-info">
                            <h2>Warns</h2>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th>Issued by</th>
                                    <th>
                                        <button class="add" onclick="punishUser('warn', '<?php echo $userID; ?>');">
                                            Add
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $sql->prepare("SELECT uid, reason, " . $issuedByField . " FROM Warns WHERE " .
                                    "User_ID = ?;");
                                $stmt->bind_param("i", $userID);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['reason'] . '</td>';
                                    $steamID = bchexdec(str_replace("steam:", "", $row[$issuedByField]));
                                    if ($steamID != 0) {
                                        global $steamAPIkey;
                                        $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $steamAPIkey .
                                            '&steamids=' . $steamID);
                                        $steamData = json_decode($steamAPI)->response->players[0];
                                        echo '<td>' . $steamData->personaname . '</td>';
                                    }
                                    echo '<td><form action="removePunishment.php" method="post"><button name="uid" value="' . $row['uid'] . '" class="remove">Remove</button>'
                                        . '<input type="hidden" name="punishType" value="warn" />' . '</form></td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="kick-info">
                            <h2>Kicks</h2>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th>Issued by</th>
                                    <th>
                                        <button class="add" onclick="punishUser('kick', '<?php echo $userID; ?>');">
                                            Add
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $sql->prepare("SELECT uid, reason, " . $issuedByField . " FROM Kicks WHERE " .
                                    "User_ID = ?;");
                                $stmt->bind_param("i", $userID);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['reason'] . '</td>';
                                    $steamID = bchexdec(str_replace("steam:", "", $row[$issuedByField]));
                                    if ($steamID != 0) {
										global $steamAPIkey;
                                        $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $steamAPIkey .
                                            '&steamids=' . $steamID);
                                        $steamData = json_decode($steamAPI)->response->players[0];
                                        echo '<td>' . $steamData->personaname . '</td>';
                                    }
                                    echo '<td><form action="removePunishment.php" method="post"><button name="uid" value="' . $row['uid'] . '" class="remove">Remove</button>'
                                        . '<input type="hidden" name="punishType" value="kick" />' . '</form></td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="tempban-info">
                            <h2>Tempbans</h2>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th>Issued by</th>
                                    <th>End Date</th>
                                    <th>
                                        <button class="add" onclick="punishUser('tempban', '<?php echo $userID; ?>');">
                                            Add
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $sql->prepare("SELECT uid, reason, " . $issuedByField . ", endDate FROM Tempbans WHERE " .
                                    "User_ID = ?;");
                                $stmt->bind_param("i", $userID);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['reason'] . '</td>';
                                    $steamID = bchexdec(str_replace("steam:", "", $row[$issuedByField]));
                                    if ($steamID != 0) {
										global $steamAPIkey;
                                        $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $steamAPIkey .
                                            '&steamids=' . $steamID);
                                        $steamData = json_decode($steamAPI)->response->players[0];
                                        echo '<td>' . $steamData->personaname . '</td>';
                                    }
                                    $date = new DateTime(date('Y-m-d h:i:s', $row['endDate']), new DateTimeZone("UTC"));
                                    $date->setTimezone(new DateTimeZone("America/New_York"));
                                    echo '<td>' . $date->format('m/d/Y h:i:s') . '</td>';
                                    echo '<td><form action="removePunishment.php" method="post"><button name="uid" value="' . $row['uid'] . '" class="remove">Remove</button>'
                                        . '<input type="hidden" name="punishType" value="tempban" />' . '</form></td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="ban-info">
                            <h2>Bans</h2>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th>Issued by</th>
                                    <th>
                                        <button class="add" onclick="punishUser('ban', '<?php echo $userID; ?>');">Add
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $sql->prepare("SELECT uid, reason, " . $issuedByField . " FROM Bans WHERE " .
                                    "User_ID = ?;");
                                $stmt->bind_param("i", $userID);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['reason'] . '</td>';
                                    $steamID = bchexdec(str_replace("steam:", "", $row[$issuedByField]));
                                    if ($steamID != 0) {
										global $steamAPIkey;
                                        $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $steamAPIkey . '&steamids=' . $steamID);
                                        $steamData = json_decode($steamAPI)->response->players[0];
                                        echo '<td>' . $steamData->personaname . '</td>';
                                    }
                                    echo '<td><form action="removePunishment.php" method="post"><button name="uid" value="' . $row['uid'] . '" class="remove">Remove</button>'
                                        . '<input type="hidden" name="punishType" value="ban" />' . '</form></td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- End tables-contain -->
                    </div>
                </div> <!-- End content-view -->
            </div> <!-- End content-contain -->
        </div>
        </body>
        <script src="js/functions.js" rel="script"></script>
        </html>
        <?php
    } else {
        // They are not staff, take them to profile page TODO
    }
} else {
    header("Location: index.php");
}
?>