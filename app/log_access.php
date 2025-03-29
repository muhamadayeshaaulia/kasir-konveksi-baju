<?php

function logAkses($page) {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    $ipArray = explode(',', $ipAddress);
    $ipAddress = trim($ipArray[0]);
    $waktuAkses = date("Y-m-d H:i:s");
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $deviceType = getDeviceType($userAgent);
    $role = isset($_SESSION['level']) ? $_SESSION['level'] : 'Guest';
    $logFile = "logs/akses_log_" . $page . ".txt";
    $logData = "IP: $ipAddress | Waktu: $waktuAkses | Halaman: $page | User-Agent: $userAgent | Perangkat: $deviceType | Role: $role\n";
    file_put_contents($logFile, $logData, FILE_APPEND);
}
function getDeviceType($userAgent) {
    if (preg_match('/mobile/i', $userAgent)) {
        return 'Mobile Device';
    } elseif (preg_match('/tablet/i', $userAgent)) {
        return 'Tablet';
    } elseif (preg_match('/(windows|macintosh|linux|x11)/i', $userAgent)) {
        return 'Laptop/Desktop';
    } else {
        return 'Unknown Device';
    }
}
if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 'dashboard';
}
logAkses($currentPage);
?>
