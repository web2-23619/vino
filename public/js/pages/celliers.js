import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";
import App from "../components/App.js";

(function () {
    new App();

    let menuOuvert = null;

    const menusHTML = document.querySelectorAll(
        ".menu-deroulant > [type='checkbox']"
    );

    //filtres
    const filterFormHTML = document.querySelector("[data-js='filtersForm']");
    filterFormHTML.addEventListener("submit", renderFilter);
    const chevronDetails = document.querySelector(".filters > details");

    // affichage de la liste complete de pays
    const btnAfficherPlus = document.querySelector("[data-js='afficherPlus']");
    const btnAfficherMoins = document.querySelector(
        "[data-js='afficherMoins']"
    );
    btnAfficherPlus.addEventListener("click", function (event) {
        const trigger = event.target;
        trigger.nextElementSibling.classList.remove("invisible");
        btnAfficherMoins.classList.remove("invisible");
        btnAfficherPlus.classList.add("invisible");
    });

    btnAfficherMoins.addEventListener("click", function (event) {
        const trigger = event.target;
        trigger.previousElementSibling.classList.add("invisible");
        btnAfficherMoins.classList.add("invisible");
        btnAfficherPlus.classList.remove("invisible");
        document.querySelector("h1").scrollIntoView();
    });



    //reinitialisation des filtres
    const btnResetFilters = filterFormHTML.querySelector(
        "[data-js='resetFilters']"
    );
    btnResetFilters.addEventListener("click", function (event) {
        event.preventDefault();
        filterFormHTML.reset();
        renderBottles({ id: currentBottles[0]?.cellar_id || 0 }, currentBottles);
    });
    

    for (const menu of menusHTML) {
        menu.addEventListener("change", checkMenu);
    }

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    // Variable qui contient les bouteilles après chaque render de la page
    // Elle est mise à jour dand la fonction updateBottleView()
    let currentBottles = [];


    document.addEventListener("DOMContentLoaded", () => {
        document.addEventListener("click", (event) => {
            const modalButton = event.target.closest(
                "[data-js-action='afficherModaleConfirmation']"
            );
            if (modalButton.matches("[data-js-type='trash']")) {
                const h3Content =
                    document.querySelector("header h3").textContent;
                afficherModaleSupressionBouteille(event, h3Content);
            } else {
                afficherModaleSupressionCellier(event);
            }
        });
    });

    document.addEventListener("click", (event) => {
        if (event.target.matches("[data-js-action='reduire']")) {
            changeQuantity(event, "reduire");
        } else if (event.target.matches("[data-js-action='augmenter']")) {
            changeQuantity(event, "augmenter");
        }
    });

    // Gestion de la fonctionnalité (Sort)
    // changer l'ordre d'affichage selon la selection
    document.addEventListener("DOMContentLoaded", () => {
        const sortingOptions = document.querySelector(".sorting__frame");
    
        sortingOptions.addEventListener("change", function () {
            const selectedSort = document.querySelector("[name='sorting']:checked");
            if (selectedSort && currentBottles.length > 0) {
                renderSort(selectedSort.value);
            }
        });
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

    /**
     * Affiche un message pour informer l'utilisateur qu'il n'y a pas de contenu
     * dans la page actuelle.
     *
     * Le message est stock  dans un template HTML et est ajouté au DOM.
     * Si un bouton "Ajouter" est présent, il est supprimé.
     */
    function displayNoContentMessage() {
        const template = document.querySelector("template#noPurchase");
        let content = template.content.cloneNode(true);
        let sectionHTML = document.querySelector("main > section.cellier-products");
        sectionHTML.append(content);

        const boutonAjout = document.querySelector("footer > div");
        if (boutonAjout) {
            boutonAjout.remove();
        }
    }


       /**
     * affiche le bouton d'action d'ajout de bouteille en bas de page
     */
       function displayAddBottleBtn() {
        const template = document.querySelector("template#action-button");
        let content = template.content.cloneNode(true);
        let sectionHTML = document.querySelector("footer");
        sectionHTML.prepend(content);
    }


    /**
     * Affiche la modale de suppression d'un cellier.
     *
     * La modale est créer avec les données suivantes :
     * - l'ID du cellier à supprimer
     * - le nom du cellier
     * - l'action à effectuer (supprimerCellier)
     * - le type d'action (supprimer)
     * - l'élément HTML à mettre à jour suite à la suppression
     *
     * @param {Event} event L'évènement qui a déclencher la fonction
     */
    function afficherModaleSupressionCellier(event) {
        menuOuvert = null;

        const declencheur = event.target;
        const cellierID = declencheur.dataset.jsCellier;
        const cellierNom = declencheur.dataset.jsName;
        const elToChange = document.querySelector(
            `#cellar-select option[value="${cellierID}"]`
        );
        const dropdown = document.querySelector(".menu-deroulant > input");
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

    /**
     * Affiche la modale de suppression d'une bouteille d'un cellier.
     *
     * La modale est créer avec les données suivantes :
     * - l'ID de l'association cellier_has_bouteille à supprimer
     * - le nom de la bouteille
     * - l'action à effectuer (retirerBouteille)
     * - le type d'action (supprimer)
     * - l'élément HTML à mettre à jour suite à la suppression
     *
     * @param {Event} event L'évènement qui a déclencher la fonction
     * @param {String} name Le nom de la bouteille
     */
    async function afficherModaleSupressionBouteille(event, name) {
        const declencheur = event.target;
        const elToChange = declencheur.closest("article");
        const h3Element = name;
        console.log(elToChange);
        const nom = elToChange.dataset.jsName;
        console.log(h3Element);
        const ids = elToChange.dataset.jsKey;

        new ModaleAction(
            ids,
            nom,
            "retirerBouteille",
            "supprimer",
            "cellier_has_bouteille",
            elToChange
        );
    }

    // Fonctionnalité pour le menu kebab et le select dans l'inventaire

    const currentCellar = document.querySelector("#cellar-select");
    const kebabMenu = document.querySelector("template#kebab-menu");

    const bottleTemplate = document.querySelector("#bottle-template");

    /**
     * Fonction qui injecte le contenu du template de menu déroulant (#kebab-menu)
     * dans le DOM en remplaçant les valeurs par défaut par celles du cellier
     * selectionné.
     *
     * La fonction est appelée lorsque le select du cellier est modifié.
     *
     * @listens select#cellar-select
     */
    function selectTheCurrentValue() {
        const kebabMenuContent = kebabMenu.content.cloneNode(true);

        const lienModifier = kebabMenuContent.querySelector(
            '[data-js-option="modifier"] a'
        );
        const currentCellarId = currentCellar.value;
        const selectedOptionName =
            currentCellar.options[currentCellar.selectedIndex].text;

        // Passer le cellarId dans le kebabMenu pour le lien modifier
        lienModifier.setAttribute(
            "href",
            `${App.instance.baseURL}/modifier/cellier/${currentCellarId}`
        );

        // Ajouter le cellarId et le nom pour le lien supprimer
        let liSupprimer = kebabMenuContent.querySelector(
            "[data-js-option='supprimer']"
        );
        liSupprimer.setAttribute("data-js-cellier", currentCellarId);
        liSupprimer.setAttribute("data-js-name", selectedOptionName);

        // Injecter dans le DOM
        const menuWrapper = document.querySelector("#kebab-menu-wrapper");
        menuWrapper.innerHTML = "";
        menuWrapper.appendChild(kebabMenuContent);

        updateBottleView(currentCellarId);
    }


    // Gestion du select des celliers dans inventaire
    currentCellar.addEventListener('change', function () {
        selectTheCurrentValue();
        // Cache le message "Aucune bouteille"
        const noContentMessage = document.querySelector(".noContent");
        if (noContentMessage) {
            noContentMessage.remove();
            const boutonAjout = document.querySelector("footer > div");
            if (!boutonAjout) {
                displayAddBottleBtn()
            }
        }
    });

    // fonction pour montrer la vue selon le cellier selectionner

    /**
     * Charge les bouteilles pour le cellier selectionné dans la vue.
     * Appelée lorsque le select du cellier est modifié.
     *
     * @param {number} cellarId Identifiant du cellier selectionné.
     */
    async function updateBottleView(cellarId) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        try {
            // Fetch les bouteilles pour le cellier selectionné
            const response = await fetch(
                `${App.instance.baseURL}/api/cellier/${cellarId}/bouteille`,
                {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        Authorization:
                            "Bearer " + localStorage.getItem("token"),
                    },
                }
            );
            if (!response.ok) {
                throw new Error("Erreur de chargement des bouteilles");
            }

            const data = await response.json();

            // Mise à jour de la liste des bouteilles dans la variable globale
            // pour que la fonctionnalité (sort) puisse avoir accès
            currentBottles = [...data.bottles];

            // Charge les bouteilles dans la vue
            renderBottles(data.cellar, currentBottles);
        } catch (error) {
            console.error("Une erreur s'est produite :", error);
        }
    }

    /**
     * affiche le bouton d'action d'ajout de bouteille en bas de page
     */
    function displayAddBottleBtn(cellar_id) {
        const template = document.querySelector("template#action-button");
        let content = template.content.cloneNode(true);
        let footerHtml = document.querySelector("footer");
        footerHtml.prepend(content);
        const button = document.querySelector("[data-template-route]");
        console.log(button);
        const templateRoute = button.dataset.templateRoute;
        const updatedRedirection = templateRoute.replace(
            ":cellar_id",
            String(cellar_id)
        );
        button.setAttribute("href", updatedRedirection);
    }

    /**
     * Charge les bouteilles pour le cellier selectionné dans la vue.
     *
     * @param {Object} cellar Informations du cellier selectionné.
     * @param {Array<Object>} bottles Informations des bouteilles du cellier.
     */
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
            const boutonAjout = document.querySelector("footer > div");
            if (!boutonAjout) {
                displayAddBottleBtn()
            }
        }

        const boutonAjout = document.querySelector("footer > div");
        console.log(boutonAjout);
        if (boutonAjout) {
            boutonAjout.remove();
        }

        displayAddBottleBtn(currentCellar.value);

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

            const quantity = clone.querySelector(
                "[data-js-quantite='quantite']"
            );
            quantity.textContent = bottle.quantity;

            const price = clone.querySelector("[data-info='price']");
            price.textContent = bottle.price;

            // Ajoute la bouteille au conteneur
            bottlesContainer.appendChild(clone);
        });

        // S'il y a aucune bouteille, affiche un message
        if (bottles.length === 0) {
            displayNoContentMessage();
            return;
        }
    }

    /**
    * tri et affiche le résultat trié
    */
    function renderSort(sortOption) {

        if (currentBottles.length === 0) return;

        const [criteria, sort] = sortOption.split("_");
        currentBottles.sort((a, b) => {
            if (criteria === "name") {
                const nameA = a.name;
                const nameB = b.name;

                if (sort === "asc") {
                    return nameA.localeCompare(nameB);
                } else {
                    return nameB.localeCompare(nameA);
                }
            } else if (criteria === "price") {
                const priceA = a.price;
                const priceB = b.price;
                if (sort === "asc") {
                    return priceA - priceB; // Ascending sort
                } else {
                    return priceB - priceA; // Descending sort
                }
            }
        });
        renderBottles({ id: currentBottles[0]?.cellar_id || 0 }, currentBottles);
    }


    /**
    * afficher données filterés
    */
    async function renderFilter(event) {
        event.preventDefault();
    
        // Filter
        const countriesHTML = filterFormHTML.querySelectorAll("[name='country']");
        const countries = [];
        for (const country of countriesHTML) {
            if (country.checked) {
                countries.push(country.value);
            }
        }
    
        const typesHTML = filterFormHTML.querySelectorAll("[name='type']");
        const types = [];
        for (const type of typesHTML) {
            if (type.checked) {
                types.push(type.value);
            }
        }
    
        let filteredBottle = currentBottles;
    
        if (
            countries.length === 0 &&
            types.length === 0 &&
            min.value === "" &&
            max.value === ""
        ) {
            filteredBottle = [...currentBottles];
        } else {
            if (countries.length > 0) {
                filteredBottle = filteredBottle.filter((bottle) =>
                    countries.includes(bottle.country)
                );
            }
            if (types.length > 0) {
                filteredBottle = filteredBottle.filter((bottle) =>
                    types.includes(bottle.type)
                );
            }
            if (min.value !== "") {
                filteredBottle = filteredBottle.filter(
                    (bottle) => bottle.price >= parseFloat(min.value)
                );
            }
            if (max.value !== "") {
                filteredBottle = filteredBottle.filter(
                    (bottle) => bottle.price <= parseFloat(max.value)
                );
            }
        }
    
        const dataFiltered = {
            bottles: filteredBottle,
            empty: false,
            filtered: true,
        };
    
        if (dataFiltered.bottles.length === 0) {
            dataFiltered.empty = true;
        }
    
        renderBottles({ id: currentBottles[0]?.cellar_id || 0 }, filteredBottle);
        chevronDetails.removeAttribute('open');
    }
    

    /**
     * Met à jour la quantité d'une bouteille dans un cellier
     * @param {Event} event Evénement déclenché par le clic sur le bouton "-" ou "+"
     * @param {String} action Action à effectuer : "reduire" ou "augmenter"
     * @returns {Promise<void>}
     */
    async function changeQuantity(event, action) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const trigger = event.target;
        const purchaseItem = trigger.closest("article");
        const [cellarId, bottleId] = purchaseItem.dataset.jsKey.split("|");
        const quantityElement = purchaseItem.querySelector(
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
            const btnReduire = purchaseItem.querySelector(
                "[data-js-action='reduire']"
            );
            if (currentQuantity === 0) {
                btnReduire.setAttribute("inert", "true");
                btnReduire.classList.add(
                    "disappear",
                    "card_purchase_deactivated"
                );
            } else {
                btnReduire.removeAttribute("inert");
                btnReduire.classList.remove(
                    "disappear",
                    "card_purchase_deactivated"
                );
            }
        } else {
            // console.log("Échec.");
        }
    }

    selectTheCurrentValue();
})();
