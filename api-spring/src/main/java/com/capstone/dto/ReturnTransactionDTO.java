package com.capstone.dto;

import java.util.Date;

public class ReturnTransactionDTO {
    private Long id;
    private Long borrowTransactionId;
    private Date returnDate;

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public Long getBorrowTransactionId() { return borrowTransactionId; }
    public void setBorrowTransactionId(Long borrowTransactionId) { this.borrowTransactionId = borrowTransactionId; }
    public Date getReturnDate() { return returnDate; }
    public void setReturnDate(Date returnDate) { this.returnDate = returnDate; }
}
