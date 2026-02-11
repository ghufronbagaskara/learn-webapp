<?php
if (!isset($_SESSION['books'])) {
  $_SESSION['books'] = [
    "Laskar Pelangi",
    "Bumi Manusia",
    "Negeri 5 Menara",
    "Dilan 1990",
    "Atomic Habbits"
  ];
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];
  $book = trim($_POST['book']);

  if ($action == "borrow") {
    if (($key = array_search($book, $_SESSION['books'])) !== false) {
      unset($_SESSION['books'][$key]);
      $message = "Bulu <b>$book</b> berhasil dipinjam.";
    } else {
      $message = "Buku tidak tersedia.";
    }
  }

  if ($action == "return") {
    if (!in_array($book, $_SESSION['books'])) {
      $_SESSION['books'][] = $book;
      $message = "Buku <b>$book</b> berhasil dikembalikkan.";
    } else {
      $message = "Buku sudah ada di perpustakaan";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Sistem Perpustakaan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
    <h1 class="text-2xl font-bold text-center mb-6">
      Perpustakaan Sederhana - Maxy Academy
    </h1>

    <?php if ($message): ?>
      <div class="mb-4 p-3 bg-blue-100 text-blue-700 rounded">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <div class="mb-6">
      <h2 class="font-semibold mb-2">
        Daftar Buku Tersedia
      </h2>

      <?php if (count($_SESSION['books']) > 0): ?>
        <ul class="list-disc pl-5 text-gray-700">
          <?php foreach ($_SESSION['books'] as $b): ?>
            <li><?= htmlspecialchars($b) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-red-500">
          Semua buku sedang dipinjam.
        </p>
      <?php endif; ?>
    </div>

    <form method="POST" class="mb-4">
      <input type="hidden" name="action" value="borrow">

      <input
        type="text"
        name="book"
        placeholder="Judul buku yang dipinjam"
        class="w-full p-2 border rounded mb-2"
        required>

      <button
        class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
        Pinjam Buku
      </button>
    </form>


    <form method="POST">
      <input type="hidden" name="action" value="return">

      <input
        type="text"
        name="book"
        placeholder="Judul buku yang dikembalikan"
        class="w-full p-2 border rounded mb-2"
        required>

      <button
        class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
        Kembalikan Buku
      </button>
    </form>
  </div>

</body>

</html>