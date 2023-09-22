### The goal of this task is to create a simple RESTful application using PHP and Symfony, which will be used to manage a basic library system.

- **Models**:
  - Book: title, author, publication year, status (available/unavailable)  
  - Reader: first name, last name, email address, list of borrowed books
- **Functions**:   
  - Adding/Editing/Deleting/Browsing books  
  - Searching for books by title, author, publication year  
  - Adding/Editing/Deleting/Browsing readers  
  - Borrowing a book by a reader (this should automatically change the book's status to 'unavailable')  
  - Returning a book by a reader (this should automatically change the book's status to 'available')  
- **Validation**:  
  - All model fields must be valid before saving them to the database.  
  - A reader cannot borrow more than 3 books at a time.  
  - A book cannot be borrowed if its status is 'unavailable'.  
- **Transactionality**:  
  - Borrowing and returning book operations should be conducted as transactions.  
- **Testing**:  
  - Prepare unit and functional tests to verify the correctness of the above functions.

    
**Extra features**:  
- Using Docker to manage the application environment.
- Applying database migrations.
- Implementing authentication and authorization using JWT (JSON Web Tokens).
- Utilizing UUIDs instead of traditional IDs.