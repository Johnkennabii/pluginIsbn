const isbn = document.getElementById('search')

// creation tableau HTML
const createLine = (table, infoIsbn) => {
    const tbody = table.getElementsByTagName('tbody')[0];
    const newRow = tbody.insertRow();
    let counter = 0;
    for (const key in infoIsbn) {
        const newCell = newRow.insertCell(counter);
        const newText = document.createTextNode(infoIsbn[key]);
        newCell.appendChild(newText);
        counter++
    }
    table.classList.remove('d-none');
}


// Creation objet 
const createIsbn = (data) => {
    const dataIsbn = new Object();
    dataIsbn.isbn10 = data.items[0].volumeInfo.industryIdentifiers[0].identifier;
    dataIsbn.isbn13 = data.items[0].volumeInfo.industryIdentifiers[1].identifier;
    dataIsbn.title = data.items[0].volumeInfo.title;
    dataIsbn.authors = data.items[0].volumeInfo.authors[0];
    dataIsbn.publishedDate = data.items[0].volumeInfo.publishedDate;
    dataIsbn.description = data.items[0].volumeInfo.description;
    dataIsbn.pageCount = data.items[0].volumeInfo.pageCount;
    dataIsbn.printType = data.items[0].volumeInfo.printType;
    dataIsbn.categorie = data.items[0].volumeInfo.categories[0];
    //prix non trouvé dans l'objet
    dataIsbn.smallThumbnail = data.items[0].volumeInfo.imageLinks.smallThumbnail;
    dataIsbn.thumbnail = data.items[0].volumeInfo.imageLinks.thumbnail;
    dataIsbn.textSnippet = data.items[0].searchInfo.textSnippet;
    return JSON.stringify(dataIsbn)
}

const parseData = (e) => {
    return JSON.parse(e)
}




// récuperation element Table
const table = document.getElementById('table'); // element table
let tableTest = []

const searchIsbn = (isbn) => {
    const xhr = new XMLHttpRequest()
    xhr.open('GET', `https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`)

    xhr.addEventListener('readystatechange', () => {
        if (xhr.readyState === 4 ) {
            if (xhr.status === 200) {
                if(isbn.length > 0){
                    let dataResponse = JSON.parse(xhr.response)
                    if (dataResponse.totalItems !== 1) {
                        console.log('Livre inexistant dans notre banque de donnée')
                    } else {
                        alert('Livre ajouté')
                        dataResponse = createIsbn(dataResponse)
                            console.log('createIsbn' +dataResponse+ '\n')
                        dataResponse = parseData(dataResponse)
                            // console.log('parseData' +dataResponse+ '\n')
                        tableTest = (Object.values(dataResponse))
                            //console.log('Table test ' +tableTest[8]+ '\n')
                        dataResponse = createLine(table, tableTest)
                            // console.log(dataResponse)
                    }

                } else {
                    alert('Veuillez saisir un Isbn')
                }
            }
        }
    })
    xhr.send()
}