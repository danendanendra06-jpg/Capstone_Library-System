package com.capstone.controller;

import com.capstone.model.Book;
import com.capstone.service.BookService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/books")
public class BookController {
    @Autowired
    private BookService service;

    @GetMapping
    public ResponseEntity<Page<Book>> getAll(
            @RequestParam(required = false) String title,
            @RequestParam(required = false) Long categoryId,
            @RequestParam(required = false) String sortCustom,
            Pageable pageable) {
        if (title != null && !title.isEmpty()) {
            return ResponseEntity.ok(service.searchBooks(title, pageable));
        }
        if ("popular".equalsIgnoreCase(sortCustom)) {
            return ResponseEntity.ok(service.getPopularBooks(pageable));
        }
        return ResponseEntity.ok(service.getAll(categoryId, pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<Book> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @PostMapping
    @PreAuthorize("hasRole('ADMIN') or hasRole('LIBRARIAN')")
    public ResponseEntity<Book> create(@RequestBody Book book) {
        return ResponseEntity.ok(service.save(book));
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN') or hasRole('LIBRARIAN')")
    public ResponseEntity<Book> update(@PathVariable Long id, @RequestBody Book book) {
        Book existing = service.getById(id);
        book.setId(id);
        return ResponseEntity.ok(service.save(book));
    }

    @DeleteMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN')")
    public ResponseEntity<Void> delete(@PathVariable Long id) {
        service.delete(id);
        return ResponseEntity.noContent().build();
    }
}
