package com.capstone.model;

import jakarta.persistence.*;
import java.util.Date;

@Entity
@Table(name = "returns")
public class BookReturn {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @OneToOne
    @JoinColumn(name = "borrow_id")
    private Borrow borrowTransaction;

    private Date returnDate;
    private String conditionOnReturn;

    @PrePersist
    protected void onCreate() { returnDate = new Date(); }

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public Borrow getBorrow() { return borrowTransaction; }
    public void setBorrow(Borrow borrowTransaction) { this.borrowTransaction = borrowTransaction; }
    public Date getReturnDate() { return returnDate; }
    public void setReturnDate(Date returnDate) { this.returnDate = returnDate; }
    public String getConditionOnReturn() { return conditionOnReturn; }
    public void setConditionOnReturn(String conditionOnReturn) { this.conditionOnReturn = conditionOnReturn; }
}
