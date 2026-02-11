<?php
if (!isset($_SESSION['siswa'])) {
  $_SESSION['siswa'] = [
    ["nama" => "Rahmat Arianto", "nilai" => 86],
    ["nama" => "Anjayani Ikhtisamul", "nilai" => 78],
    ["nama" => "Yuni Perkassa", "nilai" => 100],
    ["nama" => "Budianto Santoso", "nilai" => 60],
  ];
}
function getGrade($nilai) {
  if ($nilai >= 85) return "A";
  if ($nilai >= 70) return "B";
  if ($nilai >= 65) return "C";
  return "D";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if ($_POST['action'] === "add") {
    $_SESSION['siswa'][] = [
      "nama" => $_POST['nama'],
      "nilai" => (int)$_POST['nilai']
    ];
  }

  if ($_POST['action'] === "edit") {
    $index = $_POST['index'];
    $_SESSION['siswa'][$index]['nilai'] = (int)$_POST['nilai'];
  }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Sistem Penilaian Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-2xl">
    <h1 class="text-2xl font-bold text-center mb-6">
      Sistem Penilaian Siswa
    </h1>

    <table class="w-full border border-gray-300 text-center mb-6">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 border">No</th>
          <th class="p-2 border">Nama</th>
          <th class="p-2 border">Nilai</th>
          <th class="p-2 border">Grade</th>
          <th class="p-2 border">Edit</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($_SESSION['siswa'] as $i => $data): ?>
          <tr class="hover:bg-gray-50">
            <td class="p-2 border"><?= $i + 1 ?></td>
            <td class="p-2 border"><?= $data['nama'] ?></td>
            <td class="p-2 border"><?= $data['nilai'] ?></td>
            <td class="p-2 border font-bold"><?= getGrade($data['nilai']) ?></td>
            <td class="p-2 border">
              <form method="POST" class="flex gap-2 justify-center">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="index" value="<?= $i ?>">
                <input
                  type="number"
                  name="nilai"
                  class="w-20 border rounded px-2"
                  required>
                <button class="bg-yellow-500 text-white px-3 rounded hover:bg-yellow-600">
                  Edit
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2 class="font-semibold mb-2">Tambah Siswa</h2>
    <form method="POST" class="flex gap-2">
      <input type="hidden" name="action" value="add">

      <input
        type="text"
        name="nama"
        placeholder="Nama siswa"
        class="flex-1 border rounded px-3 py-2"
        required>

      <input
        type="number"
        name="nilai"
        placeholder="Nilai"
        class="w-24 border rounded px-3 py-2"
        required>

      <button class="bg-blue-500 text-white px-4 rounded hover:bg-blue-600">
        Tambah
      </button>
    </form>

  </div>

</body>

</html>