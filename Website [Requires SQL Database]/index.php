<?php
    session_start();
    require_once 'functions.php';
    $sql = getSQL();
    checkSessionTimer();
    /**
     * WEBSITE INFO:
     *
     * BadgerStaffPanel v1.0 ALPHA
     *
     * Permissions:
     * Player == Can view their punishments and notes
     * Trial-Moderator == Can note, warn, kick, and view player's punishments
     * Moderator == Can note, warn, kick, tempban, ban, and view player's punishments
     * Administrator == All permissions
     */
    if (!is_null($_GET['code']) && sizeof($_GET['code']) > 0) {
        // Let's verify them
        login($_GET['code']);
    }
    if (is_logged_in()) {
        if (is_staff()) {
            setLastPage("index.php");
			require_once 'config.php';
			global $serverIP;
			global $port;
			global $server_port;
			$content = file_get_contents("http://" . $serverIP . ":" . $server_port . "/players.json");
			$contentJSON = json_decode($content);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>(<?php echo sizeof($contentJSON); ?>) BadgerStaffPanel</title>
            <link rel="stylesheet" href="css/webReset.css"/>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
            <link rel="stylesheet" href="css/style.css" />
            <script src="js/functions.js" rel="script"></script>
        </head>
        <body id="background">
        <div class="row contain">
            <?php require_once 'sidebar.php'; ?>
            <div class="content-contain">
                <div class="header">Dashboard</div>
                <div class="stats-row row">
                    <div class="stat-box stat-color-yellow col-1">
                        <span>Warnings</span>
                        <p><?php
                            // TODO Get amount of warnings given out
                            $sql = getSQL();
                            $count = $sql->query("SELECT COUNT(*) AS total FROM Warns;");
                            echo $count->fetch_assoc()['total'];
                            ?>
                        </p>
                    </div>
                    <div class="stat-box stat-color-green col-1">
                        <span>Kicks</span>
                        <p><?php
                            // TODO Get amount of kicks given out
                            $count = $sql->query("SELECT COUNT(*) AS total FROM Kicks;");
                            echo $count->fetch_assoc()['total'];
                            ?>
                        </p>
                    </div>
                    <div class="stat-box stat-color-darkblue col-1">
                        <span>Tempbans</span>
                        <p><?php
                            // TODO Get amount of tempbans given out
                            $count = $sql->query("SELECT COUNT(*) AS total FROM Tempbans;");
                            echo $count->fetch_assoc()['total'];
                            ?>
                        </p>
                    </div>
                    <div class="stat-box stat-color-blue col-1">
                        <span>Bans</span>
                        <p><?php
                            // TODO Get amount of bans given out
                            $count = $sql->query("SELECT COUNT(*) AS total FROM Bans;");
                            echo $count->fetch_assoc()['total'];
                            ?>
                        </p>
                    </div>
                </div>
                <div class="stats-row row center-div">
                    <div class="stat-box stat-color-gray col-1">
                        <span>Online</span>
                        <p>
                            <?php
                            // TODO Show how many players are online
                            echo sizeof(json_decode($content));
                            ?>
                        </p>
                    </div>
                </div>
                <div id="online-players">
                    <?php
                    // Get players and display them
                    // TODO Change this with server IP in config
                    $data = array();
                    $players = json_decode($content);
                    foreach ($players as $player) {
                        $ping = $player->ping;
                        $name = $player->name;
                        $id = $player->id;
                        $gameLicense = $player->identifiers[1];
                        $userID = getUserIDFromGameLicense($gameLicense);
                        $playerData = [$id, $name, $ping, $userID];
                        array_push($data, $playerData);
                    }
                    //echo var_dump($data);
                    // TODO Make table update in real time
                    ?>
                    <table class="table table-striped" id="players">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Player Name</th>
                            <th scope="col">Ping</th>
                            <th scope="col">Note</th>
                            <th scope="col">Warn</th>
                            <th scope="col">Kick</th>
                            <th scope="col">Tempban</th>
                            <th scope="col">Ban</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($data as $player) {
                            echo '<tr>';
                            echo '<td>' . $player[0] . '</td>';
                            $userID = null;
                            echo '<td><a href="/viewUser.php?ID=' . $player[3] . '">' . $player[1] . '</a></td>';
                            echo '<td>' . $player[2] . '</td>';
                            echo '<td><button class="note" onclick="punishUser(\'note\', ' . $player[3] . ');"' . '>Note</button></td>';
                            echo '<td><button class="warn" onclick="punishUser(\'warn\', ' . $player[3] . ');"' . '>Warn</button></td>';
                            echo '<td><button class="kick" onclick="punishUser(\'kick\', ' . $player[3] . ');"' . '>Kick</button></td>';
                            echo '<td><button class="tempban" onclick="punishUser(\'tempban\', ' . $player[3] . ');"' . '>Tempban</button></td>';
                            echo '<td><button class="ban" onclick="punishUser(\'ban\', ' . $player[3] . ');"' . '>Ban</button></td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </body>
        <script
                src="https://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script rel="script" src="js/events.js"></script>
        </html>
<?php
    } else {
            // They are not staff, take them to profile page TODO
            ?>
            <h1>You have not been detected as Staff... Are you sure you are logged in using the right Discord?</h1>
            <?php
            header("Location: account.php");
        }
    } else { ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>BadgerStaffPanel v1.0 ALPHA</title>
            <link rel="stylesheet" href="css/webReset.css"/>
            <link rel="stylesheet" href="css/style.css"/>
        </head>
        <body id="discord-login">
        <div class="login-section">
            <img src="css/img/badgerstaffpanel-logo.png" alt="BadgerStaffPanel Logo"/>
            <a id="login" target="_self"
               href=""> <!-- TODO Needs Discord Redirect Authorization URL -->
                <div class="login-button-div">
                    <img src="css/img/discord-trans-honey.png"/>
                    <button>Login</button>
                </div>
            </a>
        </div>
        </body>
        <script
                src="https://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="js/functions.js" type="javascript"></script>
        <!--<script rel="script" src="js/events.js"></script>-->
        </html>
        <?php
    }
?>
