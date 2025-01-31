import App from "../components/App.js";
import Bottle from "../components/Bottle.js";

(async function () {
    new App();

    //remontrer en haut de la page
    const btnRemonter = document.querySelector('[data-js="remonter"]');
    btnRemonter.addEventListener("click", function () {
        const top = document.querySelector(".search-header");
        top.scrollIntoView();
    });

    let loading = false;
    let currentPage = 1;
    let maxPage = 1;

    // variables pour recherche et suggestion
    const searchForm = document.querySelector(".search");
    const resultContainer = document.querySelector("[data-js-list]");
    const suggestionsContainer = document.querySelector(".search_suggestions");
    const searchInput = document.querySelector("#search");

    // pour autocomplete & scanner
    if (!searchInput || !suggestionsContainer) {
        console.error(
            "L'élément #search ou .search_suggestions est introuvable !"
        );
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

    let debounceTimer;
    function debounce(func, delay) {
        return function (...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                func(...args);
            }, delay);
        };
    }
    const debouncedAutoComplete = debounce(searchAutoComplete, 300);

    function searchAutoComplete() {
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

                    suggestion.addEventListener("click", function (event) {
                        searchInput.value = item.name;
                        suggestionsContainer.style.display = "none";
                        const btnSubmit =
                            searchForm.querySelector("[type='submit']");
                        btnSubmit.click();
                    });

                    suggestionsContainer.appendChild(suggestion);
                });

                suggestionsContainer.style.display = "block";
            })
            .catch((error) => {
                console.error(
                    "Erreur lors de la récupération des suggestions :",
                    error
                );
            });
    }

    searchInput.addEventListener("input", function () {
        debouncedAutoComplete();
    });

    // --- filtres ---
    const filterFormHTML = document.querySelector("[data-js='filtersForm']");
    const activeFiltersHTML = document.querySelector(
        "[data-js='activeFilters']"
    );

    //reinitialisation des filtres
    const btnResetFilters = filterFormHTML.querySelector(
        "[data-js='resetFilters']"
    );
    btnResetFilters.addEventListener("click", function (event) {
        event.preventDefault();
        activeFiltersHTML.textContent = 0;
        filterFormHTML.reset();
    });

    filterFormHTML.addEventListener("submit", function (event) {
        event.preventDefault();

        //annuler la suggestion
        clearTimeout(debounceTimer);
        suggestionsContainer.style.display = "none";

        //fermer le tag details avec les filtres
        const fitlerDetails = document.querySelector(".filters > details");
        fitlerDetails.removeAttribute("open");

        // reinitialiser la page courante, lancer la recherche avec le tri présélectionné
        currentPage = 1;
        const selectedSort = document.querySelector("[name='sorting']:checked");
        renderSortAndFilter(selectedSort.value);
    });

    // tri
    const sortingOptions = document.querySelectorAll("[name='sorting']");
    sortingOptions.forEach(function (option) {
        option.addEventListener("change", function () {
            const selectedSort = document.querySelector(
                "[name='sorting']:checked"
            );
            if (selectedSort) {
                const sortOrder = selectedSort.value;
                currentPage = 1;
                renderSortAndFilter(sortOrder);
            }
            const sortingDetails = document.querySelector(".sorting > details");
            sortingDetails.removeAttribute("open");
        });
    });

    //lancer recherche
    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const selectedSort = document.querySelector("[name='sorting']:checked");
        const sortOrder = selectedSort.value;
        currentPage = 1;
        renderSortAndFilter(sortOrder);
    });

    /**
     * trier et afficher
     */
    async function renderSortAndFilter(sortOrder = "name_asc", page = 1) {
        // Prevent loading if there's an ongoing request
        if (loading) return;
        loading = true; // Set loading to true

        // recupérer les données et afficher
        try {
            let nbFilters = 0;

            const searchQuery = document.querySelector("#search").value;

            let formData = new FormData();
            formData.append("query", searchQuery);

            // construire tableau pour filtre de pays
            const countries =
                filterFormHTML.querySelectorAll("[name='country']");
            countries.forEach(function (country) {
                if (country.checked) {
                    nbFilters++;
                    formData.append("countries[]", country.value);
                }
            });

            // construire tableau pour filtre de type
            const types = filterFormHTML.querySelectorAll("[name='type']");
            types.forEach(function (type) {
                if (type.checked) {
                    nbFilters++;
                    formData.append("types[]", type.value);
                }
            });

            // range de prix
            const minPrice = document.querySelector("[name='min']").value;
            const maxPrice = document.querySelector("[name='max']").value;

            if (minPrice) {
                nbFilters++;
                formData.append("min_price", parseFloat(minPrice));
            }
            if (maxPrice) {
                nbFilters++;
                formData.append("max_price", parseFloat(maxPrice));
            }

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const urlParams = new URLSearchParams(window.location.search);
            let source = urlParams.get("source");
            const response = await fetch(
                `${App.instance.baseURL}/api/recherche?source=${source}&page=${page}&tri=${sortOrder}`,
                {
                    method: "post",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken, // Ajoute CSRF token
                        Authorization:
                            "Bearer " + localStorage.getItem("token"), // Ajoute le token
                    },
                }
            );

            const data = await response.json();

            const nbResults = document.createElement("p");
            if (data.results.total === 0) {
                nbResults.textContent = "0 rétulat trouvé";
            } else if (data.results.total === 1) {
                nbResults.textContent = `${data.results.total} résultat trouvé`;
            } else {
                nbResults.textContent = `${data.results.total} résultats trouvés`;
            }
            const existingResults = resultContainer.querySelector("p");

            if (page === 1) {
                resultContainer.innerHTML = "";
                existingResults.remove();
                resultContainer.append(nbResults);
            }

            const template = document.querySelector(
                "template#searchResultBottle"
            );

            data.results.data.forEach(
                (bottle) =>
                    new Bottle(
                        bottle,
                        "search",
                        template,
                        resultContainer,
                        data.source
                    )
            );
            maxPage = data.results.last_page;

            // ajouter bouton afficher plus si pas derniere page
            if (page < maxPage) {
                const btnAfficherPlus = document.createElement("button");
                btnAfficherPlus.textContent = "Afficher plus";
                btnAfficherPlus.classList.add("btn");
                btnAfficherPlus.classList.add("btn_outline_dark");
                btnAfficherPlus.dataset.js = "afficherPlusBouteille";

                resultContainer.append(btnAfficherPlus);
                const btnAfficherPlusHtml = resultContainer.lastElementChild;

                btnAfficherPlusHtml.addEventListener("click", function (event) {
                    const existingBtnAfficherPlus = event.target;
                    existingBtnAfficherPlus.remove();
                    renderSort(sortOrder, currentPage);
                });
            }

            const heading = document.querySelector(".search-header");
            heading.textContent = `Résultat pour "${searchQuery}"`;

            suggestionsContainer.style.display = "none";

            activeFiltersHTML.textContent = nbFilters;

            currentPage++;
            loading = false;
        } catch (error) {
            console.error(error);
            loading = false;
        }
    }

    //fermer suggestion si click n'importe ou que suggestion
    document.addEventListener("click", function (event) {
        const trigger = event.target;

        if (trigger.tagName !== "LI") {
            suggestionsContainer.style.display = "none";
        }
    });
})();
