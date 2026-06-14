INSERT INTO categories (id, name, description) VALUES
(2, 'Fiction', 'Fictional stories and novels'),
(3, 'Science & Technology', 'Books about science, programming, and technology'),
(4, 'History', 'Historical events and biographies'),
(5, 'Fantasy', 'Magical and fantasy worlds')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO books (title, author, category_id, isbn, stock, cover_image, available_copies, total_copies) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 2, '9780743273565', 5, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=600&auto=format&fit=crop', 5, 5),
('A Brief History of Time', 'Stephen Hawking', 3, '9780553380163', 3, 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=600&auto=format&fit=crop', 3, 3),
('Sapiens', 'Yuval Noah Harari', 4, '9780062316097', 8, 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?q=80&w=600&auto=format&fit=crop', 8, 8),
('Harry Potter and the Sorcerers Stone', 'J.K. Rowling', 5, '9780590353427', 10, 'https://images.unsplash.com/photo-1618666012174-83b441c0bc76?q=80&w=600&auto=format&fit=crop', 10, 10),
('1984', 'George Orwell', 2, '9780451524935', 7, 'https://images.unsplash.com/photo-1535905557558-afc4877a26fc?q=80&w=600&auto=format&fit=crop', 7, 7),
('Clean Code', 'Robert C. Martin', 3, '9780132350884', 4, 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=600&auto=format&fit=crop', 4, 4),
('The Hobbit', 'J.R.R. Tolkien', 5, '9780345339683', 6, 'https://images.unsplash.com/photo-1608605333502-3112bdce8104?q=80&w=600&auto=format&fit=crop', 6, 6),
('The Art of War', 'Sun Tzu', 4, '9781590302255', 12, 'https://images.unsplash.com/photo-1587590227264-0ac64ce63ce8?q=80&w=600&auto=format&fit=crop', 12, 12),
('Atomic Habits', 'James Clear', 3, '9780735211292', 15, 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=600&auto=format&fit=crop', 15, 15),
('Dune', 'Frank Herbert', 5, '9780441172719', 8, 'https://images.unsplash.com/photo-1618336753974-aae8e04506aa?q=80&w=600&auto=format&fit=crop', 8, 8);
