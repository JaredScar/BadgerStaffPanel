<?php
require_once 'config.php';
global $serverName;
?>
<html>
<head>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/sidebarStyle.css" />
</head>
<body>
<div class="row sidebar-row">
        <div id="main-sidebar">
            <div class="row main-title"><a href="index.php" target="_self"><span id="main-title"><?php echo $serverName; ?></span></a></div>
            <div class="row"><a href="index.php" target="_self"><span class="main-sect"><i class="glyphicon glyphicon-home"></i> Dashboard</span></a></div>
            <div class="row"><a href="players.php" target="_self"><span class="main-sect"><i class="glyphicon glyphicon-user"></i> Players</span></a></div>
            <div class="row"><a href="logs.php" target="_self"><span class="main-sect"><i class="glyphicon glyphicon-list-alt"></i> Logs</span></a></div>
            <div class="row"><a href="account.php" target="_self"><span class="main-sect"><i class="glyphicon glyphicon-cog"></i> Account</span></a></div>
    </div>
</div>
</body>
</html>