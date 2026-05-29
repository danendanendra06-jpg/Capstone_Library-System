package com.capstone.service;

import com.capstone.model.BorrowTransaction;
import com.capstone.repository.BorrowTransactionRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class BorrowTransactionService {
    @Autowired
    private BorrowTransactionRepository repository;

    public Page<BorrowTransaction> getAll(Pageable pageable) { return repository.findAll(pageable); }

    public BorrowTransaction getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("BorrowTransaction not found"));
    }

    public BorrowTransaction save(BorrowTransaction borrowTransaction) { return repository.save(borrowTransaction); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
