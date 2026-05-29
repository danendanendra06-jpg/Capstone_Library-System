package com.capstone.service;

import com.capstone.model.ReturnTransaction;
import com.capstone.repository.ReturnTransactionRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class ReturnTransactionService {
    @Autowired
    private ReturnTransactionRepository repository;

    public Page<ReturnTransaction> getAll(Pageable pageable) { return repository.findAll(pageable); }

    public ReturnTransaction getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("ReturnTransaction not found"));
    }

    public ReturnTransaction save(ReturnTransaction returnTransaction) { return repository.save(returnTransaction); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
