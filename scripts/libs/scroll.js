class ScrollAnimation {

    constructor() {
        this.startPositionY = 0;
        this.targetDistanceY = 0;
        this.startTime = 0;
        this.duration = 1000;
        this.animationId;
        this._init();
    }

    _init() {
        const headerLinks = document.querySelectorAll('.header__li a');
        const mobileLinks = document.querySelectorAll('.mobile-menu__link');
        headerLinks.forEach(link => {
            const selector = `#${link.dataset.target}`;
            const target = document.querySelector(selector);
            link.addEventListener('click', (event) => {
                event.preventDefault();
                this._exeScroll(target);
            });
        })

        mobileLinks.forEach(mLink => {
            const selector = `#${mLink.dataset.target}`;
            const target = document.querySelector(selector);
            mLink.addEventListener('click', (event) => {
                event.preventDefault();
                this._exeScroll(target);
            });
        })

        const logoLink = document.querySelector('.logo__link');
        const backToTop = document.querySelector('.backToTop')
        const top = document.querySelector('#container');
        logoLink.addEventListener('click', (event) => {
            event.preventDefault();
            this._exeScroll(top);
        });

        backToTop.addEventListener('click', () => {
            this._exeScroll(top);
        });
    }

    /**
     * アニメーションのイージング関数です
     * @param x
     * @returns {number}
     */
    _easeOutQuart(x) {
        return 1 - Math.pow(1 - x, 4);
    }

    /**
     * フレームごとにスクロールを実行する関数です。
     * これを連続的に繰り返すことでアニメーションさせています。
     */
    _animation() {
        const progress = Math.min(1, (Date.now() - this.startTime) / this.duration);
        const scrollValX = 0;
        const scrollValY =
            this.startPositionY +
            this.targetDistanceY * this._easeOutQuart(progress);
        window.scrollTo(scrollValX, scrollValY);
        if (progress < 1) {
            this.animationId = requestAnimationFrame(() => {
                this._animation();
            });
        }
    }

    /**
     * アニメーションをキャンセルします
     */
    _cancelScroll() {
        window.cancelAnimationFrame(this.animationId);
    }

    /**
     * スクロールアニメーションを実行します
     * @param target
     */
    _exeScroll( target ) {
        this.startPositionY = window.scrollY;
        const domRect = target.getBoundingClientRect();
        this.targetDistanceY = domRect.top;
        this.startTime = Date.now();
        this._animation();
    }
}
