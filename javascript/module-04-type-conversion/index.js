// implicit conversion (terjadi ketika js langsungng melakukan konversi secara langsung)
let result = "5" + 5  //maka akan menghasilkan string "55"
let result2 = 5 - "10" //menghasilkan number karena yang pertama merupakan number
let bool = !0 //menghasilkan true, karena 0 bernilai false
console.log(bool);
console.log(result, typeof result);
console.log(result2, typeof result2);


// explicit conversion (kita menyuruh js untuk konversi)
let number = 10
let numberString = String(number)  // akan menjadi string "10"

let stringNumber = "100"
let numberStr = parseInt(stringNumber)  // akan menghasilkan 100 number