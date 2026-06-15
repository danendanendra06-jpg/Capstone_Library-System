package com.capstone.repository;

import com.capstone.model.BookReturn;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface BookReturnRepository extends JpaRepository<BookReturn, Long> {
}
