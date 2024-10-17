let age = 15

if (age >= 18) {
    console.log('You are eligible vote')
} else {
    console.log('You are not eligible to vote')
}

let score = 50
if (score >= 90) {
    console.log('Grade : A')
} else if (score >= 80) {
    console.log('Grade : B')
} else if (score >= 70) {
    console.log('Grade : C')
} else if (score >= 60) {
    console.log('Grade : D')
} else {
    console.log('Grade : F')
}


// nested
let num = -6

if (num > 0) {
    if (num % 2 == 0) {
        console.log('Number ini positive dan dia bilangan genap')
    } else {
        console.log('Number ini positive dan bilangan ganjil')
    }
} else {
    console.log('Number ini bilangan negative')
}