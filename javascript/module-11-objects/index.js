let mobil = {
    merk: 'Toyota',
    model: 'Avanza',
    tahun: 2014
}

let buku = new Object()
buku.judul = 'Belajar JavaScript'
buku.penulis = 'Ranggo Pato'

let mahasiswa = {
    nama: "Ranggo",
    umur: 21,
    "jurusan mahasiswa": 'Teknik Informatika'
}

console.log(mahasiswa.nama)
console.log(mahasiswa["jurusan mahasiswa"])

mahasiswa.alamat = 'Jakarta'
mahasiswa.nama = 'Budi'

delete mahasiswa.alamat

let universitas = {
    "nama": "ITB",
    fakultas: {
        nama: 'Fakultas Teknik Industri',
        jurusan: 'Teknik Fisika'
    }
}
console.log(universitas)

let { nama, umur } = mahasiswa
console.log(umur)
