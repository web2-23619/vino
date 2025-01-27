import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import Bottle from "../components/Bottle.js";

(async function () {
    const appSingleton = new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    document.addEventListener("fermerModale", function () {
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
    selectOrder.addEventListener("change", function () {
        renderSort(selectOrder.value);
    });

    const cellars = await getCellars();
    populateCellarDropdown(cellars);

    // Event listener for "Add to Cellar" button
    document.addEventListener("click", async (event) => {
        if (event.target.matches('[data-js-action="addToCellar"]')) {
            const button = event.target;
            const bottleCard = button.closest(".card_bottle");
            const bottleId = bottleCard.getAttribute("data-js-id");
            const quantity = parseInt(
                bottleCard.querySelector('[data-info="quantity"]').textContent
            );

            const dropdown = document.querySelector("#cellarDropdown");
            const cellarId = dropdown.value;

            if (!cellarId) {
                console.error("Veuillez sélectionner un cellier.");
                showAlerte("Veuillez sélectionner un cellier.");
                return;
            }

            try {
                const response = await addToCellar({ bottleId, cellarId, quantity });

                if (response.message === "added" || response.message === "updated") {
                    showAlerte(
                        response.message === "added"
                            ? "La bouteille a été ajoutée au cellier !"
                            : "La quantité a été mise à jour dans le cellier !"
                    );

                    bottleCard.remove(); // Remove the bottle from the list
                } else {
                    throw new Error("Une erreur est survenue.");
                }

                const bouteilles = document.querySelectorAll(".card_bottle");
                if (bouteilles.length === 0) {
                    displayNoContentMessage();
                }
            } catch (error) {
                console.error("Erreur lors de l'ajout au cellier :", error);
                showAlerte("Erreur lors de l'ajout au cellier.");
            }
        }
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

    async function getAll() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const response = await fetch(
            `${App.instance.baseURL}/api/afficher/achat`,
            {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Authorization: "Bearer " + localStorage.getItem("token"),
                },
            }
        );

        const data = await response.json();
        console.log(data);
        return data;
    }

    async function getCellars() {
        try {
            const response = await fetch(`${App.instance.baseURL}/api/user/cellars`, {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token"),
                },
            });

            if (!response.ok) {
                throw new Error("Erreur lors de la récupération des celliers.");
            }

            const data = await response.json();
            return data.cellars;
        } catch (error) {
            console.error("Erreur lors de la récupération des celliers :", error);
            return [];
        }
    }

    function populateCellarDropdown(cellars) {
        const dropdown = document.querySelector("#cellarDropdown");

        dropdown.innerHTML = "";

        if (cellars.length === 0) {
            const option = document.createElement("option");
            option.value = "";
            option.textContent = "Aucun cellier disponible";
            dropdown.appendChild(option);
        } else {
            cellars.forEach((cellar) => {
                const option = document.createElement("option");
                option.value = cellar.id;
                option.textContent = cellar.name;
                dropdown.appendChild(option);
            });
        }
    }

    async function addToCellar({ bottleId, cellarId, quantity }) {
        console.log({ bottleId, cellarId, quantity });
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        const response = await fetch(
            `${App.instance.baseURL}/api/add-to-cellar`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Authorization: "Bearer " + localStorage.getItem("token"),
                },
                body: JSON.stringify({ bottleId, cellarId, quantity }),
            }
        );

        return response.json();
    }

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
                return order === "asc"
                    ? a.name.localeCompare(b.name)
                    : b.name.localeCompare(a.name);
            } else if (criteria === "price") {
                return order === "asc"
                    ? a.price - b.price
                    : b.price - a.price;
            }
        });

        clearAll();

        const container = document.querySelector("[data-js-list]");
        const template = document.querySelector("template#bottle");
        purchases.forEach((purchase) => {
            new Bottle(purchase, "purchase", template, container);
        });
    }

    function showAlerte(message) {
        console.error(message);
    }
})();
