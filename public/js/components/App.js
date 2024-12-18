export default class App {
    static #instance;
    #alerte;
    #btnsSupprimerCellier;

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
}
