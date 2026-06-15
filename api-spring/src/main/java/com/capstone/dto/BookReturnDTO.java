package com.capstone.dto;

import java.util.Date;

public class BookReturnDTO {
    private Long id;
    private Long borrowTransactionId;
    private Date returnDate;

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public Long getBorrowId() { return borrowTransactionId; }
    public void setBorrowId(Long borrowTransactionId) { this.borrowTransactionId = borrowTransactionId; }
    public Date getReturnDate() { return returnDate; }
    public void setReturnDate(Date returnDate) { this.returnDate = returnDate; }
}
