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

    function afficherModaleSupressionCellier(event) {
        menuOuvert = null;

        const declencheur = event.target;
        const cellierID = declencheur.dataset.jsCellier;
        const cellierNom = declencheur.dataset.jsName;
        const elToChange = declencheur.closest("article");

        const dropdown = elToChange.querySelector(".menu-deroulant > input");
        dropdown.checked = false;

        const modale = new ModaleAction(
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
    const bottlesContainer = document.querySelector(".products"); 


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
        const bottlesContainer = document.querySelector(".products");
    
        // Supprime les articles existants
        const existingArticles = bottlesContainer.querySelectorAll("article");
        existingArticles.forEach((article) => article.remove());
    
        // S'il y a aucune bouteille, affiche un message
        if (bottles.length === 0) {
            bottlesContainer.innerHTML += `<p>Aucune bouteille dans ce cellier</p>`;
            return;
        }
    
        // Affiche les bouteilles
        bottles.forEach((bottle) => {
            const clone = bottleTemplate.content.cloneNode(true);
    
            const article = clone.querySelector(".card_bottle");
            article.setAttribute("data-js-key", `${cellar.id}|${bottle.id}`);
    
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
    



    selectTheCurrentValue();
})();