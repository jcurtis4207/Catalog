from flask import Flask, render_template, request, redirect, url_for
from db import Database

app = Flask(__name__)
db = Database()


@app.route('/')
def main():
    genres = db.fetch_all_genres()

    book_rows = db.fetch_books()

    return render_template('index.html',
                           book_rows=book_rows,
                           genres=genres,
                           row_total=len(book_rows)
                           )


@app.route('/add_book')
def add_book():
    return render_template('add_book.html')


@app.route('/add_book_process', methods=['POST'])
def process_add_book():
    db.add_book(request.form)
    return redirect(url_for('main'))


@app.route('/edit_book')
def edit_book():
    isbn = request.args.get('isbn')
    book = db.fetch_a_book(isbn)
    return render_template('edit_book.html', book=book)


@app.route('/edit_book_process', methods=['POST'])
def process_edit_book():
    db.update_book(request.form)
    return redirect(url_for('main'))


@app.route('/delete_book', methods=['GET'])
def delete_book():
    isbn = request.args.get('isbn')
    db.delete_book(isbn)
    return redirect(url_for('main'))
