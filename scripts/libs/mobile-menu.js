class MobileMenu {
    constructor() {
        this.btn = document.querySelector('.mobile-menu__btn');
        this.mobileMenu = document.querySelector('.mobile-menu');
        this.menuLists = document.querySelectorAll('.mobile-menu__item');
        this.eventType = this._getEventType();
        this._addEvent();
        this._removeMenuOpen();
    }

    _getEventType() {
        const isTouchCapable =

            "ontouchstart" in window ||

            (window.DocumentTouch && document instanceof window.DocumentTouch) ||

            navigator.maxTouchPoints > 0 ||

            window.navigator.msMaxTouchPoints > 0;



        return isTouchCapable ? "touchstart" : "click";
    }

    _toggle() {
        this.btn.classList.toggle('menu-open');
        this.mobileMenu.classList.toggle('menu-open');
    }

    _addEvent() {
        this.btn.addEventListener(this.eventType, this._toggle.bind(this));
    }

    _removeMenuOpen() {
        // const menu = this.mobileMenu;
        this.menuLists.forEach(list => {
            list.addEventListener('click', () => {
                this.mobileMenu.classList.remove('menu-open');
                this.btn.classList.remove('menu-open');
            });
        });
    }
}
