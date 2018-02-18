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
        var url = root+'/wp-json/wp/v2/posts?search='+this.searchField.val();
        var term = this.searchField.val();
        // On lance plusieurs requete en meme temps
        $.when(
            $.getJSON(url),
            $.getJSON(root+'/wp-json/wp/v2/pages?search='+term)
        ).then((posts, pages, events)=>{
            var combinedResult = posts[0].concat(pages[0]);
            this.resultsDiv.html(`
            <h2 class="search-overlay__section-title">General Information</h2>
            ${combinedResult.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
                ${combinedResult.map(res => `<li><a href="${res.link}">${res.title.rendered}</a></li>`).join('')}
            ${combinedResult.length ? '</ul>' : ''}
            `);
            this.isSpinVisible = false;
        }, () => {
            this.resultsDiv.html('<p>Unexpected error, please try again</p>');
        });
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