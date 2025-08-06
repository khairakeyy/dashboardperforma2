<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Input Data Usaha</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100">

  <div class="max-w-5xl mx-auto mt-16 bg-white p-10 rounded-xl shadow-md">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-8">Form Input Usaha Diaspora</h2>
    <form action="proses_input.php" method="POST" enctype="multipart/form-data" class="space-y-6">
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="id_pegawai" class="block font-semibold mb-1 text-gray-700">ID Pegawai</label>
          <input type="text" id="id_pegawai" name="id_pegawai" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label for="tanggal_input" class="block font-semibold mb-1 text-gray-700">Tanggal Input</label>
          <input type="date" id="tanggal_input" name="tanggal_input" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="nama_owner" class="block font-semibold mb-1 text-gray-700">Nama Owner</label>
          <input type="text" id="nama_owner" name="nama_owner" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label for="alamat_owner" class="block font-semibold mb-1 text-gray-700">Alamat Owner</label>
          <input type="text" id="alamat_owner" name="alamat_owner" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="asal_owner" class="block font-semibold mb-1 text-gray-700">Asal Owner</label>
          <input type="text" id="asal_owner" name="asal_owner" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label for="handphone" class="block font-semibold mb-1 text-gray-700">No HP</label>
          <input type="text" id="handphone" name="handphone" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="foto_owner" class="block font-semibold mb-1 text-gray-700">Foto Owner (maks. 100 MB)</label>
          <input type="file" id="foto_owner" name="foto_owner" accept="image/*" required class="w-full text-sm file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
        </div>
        <div>
          <label for="nama_usaha" class="block font-semibold mb-1 text-gray-700">Nama Usaha</label>
          <input type="text" id="nama_usaha" name="nama_usaha" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="sektor_usaha" class="block font-semibold mb-1 text-gray-700">Sektor Usaha</label>
          <select id="sektor_usaha" name="sektor_usaha" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Sektor --</option>
            <option value="Fashion">Fashion</option>
            <option value="Kuliner">Kuliner</option>
            <option value="Pertanian">Pertanian</option>
            <option value="Perdagangan">Perdagangan</option>
            <option value="Jasa">Jasa</option>
            <option value="Teknologi">Teknologi</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
        <div>
          <label for="alamat_usaha" class="block font-semibold mb-1 text-gray-700">Alamat Usaha</label>
          <textarea id="alamat_usaha" name="alamat_usaha" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"></textarea>
        </div>
      </div>

      <div class="text-right">
        <button type="submit" class="bg-blue-700 text-white font-medium px-6 py-2 rounded-md hover:bg-blue-800 transition">Simpan</button>
      </div>
    </form>
  </div>

  <footer class="text-center text-sm text-gray-500 mt-12 mb-6">
    &copy; 2023 Aplikasi Database Usaha. Developed by Ollin.
  </footer>

</body>

</html>
