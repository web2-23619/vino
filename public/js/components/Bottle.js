import ModaleAction from "./ModaleAction.js";
import App from "./App.js";

export default class Bottle {
    #name;
    #price;
    #country;
    #volume;
    #type;
    #quantity;
    #img;
    #purchaseId;
    #template;
    #page;
    #container;
    #elementHTML;
    #btnRemove;
    #btnReduire;
    #btnAugmenter;
    #changeQuantity;

    constructor(data, page, template, container) {
		console.log(data);
		
        this.#name = data.name;
        this.#price = data.price;
        this.#country = data.country;
        this.#type = data.type;
        this.#volume = data.volume;
        this.#img = data.image_url;
        this.#page = page;
        this.#template = template;
        this.#container = container;

        if (this.#page === "purchase") {
            this.#purchaseId = data.purchase_id;
            this.#quantity = data.purchase_quantity;
            this.#changeQuantity = this.#changePurchaseQuantity;
        }

        this.#render();
    }

    #render() {
        const clone = this.#template.content.cloneNode(true);

        //remplacer valeur

        clone.querySelector("[data-info='name']").textContent = this.#name;
        clone.querySelector("[data-info='type']").textContent = this.#type;
        clone.querySelector("[data-info='quantity']").textContent =
            this.#quantity;
        clone.querySelector("[data-info='volume']").textContent = this.#volume;
        clone.querySelector("[data-info='price']").textContent = this.#price;
        clone.querySelector("[data-info='img']").src = this.#img;
        clone.querySelector("[data-info='country']").textContent =
            this.#country;

        //injecter dans DOM
        this.#container.append(clone);
        this.#elementHTML = this.#container.lastElementChild;

        this.#elementHTML.setAttribute("data-js-id", this.#purchaseId);
        this.#elementHTML.setAttribute("data-js-name", this.#name);

        this.#btnRemove = this.#elementHTML.querySelector(
            "[data-js-action='afficherModaleConfirmation']"
        );

        this.#btnRemove.addEventListener(
            "click",
            this.#afficherModaleSupressionAchat.bind(this)
        );

        this.#btnReduire = this.#elementHTML.querySelector(
            "[data-js-action='reduire']"
        );
        this.#btnAugmenter = this.#elementHTML.querySelector(
            "[data-js-action='augmenter']"
        );

        this.#btnReduire.addEventListener("click", (event) =>
            this.#changeQuantity(event, "reduire")
        );
        this.#btnAugmenter.addEventListener("click", (event) =>
            this.#changeQuantity(event, "augmenter")
        );
    }

    #afficherModaleSupressionAchat(event) {
        const declencheur = event.target;
        const elToChange = declencheur.closest("article");
        const purchaseID = elToChange.dataset.jsId;
        const purchaseNom = elToChange.dataset.jsName;

        const modale = new ModaleAction(
            purchaseID,
            purchaseNom,
            "supprimerAchat",
            "supprimer",
            "achat",
            elToChange
        );
    }

    async #changePurchaseQuantity(event, action) {
        console.log("clicked");
		console.log(action);

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const trigger = event.target;
        const purchaseItem = trigger.closest("article");
        const quantityElement = this.#elementHTML.querySelector(
            "[data-js-quantite='quantite']"
        );
        let currentQuantity = parseInt(quantityElement.textContent);

        // Ajuster la quantité selon l'action
        if (action === "reduire" && currentQuantity > 0) {
            currentQuantity--;
        } else if (action === "augmenter") {
            currentQuantity++;
        }

        // Envoie le PATCH request pour update seulement la quantité
        const response = await fetch(
            `${App.instance.baseURL}/api/achat/${this.#purchaseId}/quantite`,
            {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken, // Ajoute CSRF token
                    Authorization: "Bearer " + localStorage.getItem("token"), // Ajoute le token
                },
                body: JSON.stringify({
                    quantity: currentQuantity,
                }),
            }
        );

        if (response.ok) {
            // Mettre à jour le UI
            quantityElement.textContent = currentQuantity;

            // Desactiver le bouton "-" si la quantité est == 1
            const btnReduire = purchaseItem.querySelector(
                "[data-js-action='reduire']"
            );
            if (currentQuantity === 0) {
                btnReduire.setAttribute("inert", "true");
                btnReduire.classList.add("card_purchase_deactivated");
            } else {
                btnReduire.removeAttribute("inert");
                btnReduire.classList.remove("card_purchase_deactivated");
            }
        } else {
            // console.log("Échec.");
        }
    }
}
