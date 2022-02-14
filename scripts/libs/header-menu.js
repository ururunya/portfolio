class HeaderMenu {
    constructor() {
        this.header = document.querySelector('.header');
        this.ticking = false;
        this._inti();
    }

    _inti() {
        window.addEventListener('scroll', this._scrollYChecker.bind(this));
    }

    _scrollYChecker() {
        let scrollLength = window.scrollY;
        if (!this.ticking) {
            window.requestAnimationFrame(this._classToggler.bind(this, scrollLength));

        }
        this.ticking = true;
    }

    _classToggler(scrollLength) {

        if (scrollLength > 200) {
            this.header.classList.add('active');
        } else {
            this.header.classList.remove('active');
        }
        this.ticking = false;
    }

}
