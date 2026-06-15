package com.capstone.controller;

import com.capstone.model.Borrow;
import com.capstone.service.BorrowService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/borrow-transactions")
@PreAuthorize("hasRole('USER') or hasRole('LIBRARIAN') or hasRole('ADMIN')")
public class BorrowAdminController {
    @Autowired
    private BorrowService service;

    @GetMapping
    public ResponseEntity<Page<Borrow>> getAll(Pageable pageable) {
        return ResponseEntity.ok(service.getAll(pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<Borrow> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @PostMapping
    public ResponseEntity<Borrow> create(@RequestBody Borrow borrowTransaction) {
        return ResponseEntity.ok(service.save(borrowTransaction));
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<Borrow> update(@PathVariable Long id, @RequestBody Borrow borrowTransaction) {
        Borrow existing = service.getById(id);
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
