<?php
session_start();
require_once 'functions.php';
checkSessionTimer();

$userID = $_GET['UserID'];
$punishmentType = $_GET['punishType'];

$msg = $_GET['msg'];
$tempbanTime = $_GET['tempbanTime'];
$tempbanType = $_GET['tempbanType'];
$discordID = 'discord:' . getUserDiscordID();

$_SESSION['Permissions'] = getPermissions();
require_once 'config.php';
require_once 'classes/q3query.php';
global $serverIP;
global $server_port;
global $rconPassword;
checkSessionTimer();
$sql = getSQL();

	// Edit this <-
	
	$rcon = new q3query($serverIP, $server_port);
	$rcon->setRconpassword($rconPassword);

if (is_logged_in()) {
    if (is_staff()) {
        switch ($punishmentType) {
            case 'note':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Note
                    if ($permVal === "Permission.Note") {
                        // They have perms to do it, do it
                        $stmt = $sql->prepare('INSERT INTO `Notes` (`User_ID`, `steamIDStaff`, `steamIDPlayer`, `Note`) VALUES (?, ?, ?, ?);');
                        $puniserSteam = strval(getSteamFromDiscordID(getUserDiscordID()));
                        $punishedSteam = strval(getSteamFromUserID($userID));
                        $stmt->bind_param('isss', $userID, $puniserSteam, $punishedSteam, $msg);
                        if ($stmt->execute()) {
                            header('Location: ' . getLastPage() . '');
                            //echo "SUCCESS";
                        } else {
                            header('Location: ' . getLastPage() . '');
                            //echo "FAILURE";
                        }
                        return;
                    }
                }
                break;
            case 'warn':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Warn
                    if ($permVal === "Permission.Warn") {
                        // They have perms to do it, do it
                        //$stmt = $sql->prepare('INSERT INTO `Warns` (`User_ID`, `reason`) VALUES (?, ?);');
                        //$stmt->bind_param('is', $userID, $msg);
                        // Execute it through RCON (SQL through RCON too):
                        $rcon->rcon("panelWarn " . $userID . " " . $discordID . " " . $msg);
                        header("Location: " . getLastPage() . "");
                        return;
                    }
                }
                break;
            case 'kick':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Kick
                    if ($permVal === "Permission.Kick") {
                        // They have perms to do it, do it
                        //$stmt = $sql->prepare('INSERT INTO `Kicks` (`User_ID`, `reason`) VALUES (?, ?);');
                        //$stmt->bind_param('is', $userID, $msg);
                        // Execute it through RCON (SQL through RCON too):
                        $rcon->rcon("panelKick " . $userID . " " . $discordID . " " . $msg);
                        header("Location: " . getLastPage() . "");
                        return;
                    }
                }
                break;
            case 'tempban':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Tempban
                    if ($permVal === "Permission.Tempban") {
                        // They have perms to do it, do it
                        // Execute it through RCON (SQL through RCON too):
                        //$tempbanType; // HOUR, DAY, WEEK, MONTH
                        //$tempbanTime
                        switch (strtolower($tempbanType)) {
                            case "hour":
                                $rcon->rcon("panelTempban " . $userID . " " . $tempbanTime . "h " . $discordID . " "
                                . $msg);
                                break;
                            case "day":
                                $rcon->rcon("panelTempban " . $userID . " " . $tempbanTime . "d " . $discordID . " "
                                    . $msg);
                                break;
                            case "week":
                                $rcon->rcon("panelTempban " . $userID . " " . $tempbanTime . "w " . $discordID . " "
                                    . $msg);
                                break;
                            case "month":
                                $rcon->rcon("panelTempban " . $userID . " " . $tempbanTime . "m " . $discordID . " "
                                    . $msg);
                                break;
                        }
                        header("Location: " . getLastPage() . "");
                        return;
                    }
                }
                break;
            case 'ban':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Ban
                    if ($permVal === "Permission.Ban") {
                        // They have perms to do it, do it
                        //$stmt = $sql->prepare('INSERT INTO `Bans` (`User_ID`, `reason`) VALUES (?, ?);');
                        //$stmt->bind_param('is', $userID, $msg);
                        // Execute it through RCON (SQL through RCON too):
                        $rcon->rcon("panelBan " . $userID . " " . $discordID . " " . $msg);
                        header("Location: " . getLastPage() . "");
                        return;
                    }
                }
                break;
        }
    } else {
        // Not staff
    }
} else {
    // Not logged in
}