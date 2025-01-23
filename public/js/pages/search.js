document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("#search");
    const suggestionsContainer = document.querySelector(".suggestions");

    // Vérifiez que les éléments existent
    if (!searchInput || !suggestionsContainer) {
        console.error("L'élément #search ou .suggestions est introuvable !");
        return;
    }

    // Événement déclenché à chaque modification de l'input
    searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim(); // Supprime les espaces inutiles

        // Si la requête est trop courte, on masque la liste
        if (query.length < 2) {
            suggestionsContainer.innerHTML = ""; // Vider la liste
            suggestionsContainer.style.display = "none"; // Masquer
            return;
        }

        // Effectuer une requête pour récupérer les suggestions
        fetch(`/recherche-autocomplete?query=${encodeURIComponent(query)}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }
                return response.json(); // Convertir en JSON
            })
            .then((data) => {
                // Vider la liste actuelle pour éviter la duplication
                suggestionsContainer.innerHTML = "";

                // Si aucune suggestion, on masque la liste
                if (data.length === 0) {
                    suggestionsContainer.style.display = "none";
                    return;
                }

                // Afficher les suggestions correspondantes
                data.forEach((item) => {
                    const suggestion = document.createElement("li");
                    suggestion.textContent = `${item.name} (${item.type}, ${item.country})`;
                    suggestion.classList.add("suggestion-item");

                    // Événement de clic sur chaque suggestion
                    suggestion.addEventListener("click", function () {
                        searchInput.value = item.name; // Remplit l'input avec la suggestion sélectionnée
                        suggestionsContainer.innerHTML = ""; // Vider la liste
                        suggestionsContainer.style.display = "none"; // Masquer
                    });

                    suggestionsContainer.appendChild(suggestion);
                });

                // Afficher la liste
                suggestionsContainer.style.display = "block";
            })
            .catch((error) => {
                console.error("Erreur lors de la récupération des suggestions :", error);
            });
    });
});