function setupArrows(column, setDirection){
    var downArrow = "\u2193";
    var upArrow = "\u2191";
    var table = document.getElementById("results_table");
    var header = table.rows[0].getElementsByTagName("TH")[column].innerHTML;
    if(setDirection != 0)
    {
        for(var col = 0; col < table.rows[0].cells.length; col++){
            table.rows[0].cells[col].innerHTML = table.rows[0].cells[col].innerHTML.replace(downArrow, "");
            table.rows[0].cells[col].innerHTML = table.rows[0].cells[col].innerHTML.replace(upArrow, "");
        }
        dir = setDirection;
        if(dir == "asc"){
            table.rows[0].getElementsByTagName("TH")[column].innerHTML += " " + downArrow;
        }else{
            table.rows[0].getElementsByTagName("TH")[column].innerHTML += " " + upArrow;
        }
    }
    else if(header.includes(downArrow)){
        dir = "desc";
        header = header.replace(downArrow, upArrow);
        table.rows[0].getElementsByTagName("TH")[column].innerHTML = header;
    }else if(header.includes(upArrow)){
        dir = "asc";
        header = header.replace(upArrow, downArrow);
        table.rows[0].getElementsByTagName("TH")[column].innerHTML = header;
    }else{
        for(var col = 0; col < table.rows[0].cells.length; col++){
            table.rows[0].cells[col].innerHTML = table.rows[0].cells[col].innerHTML.replace(downArrow, "");
            table.rows[0].cells[col].innerHTML = table.rows[0].cells[col].innerHTML.replace(upArrow, "");
        }
        dir = "asc";
        table.rows[0].getElementsByTagName("TH")[column].innerHTML += " " + downArrow;
    }
    return dir;
}

function sortTable(column, setDirection){  
    var i, x, y, shouldSwitch;
    var dir = setupArrows(column, setDirection);
    var table = document.getElementById("results_table");
    var switching = true;
    while (switching) {
        switching = false;
        var rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[column];
            y = rows[i + 1].getElementsByTagName("TD")[column];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}

async function fetchTable(){
    var formData = new FormData();
    const response = await fetch('fetch_data.php',{
        method: 'POST', body: formData });
    const text = await response.text();
    document.getElementById('results_table').innerHTML = text;
}

async function resetTableWithSort(table){
    var col, dir = "asc";
    for(col = 0; col < table.rows[0].cells.length; col++){
        if(table.rows[0].cells[col].innerHTML.includes("\u2193")){
            dir = "asc";
            break;
        }else if(table.rows[0].cells[col].innerHTML.includes("\u2191")){
            dir = "desc";
            break;
        }
    }
    if(col > 4){
        col = 1;
    }
    await fetchTable();
    sortTable(col, dir);
}

async function filterGenres(){
    var table = document.getElementById("results_table");
    var genre = document.getElementById('genres_dropdown').value;
    if(genre == 'All'){
        resetTableWithSort(table);
        return;
    }
    var column = 6;
    for(var i = 1; i < table.rows.length; i++){
        if(table.rows[i].cells[column].innerHTML.includes(genre)){
            table.rows[i].style.display = "";
        }else{
            table.rows[i].style.display = "none";
        }
    }
}

var readFilterState = "all";
function filterRead(){
    if(readFilterState == "all"){
        readFilterState = "unread";
    }else if(readFilterState == "unread"){
        readFilterState = "read";
    }else{
        readFilterState = "all";
    }
    var table = document.getElementById("results_table");
    for(var i = 1; i < table.rows.length; i++){
        if(readFilterState == "all"){
            table.rows[i].style.display = "";
        }else if(readFilterState == "unread"){
            if(table.rows[i].cells[5].innerHTML == ""){
                table.rows[i].style.display = "";
            }else{
                table.rows[i].style.display = "none";
            }
        }else{
            if(table.rows[i].cells[5].innerHTML.includes("<div")){
                table.rows[i].style.display = "";
            }else{
                table.rows[i].style.display = "none";
            }
        }
    }
}