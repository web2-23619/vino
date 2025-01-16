import Alerte from "./Alerte.js";
import App from "./App.js";

class ModaleAction {
    #id;
    #displayName;
    #conteneurHTML;
    #btnAnnuler;
    #btnAction;
    #elToChange;
    #gabarit;
    #elementHTML;
    #action;
    #model;

    constructor(id, name, template, action, model, elToChange = null) {
        new App();

        this.#id = id;
        this.#displayName = name;
        this.#action = action;
        this.#model = model;
        this.#elToChange = elToChange;
        this.#conteneurHTML = document.querySelector("main");
        this.#gabarit = document.querySelector(`[id='${template}']`);

        if (!this.#gabarit) {
            console.error(`Template with ID '${template}' not found.`);
            return;
        }

        this.#elementHTML;
        this.#afficher();
    }

    /**
     * Méthode privée pour afficher la modale
     */
    #afficher() {
        let modale = this.#gabarit.content.cloneNode(true);
        modale.querySelector("[data-js-replace='nom']").textContent =
            this.#displayName;

        this.#conteneurHTML.prepend(modale);
        this.#elementHTML = this.#conteneurHTML.firstElementChild;

        this.#btnAnnuler = this.#elementHTML.querySelector(
            "[data-js-action='annuler']"
        );

        if (this.#btnAnnuler) {
            this.#btnAnnuler.addEventListener(
                "click",
                this.#fermerModale.bind(this)
            );
        }

        this.#btnAction = this.#elementHTML.querySelector(
            "[data-js-action='" + this.#action + "']"
        );

        if (this.#btnAction) {
            if (this.#action === "supprimer") {
                if (this.#model === "utilisateur") {
                    this.#btnAction.addEventListener(
                        "click",
                        this.#supprimerUtilisateur.bind(this)
                    );
                } else {
                    this.#btnAction.addEventListener(
                        "click",
                        this.#supprimer.bind(this)
                    );
                }
            } else if (this.#action === "deconnexion") {
                this.#btnAction.addEventListener(
                    "click",
                    this.#deconnexion.bind(this)
                );
            }
        }

        // Vérifiez si des éléments existent avant d’appliquer des classes
        const mainElement = document.querySelector("main");
        const footerDiv = document.querySelector("footer > div");

        if (mainElement) {
            mainElement.classList.add("action-locked");
        }
        if (footerDiv) {
            footerDiv.classList.add("action-locked");
        }
    }

    /**
     * Méthode privée pour fermer la modale
     */
    #fermerModale() {
        this.#déverouiller();

        if (this.#elementHTML) {
            this.#elementHTML.classList.add("remove");

            setTimeout(() => {
                this.#elementHTML.remove();
            }, 2650);
        }
    }

    /**
     * Méthode privée pour fermer déverouiller les acions
     */
    #déverouiller() {
        const mainElement = document.querySelector("main");
        const footerDiv = document.querySelector("footer > div");

        if (mainElement) {
            mainElement.classList.remove("action-locked");
        }
        if (footerDiv) {
            footerDiv.classList.remove("action-locked");
        }
    }

    /**
     * Méthode privée pour supprimer
     */
    async #supprimer() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        const response = await fetch(
            `${App.instance.baseURL}/api/${this.#action}/${this.#model}/${
                this.#id
            }`,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + localStorage.getItem("token"), // ajouter token
                    "X-CSRF-TOKEN": csrfToken,
                },
            }
        );

        const top = document.querySelector("[data-js='header']");

        if (response.ok) {
            const message = "Cellier supprimé avec succès";
            this.#elToChange?.remove();

            this.#elementHTML.remove();
            top.scrollIntoView();
            new Alerte(null, message, "succes");
            this.#déverouiller();
        } else {
            const message = "Erreur à la suppression du cellier";
            this.#elementHTML.remove();
            top.scrollIntoView();
            new Alerte(null, message, "erreur");

            this.#déverouiller();
        }
    }

    /**
     * Méthode privée pour supprimer utilisateur
     */
    async #supprimerUtilisateur() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        try {
            const response = await fetch(
                `${App.instance.baseURL}/api/${this.#action}/${this.#model}/${
                    this.#id
                }`,
                {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization:
                            "Bearer " + localStorage.getItem("token"), // ajouter token
                        "X-CSRF-TOKEN": csrfToken,
                    },
                }
            );

            if (response.ok) {
                this.#fermerModale();
                const tokenDelete = await fetch(
                    `${App.instance.baseURL}/api/logout`,
                    {
                        "Content-Type": "application/json",
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                    }
                );
                localStorage.removeItem("token");
				window.location.href = `${App.instance.baseURL}/utilisateurSupprime`
            } else {
                const message = "Erreur à la suppression de l'utilisateur";

                const top = document.querySelector("[data-js='header']");
                top.scrollIntoView();
                new Alerte(null, message, "erreur");
                this.#fermerModale();
            }
        } catch (error) {
            const message = "Erreur à la suppression de l'utilisateur";

            const top = document.querySelector("[data-js='header']");
            top.scrollIntoView();
            new Alerte(null, message, "erreur");
            this.#fermerModale();
        }
    }

    /**
     * Méthode privée pour déconnexion
     */
    async #deconnexion() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        // Envoyer le formulaire de déconnexion masquée
        const logoutForm = document.getElementById("logout-form");
        if (logoutForm) {
            const response = await fetch(`${App.instance.baseURL}/api/logout`, {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            });
            localStorage.removeItem("token");
            logoutForm.submit();
        } else {
            console.error("Logout form not found.");
        }

        this.#fermerModale();
    }
}

export default ModaleAction;
