package com.capstone.model;

import jakarta.persistence.*;
import java.math.BigDecimal;

@Entity
@Table(name = "fines")
public class Fine {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne
    @JoinColumn(name = "user_id")
    private User user;

    @ManyToOne
    @JoinColumn(name = "borrow_id")
    @com.fasterxml.jackson.annotation.JsonIgnore
    private Borrow borrowTransaction;

    @Column(name = "fine_type")
    private String fineType; // LATE, DAMAGED, LOST

    @Column(name = "payment_status")
    private String paymentStatus = "UNPAID"; // UNPAID, PAID

    private BigDecimal amount;
    private String reason;

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public User getUser() { return user; }
    public void setUser(User user) { this.user = user; }
    public Borrow getBorrow() { return borrowTransaction; }
    public void setBorrow(Borrow borrowTransaction) { this.borrowTransaction = borrowTransaction; }
    public BigDecimal getAmount() { return amount; }
    public void setAmount(BigDecimal amount) { this.amount = amount; }
    public String getReason() { return reason; }
    public void setReason(String reason) { this.reason = reason; }
    public String getFineType() { return fineType; }
    public void setFineType(String fineType) { this.fineType = fineType; }
    public String getPaymentStatus() { return paymentStatus; }
    public void setPaymentStatus(String paymentStatus) { this.paymentStatus = paymentStatus; }
}
