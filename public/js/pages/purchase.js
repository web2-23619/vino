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
    const selectOrder = document.querySelector("[name='order']");
    selectOrder.addEventListener("click", function () {
        renderSort(selectOrder.value);
    });

    //filtres
    const filterFormHTML = document.querySelector("[data-js='filtersForm']");
    filterFormHTML.addEventListener("submit", renderFilter);

    const btnResetFilters = filterFormHTML.querySelector(
        "[data-js='resetFilters']"
    );
    btnResetFilters.addEventListener("click", function (event) {
        event.preventDefault();
        filterFormHTML.reset();
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
    function renderSort(orderOption) {
        const [criteria, order] = orderOption.split("_");
        purchases.sort((a, b) => {
            if (criteria === "name") {
                const nameA = a.name;
                const nameB = b.name;

                if (order === "asc") {
                    return nameA.localeCompare(nameB);
                } else {
                    return nameB.localeCompare(nameA);
                }
            } else if (criteria === "price") {
                const priceA = a.price;
                const priceB = b.price;
                if (order === "asc") {
                    return priceA - priceB; // Ascending order
                } else {
                    return priceB - priceA; // Descending order
                }
            }
        });

        clearAll();

        const container = document.querySelector("[data-js-list]");
        const template = document.querySelector("template#bottle");
        purchases.forEach((purchase) => {
            new Bottle(purchase, "purchase", template, container);
        });
    }

    /**
     * afficher données filterés
     */
    async function renderFilter(event) {
        event.preventDefault();

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
            clearAll();
            render(dataFiltered);
        }

        // const formData = new FormData();
        // formData.append("type[]", types);
        // formData.append("country[]", countries);
        // formData.append("min", min.value);
        // formData.append("max", max.value);

        // const csrfToken = document
        //     .querySelector('meta[name="csrf-token"]')
        //     .getAttribute("content");
        // const response = await fetch("http://example.org/post", {
        //     method: "POST",
        //     body: formData,
        //     headers: {
        //         "Content-Type": "application/json",
        //         "X-CSRF-TOKEN": csrfToken, // Ajoute CSRF token
        //         Authorization: "Bearer " + localStorage.getItem("token"), // Ajoute le token
        //     },
        // });
    }

    /**
     * réinitialiser filtre
     */
})();
