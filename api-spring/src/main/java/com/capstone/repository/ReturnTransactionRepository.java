package com.capstone.repository;

import com.capstone.model.ReturnTransaction;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ReturnTransactionRepository extends JpaRepository<ReturnTransaction, Long> {
}
