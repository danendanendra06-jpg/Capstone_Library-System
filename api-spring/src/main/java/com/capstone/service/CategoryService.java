package com.capstone.service;

import com.capstone.model.Category;
import com.capstone.repository.CategoryRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class CategoryService {
    @Autowired
    private CategoryRepository repository;

    public Page<Category> getAll(Pageable pageable) { return repository.findAll(pageable); }

    public Category getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Category not found"));
    }

    public Category save(Category category) { return repository.save(category); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
