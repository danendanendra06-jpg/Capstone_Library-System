package com.capstone.repository;

import com.capstone.model.Fine;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

@Repository
public interface FineRepository extends JpaRepository<Fine, Long> {
    Page<Fine> findByUserId(Long userId, Pageable pageable);
}
