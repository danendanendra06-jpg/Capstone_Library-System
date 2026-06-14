package com.capstone.service;

import com.capstone.model.Notification;
import com.capstone.repository.NotificationRepository;
import com.capstone.exception.ResourceNotFoundException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;

@Service
public class NotificationService {
    @Autowired
    private NotificationRepository repository;

    public Page<Notification> getAll(Pageable pageable) { return repository.findAll(pageable); }
    public Page<Notification> getByUserId(Long userId, Pageable pageable) { return repository.findByUserId(userId, pageable); }

    public Notification getById(Long id) {
        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException("Notification not found"));
    }

    public Notification save(Notification notification) { return repository.save(notification); }

    public void delete(Long id) { repository.delete(getById(id)); }
}
