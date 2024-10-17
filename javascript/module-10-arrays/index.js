let fruits = ["apple", "banana", "mango"]

let num = new Array(1, 2, 3, 4, 5)

fruits[3] = "strawberry"
fruits[0] = "orange"

let numbers = [1, 2, 3]
numbers.push(4) // menempatkan di belakang array
numbers.pop() // mengeluarkan array paling belakang
numbers.shift() // mengeluarkan elem paling depan
numbers.unshift(5) // menambah elem di awal
console.log(numbers)

let numbers2 = [4, 5]
let newNumber = numbers.concat(numbers2)  // menggabungkan dua array
console.log(newNumber)
newNumber.splice(2, 1, 10)
console.log(newNumber)
console.log(newNumber.indexOf(10))
console.log(newNumber.includes(5))

let matrix = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
]