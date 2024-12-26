<?php
include 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Penjadwalan Kegiatan</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group button {
            width: 100%;
            padding: 10px 15px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.2);
        }

        .form-group button {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background: #007BFF;
            color: #ffffff;
        }

        th,
        td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f4f6f8;
        }

        tr:hover {
            background-color: #e9f2fb;
        }

        .button-container {
            margin-top: 30px;
            text-align: right;
        }

        .download-button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .download-button:hover {
            background-color: #218838;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 6px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Program Penjadwalan Kegiatan</h1>

        <!-- Form Tambah Data -->
        <form action="save_schedule.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="kegiatan">Nama Kegiatan:</label>
                <input type="text" id="kegiatan" name="kegiatan" placeholder="Masukkan nama kegiatan" required>
            </div>
            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai:</label>
                <input type="date" id="waktu_mulai" name="waktu_mulai" required>
            </div>
            <div class="form-group">
                <label for="jeda_waktu">Jeda Waktu (hari):</label>
                <input type="number" id="jeda_waktu" name="jeda_waktu" placeholder="Masukkan jeda waktu" required>
            </div>
            <div class="form-group">
                <label for="biaya">Biaya (Rp):</label>
                <input type="number" id="biaya" name="biaya" placeholder="Masukkan biaya kegiatan" required>
            </div>
            <div class="form-group">
                <label for="gambar">Upload Gambar:</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" required>
            </div>
            <div class="form-group">
                <button type="submit">Tambahkan Kegiatan</button>
            </div>
        </form>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Jeda Waktu</th>
                    <th>Biaya (Rp)</th>
                    <th>Gambar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Contoh penggunaan data dari database
                include 'db_connection.php';
                $sql = "SELECT * FROM jadwal";
                $result = $conn->query($sql);
                $total_biaya = 0; // Variabel untuk menghitung total biaya

                if ($result->num_rows > 0) {
                    $count = 0;
                    while ($row = $result->fetch_assoc()) {
                        $count++;
                        $waktu_selesai = date('Y-m-d', strtotime($row['waktu_mulai'] . ' + ' . $row['jeda_waktu'] . ' days'));
                        $status_label = $row['status'] === 'done' ? '✔ Selesai' : '⏳ Pending';
                        $total_biaya += $row['biaya'];
                        echo "<tr>
                                <td>{$count}</td>
                                <td>{$row['kegiatan']}</td>
                                <td>{$row['waktu_mulai']}</td>
                                <td>{$waktu_selesai}</td>
                                <td>{$row['jeda_waktu']}</td>
                                <td>Rp " . number_format($row['biaya'], 0, ',', '.') . "</td>
                                <td><img src='uploads/{$row['gambar']}' alt='gambar'></td>
                                <td>{$status_label}</td>
                                <td class='action-btns'>
                                    <a href='edit_schedule.php?id={$row['id']}' class='edit'>Edit</a>
                                    <a href='delete_schedule.php?id={$row['id']}' class='delete'>Hapus</a>
                                    </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Tidak ada data tersedia.</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <!-- Tambahkan baris Total Biaya -->
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Total Biaya:</td>
                    <td style="font-weight: bold;">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>

        <div class="button-container">
            <a href="download_pdf.php" class="download-button">Unduh Tabel ke PDF</a>
        </div>
    </div>
</body>

</html>
