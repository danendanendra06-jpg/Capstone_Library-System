package com.capstone.service;

import com.capstone.model.Cart;
import com.capstone.repository.CartRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class CartService {
    @Autowired
    private CartRepository repository;

    public Page<Cart> getAll(Pageable pageable) { return repository.findAll(pageable); }

    public Cart getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Cart not found"));
    }

    public Cart save(Cart cart) { return repository.save(cart); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
