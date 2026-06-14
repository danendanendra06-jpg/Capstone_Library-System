package com.capstone.model;

import jakarta.persistence.*;
import java.util.Date;

@Entity
@Table(name = "borrow_transactions")
public class BorrowTransaction {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne
    @JoinColumn(name = "user_id")
    private User user;

    @ManyToOne
    @JoinColumn(name = "book_id")
    private Book book;

    private Date borrowDate;
    @Column(name = "due_date")
    private Date expectedReturnDate;
    
    private String status; // BORROWED, RETURNED, DAMAGED, LOST
    
    @Column(name = "return_condition")
    private String returnCondition; // GOOD, DAMAGED, LOST
    
    @Column(name = "late_days")
    private Integer lateDays = 0;
    
    @Transient
    private Double fineAmount = 0.0;

    @PrePersist
    protected void onCreate() { borrowDate = new Date(); }

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public User getUser() { return user; }
    public void setUser(User user) { this.user = user; }
    public Book getBook() { return book; }
    public void setBook(Book book) { this.book = book; }
    public Date getBorrowDate() { return borrowDate; }
    public void setBorrowDate(Date borrowDate) { this.borrowDate = borrowDate; }
    public Date getExpectedReturnDate() { return expectedReturnDate; }
    public void setExpectedReturnDate(Date expectedReturnDate) { this.expectedReturnDate = expectedReturnDate; }
    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }
    public String getReturnCondition() { return returnCondition; }
    public void setReturnCondition(String returnCondition) { this.returnCondition = returnCondition; }
    public Integer getLateDays() { return lateDays; }
    public void setLateDays(Integer lateDays) { this.lateDays = lateDays; }
    @OneToMany(mappedBy = "borrowTransaction", cascade = CascadeType.ALL, fetch = FetchType.EAGER)
    private java.util.List<Fine> fines;

    public Double getFineAmount() { 
        if (fines == null || fines.isEmpty()) return 0.0;
        return fines.stream()
            .map(f -> f.getAmount().doubleValue())
            .reduce(0.0, Double::sum);
    }
    public void setFineAmount(Double fineAmount) { this.fineAmount = fineAmount; }
}
