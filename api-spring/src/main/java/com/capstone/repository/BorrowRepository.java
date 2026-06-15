package com.capstone.repository;

import com.capstone.model.Borrow;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface BorrowRepository extends JpaRepository<Borrow, Long> {
    List<Borrow> findByUserId(Long userId);
    List<Borrow> findByUserIdAndBookIdAndStatus(Long userId, Long bookId, String status);
}
