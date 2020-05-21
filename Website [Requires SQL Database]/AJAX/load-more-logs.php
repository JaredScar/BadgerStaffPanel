<?php
$page = $_SESSION['logs-page'] + 1;
$_SESSION['logs-page'] = $page;
$start = 15 * $page;
$end = $start + 15;
require_once '../functions.php';
$sql = getSQL();

$stmt = $sql->prepare("SELECT * FROM `Logger` ORDER BY Punish_ID DESC LIMIT $start, $end;");
$stmt->execute();
$res = $stmt->get_result();
$response = '';
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
    $response .= '<tr>';
    $response .= '<td><span class="' . $action . '">' . $action . '</span></td>';
    $response .= '<td>' . $pid . '</td>';
    $response .= '<td>' . $ptype . '</td>';
	$response .= '<td><textarea disabled="" style="resize: none;">' . $data . '</textarea></td>';
    $steamAPI = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=419B129CA3FE72C01B2982B6A305CE0F&steamids=' . $steamID);
    $steamData = json_decode($steamAPI)->response->players[0];
    $response .= '<td><a href="viewUser.php?ID=' . $punisherUID .'" target="_self">' . $steamData->personaname . '</a></td>';
    $response .= '<td><a href="viewUser.php?ID=' . $punishedUID . '" target="_self">' . $punishedUID . '</a></td>';
    $response .= '<td>' . $date . '</td>';
    $response .= '</tr>';
}
echo $response;