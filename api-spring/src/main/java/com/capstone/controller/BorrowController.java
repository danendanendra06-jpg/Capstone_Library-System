package com.capstone.controller;

import com.capstone.model.Book;
import com.capstone.model.BorrowTransaction;
import com.capstone.model.User;
import com.capstone.repository.BookRepository;
import com.capstone.repository.BorrowTransactionRepository;
import com.capstone.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.web.bind.annotation.*;

import java.util.Date;
import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api")
public class BorrowController {

    @Autowired
    private BorrowTransactionRepository borrowRepo;

    @Autowired
    private BookRepository bookRepo;

    @Autowired
    private UserRepository userRepo;

    private User getCurrentUser() {
        Object principal = SecurityContextHolder.getContext().getAuthentication().getPrincipal();
        if (principal instanceof UserDetails) {
            String username = ((UserDetails) principal).getUsername();
            return userRepo.findByUsername(username).orElse(null);
        }
        return null;
    }

    @PostMapping("/borrow")
    public ResponseEntity<?> borrowBook(@RequestBody Map<String, Long> payload) {
        Long bookId = payload.get("bookId");
        if (bookId == null) {
            return ResponseEntity.badRequest().body("Book ID is required");
        }

        User user = getCurrentUser();
        if (user == null) {
            return ResponseEntity.status(401).build();
        }

        Book book = bookRepo.findById(bookId).orElse(null);
        if (book == null) {
            return ResponseEntity.notFound().build();
        }

        BorrowTransaction transaction = new BorrowTransaction();
        transaction.setUser(user);
        transaction.setBook(book);
        transaction.setBorrowDate(new Date());
        
        long FOURTEEN_DAYS = 14L * 24 * 60 * 60 * 1000;
        transaction.setExpectedReturnDate(new Date(System.currentTimeMillis() + FOURTEEN_DAYS));
        
        transaction.setStatus("BORROWED");

        borrowRepo.save(transaction);

        return ResponseEntity.ok(transaction);
    }

    @GetMapping("/transactions")
    public ResponseEntity<?> getTransactions() {
        User user = getCurrentUser();
        if (user == null) {
            return ResponseEntity.status(401).build();
        }

        List<BorrowTransaction> transactions = borrowRepo.findByUserId(user.getId());
        return ResponseEntity.ok(transactions);
    }
}
