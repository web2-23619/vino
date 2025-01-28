document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("#search");
    const suggestionsContainer = document.querySelector(".search_suggestions");
    const barCodeScannerButton = document.querySelector("[data-js-action='scanner']");

    

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

    // Lancer le scanner au click en utilisant la camera de l'utilisateur

    barCodeScannerButton.addEventListener("click", function () {
        console.log("Scanner button clicked");
        // Selection de l'emplacement du scanner
        const scannerContainer = document.querySelector("template#interactive-container");
        const scannerContent = scannerContainer.content.cloneNode(true);
        document.querySelector("main > section").append(scannerContent);

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector("#interactive"),
                constraints: {
                    width: { min: 640 },
                    height: { min: 480 },
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "upc_reader"],
            }
        }, function(err) {
            if (err) {
                console.error("Error initializing Quagga:", err);
                return;
            }
            console.log("Quagga initialized.");
            Quagga.start();
        });

        // Écouteur d'évènement pour la détection d'un barcode
        Quagga.onDetected((data) => {
            console.log("Barcode detected:", data.codeResult.code);
            const url = `/recherche?query=${encodeURIComponent(data.codeResult.code)}&source=${encodeURIComponent(source)}`;
            window.location.href = url;


            Quagga.stop();
            scannerContent.innerHTML = "";
        });
    })

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