function taskProcess(task, callback) {
    console.log("menyelesaikan tugas : " + task);
    callback()
} //high order function

function taskDone() {
    console.log("tugas telah selesai");  // callback
}

console.log(taskProcess("menyimpan", taskDone));
