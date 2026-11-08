// configures numeric/lexographic sorting
const collator = new Intl.Collator(undefined, {
    numeric: true,
    sensitivity: "base"
});
const sortDirections = new Map();

const arrows = {
    asc: "\u2193",
    desc: "\u2191"
};

var readFilterState = "all";

function setArrows(column, direction) {
    var table = document.getElementById("results_table");

    for (var col = 0; col < table.rows[0].cells.length; col++) {
        var cell = table.rows[0].cells[col]
        // remove any previous arrows
        cell.innerHTML = cell.innerHTML.trim().split(/\s+/)[0];

        // add arrow for sorting column
        if (col === column) {
            cell.innerHTML = cell.innerHTML + " " + arrows[direction];
        }
    }
}

function sortTable(column) {
    const table = document.getElementById("results_table");
    const tbody = table.tBodies[0];

    const direction = sortDirections.get(column) === "asc" ? "desc" : "asc";
    sortDirections.set(column, direction);
    setArrows(column, direction)

    // exclude header rows
    const rows = Array.from(tbody.rows).filter(row => row.cells[column]?.tagName === "TD");

    rows.sort((a, b) => {
        const aCell = a.cells[column];
        const bCell = b.cells[column];

        const aValue = aCell?.dataset.sortValue ?? aCell?.textContent.trim() ?? "";
        const bValue = bCell?.dataset.sortValue ?? bCell?.textContent.trim() ?? "";

        const comparison = collator.compare(aValue, bValue);
        return direction === "asc" ? comparison : -comparison;
    });

    tbody.append(...rows);
}

function getRowCount() {
    var table = document.getElementById("results_table");
    var count = 0;
    for (var i = 1; i < table.rows.length; i++) {
        if(table.rows[i].style.display != "none") {
            count++;
        }
    }
    document.getElementById("row_count").textContent = `Total: ${count}`;
}

function filterRead() {
    // increment filter state
    if (readFilterState == "all") {
        readFilterState = "unread";
    } else if (readFilterState == "unread") {
        readFilterState = "read";
    } else {
        readFilterState = "all";
    }

    const table = document.getElementById("results_table");

    for (const row of table.rows) {
        const cell = row.cells[5];

        // skip header row
        if (cell.tagName !== "TD") continue;

        const isUnread = cell.querySelector("div") !== null;

        row.style.display =
            readFilterState === "all" ||
            (readFilterState === "unread" && isUnread) ||
            (readFilterState === "read" && !isUnread)
                ? ""
                : "none";
    }
    // reset genre dropdown (which this overrides)
    document.getElementById("genres_dropdown").value = "All";
    getRowCount();
}

function filterGenres() {
    var table = document.getElementById("results_table");
    const genre = document.getElementById('genres_dropdown').value;

    const genreColumn = 6;
    for (var i = 1; i < table.rows.length; i++) {
        if (table.rows[i].cells[genreColumn].innerHTML.includes(genre) || genre == 'All') {
            table.rows[i].style.display = "";
        } else {
            table.rows[i].style.display = "none";
        }
    }
    getRowCount();
}
