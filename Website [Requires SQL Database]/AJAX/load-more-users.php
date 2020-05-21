<?php
session_start();
$_SESSION['players-page'] = $_SESSION['players-page'] + 1;
$page = $_SESSION['players-page'];
$start = 20 * ($page);
$end = $start + 20;
$result = '';
require_once '../functions.php';
$sql = getSQL();
$stmt = $sql->prepare("SELECT ID, lastPlayerName, steamID, discord FROM Users LIMIT " . $start . ", " . $end . ";");
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $result .= '<tr>';
    $userID = $row['ID'];
    $discord = str_replace("discord:", "", $row['discord']);
    $steamID = bchexdec(str_replace("steam:", "", $row['steamID']));
    $steamLink = 'https://steamcommunity.com/profiles/' . $steamID;
    $playerName = $row['lastPlayerName'];

    $result .= '<td><a href="viewUser.php?ID=' . $userID . '">' . $userID . '</a></td>';
    $result .= '<td>' . $playerName . '</td>';
    $result .= '<td>' . $discord . '</td>';
    $result .= '<td><a href="' . $steamLink . '" target="_blank">' . $steamLink . '</a></td>';

    $tables = ['Notes', 'Warns', 'Kicks', 'Tempbans', 'Bans'];
    foreach ($tables as $table) {
        $statem = $sql->prepare("SELECT COUNT(*) AS total FROM " . $table . " WHERE User_ID = " . $userID . ";");
        $statem->execute();
        $resul = $statem->get_result();
        $count = $resul->fetch_assoc()['total'];
        $result .= '<td>' . $count . '</td>';
    }
    $result .= '</tr>';
}
echo $result;
