<?php
header("Content-Type: application/json");

$file = 'list_data.json';
$data = [];

if (file_exists($file)) {
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);

    $today = new DateTime();

    foreach ($data as &$entry) {
        if (isset($entry['expiry']) && $entry['expiry'] !== '-') {
            $expiryDate = DateTime::createFromFormat('Y-m-d', $entry['expiry']);
            if ($expiryDate && $expiryDate < $today) {
                $entry['status'] = 'Expired';
            }
        }
    }

    // Simpan kembali data yang sudah di-update (opsional)
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

echo json_encode($data);
