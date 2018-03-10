import $ from 'jquery';

class Search {

    // 1. descrbibe and initiate our object
    constructor() {
        this.addSearchHTML();

        this.openButton     = $('.js-search-trigger');
        this.closeButton    = $('.search-overlay__close');
        this.searchOverlay  = $('.search-overlay');
        this.searchField    = $('#search-term');
        this.isOpen         = false;
        this.typingTimer;
        this.resultsDiv     = $('#search-overlay__result');
        this.previousValue;
        this.isSpinVisible  = false;
        
        this.events();
    }

    // 2. events
    events() {
        this.openButton.on('click', this.openOverlay.bind(this));
        this.closeButton.on('click', this.closeOverlay.bind(this));
        $(document).on('keydown', this.keyPressDispatch.bind(this));
        this.searchField.on('keyup', this.typingLogic.bind(this));
    }

    // 3. methods (function, action...)
    typingLogic() {
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
            if (this.searchField.val()) {
                if (!this.isSpinVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>')
                    this.isSpinVisible = true;
                }
                this.typingTimer = setTimeout( this.getResults.bind(this) , 500); 
            } else {
                this.resultsDiv.html('');
                this.isSpinVisible = false;
            }
                           
        }
        this.previousValue = this.searchField.val();  
    }

    getResults() {
        var root = universityData.root_url;
        var term = this.searchField.val();
        var url = root+'/wp-json/university/v1/search?term='+term;
        
        $.get(url, (data) => {
            this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Informations</h2>
                        ${data.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
                        ${data.generalInfo.map(res => `<li><a href="${res.url}">${res.title}</a> ${res.postType == 'post' ? `by ${res.authorName}`:''}</li>`).join('')}
                        ${data.generalInfo.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${data.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match. <a href="${root}/programs">View all programs</a></p>`}
                        ${data.programs.map(res => `<li><a href="${res.url}">${res.title}</a> ${res.postType == 'post' ? `by ${res.authorName}`:''}</li>`).join('')}
                        ${data.programs.length ? '</ul>' : ''}
                        
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${data.professors.length ? '<ul class="professor-cards">' : `<p>No professors</p>`}
                        ${data.professors.map(res => `<li class="professor-card__list-item">
                        <a class="professor-card" href="${res.url}">
                            <img class="professor-card__image" src="${res.photo}">
                            <span class="professor-card__name">${res.title}</span>
                        </a>
                    </li>`).join('')}
                        ${data.professors.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${data.campuses.length ? '<ul class="link-list min-list">' : `<p>No Campuses match <a href="${root}/campuses">View all Campuses</a></p>`}
                        ${data.campuses.map(res => `<li><a href="${res.url}">${res.title}</a> ${res.postType == 'post' ? `by ${res.authorName}`:''}</li>`).join('')}
                        ${data.campuses.length ? '</ul>' : ''}
                        
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${data.events.length ? '' : `<p>No Events match <a href="${root}/events">View all Events</a></p>`}
                        ${data.events.map(res => `
                        <div class="event-summary">
                        <a class="event-summary__date t-center" href="${res.url}">
                            <span class="event-summary__month">${res.month}</span>
                            <span class="event-summary__day">${res.day}</span>  
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="${res.url}">${res.title}</a></h5>
                            <p> ${res.desc}
                             <a href="${res.url}" class="nu gray">Learn more</a></p>
                        </div>
                    </div>
                        `).join('')}
                        
                    </div>
                </div>
            `);
            this.isSpinVisible = false;
        })
        
    }

    keyPressDispatch (e) {
        if (e.keyCode === 83 && !this.isOpen && !$('input, textarea').is(':focus')) {            
            this.openOverlay();
        }

        if (e.keyCode === 27 && this.isOpen) {
            this.closeOverlay();
        }
    }

    openOverlay() {        
        this.searchOverlay.addClass('search-overlay--active');
        $('body').addClass('body-no-scroll');
        // pour attendre que la transition css soit faite
        this.searchField.val('');
        setTimeout(() => {
            this.searchField.focus();
        }, 301)        
        this.isOpen = true;
        return false // prevent default
    }

    closeOverlay() {
        this.searchOverlay.removeClass('search-overlay--active');
        $('body').removeClass('body-no-scroll');
        this.isOpen = false;
    }

    addSearchHTML() {
        $('body').append(`
        <!-- Barre de recherche --> 
        <div class="search-overlay">
          <div class="search-overlay__top">
            <div class="container">
              <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
              <input type="text" class="search-term" placeholder="What are you looking for" id="search-term">
              <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
            </div>
          </div>
          <div class="container">
            <div id="search-overlay__result">
              <!-- Search result here -->
            </div>
          </div>
        </div>
        `)
    }
}

export default Search;