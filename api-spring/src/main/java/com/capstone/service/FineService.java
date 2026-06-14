package com.capstone.service;

import com.capstone.model.Fine;
import com.capstone.repository.FineRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class FineService {
    @Autowired
    private FineRepository repository;

    public Page<Fine> getAll(Pageable pageable) { return repository.findAll(pageable); }
    public Page<Fine> getByUserId(Long userId, Pageable pageable) { return repository.findByUserId(userId, pageable); }

    public Fine getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Fine not found"));
    }

    public Fine save(Fine fine) { return repository.save(fine); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
