import sqlite3
import os
import logging
from pathlib import Path


class Database:
    def __init__(self):
        self.logger = logging.getLogger(__name__)
        logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)-8s - %(message)s')

        self.database_name = Path(__file__).resolve().parent / "DB" / "catalog.db"
        self.create_database()

    def create_database(self):
        if os.path.exists(self.database_name):
            self.logger.info('Database %s already exists', self.database_name)
            return

        try:
            con = sqlite3.connect(self.database_name, autocommit=True)
            cur = con.cursor()

            cur.execute('PRAGMA foreign_keys = ON')
            cur.execute('''CREATE TABLE books (
                                ISBN TEXT PRIMARY KEY NOT NULL,
                                Title TEXT NOT NULL,
                                Author TEXT,
                                LCNo TEXT,
                                Year TEXT,
                                Read BOOLEAN
                            )''')
            cur.execute('''CREATE TABLE genres (
                                ISBN TEXT,
                                Genre TEXT,
                                PRIMARY KEY(ISBN, Genre),
                                FOREIGN KEY (ISBN) REFERENCES books(ISBN)
                            )''')

            self.logger.info('Database % created successfully', self.database_name)

        finally:
            con.close()

    def fetch_books(self):
        try:
            con = sqlite3.connect(self.database_name)
            cur = con.cursor()
            output = []

            sql = ' '.join("""
                SELECT books.*, GROUP_CONCAT(genres.genre, '<br>') AS genre_list
                FROM books
                LEFT JOIN genres ON books.isbn = genres.isbn
                GROUP BY books.isbn
                ORDER BY books.title ASC
            """.split())

            self.logger.debug('Running query: %s', sql)
            book_rows = cur.execute(sql).fetchall()

            for row in book_rows:
                # set read cell ID based on value
                read = '' if row[5] == 0 else '<div id="filled_cell"></div>'
                new_row = row[:5] + (read, row[6],)
                output.append(new_row)

            return output

        finally:
            con.close()

    def fetch_a_book(self, isbn):
        try:
            con = sqlite3.connect(self.database_name)
            cur = con.cursor()

            sql = ' '.join("""
                SELECT books.*, GROUP_CONCAT(genres.genre, ';') AS genre_list
                FROM books
                LEFT JOIN genres ON books.isbn = genres.isbn
                WHERE books.isbn = ?
                GROUP BY books.isbn
            """.split())

            self.logger.debug('Running query: %s', sql)
            book_rows = cur.execute(sql, (isbn,)).fetchall()
            if len(book_rows) != 1:
                raise RuntimeError(f"Query didn't return 1 row, returned: {len(book_rows)}")

            output = book_rows[0]
            read = '' if output[5] == 0 else 'checked'
            return output[:5] + (read, output[6],)

        finally:
            con.close()

    def fetch_all_genres(self):
        try:
            con = sqlite3.connect(self.database_name)
            cur = con.cursor()
            sql = "SELECT DISTINCT Genre FROM genres ORDER BY Genre ASC"
            res = cur.execute(sql)
            return res.fetchall()

        finally:
            con.close()

    def add_book(self, form_data):
        self.logger.info('Adding new book')
        try:
            con = sqlite3.connect(self.database_name, autocommit=True)
            cur = con.cursor()
            read = 1 if form_data.get('Read', 0) != 0 else 0

            # insert books
            sql = 'INSERT INTO books VALUES (:isbn, :title, :author, :lcno, :year, :read)'
            self.logger.debug('Running query: %s', sql)
            cur.execute(sql, {
                'title': form_data['Title'].strip(),
                'author': form_data['Author'].strip(),
                'lcno': form_data['LCNo'].strip(),
                'year': form_data['Year'].strip(),
                'isbn': form_data['ISBN'].strip(),
                'read': read
            })

            # insert genres
            genres = form_data['Genre'].split(';')
            for genre in genres:
                sql = 'INSERT INTO genres VALUES (:isbn, :genre)'
                self.logger.debug('Running query: %s', sql)
                cur.execute(sql, {'isbn': form_data['ISBN'].strip(), 'genre': genre.strip()})

        finally:
            con.close()

    def update_book(self, form_data):
        isbn = form_data['ISBN'].strip()
        self.logger.info('Updating book with ISBN %s', isbn)
        try:
            con = sqlite3.connect(self.database_name, autocommit=True)
            cur = con.cursor()
            read = 1 if form_data.get('Read', 0) != 0 else 0

            # update books
            sql = 'UPDATE books SET Title=:title, Author=:author, LCNo=:lcno, Year=:year, Read=:read WHERE ISBN=:isbn'
            self.logger.debug('Running query: %s', sql)
            cur.execute(sql, {
                'title': form_data['Title'].strip(),
                'author': form_data['Author'].strip(),
                'lcno': form_data['LCNo'].strip(),
                'year': form_data['Year'].strip(),
                'isbn': isbn,
                'read': read
            })

            # update genres
            sql = 'DELETE FROM genres WHERE ISBN = ?'
            self.logger.debug('Running query: %s', sql)
            cur.execute(sql, (isbn,))
            genres = form_data['Genre'].split(';')
            for genre in genres:
                sql = 'INSERT INTO genres VALUES (:isbn, :genre)'
                self.logger.debug('Running query: %s', sql)
                cur.execute(sql, {'isbn': isbn, 'genre': genre.strip()})

        finally:
            con.close()

    def delete_book(self, isbn):
        self.logger.info('Deleting book with ISBN %s', isbn)
        try:
            con = sqlite3.connect(self.database_name, autocommit=True)
            cur = con.cursor()

            # update books
            sql = 'DELETE FROM books WHERE ISBN = ?'
            self.logger.debug('Running query: %s', sql)
            cur.execute(sql, (isbn,))

            # update genres
            sql = 'DELETE FROM genres WHERE ISBN = ?'
            self.logger.debug('Running query: %s', sql)
            cur.execute(sql, (isbn,))

        finally:
            con.close()
