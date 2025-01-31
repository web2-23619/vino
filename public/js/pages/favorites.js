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
            displayNoContentMessage("noFavorite");
        }
    });

    // Récupérer et afficher les favoris
    const data = await getAllFavorites();
    render(data);
    console.log(data);

    let favorites = data.favorites;

    //affichage du bouton ajouter bouteille selon l'ouverture des filtres et tri
    const sortingDetails = document.querySelector(".sorting > details");
    const filterDetails = document.querySelector(".filters > details");

    sortingDetails.addEventListener("toggle", modifyDiplayAddBtn);
    filterDetails.addEventListener("toggle", modifyDiplayAddBtn);

    // changer l'ordre d'affichage selon la selection
    const sortingOptions = document.querySelector(".sorting__frame");
    sortingOptions.addEventListener("click", function () {
        const selectedSort = document.querySelector("[name='sorting']:checked");
        sortingDetails.removeAttribute("open");
        renderSort(selectedSort.value);
    });

    //filtres
    const filterFormHTML = document.querySelector("[data-js='filtersForm']");
    filterFormHTML.addEventListener("submit", function (event) {
        filterDetails.removeAttribute("open");
        renderFilter(event);
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

    /**
     * Efface tout le contenu affiché.
     */
    function clearAll() {
        document.querySelector("[data-js-list]").innerHTML = "";
        const boutonAjout = document.querySelector("footer > div");
        if (boutonAjout) {
            boutonAjout.remove();
        }
    }

    /**
     * Affiche un message si la liste des favoris est vide.
     */
    function displayNoContentMessage(templateName) {
        const template = document.querySelector(`[id='${templateName}']`);
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
     * Récupère tous les favoris de l'utilisateur connecté.
     *
     * @async
     * @returns {object} Un objet JSON contenant les favoris (favorites) de l'utilisateur.
     */
    async function getAllFavorites() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const response = await fetch(`${App.instance.baseURL}/api/favoris`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        });

        const data = await response.json();
        return data;
    }

    /**
     * Rendu des favoris sur la page.
     *
     * @param {object} data - Les données des favoris.
     * @param {Array} data.favorites - Un tableau contenant les objets des favoris.
     */
    function render(data, filtered = false) {
        const container = document.querySelector("[data-js-list]");
        const template = document.querySelector("template#favoriteBottle");
        const actionButtonTemplate = document.querySelector(
            "template#action-button"
        );

        // Si des favoris existent, on les affiche, sinon, un message vide
        if (data && data.favorites && data.favorites.length > 0) {
            data.favorites.forEach((favorite) => {
                const favoriteClone = template.content.cloneNode(true);
                favoriteClone.querySelector("[data-js-id]").dataset.jsId =
                    favorite.id;
                favoriteClone.querySelector("[data-info='img']").src =
                    favorite.image_url;
                favoriteClone.querySelector("[data-info='name']").textContent =
                    favorite.name;
                favoriteClone.querySelector("[data-info='price']").textContent =
                    favorite.price;
                favoriteClone.querySelector(
                    "[data-info='volume']"
                ).textContent = favorite.volume;
                favoriteClone.querySelector(
                    "[data-info='country']"
                ).textContent = favorite.country;
                favoriteClone.querySelector("[data-info='type']").textContent =
                    favorite.type;

                const favoriteIcon =
                    favoriteClone.querySelector(".favorite-icon");
                favoriteIcon.dataset.jsFavorite = "true";
                favoriteIcon.innerHTML = "❤️";
                favoriteIcon.title = "Retirer des favoris";

                // Ajouter des actions aux boutons
                favoriteClone
                    .querySelector("[data-js-action='removeFromFavorites']")
                    .addEventListener("click", (e) => {
                        e.preventDefault(); // Empêche tout comportement par défaut

                        const bottleElement = e.target.closest(".card_bottle"); // Récupère l'élément de la bouteille
                        const bottleName =
                            bottleElement.querySelector(
                                "[data-info='name']"
                            ).textContent; // Récupère le nom

                        new ModaleAction(
                            favorite.id,
                            bottleName,
                            "supprimerFavoris",
                            "supprimer",
                            "favoris",
                            bottleElement
                        );
                    });

                favoriteClone.querySelector("[data-js-action='moveToCellar']");
                favoriteClone.querySelector(
                    "[data-js-action='moveToPurchaseList']"
                );

                container.appendChild(favoriteClone);
            });

            displayAddBottleBtn();
        } else if (filtered) {
            displayNoContentMessage("noResult");
        } else {
            displayNoContentMessage("noFavorite");
        }
    }

    /**
     * Supprime un favori.
     *
     * @param {string} id - L'ID du favori à supprimer.
     */
    async function removeFavorite(id) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const response = await fetch(
            `${App.instance.baseURL}/api/favoris/${id}`,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Authorization: "Bearer " + localStorage.getItem("token"),
                },
            }
        );
        if (response.ok) {
            favorites = favorites.filter((favorite) => favorite.id !== id);
            clearAll();
            render({ favorites });
        }
    }

    document
        .querySelectorAll("[data-js-action='moveToCellar']")
        .forEach((button) => {
            button.addEventListener("click", function (event) {
                event.preventDefault(); // Empêche l'action par défaut du lien

                // Récupérer l'ID de la bouteille
                const bottleElement = event.target.closest(".card_bottle");
                const bottleId = bottleElement.getAttribute("data-js-id");

                if (bottleId) {
                    // Construire l'URL de redirection
                    const url = `/cellier/bouteille/ajouter/${bottleId}?source=cellier`;

                    // Rediriger vers l'URL
                    window.location.href = url;
                } else {
                    console.error("ID de la bouteille introuvable !");
                }
            });
        });
    document
        .querySelectorAll("[data-js-action='moveToPurchaseList']")
        .forEach((button) => {
            button.addEventListener("click", function (event) {
                event.preventDefault(); // Empêche l'action par défaut du lien

                // Récupérer l'ID de la bouteille
                const bottleElement = event.target.closest(".card_bottle");
                const bottleId = bottleElement.getAttribute("data-js-id");

                if (bottleId) {
                    // Construire l'URL de redirection
                    const url = `/cellier/bouteille/ajouter/${bottleId}?source=listeAchat`;

                    // Rediriger vers l'URL
                    window.location.href = url;
                } else {
                    console.error("ID de la bouteille introuvable !");
                }
            });
        });

    document.querySelectorAll(".favorite-icon").forEach((icon) => {
        icon.addEventListener("click", async () => {
            const bottleId = icon.closest(".card_bottle").dataset.jsId;
            const isFavorite = icon.dataset.jsFavorite === "true";

            // Envoie une requête pour changer le statut du favori
            const response = await fetch(`/favoris/toggle`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({ bottle_id: bottleId }),
            });

            if (response.ok) {
                // Mettre à jour l'état de l'icône du cœur
                icon.dataset.jsFavorite = !isFavorite;
                icon.innerHTML = !isFavorite ? "❤️" : "🤍";
                icon.title = !isFavorite
                    ? "Retirer des favoris"
                    : "Ajouter aux favoris";
            }
        });
    });

    function modifyDiplayAddBtn() {
        const btnAjouterBouteilleHTML = document.querySelector("footer>div");
        if (sortingDetails.open || filterDetails.open) {
            btnAjouterBouteilleHTML.classList.add("invisible");
        } else {
            btnAjouterBouteilleHTML.classList.remove("invisible");
        }
    }

    /**
     * tri et affiche le résultat trié
     */
    function renderSort(sortOption) {
        const [criteria, sort] = sortOption.split("_");
        favorites.sort((a, b) => {
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

        render({ favorites });
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

        let filteredFavorites = favorites;

        if (
            countries.length === 0 &&
            types.length === 0 &&
            min.value === "" &&
            max.value === ""
        ) {
            clearAll();
            render(data);
            favorites = data.filteredFavorites;
        } else {
            if (countries.length > 0) {
                filteredFavorites = filteredFavorites.filter((favorite) =>
                    countries.includes(favorite.country)
                );
            }
            if (types.length > 0) {
                filteredFavorites = filteredFavorites.filter((favorite) =>
                    types.includes(favorite.type)
                );
            }
            if (min.value != "") {
                filteredFavorites = filteredFavorites.filter(
                    (favorite) => favorite.price >= parseFloat(min.value)
                );
            }
            if (max.value != "") {
                filteredFavorites = filteredFavorites.filter(
                    (favorite) => favorite.price <= parseFloat(max.value)
                );
            }

            favorites = filteredFavorites;
            clearAll();
            render({ favorites }, true);
        }
    }
})();
