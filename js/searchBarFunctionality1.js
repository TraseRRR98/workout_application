let searchBar = document.getElementById('searchBar');
let tBody = document.getElementById('planTableBody');

searchBar.addEventListener('input', function() {
    let searchValue = searchBar.value.toLowerCase();
    let planRows = tBody.getElementsByTagName('tr');
    let regExp = new RegExp(searchValue, 'i');

    for (let row of planRows) {
        let matchFound = false;
        let planCols = row.getElementsByTagName('td');
        for(let col of planCols){
            if(col.innerHTML.search(regExp) !== -1 && !col.classList.contains('planTableActions')){
                matchFound = true;
                break;
            }
        }
        row.hidden = !matchFound;
    }
});
