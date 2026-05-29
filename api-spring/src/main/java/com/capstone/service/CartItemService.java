package com.capstone.service;

import com.capstone.model.CartItem;
import com.capstone.repository.CartItemRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class CartItemService {
    @Autowired
    private CartItemRepository repository;

    public Page<CartItem> getAll(Pageable pageable) { return repository.findAll(pageable); }

    public CartItem getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("CartItem not found"));
    }

    public CartItem save(CartItem cartItem) { return repository.save(cartItem); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
