export default class Bottle {
    #name;
    #price;
    #country;
    #volume;
    #type;
    #quantity;
    #img;
    #purchaseId;
    #template;
    #page;
    #container;
    #elementHTML;

    constructor(data, page, template, container) {
        console.log(data);

        this.#name = data.bottle.name;
        this.#price = data.bottle.price;
        this.#country = data.bottle.country;
        this.#type = data.bottle.type;
        this.#volume = data.bottle.volume;
        this.#img = data.bottle.image_url;
        this.#page = page;
        this.#template = template;
        this.#container = container;

        if (this.#page === "purchase") {
            this.#purchaseId = data.id;
            this.#quantity = data.quantity;
        }

        this.#render();
    }

    #render() {
        const clone = this.#template.content.cloneNode(true);

        console.log(clone);

        //remplacer valeur

        clone.querySelector("[data-info='name']").textContent = this.#name;
        clone.querySelector("[data-info='type']").textContent = this.#type;
        clone.querySelector("[data-info='quantity']").textContent =
            this.#quantity;
        clone.querySelector("[data-info='volume']").textContent = this.#volume;
        clone.querySelector("[data-info='price']").textContent = this.#price;
        clone.querySelector("[data-info='img']").src = this.#img;
        clone.querySelector("[data-info='country']").textContent =
            this.#country;

        //injecter dans DOM
        this.#container.append(clone);
        this.#elementHTML = this.#container.lastElementChild;

        this.#elementHTML.setAttribute("data-js-id", this.#purchaseId);
        this.#elementHTML.setAttribute("data-js-name", this.#name);

    }
}
