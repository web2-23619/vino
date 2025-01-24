import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";
import App from "../components/App.js";

(function () {
    new App();

    let menuOuvert = null;

    const menusHTML = document.querySelectorAll(
        ".menu-deroulant > [type='checkbox']"
    );

    for (const menu of menusHTML) {
        menu.addEventListener("change", checkMenu);
    }

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    const btnsModaleConfirmation = document.querySelectorAll(
        "[data-js-action='afficherModaleConfirmation']"
    );

    if (btnsModaleConfirmation) {
        for (const btn of btnsModaleConfirmation) {
            btn.addEventListener("click", afficherModaleSupressionCellier);
        }
    }

        
    document.addEventListener("click", (event) => {
        if (event.target.matches("[data-js-action='reduire']")) {
            changeQuantity(event, "reduire");
        } else if (event.target.matches("[data-js-action='augmenter']")) {
            changeQuantity(event, "augmenter");
        }
    });
        

    function checkMenu(event) {
        const trigger = event.target;

        if (trigger.checked) {
            if (menuOuvert !== null) {
                menuOuvert.checked = false;
            }
            menuOuvert = trigger;
        } else {
            menuOuvert = null;
        }
    }

    
    function displayNoContentMessage() {
        const template = document.querySelector("template#noPurchase");
        let content = template.content.cloneNode(true);
        let sectionHTML = document.querySelector("main > section");
        sectionHTML.append(content);

        const boutonAjout = document.querySelector("footer > div");
        if (boutonAjout) {
            boutonAjout.remove();
        }
    }


    function afficherModaleSupressionCellier(event) {
        menuOuvert = null;

        const declencheur = event.target;
        const cellierID = declencheur.dataset.jsCellier;
        const cellierNom = declencheur.dataset.jsName;
        const elToChange = declencheur.closest("article");

        const dropdown = elToChange.querySelector(".menu-deroulant > input");
        dropdown.checked = false;

        new ModaleAction(
            cellierID,
            cellierNom,
            "supprimerCellier",
            "supprimer",
            "cellier",
            elToChange
        );
    }

    // Fonctionnalité pour le menu kebab et le select dans l'inventaire

    const currentCellar = document.querySelector("#cellar-select");
    const kebabMenu = document.querySelector("template#kebab-menu");

    const bottleTemplate = document.querySelector("#bottle-template");


    function selectTheCurrentValue(){
        const kebabMenuContent = kebabMenu.content.cloneNode(true);

        const lienModifier = kebabMenuContent.querySelector('[data-js-option="modifier"] a');
        const currentCellarId = currentCellar.value;
        const selectedOptionName = currentCellar.options[currentCellar.selectedIndex].text

        // Passer le cellarId dans le kebabMenu pour le lien modifier
        lienModifier.setAttribute('href', `${App.instance.baseURL}/modifier/cellier/${currentCellarId}`);

        // Ajouter le cellarId et le nom pour le lien supprimer
        let liSupprimer = kebabMenuContent.querySelector("[data-js-option='supprimer']");
        liSupprimer.setAttribute('data-js-cellier', currentCellarId);
        liSupprimer.setAttribute('data-js-name', selectedOptionName);

        // Injecter dans le DOM
        const menuWrapper = document.querySelector('#kebab-menu-wrapper');
        menuWrapper.innerHTML = '';
        menuWrapper.appendChild(kebabMenuContent);

        updateBottleView(currentCellarId);

    }

    currentCellar.addEventListener('change', selectTheCurrentValue);

    // fonction pour montrer la vue selon le cellier selectionner

    async function updateBottleView(cellarId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            // Fetch les bouteilles pour le cellier selectionné
            const response = await fetch(`${App.instance.baseURL}/api/cellier/${cellarId}/bouteille`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Authorization: "Bearer " + localStorage.getItem("token"),
                },
            })
            if (!response.ok) {
                throw new Error("Erreur de chargement des bouteilles");
            }

            const data = await response.json();

            // Charge les bouteilles dans la vue
            renderBottles(data.cellar, data.bottles);
        } catch (error) {
            console.error("Une erreur s'est produite :", error);
        }
    }

    function renderBottles(cellar, bottles) {
    
        // Selectionne le conteneur
        const bottlesContainer = document.querySelector(".cellier-products");
    
        // Supprime les articles existants
        const existingArticles = bottlesContainer.querySelectorAll("article");
        existingArticles.forEach((article) => article.remove());
    
        // S'il y a aucune bouteille, affiche un message
        if (bottles.length === 0) {
            displayNoContentMessage();
            return;
        }

        // Cache le message "Aucune bouteille"
        const noContentMessage = document.querySelector(".noContent");
        if (noContentMessage) {
            noContentMessage.remove();
        }
    
        // Affiche les bouteilles
        bottles.forEach((bottle) => {
            const clone = bottleTemplate.content.cloneNode(true);
    
            const article = clone.querySelector(".card_bottle");
            article.setAttribute("data-js-key", `${cellar.id}|${bottle.id}`);
            article.setAttribute("data-js-id", bottle.id);
    
            const img = clone.querySelector("img");
            img.src = bottle.image_url;
            img.alt = bottle.name;
    
            const type = clone.querySelector(".card_bottle__metainfo");
            type.textContent = bottle.type;
    
            const name = clone.querySelector("h3");
            name.textContent = bottle.name;
    
            const details = clone.querySelectorAll(".card_bottle__metainfo")[1];
            details.textContent = `${bottle.volume} ml | ${bottle.country}`;
    
            const quantity = clone.querySelector("[data-js-quantite='quantite']");
            quantity.textContent = bottle.quantity;
    
            // Ajoute la bouteille au conteneur
            bottlesContainer.appendChild(clone);
        });
    }
    
    async function changeQuantity(event, action) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const trigger = event.target;
        const purchaseItem = trigger.closest("article");
        const [cellarId, bottleId] = purchaseItem.dataset.jsKey.split('|');  
        const quantityElement = purchaseItem.querySelector("[data-js-quantite='quantite']");
        let currentQuantity = parseInt(quantityElement.textContent);
    
        // Ajuster la quantité selon l'action
        if (action === "reduire" && currentQuantity > 0) {
            currentQuantity--;
        } else if (action === "augmenter") {
            currentQuantity++;
        }

        // Envoie le PATCH request pour update seulement la quantité
        const response = await fetch(

            `${App.instance.baseURL}/api/cellier/${cellarId}/${bottleId}`,
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
            const btnReduire = purchaseItem.querySelector("[data-js-action='reduire']");
            if (currentQuantity === 0) {
                btnReduire.setAttribute("inert", "true");
                btnReduire.classList.add("disappear", "card_purchase_deactivated");
            } else {
                btnReduire.removeAttribute("inert");
                btnReduire.classList.remove("disappear", "card_purchase_deactivated");
            }
        } else {
            // console.log("Échec.");
        }
    }


    selectTheCurrentValue();
})();
