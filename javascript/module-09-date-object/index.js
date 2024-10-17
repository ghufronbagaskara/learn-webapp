let now = new Date() // tanggal dan waktu saat ini

let spesificDate = new Date("August 20, 2024 10:30:12")
console.log(spesificDate);

let customDate = new Date(2024, 7, 20, 10, 30) // parameter => (tahun, bulan, hari, jam, menit, detik, milidetik), bulan dimulai dari 0

let today = new Date()
console.log(today.getFullYear);
console.log(today.getMonth);
console.log(today.getDate);
console.log(today.getDay);
console.log(today.getHours);
console.log(today.getMinutes);
console.log(today.getSeconds);

let date = new Date()
date.setFullYear(2025)
date.setMonth(11)
date.setDate(29)
console.log();


//perhitungan waktu dengan date object
let startDate = new Date(2024, 7, 1)
let endDate = new Date(2024, 10, 12)
console.log(endDate - startDate);  // menghasilkan milis
console.log((endDate - startDate) / (1000 * 3600 * 24)); // in days format

