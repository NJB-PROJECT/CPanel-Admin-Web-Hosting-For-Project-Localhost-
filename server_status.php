<?php
header('Content-Type: application/json');

// CPU & RAM dari Windows ga bisa pakai /proc, jadi kita isi dummy dulu
$cpuLoad = rand(5, 50); // Simulasi
$ramUsed = rand(20, 70); // Simulasi
$diskUsed = round((disk_total_space("C:") - disk_free_space("C:")) / disk_total_space("C:") * 100, 2);

// Cek koneksi MySQL
$mysqli = @new mysqli("localhost", "root", "", "admin_panel_db");
$mysqlStatus = $mysqli->connect_errno ? "Stopped" : "Running";
$mysqli->close();

// Apache di Windows bisa dicek pakai tasklist
$apacheRaw = shell_exec('tasklist | findstr "httpd.exe"');
$apacheStatus = strpos($apacheRaw, "httpd.exe") !== false ? "Running" : "Stopped";

echo json_encode([
    "cpuLoad" => $cpuLoad,
    "ramUsed" => $ramUsed,
    "diskUsed" => $diskUsed,
    "apache" => $apacheStatus,
    "mysql" => $mysqlStatus
]);
