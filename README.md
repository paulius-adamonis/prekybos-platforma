# Prekybos platforma

**Prisijungimai prie sistemos:**

                    ADMIN:                  USER:
    email:          admin@admin.lt          user@user.lt
    pass:           admin                   user

**Rolės:**

    Sandėlio valdytojui:    "ROLE_VALDYTOJAS"
    Sandėlio darbininkui:   "ROLE_DARBININKAS"
    Vartotojui:             "ROLE_USER"
    Administratoriui:       "ROLE_ADMIN"
    Moderatoriui:           "ROLE_MOD"
    
    Pavyzdys, kai vartotojas turi kelias roles (pvz kai vartotojas yra sandėlio valdytojas):
    ["ROLE_VALDYTOJAS", "ROLE_USER"]

**Rolių pavyzdžiai:**

    Vartotojui:         ["ROLE_USER"]
    Darbininkui:        ["ROLE_USER", "ROLE_DARBININKAS"]
    Valdytojui:         ["ROLE_USER", "ROLE_VALDYTOJAS"]
    Moderatoriui:       ["ROLE_USER", "ROLE_MOD"]
    Administratoriui:   ["ROLE_USER", "ROLE_MOD", "ROLE_ADMIN"]

**Įrašų šalinimas**

Bet kokių įrašų šalinimas realizjuoamas naudojant "soft delete", per įrašą *arPasalinta*.

Kaip pavyzdį galima paimti parduotuvės prekės "Entity".

Norint iš duombazės išsitraukti masyvą su nepašalintais įrašais, naudojame ne funkciją *findAll*, bet funkciją *->findBy(['arPasalinta' => false])*

**Po COMPOSER update būtina redaguoti js ir css failus, jog taisyklingai veiktų starrating bundle:**

```javascript
// public/bundles/starrating/js/rating.js
$(function(){
    var labelWasClicked = function labelWasClicked(){
        var input = $(this).siblings().filter('span').children().filter('input');
        if (input.attr('disabled')) {
            return;
        }
        input.val($(this).attr('data-value'));
    };
    var turnToStar = function turnToStar(){
        if ($(this).find('input').attr('disabled')) {
            return;
        }
        var labels = $(this).find('div');
        labels.removeClass();
        labels.addClass('star');
    };
    var turnStarBack = function turnStarBack(){
        var rating = parseInt($(this).find('input').val());
        if (rating > 0) {
            var selectedStar = $(this).children().filter('#rating_star_'+rating)
            var prevLabels = $(selectedStar).nextAll();
            prevLabels.removeClass();
            prevLabels.addClass('star-full');
            selectedStar.removeClass();
            selectedStar.addClass('star-full');
        }
    };
    $('.star').click(labelWasClicked);
    $('.rating-well').each(turnStarBack);
    $('.rating-well').hover(turnToStar,turnStarBack);

});
```

```css
/* public/bundles/starrating/css/rating.css */
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
.rating { font-size:16px; }
.rating-well { display: inline-block; direction: rtl; }
.rating input.rating { display: none; }
.rating div.star , .rating span.star { font-family:FontAwesome; font-weight:normal; font-style:normal; font-size: 25px; display:inline-block; position: relative; }
.rating div.star:hover , .rating span.star:hover { cursor:pointer; }
.rating div.star:before, .rating span.star:before { content:"\f006"; padding-right:5px; color:#999; }
.rating div.star:hover:before,.rating div.star:hover~div.star:before, .rating span.star:hover:before,.rating span.star:hover~span.star:before { content:"\f005"; color:#009688; }
.rating div.star-full, .rating span.star-full { font-family:FontAwesome; font-weight:normal; font-style:normal; font-size: 25px; display:inline-block; position: relative; }
.rating div.star-full:before, .rating span.star-full:before  { content:"\f005"; padding-right:5px; color:#009688; }
.rating div.star-empty, .rating span.star-empty { font-family:FontAwesome; font-weight:normal; font-style:normal; font-size: 25px; display:inline-block; position: relative; }
.rating div.star-empty:before ,.rating span.star-empty:before  { content:"\f006"; padding-right:5px; color:#999; }
.rating div.fa-norm , .rating span.fa-norm{ font-size: 1em; }
.rating div.fa-lg , .rating span.fa-lg{ font-size: 1.33333333em; line-height: 0.75em; vertical-align: -15%; }
.rating div.fa-2x, .rating span.fa-2x { font-size: 2em; }
.rating div.fa-3x, .rating span.fa-3x { font-size: 3em; }
.rating div.fa-4x, .rating span.fa-4x { font-size: 4em; }
.rating div.fa-5x , .rating span.fa-5x{ font-size: 5em; }
```

**Templeitas**

Template'ui naudojamas "Material Design for Bootstrap":
https://fezvrasta.github.io/bootstrap-material-design/
