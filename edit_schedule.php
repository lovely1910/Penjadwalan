<?php
// Sambungkan ke database
$conn = new mysqli('localhost', 'root', '', 'penjadwalan');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM jadwal WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
}

// Proses pembaruan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $kegiatan = $_POST['kegiatan'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $jeda_waktu = $_POST['jeda_waktu'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];

    // Perbarui data ke database
    $sql = "UPDATE jadwal SET kegiatan = ?, waktu_mulai = ?, jeda_waktu = ?, biaya = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssidsi', $kegiatan, $waktu_mulai, $jeda_waktu, $biaya, $status, $id);

    if ($stmt->execute()) {
        header('Location: penjadwalan.php?message=Data berhasil diperbarui');
        exit;
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal Kegiatan</title>
    <style>
        /* Tambahkan CSS untuk tampilan */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }
        .form-group input, .form-group select, .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Jadwal Kegiatan</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
            <div class="form-group">
                <label for="kegiatan">Nama Kegiatan:</label>
                <input type="text" id="kegiatan" name="kegiatan" value="<?= htmlspecialchars($data['kegiatan']) ?>" required>
            </div>
            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai:</label>
                <input type="date" id="waktu_mulai" name="waktu_mulai" value="<?= htmlspecialchars($data['waktu_mulai']) ?>" required>
            </div>
            <div class="form-group">
                <label for="jeda_waktu">Jeda Waktu (hari):</label>
                <input type="number" id="jeda_waktu" name="jeda_waktu" value="<?= htmlspecialchars($data['jeda_waktu']) ?>" required>
            </div>
            <div class="form-group">
                <label for="biaya">Biaya (Rp):</label>
                <input type="number" id="biaya" name="biaya" value="<?= htmlspecialchars($data['biaya']) ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="pending" <?= $data['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="done" <?= $data['status'] === 'done' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>
