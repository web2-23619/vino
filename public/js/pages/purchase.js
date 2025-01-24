import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";
import Bottle from "../components/Bottle.js";

(async function () {
    const appSingleton = new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    document.addEventListener("fermerModale", function (event) {
        const bouteilles = document.querySelectorAll(".card_bottle");
        const nbBouteilles = bouteilles.length;

        if (nbBouteilles === 0) {
            displayNoContentMessage();
        }
    });

    const data = await getAll();
    render(data);

	let purchases = data.purchases;

    const selectOrder = document.querySelector("[name='order']");
    selectOrder.addEventListener("click", function () {
        renderSort(selectOrder.value);
    });

    function clearAll() {
        document.querySelector("[data-js-list]").innerHTML = "";
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

    function displayAddBottleBtn() {
        const template = document.querySelector("template#action-button");
        let content = template.content.cloneNode(true);
        let sectionHTML = document.querySelector("footer");
        sectionHTML.prepend(content);
    }

    /**
     * Fetch les achats de l'utilisateur connect .
     *
     * @async
     * @returns {object} Un objet JSON contenant les achats (purchases) de l'utilisateur.
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
     * Affiche la liste d'achat sur la page.
     *
     * Si la liste n'est pas vide, la fonction parcourt le tableau d'objets d'achats et
     * crée une instance de Bottle pour chaque objet. La fonction affiche ensuite le bouton
     * Ajouter une bouteille . Si la liste est vide, la fonction affiche le message
     * correspondant.
     *
     * @param {object} data - Un objet contenant les données de la liste d'achat.
     * @param {boolean} data.empty - Un indicateur indiquant si la liste est vide.
     * @param {Array} data.purchases - Un tableau d'objets d'achats à afficher.
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
})();
