<?php
date_default_timezone_set("Asia/Jakarta");

$file = 'list_data.json';

function getExpiryDateFromCert($certContent) {
    // Decode jika masih dalam format Base64
    $decodedCert = base64_decode($certContent);
    if (!$decodedCert) {
        error_log("Failed to decode Base64 certificate.");
        return "-";
    }

    $certInfo = openssl_x509_parse($decodedCert);
    if (!$certInfo) {
        error_log("Error parsing certificate.");
        return "-";
    }

    error_log("Certificate Info: " . print_r($certInfo, true));

    if (isset($certInfo['validTo_time_t'])) {
        return date('Y-m-d', $certInfo['validTo_time_t']);
    }
    return "-";
}



// Validasi input
$domain = $_POST['domain'] ?? '';
if (!$domain || !isset($_FILES['crt_file']) || !isset($_FILES['key_file'])) {
    echo "Invalid input.";
    exit;
}
// Ambil isi file
$certContent = file_get_contents($_FILES['crt_file']['tmp_name']);
$keyContent = file_get_contents($_FILES['key_file']['tmp_name']);

error_log("Uploaded Cert Content:\n" . $certContent); // Cek isi cert

$expiry = getExpiryDateFromCert($certContent);
$status = ($expiry !== "-") ? "Valid" : "Invalid";

// Ambil isi file
$certContent = file_get_contents($_FILES['crt_file']['tmp_name']);
$keyContent = file_get_contents($_FILES['key_file']['tmp_name']);

error_log("Uploaded Cert Content:\n" . $certContent); // Cek isi cert

$expiry = getExpiryDateFromCert($certContent);
$status = ($expiry !== "-") ? "Valid" : "Invalid";

// Ambil data lama
$data = [];
if (file_exists($file)) {
    $json = file_get_contents($file);
    $decoded = json_decode($json, true);
    if (is_array($decoded)) {
        $data = $decoded;
    }
}

// Tambah data baru
$data[] = [
    "domain" => $domain,
    "certificate" => base64_encode($certContent),
    "key" => base64_encode($keyContent),
    "expiry" => $expiry,
    "status" => $status,
    "timestamp" => date("Y-m-d H:i:s")
];

// Simpan ulang sebagai array JSON valid
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

// Balik ke index
header("Location: index.html");
exit;
?>
