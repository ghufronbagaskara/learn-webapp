import React from "react";
import ReactDOM from "react-dom/client";

const element = document.getElementById("root")
const root = ReactDOM.createRoot(element)

const App = () => {
   return <div>Selamat Datang di React Tutorial</div>
}

root.render(<App/>)