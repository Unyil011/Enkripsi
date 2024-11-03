<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Himitsu</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">
    <h1>Himitsu</h1>
    <form method="POST">
        <label for="inputText">Masukkan Teks:</label>
        <textarea name="inputText" id="inputText" rows="3" required></textarea>

        <label for="keyword">Kata Kunci:</label>
        <input type="text" name="keyword" id="keyword" autocomplete="off" required>

        <label>Pilih Operasi:</label>
        <input type="radio" name="action" value="encrypt" checked> Enkripsi
        <input type="radio" name="action" value="decrypt"> Dekripsi

        <button type="submit">Proses</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inputText']) && isset($_POST['action']) && isset($_POST['keyword'])) {
        $h1 = "1234567890abcdefghijklmnopqrstuvwxyz.,?!ABCDEFGHIJKLMNOPQRSTUVWXYZ ";
        $h2 = "hijvwx123456defgyz.78ABCDEFGHIJKLMNOPQRSTUVWXYZ90abc,?!klmnopqrstu ";

        // Menyimpan kata kunci untuk keperluan dekripsi
        session_start();
        $keyword = $_POST['keyword'];

        // enkripsi
        function encme($kata, $keyword) {
            global $h1, $h2;
            $kata_kunci = str_repeat($keyword, ceil(strlen($kata) / strlen($keyword)));
            $kata_kunci = substr($kata_kunci, 0, strlen($kata));

            for ($i = 0; $i < strlen($kata); $i++) {
                $posisi_kata = strpos($h1, $kata[$i]);
                $posisi_kunci = strpos($h1, $kata_kunci[$i]);

                if ($posisi_kata !== false && $posisi_kunci !== false) {
                    $kata[$i] = $h2[($posisi_kata + $posisi_kunci) % strlen($h1)];
                }
            }
            return $kata;
        }

        // Dekripsi 
        function decme($kata, $keyword) {
            global $h1, $h2;
            $kata_kunci = str_repeat($keyword, ceil(strlen($kata) / strlen($keyword)));
            $kata_kunci = substr($kata_kunci, 0, strlen($kata));

            for ($i = 0; $i < strlen($kata); $i++) {
                $posisi_kata = strpos($h2, $kata[$i]);
                $posisi_kunci = strpos($h1, $kata_kunci[$i]);

                if ($posisi_kata !== false && $posisi_kunci !== false) {
                    $kata[$i] = $h1[($posisi_kata - $posisi_kunci + strlen($h1)) % strlen($h1)];
                }
            }
            return $kata;
        }

        $inputText = $_POST['inputText'];
        $action = $_POST['action'];
        $result = '';
        
        // periksa kunci
        if ($action == 'encrypt') {
            $_SESSION['keyword'] = $keyword; // Simpan kata kunci
            $result = encme($inputText, $keyword);
            echo "<div class='result'><strong>Hasil Enkripsi:</strong><br>
                  <textarea rows='3' style='width: 95%;'>" . htmlspecialchars($result) . "</textarea></div>";
        } elseif ($action == 'decrypt') {
            // Periksa sesuai tidak sama enkrip
            if (isset($_SESSION['keyword']) && $_SESSION['keyword'] === $keyword) {
                $result = decme($inputText, $keyword);
                echo "<div class='result'><strong>Hasil Dekripsi:</strong><br>
                      <textarea rows='3' style='width: 95a%;'>" . htmlspecialchars($result) . "</textarea></div>";
            } else {
                echo "<div class='result' style='color: red;'><strong>Peringatan:</strong> Kata kunci salah!</div>";
            }
        }
    }
    ?>
    <h5>Tugas mata kuliah "Keamanan Informasi & Jaringan"</h5> 
    <h6>By:</h6> 
    <p>M Rival Ramjani : 227200006</p> 
    <p>Algifa Cahya    : 227200008</p>
    
</div>

</body>
</html>
 