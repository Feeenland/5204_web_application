

fetch('https://api.magicthegathering.io/v1/cards')
    .then(response => response.json())
    .then(data => console.log(data));


fetch('https://api.scryfall.com/cards/named?fuzzy=tarmogoyf')
    .then(response => response.json())
    .then(data => console.log(data));