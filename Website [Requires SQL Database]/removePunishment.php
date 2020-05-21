<?php
session_start();
require_once 'functions.php';
$sql = getSQL();
checkSessionTimer();
$uid = $_POST['uid'];
$punishmentType = $_POST['punishType'];
$_SESSION['Permissions'] = getPermissions();
if (is_logged_in()) {
    if (is_staff()) {
        switch ($punishmentType) {
            case 'note':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Note
                    if ($permVal === "Permission.RemoveNote") {
                        // They have perms to do it, do it
                        // We need to log it first
                        $stmt = $sql->prepare("SELECT * FROM `Notes` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $punishID = 0;
                        $punishType = 'Note';
                        $punishedBySteam = $row['steamIdStaff'];
                        $IDpunished = $row['User_ID'];
                        $removedByID = getUserIDFromDiscord('discord:' . getUserDiscordID());
                        $data = 'note: ' . $row['note'] . " || " . "revoked_by_UserID: " . $removedByID;
                        $date = new DateTime('America/New_York');
                        $actionDate = $date->format('m/d/Y h:i:s a');
                        $action = 'Remove';
                        $insert = $sql->prepare("INSERT INTO `Logger` VALUES (?, ?, ?, ?, ?, ?, ?);");
                        $insert->bind_param("ississs", $punishID, $punishType, $punishedBySteam,
                            $IDpunished, $data, $actionDate, $action);
                        $insert->execute();
                        // End logging it
                        $stmt = $sql->prepare("DELETE FROM `Notes` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        if ($stmt->execute()) {
                            // It was successful
                            header("Location: " . getLastPage() . "&result=success");
                        } else {
                            // Failure
                            header("Location: " . getLastPage() . "&result=failure");
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
                    if ($permVal === "Permission.RemoveWarn") {
                        // They have perms to do it, do it
                        // We need to log it first
                        $stmt = $sql->prepare("SELECT * FROM `Warns` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $punishID = 0;
                        $punishType = 'Warn';
                        $punishedBySteam = $row['steamIdStaff'];
                        $IDpunished = $row['User_ID'];
                        $removedByID = getUserIDFromDiscord('discord:' . getUserDiscordID());
                        $data = 'reason: ' . $row['reason'] . " || " . "revoked_by_UserID: " . $removedByID;
                        $date = new DateTime('America/New_York');
                        $actionDate = $date->format('m/d/Y h:i:s a');
                        $action = 'Remove';
                        $insert = $sql->prepare("INSERT INTO `Logger` VALUES (?, ?, ?, ?, ?, ?, ?);");
                        $insert->bind_param("ississs", $punishID, $punishType, $punishedBySteam,
                            $IDpunished, $data, $actionDate, $action);
                        $insert->execute();
                        // End logging it
                        $stmt = $sql->prepare("DELETE FROM `Warns` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        if ($stmt->execute()) {
                            // It was successful
                            header("Location: " . getLastPage() . "&result=success");
                        } else {
                            // Failure
                            header("Location: " . getLastPage() . "&result=failure");
                        }
                        return;
                    }
                }
                break;
            case 'kick':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Kick
                    if ($permVal === "Permission.RemoveKick") {
                        // They have perms to do it, do it
                        // We need to log it first
                        $stmt = $sql->prepare("SELECT * FROM `Kicks` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $punishID = 0;
                        $punishType = 'Kick';
                        $punishedBySteam = $row['steamIdStaff'];
                        $IDpunished = $row['User_ID'];
                        $removedByID = getUserIDFromDiscord('discord:' . getUserDiscordID());
                        $data = 'reason: ' . $row['reason'] . " || " . "revoked_by_UserID: " . $removedByID;
                        $date = new DateTime('America/New_York');
                        $actionDate = $date->format('m/d/Y h:i:s a');
                        $action = 'Remove';
                        $insert = $sql->prepare("INSERT INTO `Logger` VALUES (?, ?, ?, ?, ?, ?, ?);");
                        $insert->bind_param("ississs", $punishID, $punishType, $punishedBySteam,
                            $IDpunished, $data, $actionDate, $action);
                        $insert->execute();
                        // End logging it
                        $stmt = $sql->prepare("DELETE FROM `Kicks` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        if ($stmt->execute()) {
                            // It was successful
                            header("Location: " . getLastPage() . "&result=success");
                        } else {
                            // Failure
                            header("Location: " . getLastPage() . "&result=failure");
                        }
                        return;
                    }
                }
                break;
            case 'tempban':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Tempban
                    if ($permVal === "Permission.RemoveTempban") {
                        // They have perms to do it, do it
                        // We need to log it first
                        $stmt = $sql->prepare("SELECT * FROM `Tempbans` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $punishID = 0;
                        $punishType = 'Tempban';
                        $punishedBySteam = $row['steamIdStaff'];
                        $IDpunished = $row['User_ID'];
                        $removedByID = getUserIDFromDiscord('discord:' . getUserDiscordID());
                        $data = 'reason: ' . $row['reason'] . " || " . "endDate: " . $row['endDate'] .
                            " || " . "revoked_by_UserID: " . $removedByID;
                        $date = new DateTime('America/New_York');
                        $actionDate = $date->format('m/d/Y h:i:s a');
                        $action = 'Remove';
                        $insert = $sql->prepare("INSERT INTO `Logger` VALUES (?, ?, ?, ?, ?, ?, ?);");
                        $insert->bind_param("ississs", $punishID, $punishType, $punishedBySteam,
                            $IDpunished, $data, $actionDate, $action);
                        $insert->execute();
                        // End logging it
                        $stmt = $sql->prepare("DELETE FROM `Tempbans` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        if ($stmt->execute()) {
                            // It was successful
                            header("Location: " . getLastPage() . "&result=success");
                        } else {
                            // Failure
                            header("Location: " . getLastPage() . "&result=failure");
                        }
                        return;
                    }
                }
                break;
            case 'ban':
                global $permissionsSetup;
                $perms = $permissionsSetup[$_SESSION['Permissions']];
                foreach ($perms as $permVal) {
                    // Check if they have Permission.Ban
                    if ($permVal === "Permission.RemoveBan") {
                        // They have perms to do it, do it
                        // We need to log it first
                        $stmt = $sql->prepare("SELECT * FROM `Bans` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $punishID = 0;
                        $punishType = 'Ban';
                        $punishedBySteam = $row['steamIdStaff'];
                        $IDpunished = $row['User_ID'];
                        $removedByID = getUserIDFromDiscord('discord:' . getUserDiscordID());
                        $data = 'reason: ' . $row['reason'] . " || " . "revoked_by_UserID: " . $removedByID;
                        $date = new DateTime('America/New_York');
                        $actionDate = $date->format('m/d/Y h:i:s a');
                        $action = 'Remove';
                        $insert = $sql->prepare("INSERT INTO `Logger` VALUES (?, ?, ?, ?, ?, ?, ?);");
                        $insert->bind_param("ississs", $punishID, $punishType, $punishedBySteam,
                            $IDpunished, $data, $actionDate, $action);
                        $insert->execute();
                        // End logging it
                        $stmt = $sql->prepare("DELETE FROM `Bans` WHERE `uid` = ?;");
                        $stmt->bind_param("i", $uid);
                        if ($stmt->execute()) {
                            // It was successful
                            header("Location: " . getLastPage() . "?result=success");
                        } else {
                            // Failure
                            header("Location: " . getLastPage() . "?result=failure");
                        }
                        return;
                    }
                }
                break;
        }
    } else {
        // Is not staff, TODO
    }
} else {
    // Not logged in, TODO
}