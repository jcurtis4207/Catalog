# Catalog
Catalog is a simple library organization tool written in Python using Flask with SQLite3.

The impetus for creating Catalog was when I found myself in need of a better system
of organization for my books. I wanted to begin using the Library of Congress numbering
system but didn't have a good way to keep track of where each book belongs in that system.
Therefore I put together Catalog as a straightforward way to manage and organize my books.

Originally it was written in PHP with Javascript, but I was never satisfied with that solution.
I am much more familiar with Python, but I haven't used Flask before, so it seemed like a good
excuse to modernize the approach.

### Features
* The home page can be sorted by ISBN, Title, Author, LC Number, or Year by clicking on the column headers.
* To filter books by whether they've been read, click the Unread column header.
* The dropdown at the top right can filter books by their genre.
* Adding a book entry is done using the 'Add Book' button.
* Editing a book entry is done by clicking the row in the table.
(This page also give you option to delete a book entry.)

### Installation

I run this and other webapps through Apache. Follow these steps to deploy it as an apache site:

1. Install the WSGI Python3 package
```bash
apt install libapache2-mod-wsgi-py3
```

2. Create the site directory
```bash
mkdir /var/www/catalog-flask
```

3. Create a virtual environment
```bash
cd /var/www/catalog-flask
python3 -m venv pyenv
```

4. Install the requirements
```bash
. pyenv/bin/activate
pip install -r requirements.txt
```

5. Create WSGI entrypoint
```bash
touch flaskapp.wsgi
```

```python
import sys
import logging

sys.path.insert(0, '/var/www/catalog-flask')
sys.path.insert(0, '/var/www/catalog-flask/venv/lib/python3.13/site-packages/')

# Set up logging
logging.basicConfig(stream=sys.stderr, level=logging.INFO)

# Import and run the Flask app
from app import app as application
```

6. Set permissions for apache user
```bash
chown -R www-data:www-data /var/www/catalog-flask
```

7. Create site in apache
Create the site in `/etc/apache2/sites-enabled`
```xml
<VirtualHost *:80>
        DocumentRoot /var/www/catalog-flask

        WSGIDaemonProcess app threads=1
        WSGIScriptAlias / /var/www/catalog-flask/flaskapp.wsgi
        WSGIApplicationGroup %{GLOBAL}
        <Directory flaskapp>
             WSGIProcessGroup app
             WSGIApplicationGroup %{GLOBAL}
             Order deny,allow
             Allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/flask-error.log
        CustomLog ${APACHE_LOG_DIR}/flask-access.log combined
</VirtualHost>
```

8. Start apache
```bash
systemctl restart apache2
```
