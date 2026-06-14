package com.capstone.service;

import com.capstone.model.Review;
import com.capstone.repository.ReviewRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class ReviewService {
    @Autowired
    private ReviewRepository repository;

    public Page<Review> getAll(Pageable pageable) { return repository.findAll(pageable); }
    public Page<Review> getByBookId(Long bookId, Pageable pageable) { return repository.findByBookId(bookId, pageable); }

    public Review getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Review not found"));
    }

    public Review save(Review review) { return repository.save(review); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
