# Catalog
Catalog is a simple library organization tool, written in PHP with SQLite3.

The impetus for creating Catalog was when I found myself in need of a better system
of organization for my books. I wanted to begin using the Library of Congress numbering
system but didn't have a good way to keep track of where each book belongs in that system.
Therefore I put together Catalog as a straightforward way to manage and organize my books.

### Features
* The home page can be sorted by ISBN, Title, Author, LC Number, or Year by clicking on the column headers.
* To filter books by whether they've been read, click the Read column header.
* The dropdown at the top right can filter books by their genre.
* Adding a book entry is done using the 'Add Book' button.
* Editing a book entry is done by clicking the row in the table.
(This page also give you option to delete a book entry.)

### Installation
To install with Docker-Compose:
 * Clone the Repository
 * Edit the docker-compose.yml file to change the published port and set volume mount location
 * Create and run the container <br /> `docker-compose up -d`
 * Set volume permissions <br /> `chown www-data:www-data <path-to-volume>`
 * Access Catalog at `http://<IP-ADDRESS:PORT>`
