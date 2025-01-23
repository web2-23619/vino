document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("#search");
    const suggestionsContainer = document.querySelector(".search_suggestions");

    if (!searchInput || !suggestionsContainer) {
        console.error("L'élément #search ou .search_suggestions est introuvable !");
        return;
    }

    // Récupérer la source actuelle de l'URL ou depuis sessionStorage
    const urlParams = new URLSearchParams(window.location.search);
    let source = urlParams.get("source");

    if (!source) {
        // Si aucune source dans l'URL, essayez de récupérer depuis sessionStorage
        source = sessionStorage.getItem("source") || "default";
    } else {
        // Si une source existe dans l'URL, la sauvegarder dans sessionStorage
        sessionStorage.setItem("source", source);
    }

    searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim();

        if (query.length < 2) {
            suggestionsContainer.innerHTML = "";
            suggestionsContainer.style.display = "none";
            return;
        }

        fetch(`/recherche-autocomplete?query=${encodeURIComponent(query)}`)
            .then((response) => response.json())
            .then((data) => {
                suggestionsContainer.innerHTML = "";

                if (data.length === 0) {
                    // Afficher un message d'absence de résultats (optionnel)
                    const noResultMessage = document.createElement("li");
                    noResultMessage.textContent = "Aucun résultat trouvé.";
                    noResultMessage.classList.add("suggestion-item");
                    suggestionsContainer.appendChild(noResultMessage);
                    suggestionsContainer.style.display = "block";
                    return;
                }

                data.forEach((item) => {
                    const suggestion = document.createElement("li");
                    suggestion.textContent = `${item.name} (${item.type}, ${item.country})`;
                    suggestion.classList.add("search_suggestion-item");

                    suggestion.addEventListener("click", function () {
                        // Inclure "query" et "source" dans la redirection
                        window.location.href = `/recherche?query=${encodeURIComponent(item.name)}&source=${encodeURIComponent(source)}`;
                    });

                    suggestionsContainer.appendChild(suggestion);
                });

                suggestionsContainer.style.display = "block";
            })
            .catch((error) => {
                console.error("Erreur lors de la récupération des suggestions :", error);
            });
    });
});