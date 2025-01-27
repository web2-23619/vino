import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import Bottle from "../components/Bottle.js";

(async function () {
    new App();

    const alerte = document.querySelector(".alerte");
    if (alerte) {
        new Alerte(alerte);
    }

    //ecouteur d'evenement sur la composante Modale
    document.addEventListener("fermerModale", async function (event) {
        dataAll = await getAll();
        purchases = dataAll.purchases;

        const bouteilles = document.querySelectorAll(".card_bottle");
        const nbBouteilles = bouteilles.length;

        if (nbBouteilles === 0) {
            displayNoContentMessage();
        }
    });

    // récupérer et afficher les données
    let dataAll = await getAll();
    render(dataAll);
    let purchases = dataAll.purchases;

    // changer l'ordre d'affichage selon la selection
    const sortingOptions = document.querySelector(".sorting__frame");
    sortingOptions.addEventListener("click", function () {
        const selectedSort = document.querySelector("[name='sorting']:checked");
        renderSort(selectedSort.value);
    });

    //filtres
    const filterFormHTML = document.querySelector("[data-js='filtersForm']");
    filterFormHTML.addEventListener("submit", renderFilter);

	//calcul de la hauteur du footer pour la position du filtre
	const footerHTML = document.querySelector(".nav-menu");
	const footerHeight = footerHTML.offsetHeight;
	filterFormHTML.style.setProperty(
        "--bottom",
        `${footerHeight}px`
    );
	const btnFilters = document.querySelector("#btn-filters");
	const btnFilterY = App.instance.getAbsoluteYPosition(btnFilters);
		filterFormHTML.style.setProperty("--top", `${btnFilterY}px`);

	btnFilters.addEventListener("change", function () {
        document
            .querySelector("[data-js-list]")
            .classList.toggle("invisible", btnFilters.checked);
    });

	//reinitialisation des filtres
    const btnResetFilters = filterFormHTML.querySelector(
        "[data-js='resetFilters']"
    );
    btnResetFilters.addEventListener("click", function (event) {
        event.preventDefault();
        filterFormHTML.reset();
    });

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

    // --- fonctions auxilières ---

    /**
     * retire toutes les cartes de l'affichage
     */
    function clearAll() {
        document.querySelector("[data-js-list]").innerHTML = "";
        const boutonAjout = document.querySelector("footer > div");
        if (boutonAjout) {
            boutonAjout.remove();
        }
    }

    /**
     * affiche le message de liste vide avec appel à l'action
     */
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
     * recupere tous les achats
     */
    async function getAll() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const response = await fetch(
            `${App.instance.baseURL}/api/afficher/achat`,
            {
                method: "get",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken, // Ajoute CSRF token
                    Authorization: "Bearer " + localStorage.getItem("token"), // Ajoute le token
                },
            }
        );

        const data = await response.json();
        console.log(data);
        return data;
    }

    /**
     * affiche les cartes de bouteilles selon les données reçues
     */
    function render(data) {
        const container = document.querySelector("[data-js-list]");
        const template = document.querySelector("template#bottle");

        if (!data.empty) {
            data.purchases.forEach((purchase) => {
                new Bottle(purchase, "purchase", template, container);
            });
            displayAddBottleBtn();
        } else {
            displayNoContentMessage();
        }
    }

    /**
     * tri et affiche le résultat trié
     */
    function renderSort(sortOption) {
        const [criteria, sort] = sortOption.split("_");
        purchases.sort((a, b) => {
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

        clearAll();

        const container = document.querySelector("[data-js-list]");
        const template = document.querySelector("template#bottle");
        purchases.forEach((purchase) => {
            new Bottle(purchase, "purchase", template, container);
        });
        displayAddBottleBtn();
    }

    /**
     * afficher données filterés
     */
    async function renderFilter(event) {
        event.preventDefault();

        //filtrer
        const countriesHTML =
            filterFormHTML.querySelectorAll("[name='country']");
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

        let filteredPurchase = purchases;

        if (
            countries.length === 0 &&
            types.length === 0 &&
            min.value === "" &&
            max.value === ""
        ) {
            btnFilters.checked = false;
			btnFilters.dispatchEvent(new Event("change"));
            clearAll();
            render(dataAll);
            purchases = dataAll.purchases;
        } else {
            if (countries.length > 0) {
                filteredPurchase = filteredPurchase.filter((purchase) =>
                    countries.includes(purchase.country)
                );
            }
            if (types.length > 0) {
                filteredPurchase = filteredPurchase.filter((purchase) =>
                    types.includes(purchase.type)
                );
            }
            if (min.value != "") {
                filteredPurchase = filteredPurchase.filter(
                    (purchase) => purchase.price >= parseFloat(min.value)
                );
            }
            if (max.value != "") {
                filteredPurchase = filteredPurchase.filter(
                    (purchase) => purchase.price <= parseFloat(max.value)
                );
            }
            const dataFiltered = { purchases: filteredPurchase, empty: false };
            purchases = filteredPurchase;

            btnFilters.checked = false;
			btnFilters.dispatchEvent(new Event("change"));
            clearAll();
            render(dataFiltered);
        }
    }
})();
