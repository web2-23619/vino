import Alerte from "./Alerte.js";

export default class App {
    static #instance;

    //Permet d'accéder à l'instance de la classe de n'importe où dans le code en utilisant App.instance
    static get instance() {
        return App.#instance;
    }

    constructor() {
        //singleton
        if (App.#instance) {
            return App.#instance;
        } else {
            App.#instance = this;
        }

        this.baseURL = "http://localhost:8000";
    }

    async removeBottleFromCellar(key) {
        new App();

        const ids = key.split("|");

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        const response = await fetch(
            `${App.instance.baseURL}/api/retirer/${ids[0]}/${ids[1]}`,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + localStorage.getItem("token"), // ajouter token
                    "X-CSRF-TOKEN": csrfToken,
                },
            }
        );

        if (response.ok) {
            const message = "Bouteille retirée avec succès";

            new Alerte(null, message, "succes");
        } else {
            const message = "Erreur au retrait de la bouteille";
            new Alerte(null, message, "erreur");
        }
    }
}
