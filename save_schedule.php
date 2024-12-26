<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $kegiatan = $_POST['kegiatan'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $jeda_waktu = $_POST['jeda_waktu'];
    $biaya = $_POST['biaya'];

    // Upload file gambar
    $target_dir = "uploads/";
    $gambar_name = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $gambar_name;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        // Query untuk memasukkan data ke dalam database
        $sql = "INSERT INTO jadwal (kegiatan, waktu_mulai, jeda_waktu, biaya, gambar, status)
                VALUES ('$kegiatan', '$waktu_mulai', $jeda_waktu, $biaya, '$gambar_name', 'pending')";

        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil ditambahkan!";
            header("Location: penjadwalan.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error dalam mengupload file.";
    }
}
$conn->close();
?>
