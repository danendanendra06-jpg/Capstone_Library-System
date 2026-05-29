package com.capstone.controller;

import com.capstone.model.CartItem;
import com.capstone.service.CartItemService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/cart-items")
@PreAuthorize("hasRole('USER') or hasRole('ADMIN')")
public class CartItemController {
    @Autowired
    private CartItemService service;

    @GetMapping
    public ResponseEntity<Page<CartItem>> getAll(Pageable pageable) {
        return ResponseEntity.ok(service.getAll(pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<CartItem> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @PostMapping
    public ResponseEntity<CartItem> create(@RequestBody CartItem cartItem) {
        return ResponseEntity.ok(service.save(cartItem));
    }

    @PutMapping("/{id}")
    public ResponseEntity<CartItem> update(@PathVariable Long id, @RequestBody CartItem cartItem) {
        CartItem existing = service.getById(id);
        cartItem.setId(id);
        return ResponseEntity.ok(service.save(cartItem));
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Void> delete(@PathVariable Long id) {
        service.delete(id);
        return ResponseEntity.noContent().build();
    }
}
