package com.capstone.controller;

import com.capstone.model.Cart;
import com.capstone.service.CartService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/carts")
@PreAuthorize("hasRole('USER') or hasRole('ADMIN')")
public class CartController {
    @Autowired
    private CartService service;

    @GetMapping
    public ResponseEntity<Page<Cart>> getAll(Pageable pageable) {
        return ResponseEntity.ok(service.getAll(pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<Cart> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @PostMapping
    public ResponseEntity<Cart> create(@RequestBody Cart cart) {
        return ResponseEntity.ok(service.save(cart));
    }

    @PutMapping("/{id}")
    public ResponseEntity<Cart> update(@PathVariable Long id, @RequestBody Cart cart) {
        Cart existing = service.getById(id);
        cart.setId(id);
        return ResponseEntity.ok(service.save(cart));
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Void> delete(@PathVariable Long id) {
        service.delete(id);
        return ResponseEntity.noContent().build();
    }
}
