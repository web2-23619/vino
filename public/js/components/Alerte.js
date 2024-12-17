class Alerte {
    #elementHTML;
    #boutonAction;

    constructor(elementHtml) {
        this.#elementHTML = elementHtml;
        this.#boutonAction = document.querySelector(
            "[data-js-action='fermer']"
        );

        this.#boutonAction.addEventListener(
            "click",
            this.#fermerAlerte.bind(this)
        );

        setTimeout(() => {
            this.#elementHTML.remove();
        }, 2650);
    }

    #fermerAlerte() {
        this.#elementHTML.classList.add("invisible");
    }
}

export default Alerte;
