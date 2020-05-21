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
require __DIR__ . '/../SourceQuery/bootstrap.php';
//use 'classes\SourceQuery\SourceQuery';
require_once 'classes/SourceQuery/SourceQuery';
global $serverIP;
global $port;
global $rconPassword;
checkSessionTimer();
$sql = getSQL();
define( 'SQ_SERVER_ADDR', $serverIP );
	define( 'SQ_SERVER_PORT', $port );
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery::SOURCE );
	// Edit this <-
	
	$rcon = new SourceQuery( );
	
	try
	{
		$rcon->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
		
		$rcon->SetRconPassword( $rconPassword );
	}
	catch( Exception $e )
	{
		echo $e->getMessage( );
	}
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
                        $stmt = $sql->prepare('INSERT INTO `Notes` (`User_ID`, `steamIDStaff`, `Note`) VALUES (?, ?, ?);');
                        $stmt->bind_param('iss', $userID, getSteamFromDiscordID(getUserDiscordID()), $msg);
                        //TODO MAKE BELOW MORE SOPHISTICATED:
                        if ($stmt->execute()) {
                            header('Location: ' . getLastPage() . '&result=success');
                        } else {
                            header('Location: index.php' . getLastPage() . '&result=failure');
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
                        $rcon->Rcon("panelWarn " . $userID . " " . $discordID . " " . $msg);
                        header("Location: " . getLastPage() . "&result=success");
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
                        $rcon->Rcon("panelKick " . $userID . " " . $discordID . " " . $msg);
                        header("Location: " . getLastPage() . "&result=success");
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
                                $rcon->Rcon("panelTempban " . $userID . " " . $tempbanTime . "h " . $discordID . " "
                                . $msg);
                                break;
                            case "day":
                                $rcon->Rcon("panelTempban " . $userID . " " . $tempbanTime . "d " . $discordID . " "
                                    . $msg);
                                break;
                            case "week":
                                $rcon->Rcon("panelTempban " . $userID . " " . $tempbanTime . "w " . $discordID . " "
                                    . $msg);
                                break;
                            case "month":
                                $rcon->Rcon("panelTempban " . $userID . " " . $tempbanTime . "m " . $discordID . " "
                                    . $msg);
                                break;
                        }
                        header("Location: " . getLastPage() . "&result=success");
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
                        $rcon->Rcon("panelBan " . $userID . " " . $discordID . " " . $msg);
                        header("Location: " . getLastPage() . "&result=success");
                        return;
                    }
                }
                break;
        }
    } else {
        // Not staff TODO
    }
} else {
    // Not logged in TODO
}