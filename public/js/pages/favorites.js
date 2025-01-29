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

    // Récupérer et afficher les favoris
    const data = await getAllFavorites();
    render(data);

    let favorites = data.favorites;

    /**
     * Efface tout le contenu affiché.
     */
    function clearAll() {
        document.querySelector("[data-js-list]").innerHTML = "";
    }

    /**
     * Affiche un message si la liste des favoris est vide.
     */
    function displayNoContentMessage() {
        const template = document.querySelector("template#noFavorite");
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        const response = await fetch(`${App.instance.baseURL}/api/favoris`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        });

        const data = await response.json();
        console.log(data); 
        return data;
    }

    /**
     * Rendu des favoris sur la page.
     *
     * @param {object} data - Les données des favoris.
     * @param {Array} data.favorites - Un tableau contenant les objets des favoris.
     */
function render(data) {
    const container = document.querySelector("[data-js-list]");
    const template = document.querySelector("template#favoriteBottle");
    const actionButtonTemplate = document.querySelector("template#action-button");
    
    // Si des favoris existent, on les affiche, sinon, un message vide
    if (data && data.favorites && data.favorites.length > 0) {
        data.favorites.forEach(favorite => {
            const favoriteClone = template.content.cloneNode(true);
            favoriteClone.querySelector("[data-js-id]").dataset.jsId = favorite.id;
            favoriteClone.querySelector("[data-info='img']").src = favorite.image_url;
            favoriteClone.querySelector("[data-info='name']").textContent = favorite.name;
            favoriteClone.querySelector("[data-info='price']").textContent = favorite.price;
            favoriteClone.querySelector("[data-info='volume']").textContent = favorite.volume;
            favoriteClone.querySelector("[data-info='country']").textContent = favorite.country;
            favoriteClone.querySelector("[data-info='type']").textContent = favorite.type;

                        const favoriteIcon = favoriteClone.querySelector('.favorite-icon');
            favoriteIcon.dataset.jsFavorite = 'true';
            favoriteIcon.innerHTML = '❤️';
            favoriteIcon.title = 'Retirer des favoris';

            // Ajouter des actions aux boutons
            favoriteClone.querySelector("[data-js-action='removeFromFavorites']").addEventListener("click", (e) => {
                e.preventDefault(); // Empêche tout comportement par défaut
                
                const bottleElement = e.target.closest(".card_bottle"); // Récupère l'élément de la bouteille
                const bottleName = bottleElement.querySelector("[data-info='name']").textContent; // Récupère le nom
            
                new ModaleAction(favorite.id, bottleName, "supprimerFavoris", "supprimer", "favoris", bottleElement);
            });
            
            favoriteClone.querySelector("[data-js-action='moveToCellar']");
            favoriteClone.querySelector("[data-js-action='moveToPurchaseList']");

            container.appendChild(favoriteClone);
        });

        displayAddBottleBtn();
    } else {
        displayNoContentMessage();
    }
}


    /**
     * Supprime un favori.
     *
     * @param {string} id - L'ID du favori à supprimer.
     */
    async function removeFavorite(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        const response = await fetch(`${App.instance.baseURL}/api/favoris/${id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        });
        if (response.ok) {
            favorites = favorites.filter((favorite) => favorite.id !== id);
            clearAll();
            render({ favorites });
        }
    }

    /**
     * Gère les clics pour supprimer ou déplacer des favoris.
     */
    // document.querySelector("[data-js-list]").addEventListener("click", (e) => {
    //     const bottleCard = e.target.closest(".card_bottle");
    //     if (!bottleCard) return;

    //     const id = bottleCard.getAttribute("data-js-id");

    //     if (e.target.matches("[data-js-action='removeFromFavorites']")) {
    //         removeFavorite(id);
    //     }
    // });

    
    document.addEventListener("click", function () {
        document.querySelectorAll("[data-js-action='moveToCellar']").forEach(button => {
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
    });
        document.querySelectorAll("[data-js-action='moveToPurchaseList']").forEach(button => {
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
    

    document.querySelectorAll('.favorite-icon').forEach(icon => {
        icon.addEventListener('click', async () => {
            const bottleId = icon.closest('.card_bottle').dataset.jsId;
            const isFavorite = icon.dataset.jsFavorite === 'true';
            
            // Envoie une requête pour changer le statut du favori
            const response = await fetch(`/favoris/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ bottle_id: bottleId })
            });
    
            if (response.ok) {
                // Mettre à jour l'état de l'icône du cœur
                icon.dataset.jsFavorite = !isFavorite;
                icon.innerHTML = !isFavorite ? '❤️' : '🤍';
                icon.title = !isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris';
            }
        });
    });
    

})();

// document.addEventListener('DOMContentLoaded', () => {
// 	const favoriteIcons = document.querySelectorAll('.favorite-icon');

// 	favoriteIcons.forEach(icon => {
// 		icon.addEventListener('click', async () => {
// 			const bottleId = icon.dataset.jsFavorite;
// 			const isFavorite = icon.dataset.favorite === 'true';

// 			try {
// 				// Envoyer une requête Ajax pour mettre à jour les favoris
// 				const response = await fetch(`/favorites/toggle`, {
// 					method: 'POST',
// 					headers: {
// 						'Content-Type': 'application/json',
// 						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
// 					},
// 					body: JSON.stringify({ bottle_id: bottleId })
// 				});

// 				if (response.ok) {
// 					// Mettre à jour l'état du cœur
// 					icon.dataset.favorite = !isFavorite;
// 					icon.innerHTML = !isFavorite ? '❤️' : '🤍';
// 					icon.title = !isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris';
// 				} else {
// 					console.error('Erreur lors de la mise à jour des favoris');
// 				}
// 			} catch (error) {
// 				console.error('Erreur réseau:', error);
// 			}
// 		});
// 	});
// });