<?php
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Sambungkan ke database
$conn = new mysqli('localhost', 'root', '', 'penjadwalan');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari database
$sql = "SELECT * FROM jadwal";
$result = $conn->query($sql);

$html = '<h1 style="font-family: Arial, sans-serif; text-align: center; color: #333; margin-bottom: 20px;">Jadwal Kegiatan</h1>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
            <thead>
                <tr style="background-color: #007BFF; color: #ffffff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #dddddd;">No</th>
                    <th style="padding: 10px; border: 1px solid #dddddd;">Nama Kegiatan</th>
                    <th style="padding: 10px; border: 1px solid #dddddd;">Waktu Mulai</th>
                    <th style="padding: 10px; border: 1px solid #dddddd;">Waktu Selesai</th>
                    <th style="padding: 10px; border: 1px solid #dddddd;">Jeda Waktu (hari)</th>
                    <th style="padding: 10px; border: 1px solid #dddddd;">Biaya (Rp)</th>
                    <th style="padding: 10px; border: 1px solid #dddddd;">Gambar (Rp)</th>
                </tr>
            </thead>
            <tbody>';

$total_biaya = 0; // Variabel untuk menyimpan total biaya
if ($result->num_rows > 0) {
    $counter = 0;
    while ($row = $result->fetch_assoc()) {
        $counter++;
        $waktu_selesai = date('Y-m-d', strtotime($row['waktu_mulai'] . ' + ' . $row['jeda_waktu'] . ' days'));
        $row_background = $counter % 2 === 0 ? '#f2f2f2' : '#ffffff';

        $html .= "<tr style='background-color: {$row_background};'>
                    <td style='padding: 10px; border: 1px solid #dddddd;'>{$counter}</td>
                    <td style='padding: 10px; border: 1px solid #dddddd;'>{$row['kegiatan']}</td>
                    <td style='padding: 10px; border: 1px solid #dddddd;'>{$row['waktu_mulai']}</td>
                    <td style='padding: 10px; border: 1px solid #dddddd;'>{$waktu_selesai}</td>
                    <td style='padding: 10px; border: 1px solid #dddddd;'>{$row['jeda_waktu']}</td>
                    <td style='padding: 10px; border: 1px solid #dddddd;'>Rp " . number_format($row['biaya'], 2, ',', '.') . "</td>
                    <td style='padding: 10px; border: 1px solid #dddddd;'><img src='uploads/{$row['gambar']}' alt='gambar'></td>
                    </td>
                </tr>";
        $total_biaya += $row['biaya'];
    }
    
} else {
    $html .= '<tr><td colspan="6" style="padding: 10px; text-align: center; border: 1px solid #dddddd;">Tidak ada data.</td></tr>';
}

$html .= '</tbody>
          <tfoot>
              <tr style="background-color: #f8f9fa; font-weight: bold;">
                  <td colspan="5" style="padding: 10px; border: 1px solid #dddddd; text-align: right;">Total Biaya:</td>
                  <td style="padding: 10px; border: 1px solid #dddddd;">Rp ' . number_format($total_biaya, 2, ',', '.') . '</td>
              </tr>
          </tfoot>
          </table>';

// Inisialisasi DOMPDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'landscape');

// Render HTML menjadi PDF
$dompdf->render();

// Output file PDF ke browser
$dompdf->stream("jadwal_kegiatan.pdf", ["Attachment" => 1]);

$conn->close();

?>
