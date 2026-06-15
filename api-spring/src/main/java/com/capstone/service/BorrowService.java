package com.capstone.service;

import com.capstone.model.Borrow;
import com.capstone.repository.BorrowRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class BorrowService {
    @Autowired
    private BorrowRepository repository;

    public Page<Borrow> getAll(Pageable pageable) { return repository.findAll(pageable); }

    public Borrow getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Borrow not found"));
    }

    public Borrow save(Borrow borrowTransaction) { return repository.save(borrowTransaction); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
