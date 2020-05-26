<?php
session_start();
require_once 'config.php';
function bchexdec($hex)
{
    $dec = 0;
    $len = strlen($hex);
    for ($i = 1; $i <= $len; $i++) {
        $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
    }
    return $dec;
}

function getSQL() {
    global $host;
    global $username;
    global $password;
    global $db;
    global $port;
    $sql = new mysqli($host, $username, $password, $db, $port);
    return $sql;
}

function is_logged_in() {
    if ($_SESSION['is-logged-in'] === true) {
        return true;
    }
    return false;
}
function is_staff() {
    if ($_SESSION['is-staff'] === true) {
        return true;
    }
    return false;
}
function getPermissions() {
    return $_SESSION['Permissions'];
}

function checkSessionTimer() {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
        // last request was more than 15 minutes ago
        // 300 = 5 minutes
        // 600 = 10 minutes
        // 900 = 15 minutes
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
    }
    $_SESSION['LAST_ACTIVITY'] = time();
}

function getUserIDFromGameLicense($gameLicense) {
    $sql = getSQL();
    $stmt = $sql->prepare("SELECT ID FROM Users WHERE gameLicense = ?;");
    $stmt->bind_param("s", $gameLicense);
    if($stmt->execute()) {
        $res = $stmt->get_result();
        return $res->fetch_assoc()['ID'];
    }
    return false; // Didn't execute successfully
}
function getUserIDFromSteamID($steam) {
    $sql = getSQL();
    $stmt = $sql->prepare("SELECT ID FROM Users WHERE steamID = ?;");
    $stmt->bind_param("s", $steam);
    if($stmt->execute()) {
        $res = $stmt->get_result();
        return $res->fetch_assoc()['ID'];
    }
    return false; // Didn't execute successfully
}
function getUserIDFromDiscord($discord) {
    $sql = getSQL();
    $stmt = $sql->prepare("SELECT ID FROM Users WHERE discord = ?;");
    $stmt->bind_param("s", $discord);
    if($stmt->execute()) {
        $res = $stmt->get_result();
        return $res->fetch_assoc()['ID'];
    }
    return false; // Didn't execute successfully
}
function getSteamFromDiscordID($id) {
    $sql = getSQL();
    $disc = "discord:" . $id;
    $stmt = $sql->prepare("SELECT steamID FROM Users WHERE discord = ?;");
    $stmt->bind_param("s", $disc);
    if($stmt->execute()) {
        $res = $stmt->get_result();
        return $res->fetch_assoc()['steamID'];
    }
    return false; // Didn't execute successfully
}
function getUserDiscordID() {
    return $_SESSION['discord'];
}
function setLastPage($page) {
    $_SESSION['lastPage'] = $page;
}
function getLastPage() {
    return $_SESSION['lastPage'];
}
function logout() {
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
}
require_once 'config.php';
function login($discordCode) {
    // TODO Log them in thru discord
    global $clientID;
    global $clientSecret;
    global $redirect_URI;
    $data = [
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'grant_type' => 'authorization_code',
        'code' => $discordCode,
        'redirect_uri' => $redirect_URI,
        'scope' => 'identify email guilds guilds.join'
    ];
    $options =  [
        'https' => [
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'method' => 'POST',
            'content' => http_build_query($data)
        ],
        'http' => [
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'method' => 'POST',
            'content' => http_build_query($data)
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents('https://discordapp.com/api/oauth2/token', false, $context);
    $jsonTokens = json_decode($result, true);
    $accessToken = $jsonTokens['access_token'];
    $refreshToken = $jsonTokens['refresh_token'];

    //var_dump($result);
    //var_dump('Access Token: ' . $accessToken);
    //var_dump('Refresh Token: ' . $refreshToken);

    $options['https']['header'] = 'Authorization: Bearer ' . $accessToken;
    $options['http']['header'] = 'Authorization: Bearer ' . $accessToken;
    $options['http']['content'] = null;
    $options['https']['content'] = null;
    $options['https']['method'] = 'GET';
    $options['http']['method'] = 'GET';

    $context = stream_context_create($options);

    $result = file_get_contents('https://discordapp.com/api/users/@me', false, $context);
    //var_dump($result);
    $jsonUser = json_decode($result, true);
    $userID = $jsonUser['id'];
    $userEmail = $jsonUser['email'];
    $_SESSION['discord'] = $userID;

    global $guildID;
    global $botToken;
    $options['https']['header'] = 'Authorization: Bot ' . $botToken;
    $options['http']['header'] = 'Authorization: Bot ' . $botToken;
    $context = stream_context_create($options);
    //var_dump($userID);
    $result = file_get_contents('https://discordapp.com/api/guilds/' . $guildID . "/members/" . $userID, false, $context);
    //var_dump($result);
    $jsonMember = json_decode($result, true);
    $roles = $jsonMember['roles'];
    global $permissionsSetup;
    foreach ($permissionsSetup as $key => $val) {
        for ($j = 0; $j < sizeof($roles); $j++) {
            $roleID = $roles[$j];
            $permRole = $key;
            //var_dump("The roleID is: " . $roleID);
            //var_dump("The permRole is: " . $permRole);
            if (strval($roleID) === strval($permRole)) {
                // This is their permissions
                $_SESSION['Permissions'] = strval($permRole);
                $_SESSION['is-staff'] = true;
            }
        }
    }
    $_SESSION['is-logged-in'] = true;
    // TODO Get their profile picture for profile and set it as their icon in right-hand corner
    //header('Location: ' . $redirect_URI);
}