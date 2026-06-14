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
    public ResponseEntity<?> borrowBook(@RequestBody Map<String, Object> payload) {
        Object bookIdObj = payload.get("bookId");
        if (bookIdObj == null) {
            return ResponseEntity.badRequest().body(Map.of("message", "Book ID is required"));
        }
        Long bookId = Long.valueOf(bookIdObj.toString());

        User user = getCurrentUser();
        if (user == null) {
            return ResponseEntity.status(401).build();
        }

        if ("SUSPENDED".equals(user.getStatus())) {
            return ResponseEntity.status(403).body(Map.of("message", "Akun Anda ditangguhkan (SUSPENDED). Tidak dapat melakukan peminjaman."));
        }

        Book book = bookRepo.findById(bookId).orElse(null);
        if (book == null) {
            return ResponseEntity.notFound().build();
        }

        if (book.getAvailableCopies() == null || book.getAvailableCopies() <= 0) {
            return ResponseEntity.badRequest().body(Map.of("message", "Stok buku habis."));
        }

        List<BorrowTransaction> existingBorrows = borrowRepo.findByUserIdAndBookIdAndStatus(user.getId(), bookId, "BORROWED");
        if (!existingBorrows.isEmpty()) {
            return ResponseEntity.badRequest().body(Map.of("message", "Anda masih meminjam buku ini dan belum mengembalikannya."));
        }

        BorrowTransaction transaction = new BorrowTransaction();
        transaction.setUser(user);
        transaction.setBook(book);
        Date now = new Date();
        transaction.setBorrowDate(now);
        
        Object dueDateObj = payload.get("dueDate");
        if (dueDateObj != null) {
            try {
                java.time.Instant instant = java.time.Instant.parse(dueDateObj.toString());
                Date dueDate = Date.from(instant);

                // Validation: due date not before today (ignore hours/minutes, just roughly check)
                if (dueDate.before(now) && !isSameDay(dueDate, now)) {
                    return ResponseEntity.badRequest().body(Map.of("message", "Tanggal pengembalian tidak boleh sebelum hari ini."));
                }
                
                // Max 14 days
                long FOURTEEN_DAYS = 14L * 24 * 60 * 60 * 1000;
                Date maxDate = new Date(now.getTime() + FOURTEEN_DAYS);
                if (dueDate.after(maxDate) && !isSameDay(dueDate, maxDate)) {
                    return ResponseEntity.badRequest().body(Map.of("message", "Tanggal pengembalian maksimal 14 hari dari hari ini."));
                }

                transaction.setExpectedReturnDate(dueDate);
            } catch (Exception e) {
                long FOURTEEN_DAYS = 14L * 24 * 60 * 60 * 1000;
                transaction.setExpectedReturnDate(new Date(now.getTime() + FOURTEEN_DAYS));
            }
        } else {
            long FOURTEEN_DAYS = 14L * 24 * 60 * 60 * 1000;
            transaction.setExpectedReturnDate(new Date(now.getTime() + FOURTEEN_DAYS));
        }
        
        transaction.setStatus("BORROWED");
        
        // Decrease stock
        book.setAvailableCopies(book.getAvailableCopies() - 1);
        bookRepo.save(book);

        borrowRepo.save(transaction);

        return ResponseEntity.ok(transaction);
    }

    private boolean isSameDay(Date date1, Date date2) {
        java.time.LocalDate localDate1 = date1.toInstant().atZone(java.time.ZoneId.systemDefault()).toLocalDate();
        java.time.LocalDate localDate2 = date2.toInstant().atZone(java.time.ZoneId.systemDefault()).toLocalDate();
        return localDate1.isEqual(localDate2);
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
