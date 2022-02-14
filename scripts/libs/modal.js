class Modal {
    constructor() {
        this.portfolios = document.querySelectorAll('.portfolio__work');
        this.modals = document.querySelectorAll('.modal');
        this._init();
    }

    _init() {
        this.portfolios.forEach((portfolio) => {
            portfolio.addEventListener('click', () => {
                const className = portfolio.dataset['work'];
                const modal = document.querySelector(`.${className}`);
                const inner = modal.querySelector('.modal__inner');
                modal.classList.add('active');
                // Promise.resolve().then(() => {
                //     inner.classList.add('show');
                // })
                setTimeout(() => {

                    inner.classList.add('show');
                });
            });
        });

        this.modals.forEach(modal => {
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
                modal.querySelector('.modal__inner').classList.remove('show');
            })
        });
    }

}
