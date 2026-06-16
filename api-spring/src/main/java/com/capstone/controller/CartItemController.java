package com.capstone.controller;

import com.capstone.model.Book;
import com.capstone.model.Cart;
import com.capstone.model.CartItem;
import com.capstone.model.User;
import com.capstone.repository.BookRepository;
import com.capstone.repository.CartItemRepository;
import com.capstone.repository.CartRepository;
import com.capstone.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.web.bind.annotation.*;

import java.util.ArrayList;
import java.util.List;

@RestController
@RequestMapping("/api/cart-items")
@PreAuthorize("hasRole('USER') or hasRole('ADMIN')")
public class CartItemController {

    @Autowired
    private CartItemRepository cartItemRepo;

    @Autowired
    private CartRepository cartRepo;

    @Autowired
    private UserRepository userRepo;

    @Autowired
    private BookRepository bookRepo;

    private User getCurrentUser() {
        Object principal = SecurityContextHolder.getContext().getAuthentication().getPrincipal();
        if (principal instanceof UserDetails) {
            String username = ((UserDetails) principal).getUsername();
            return userRepo.findByUsername(username).orElse(null);
        }
        return null;
    }

    private Cart getOrCreateCart(User user) {
        return cartRepo.findByUserId(user.getId()).orElseGet(() -> {
            Cart newCart = new Cart();
            newCart.setUser(user);
            newCart.setItems(new ArrayList<>());
            return cartRepo.save(newCart);
        });
    }

    @GetMapping
    public ResponseEntity<?> getMyCartItems() {
        User user = getCurrentUser();
        if (user == null) {
            return ResponseEntity.status(401).build();
        }

        Cart cart = getOrCreateCart(user);
        return ResponseEntity.ok(cart.getItems() != null ? cart.getItems() : new ArrayList<>());
    }

    @PostMapping
    public ResponseEntity<?> addItem(@RequestBody CartItem requestItem) {
        User user = getCurrentUser();
        if (user == null) {
            return ResponseEntity.status(401).build();
        }

        if (requestItem.getBook() == null || requestItem.getBook().getId() == null) {
            return ResponseEntity.badRequest().body("Book ID is required");
        }

        Book book = bookRepo.findById(requestItem.getBook().getId()).orElse(null);
        if (book == null) {
            return ResponseEntity.badRequest().body("Book not found");
        }

        Cart cart = getOrCreateCart(user);

        // Check if book already in cart
        boolean exists = cart.getItems().stream().anyMatch(i -> i.getBook().getId().equals(book.getId()));
        if (exists) {
            return ResponseEntity.badRequest().body("Book is already in the cart");
        }

        CartItem newItem = new CartItem();
        newItem.setCart(cart);
        newItem.setBook(book);
        cartItemRepo.save(newItem);

        return ResponseEntity.ok(newItem);
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<?> removeItem(@PathVariable Long id) {
        User user = getCurrentUser();
        if (user == null) {
            return ResponseEntity.status(401).build();
        }

        CartItem item = cartItemRepo.findById(id).orElse(null);
        if (item == null || !item.getCart().getUser().getId().equals(user.getId())) {
            return ResponseEntity.notFound().build();
        }

        cartItemRepo.delete(item);
        return ResponseEntity.noContent().build();
    }
}
