import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import Bottle from "../components/Bottle.js";

(async function () {
    new App();

    const alerte = document.querySelector(".alerte");
    if (alerte) {
        new Alerte(alerte);
    }

    document.addEventListener("click", (event) => {
        if (event.target.matches('[data-js-action="addToCellar"]')) {
            const bottleCard = event.target.closest(".card_bottle");

            const bottleId = bottleCard.getAttribute("data-js-bottle-id");

            // Trouver l’élément de quantité à l’intérieur de la carte
            const quantityElement = bottleCard.querySelector(
                "[data-info='quantity']"
            );
            const quantityInput = bottleCard.querySelector(
                "input[data-js-quantity]"
            );

            // Récupérer la quantité de l’élément affiché et la stocker dans l’entrée
            const bottleQuantity = quantityElement.textContent.trim();
            quantityInput.value = bottleQuantity;

            let source = window.location.href.includes("listeAchat")
                ? "listeAchat"
                : "cellier";

            // Passer 'quantity' dans la chaîne de requête
            window.location.href = `/listeAchat/bouteille/ajouter/${bottleId}?source=${source}&quantity=${bottleQuantity}`;
        }
    });

    // Retirer la bouteille de l’interface utilisateur après l’ajout
    document.addEventListener("DOMContentLoaded", function () {
        const successMessage = document.querySelector(".alert-success");
        if (successMessage && window.location.href.includes("inventaire")) {
            const bottleId = new URLSearchParams(window.location.search).get(
                "bottle_id"
            );
            const bottleCard = document.querySelector(
                `[data-js-id="${bottleId}"]`
            );
            if (bottleCard) {
                bottleCard.remove();
            }
        }
    });

    // récupérer et afficher les données
    let data = await getAll();
    render(data);
    let purchases = data.purchases;

    //ecouteur d'evenement sur la composante Modale
    document.addEventListener("fermerModale", async function (event) {
        const bouteilles = document.querySelectorAll(".card_bottle");
        const nbBouteilles = bouteilles.length;

        if (nbBouteilles === 0) {
            displayNoContentMessage("noPurchase");
        }
    });

    // --- fonctions auxilières ---

    /**
     * Supprime tout le contenu de la liste des achats.
     * Utile pour les cas de reset de la liste.
     */

    function clearAll() {
        document.querySelector("[data-js-list]").innerHTML = "";

        document.querySelector("main > section > .noContent")?.remove();

        const boutonAjout = document.querySelector("footer > div");
        if (boutonAjout) {
            boutonAjout.remove();
        }
    }

    /**
     * affiche le message de liste vide avec appel à l'action
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
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Authorization: "Bearer " + localStorage.getItem("token"),
                },
            }
        );

        const data = await response.json();
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
    function render(data, filtered = false) {
        const container = document.querySelector("[data-js-list]");
        const template = document.querySelector("template#bottle");

        if (data.purchases.length > 0) {
            // On parcourt les achats et on les affiche
            data.purchases.forEach((purchase) => {
                // Création de la bouteille
                new Bottle(purchase, "purchase", template, container);
                const bottleElement = container.querySelector(
                    `[data-js-bottle-id="${purchase.id}"]`
                );
                if (!bottleElement) {
                    console.error(
                        `Erreur : Impossible de trouver la bouteille avec l'ID ${purchase.id}`
                    );
                    return;
                }

                // Sélection et mise à jour de l'icône de favori
                const heartIcon = bottleElement.querySelector(".favorite-icon");
                if (!heartIcon) {
                    console.error(
                        `Erreur : Icône de favori introuvable pour la bouteille ${purchase.id}`
                    );
                    return;
                }

                // Mettre à jour l'affichage du favori en fonction de l'état des données serveur
                heartIcon.dataset.jsFavorite = purchase.is_favorite
                    ? "true"
                    : "false";
                heartIcon.innerHTML = purchase.is_favorite ? "❤️" : "🤍";
                heartIcon.title = purchase.is_favorite
                    ? "Retirer des favoris"
                    : "Ajouter aux favoris";

                // Ajouter un événement pour basculer le favori
                heartIcon.addEventListener("click", async () => {
                    try {
                        const response = await fetch(`/favoris/toggle`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content,
                            },
                            body: JSON.stringify({ bottle_id: purchase.id }),
                        });

                        if (!response.ok) {
                            throw new Error("Erreur serveur !");
                        }

                        const responseData = await response.json();

                        // Mise à jour du favori après la réponse du serveur
                        if (responseData.status === "added") {
                            heartIcon.dataset.jsFavorite = "true";
                            heartIcon.innerHTML = "❤️";
                            heartIcon.title = "Retirer des favoris";
                        } else {
                            heartIcon.dataset.jsFavorite = "false";
                            heartIcon.innerHTML = "🤍";
                            heartIcon.title = "Ajouter aux favoris";
                        }
                    } catch (error) {
                        console.error(
                            "Erreur lors du changement du statut du favori :",
                            error
                        );
                    }
                });
            });
            displayAddBottleBtn();
        } else if (filtered) {
            displayNoContentMessage("noResult");
        } else {
            displayNoContentMessage("noPurchase");
        }
    }

})();
