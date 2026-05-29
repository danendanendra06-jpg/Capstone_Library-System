package com.capstone.controller;

import com.capstone.model.BorrowTransaction;
import com.capstone.service.BorrowTransactionService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/borrow-transactions")
@PreAuthorize("hasRole('USER') or hasRole('LIBRARIAN') or hasRole('ADMIN')")
public class BorrowTransactionController {
    @Autowired
    private BorrowTransactionService service;

    @GetMapping
    public ResponseEntity<Page<BorrowTransaction>> getAll(Pageable pageable) {
        return ResponseEntity.ok(service.getAll(pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<BorrowTransaction> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @PostMapping
    public ResponseEntity<BorrowTransaction> create(@RequestBody BorrowTransaction borrowTransaction) {
        return ResponseEntity.ok(service.save(borrowTransaction));
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<BorrowTransaction> update(@PathVariable Long id, @RequestBody BorrowTransaction borrowTransaction) {
        BorrowTransaction existing = service.getById(id);
        borrowTransaction.setId(id);
        return ResponseEntity.ok(service.save(borrowTransaction));
    }

    @DeleteMapping("/{id}")
    @PreAuthorize("hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<Void> delete(@PathVariable Long id) {
        service.delete(id);
        return ResponseEntity.noContent().build();
    }
}
