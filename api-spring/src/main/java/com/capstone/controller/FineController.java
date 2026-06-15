package com.capstone.controller;

import com.capstone.model.Fine;
import com.capstone.service.FineService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/fines")
@PreAuthorize("hasRole('USER') or hasRole('LIBRARIAN') or hasRole('ADMIN')")
public class FineController {
    @Autowired
    private FineService service;

    @Autowired
    private com.capstone.repository.UserRepository userRepo;

    private com.capstone.model.User getCurrentUser() {
        Object principal = org.springframework.security.core.context.SecurityContextHolder.getContext().getAuthentication().getPrincipal();
        if (principal instanceof org.springframework.security.core.userdetails.UserDetails) {
            String username = ((org.springframework.security.core.userdetails.UserDetails) principal).getUsername();
            return userRepo.findByUsername(username).orElse(null);
        }
        return null;
    }

    @GetMapping
    public ResponseEntity<Page<Fine>> getAll(Pageable pageable) {
        com.capstone.model.User user = getCurrentUser();
        if (user != null) {
            return ResponseEntity.ok(service.getByUserId(user.getId(), pageable));
        }
        return ResponseEntity.ok(service.getAll(pageable));
    }

    @GetMapping("/{id}")
    public ResponseEntity<Fine> getById(@PathVariable Long id) {
        return ResponseEntity.ok(service.getById(id));
    }

    @PostMapping
    @PreAuthorize("hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<Fine> create(@RequestBody Fine fine) {
        return ResponseEntity.ok(service.save(fine));
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<Fine> update(@PathVariable Long id, @RequestBody Fine fine) {
        Fine existing = service.getById(id);
        fine.setId(id);
        return ResponseEntity.ok(service.save(fine));
    }

    @DeleteMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN')")
    public ResponseEntity<Void> delete(@PathVariable Long id) {
        service.delete(id);
        return ResponseEntity.noContent().build();
    }

    @PostMapping("/{id}/pay")
    @PreAuthorize("hasRole('USER') or hasRole('LIBRARIAN') or hasRole('ADMIN')")
    public ResponseEntity<?> payFine(@PathVariable Long id, @RequestBody PaymentRequest request) {
        Fine fine = service.getById(id);
        if (fine == null) return ResponseEntity.notFound().build();
        if ("PAID".equals(fine.getPaymentStatus())) {
            return ResponseEntity.badRequest().body(new PaymentResponse(false, null, "Fine is already paid."));
        }

        if ("CASH".equalsIgnoreCase(request.method)) {
            if (request.amountPaid == null || request.amountPaid.compareTo(fine.getAmount()) < 0) {
                return ResponseEntity.badRequest().body(new PaymentResponse(false, null, "Insufficient cash amount."));
            }
            java.math.BigDecimal change = request.amountPaid.subtract(fine.getAmount());
            fine.setPaymentStatus("PAID");
            fine.setPaymentMethod("CASH");
            service.save(fine);
            String msg = change.compareTo(java.math.BigDecimal.ZERO) == 0 ? "Exact amount received." : "Payment successful. Change returned: " + change;
            return ResponseEntity.ok(new PaymentResponse(true, change, msg));
        } else {
            fine.setPaymentStatus("PAID");
            fine.setPaymentMethod(request.method);
            service.save(fine);
            return ResponseEntity.ok(new PaymentResponse(true, java.math.BigDecimal.ZERO, request.method + " payment processed successfully."));
        }
    }

    public static class PaymentRequest {
        public String method;
        public java.math.BigDecimal amountPaid;
    }

    public static class PaymentResponse {
        public boolean success;
        public java.math.BigDecimal change;
        public String message;
        public PaymentResponse(boolean success, java.math.BigDecimal change, String message) {
            this.success = success;
            this.change = change;
            this.message = message;
        }
    }
}
