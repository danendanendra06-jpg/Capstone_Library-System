package com.capstone.model;

import jakarta.persistence.*;
import java.util.Date;

@Entity
public class ReturnTransaction {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @OneToOne
    @JoinColumn(name = "borrow_id")
    private BorrowTransaction borrowTransaction;

    private Date returnDate;
    private String conditionOnReturn;

    @PrePersist
    protected void onCreate() { returnDate = new Date(); }

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public BorrowTransaction getBorrowTransaction() { return borrowTransaction; }
    public void setBorrowTransaction(BorrowTransaction borrowTransaction) { this.borrowTransaction = borrowTransaction; }
    public Date getReturnDate() { return returnDate; }
    public void setReturnDate(Date returnDate) { this.returnDate = returnDate; }
    public String getConditionOnReturn() { return conditionOnReturn; }
    public void setConditionOnReturn(String conditionOnReturn) { this.conditionOnReturn = conditionOnReturn; }
}
