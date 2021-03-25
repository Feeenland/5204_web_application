<?php
/**
 * home.php = this file generates the content for the home page.
 */

// TODO style the card proportional
?>
<h1>Home</h1>
<div class="container filter_deck">
    <div class="row justify-content-between">
        <div class="col-12 col-md-2">
            <p>deck Filter</p>
        </div>
        <div class="col-12 col-md-5">
            <form action="">
                <div class="checkbox filter__color_check">
                    <input type="checkbox" id="color1" name="color1" value="green">
                    <label for="color1"> green</label><br>
                    <input type="checkbox" id="color2" name="color2" value="blue">
                    <label for="color2"> blue</label><br>
                    <input type="checkbox" id="color3" name="color3" value="white">
                    <label for="color2"> white</label><br>
                    <input type="checkbox" id="color4" name="color4" value="red">
                    <label for="color2"> red</label><br>
                    <input type="checkbox" id="color5" name="color5" value="black">
                    <label for="color2"> black</label><br>
                </div>

                <label for="deck">Choose a deck:</label>
                <select id="deck" name="deck">
                    <option value="all">all</option>
                    <option value="standard">standard</option>
                    <option value="future">future</option>
                    <option value="historic">historic</option>
                    <option value="gladiator">gladiator</option>
                    <option value="pioneer">pioneer</option>
                    <option value="modern">modern</option>
                    <option value="legacy">legacy</option>
                    <option value="pauper">pauper</option>
                    <option value="vintage">vintage</option>
                    <option value="penny">penny</option>
                    <option value="commander">commander</option>
                    <option value="brawl">brawl</option>
                    <option value="duel">duel</option>
                    <option value="oldschool">oldschool</option>
                    <option value="premodern">premodern</option>
                </select>
            </form>
        </div>
        <div class="col-12 col-md-5">
            <form class="form-inline">
                <input class="form-control mr-sm-2" type="search" placeholder="deck or card name" aria-label="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">search</button>
            </form>
        </div>
    </div>
</div>

<div class="container decks">
    <div class="row">
        <article class="col-12 col-md-6 col-lg-4 decks__deck">
            <img class="decks__img" src="img/tsr-235-tarmogoyf.png" alt="magic deck">

            <div class="decks__text">
                <h2>deck Name</h2>
                <p>deck art (modern)</p>
                <img class="color__img" src="img/white.png" alt="">
                <img class="color__img" src="img/blue.png" alt="">
                <img class="color__img" src="img/blue.png" alt="">
                <img class="color__img" src="img/blue.png" alt="">
                <img class="color__img" src="img/blue.png" alt="">
                <p>created By NAME</p>
            </div>
        </article>

        <article class="col-12 col-md-6 col-lg-4 decks__deck">
            <img class="decks__img" src="img/tsr-235-tarmogoyf.png" alt="magic deck">
            <div class="decks__text">
                <h2>deck Name</h2>
                <p>deck art (modern)</p>
                <img class="color__img" src="img/green.png" alt="">
                <img class="color__img" src="img/red.png" alt="">
                <p>created By NAME</p>
            </div>
        </article>
    </div>
</div>