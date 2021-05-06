console.log('app.js');

$(document).ready(function() {
    console.log('app ready');

    window.addEventListener("load", function(){
        console.log('app load');
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#000004"
                },
                "button": {
                    "background": "#3D70DA"
                }
            }
        })});

});
