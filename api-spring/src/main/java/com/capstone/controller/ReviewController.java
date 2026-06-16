package com.capstone.controller;

import com.capstone.model.Review;
import com.capstone.service.ReviewService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/reviews")
public class ReviewController {
    @Autowired
    private ReviewService service;

    @GetMapping
    public ResponseEntity<Page<Review>> getAll(@RequestParam(required = false) Long bookId, Pageable pageable) {
        if (bookId != null) {
            return ResponseEntity.ok(service.getByBookId(bookId, pageable));
        }
        return ResponseEntity.ok(service.getAll(pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<Review> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @Autowired
    private com.capstone.service.BookService bookService;

    @Autowired
    private com.capstone.repository.UserRepository userRepo;

    private com.capstone.model.User getCurrentUser() {
        Object principal = org.springframework.security.core.context.SecurityContextHolder.getContext().getAuthentication().getPrincipal();
        if (principal instanceof org.springframework.security.core.userdetails.UserDetails) {
            String username = ((org.springframework.security.core.userdetails.UserDetails) principal).getUsername();
            return userRepo.findByUsername(username).orElse(null);
        }
        return null;
    }

    public static class ReviewRequest {
        public Long bookId;
        public Integer rating;
        public String comment;
    }

    @PostMapping
    @PreAuthorize("hasRole('USER')")
    public ResponseEntity<?> create(@RequestBody ReviewRequest req) {
        com.capstone.model.User user = getCurrentUser();
        if (user == null) {
            return ResponseEntity.status(401).body("Unauthorized");
        }
        
        com.capstone.model.Book book = bookService.getById(req.bookId);
        if (book == null) {
            return ResponseEntity.badRequest().body("Book not found");
        }

        Review review = new Review();
        review.setRating(req.rating);
        review.setComment(req.comment);
        review.setUser(user);
        review.setBook(book);

        return ResponseEntity.ok(service.save(review));
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('USER') or hasRole('ADMIN')")
    public ResponseEntity<Review> update(@PathVariable Long id, @RequestBody Review review) {
        Review existing = service.getById(id);
        review.setId(id);
        return ResponseEntity.ok(service.save(review));
    }

    @DeleteMapping("/{id}")
    @PreAuthorize("hasRole('USER') or hasRole('ADMIN')")
    public ResponseEntity<Void> delete(@PathVariable Long id) {
        service.delete(id);
        return ResponseEntity.noContent().build();
    }
}
