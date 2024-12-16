import Alerte from "./components/Alerte.js";

(function () {
    console.log("connected");

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }
})();
