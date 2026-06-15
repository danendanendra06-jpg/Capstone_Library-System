package com.capstone.service;

import com.capstone.model.BookReturn;
import com.capstone.repository.BookReturnRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class BookReturnService {
    @Autowired
    private BookReturnRepository repository;

    public Page<BookReturn> getAll(Pageable pageable) { return repository.findAll(pageable); }

    public BookReturn getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("BookReturn not found"));
    }

    public BookReturn save(BookReturn returnTransaction) { return repository.save(returnTransaction); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
