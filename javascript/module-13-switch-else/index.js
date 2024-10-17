const hari = 5;
let namaHari;

switch (hari) {
    case 1:
        namaHari = 'Senin';
        console.log('Senin');
    case 2:
        namaHari = 'Selasa';
        console.log('Selasa');
    case 3:
        namaHari = 'Rabu';
        console.log('Rabu');
    case 4:
        namaHari = 'Kamis';
        console.log('Kamis');
    case 5:
        namaHari = 'Jumat';
        console.log('Jumaat');
        break;
    case 6:
        namaHari = 'Sabtu';
        console.log('Sabtu');
    case 7:
        namaHari = 'Minggu';
        console.log('Minggu');
    default:
        namaHari = 'Hari tidak valid';
}



// ⁡⁣⁢⁢Switch-Case dengan Ekspresi atau Operasi⁡
let nilai = 70;
switch (true) {
    case nilai >= 90:
        console.log('Grade : A');
        break
    case nilai >= 80:
        console.log('Grade : B');
        break
    case nilai >= 70:
        console.log('Grade : C');
        break
    case nilai >= 60:
        console.log('Grade : D');
        break
    default:
        console.log('Grade : F')
}
