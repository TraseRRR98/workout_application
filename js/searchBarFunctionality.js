let searchBar = document.getElementById('searchBar');
let tBody = document.getElementById('exerciseTableBody');

searchBar.addEventListener('input', function() {
    let searchValue = searchBar.value.toLowerCase();
    let exerciseRows = tBody.getElementsByTagName('tr');
    let regExp = new RegExp(searchValue, 'i');

    for (let row of exerciseRows) {
        let matchFound = false;
        let exerciseCols = row.getElementsByTagName('td');
        for(let col of exerciseCols){
            if(col.innerHTML.search(regExp) !== -1 && !col.classList.contains('exerciseTableActions')){
                matchFound = true;
                break;
            }
        }
        row.hidden = !matchFound;
    }
});
