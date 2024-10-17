// Primitive data type
const nama = "Ghufron Bagaskara";
console.log(nama, typeof nama);

const negara = "Indonesia";
const domisili = `From ${negara} an country`;
console.log(domisili);

const umur = 20

const menikah = false

let x;  //undefined type data

const output = null  //object type data

let symbol1 = Symbol("description 1") // symbol data type

let bigInt = 901273901722837n  //bigInt data type


//Reference data type
const person = {
    name: "Ghufron Bagaskara",
    age: 20
} // object data type

const hobby = ["berenang", "bernyanyi", "baseball"]// array data type

function sayHello() {
    return "Hello World"
}  // function data type



// Perbedaan reference dengan primitive

//primitive 
let a = 10
let b = a // akan tetap menyimpan 10 meskipun kita mengubah a = 20

a = 20 // hal ini terjadi dikarenakan 

//reference
var person1 = { name: "ghufron", age: 20 }

let person2 = person1  // jika attribut person1 diubah, maka pada person2 jg akan terubah




