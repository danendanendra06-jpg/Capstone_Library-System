package com.capstone.service;

import com.capstone.model.Book;
import com.capstone.repository.BookRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

import org.springframework.data.jpa.domain.Specification;
import jakarta.persistence.criteria.Predicate;
import java.util.ArrayList;
import java.util.List;

@Service
public class BookService {
    @Autowired
    private BookRepository repository;

    public Page<Book> getAll(Pageable pageable) { return repository.findAll(pageable); }
    
    public Page<Book> searchBooks(String keyword, Pageable pageable) {
        Specification<Book> spec = (root, query, cb) -> {
            if (keyword == null || keyword.trim().isEmpty()) {
                return cb.conjunction();
            }
            String[] words = keyword.trim().split("\\s+");
            List<Predicate> predicates = new ArrayList<>();
            for (String word : words) {
                String pattern = "%" + word.toLowerCase() + "%";
                Predicate titleLike = cb.like(cb.lower(root.get("title")), pattern);
                Predicate authorLike = cb.like(cb.lower(root.get("author")), pattern);
                Predicate isbnLike = cb.like(cb.lower(root.get("isbn")), pattern);
                predicates.add(cb.or(titleLike, authorLike, isbnLike));
            }
            return cb.and(predicates.toArray(new Predicate[0]));
        };
        return repository.findAll(spec, pageable);
    }

    public Book getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Book not found"));
    }

    public Book save(Book book) { return repository.save(book); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
