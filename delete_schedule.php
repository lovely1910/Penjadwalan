<?php
$conn = new mysqli('localhost', 'root', '', 'penjadwalan');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM jadwal WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: penjadwalan.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: penjadwalan.php");
}

$conn->close();
?>
