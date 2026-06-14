package com.capstone.repository;

import com.capstone.model.Book;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import org.springframework.data.jpa.repository.JpaSpecificationExecutor;

@Repository
public interface BookRepository extends JpaRepository<Book, Long>, JpaSpecificationExecutor<Book> {
    Page<Book> findByTitleContainingIgnoreCase(String title, Pageable pageable);
    Page<Book> findByCategoryId(Long categoryId, Pageable pageable);
    
    @org.springframework.data.jpa.repository.Query("SELECT b FROM Book b LEFT JOIN BorrowTransaction t ON t.book = b GROUP BY b ORDER BY COUNT(t) DESC")
    Page<Book> findPopularBooks(Pageable pageable);
}
