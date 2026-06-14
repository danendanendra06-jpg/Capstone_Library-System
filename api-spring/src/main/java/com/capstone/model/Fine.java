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

    @OneToOne
    @JoinColumn(name = "return_id")
    private ReturnTransaction returnTransaction;

    private BigDecimal amount;
    private String reason;
    private Boolean isPaid;

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public User getUser() { return user; }
    public void setUser(User user) { this.user = user; }
    public ReturnTransaction getReturnTransaction() { return returnTransaction; }
    public void setReturnTransaction(ReturnTransaction returnTransaction) { this.returnTransaction = returnTransaction; }
    public BigDecimal getAmount() { return amount; }
    public void setAmount(BigDecimal amount) { this.amount = amount; }
    public String getReason() { return reason; }
    public void setReason(String reason) { this.reason = reason; }
    public Boolean getIsPaid() { return isPaid; }
    public void setIsPaid(Boolean isPaid) { this.isPaid = isPaid; }
}
