package com.capstone.service;

import com.capstone.model.Book;
import com.capstone.repository.BookRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class BookService {
    @Autowired
    private BookRepository repository;

    public Page<Book> getAll(Pageable pageable) { return repository.findAll(pageable); }
    
    public Page<Book> searchBooks(String title, Pageable pageable) {
        return repository.findByTitleContainingIgnoreCase(title, pageable);
    }

    public Book getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Book not found"));
    }

    public Book save(Book book) { return repository.save(book); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
