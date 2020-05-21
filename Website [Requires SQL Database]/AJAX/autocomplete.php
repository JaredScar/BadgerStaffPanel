<?php
require_once '../functions.php';
$sql = getSQL();
$input = "'%" . $_GET['input'] . "%'";
$stmt = $sql->prepare("SELECT ID, lastPlayerName, steamID, discord FROM Users WHERE lastPlayerName LIKE "
. $input . " OR steamID LIKE " . $input . " OR discord LIKE " . $input . ";");
$stmt->execute();
$res = $stmt->get_result();
$return = '';
while ($row = $res->fetch_assoc()) {
    $return .= '<tr>';
    $userID = $row['ID'];
    $discord = str_replace("discord:", "", $row['discord']);
    $steamID = bchexdec(str_replace("steam:", "", $row['steamID']));
    $steamLink = 'https://steamcommunity.com/profiles/' . $steamID;
    $playerName = $row['lastPlayerName'];

    $return .= '<td><a href="viewUser.php?ID=' . $userID . '">' . $userID . '</a></td>';
    $return .= '<td>' . $playerName . '</td>';
    $return .= '<td>' . $discord . '</td>';
    $return .= '<td><a href="' . $steamLink . '" target="_blank">' . $steamLink . '</a></td>';

    $tables = ['Notes', 'Warns', 'Kicks', 'Tempbans', 'Bans'];
    foreach ($tables as $table) {
        $statem = $sql->prepare("SELECT COUNT(*) AS total FROM " . $table . " WHERE User_ID = " . $userID . ";");
        $statem->execute();
        $resul = $statem->get_result();
        $count = $resul->fetch_assoc()['total'];
        $return .= '<td>' . $count . '</td>';
    }
    $return .= '</tr>';
}
echo $return;