package com.capstone.controller;

import com.capstone.model.ReturnTransaction;
import com.capstone.service.ReturnTransactionService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/return-transactions")
@PreAuthorize("hasRole('USER') or hasRole('LIBRARIAN') or hasRole('ADMIN')")
public class ReturnTransactionController {
    @Autowired
    private ReturnTransactionService service;

    @GetMapping
    public ResponseEntity<Page<ReturnTransaction>> getAll(Pageable pageable) {
        return ResponseEntity.ok(service.getAll(pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<ReturnTransaction> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @PostMapping
    @PreAuthorize("hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<ReturnTransaction> create(@RequestBody ReturnTransaction returnTransaction) {
        return ResponseEntity.ok(service.save(returnTransaction));
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<ReturnTransaction> update(@PathVariable Long id, @RequestBody ReturnTransaction returnTransaction) {
        ReturnTransaction existing = service.getById(id);
        returnTransaction.setId(id);
        return ResponseEntity.ok(service.save(returnTransaction));
    }

    @DeleteMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN')")
    public ResponseEntity<Void> delete(@PathVariable Long id) {
        service.delete(id);
        return ResponseEntity.noContent().build();
    }
}
