document.addEventListener('DOMContentLoaded', function () {
    const main = new Main();
});

class Main {
    constructor() {
        this._init();
    }

    _init() {
        new MobileMenu();
        new Modal();
        new HeaderMenu()
        new ScrollAnimation();

    }

}
