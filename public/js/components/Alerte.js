class Alerte {
    #elementHTML;
    #message;
    #type;
    #gabarit;
    #boutonAction;

    constructor(elementHtml = null, message = null, type = null) {
        this.#elementHTML = elementHtml;
        this.#message = message;
        this.#type = type;

        if (this.#elementHTML == null) {
            this.#gabarit = document.querySelector("template#alerte");

            let alert = this.#gabarit.content.cloneNode(true);
            alert.querySelector("p").textContent = this.#message;

            document.querySelector("[data-js='header']").after(alert);
            this.#elementHTML = document.querySelector(".alerte");
            this.#elementHTML.classList.add("alerte_".concat(this.#type));

            this.#boutonAction = this.#elementHTML.querySelector(
                "[data-js-action='fermer']"
            );

            this.#boutonAction.addEventListener(
                "click",
                this.#fermerAlerte.bind(this)
            );
        } else {
            this.#boutonAction = this.#elementHTML.querySelector(
                "[data-js-action='fermer']"
            );

            this.#boutonAction.addEventListener(
                "click",
                this.#fermerAlerte.bind(this)
            );
        }

        setTimeout(() => {
            this.#elementHTML.classList.add("alerte_remove");
        }, 2650);

        setTimeout(() => {
            this.#elementHTML.remove();
        }, 3000);
    }

    #fermerAlerte() {
        this.#elementHTML.remove();
    }
}

export default Alerte;
