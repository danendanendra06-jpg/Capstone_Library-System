package com.capstone.controller;

import com.capstone.model.User;
import com.capstone.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
@RequestMapping("/api/profile")
public class ProfileController {
    
    @Autowired
    private UserRepository userRepository;

    @GetMapping
    public ResponseEntity<?> getProfile() {
        Object principal = SecurityContextHolder.getContext().getAuthentication().getPrincipal();
        if (principal instanceof UserDetails) {
            String username = ((UserDetails) principal).getUsername();
            User user = userRepository.findByUsername(username).orElse(null);
            if (user != null) {
                return ResponseEntity.ok(user);
            }
        }
        return ResponseEntity.status(401).build();
    }

    @PutMapping
    public ResponseEntity<?> updateProfile(@org.springframework.web.bind.annotation.RequestBody java.util.Map<String, String> payload) {
        Object principal = SecurityContextHolder.getContext().getAuthentication().getPrincipal();
        if (principal instanceof UserDetails) {
            String username = ((UserDetails) principal).getUsername();
            User user = userRepository.findByUsername(username).orElse(null);
            if (user != null) {
                if (payload.containsKey("email")) user.setEmail(payload.get("email"));
                // Can't update username easily because it's used in JWT, but let's allow it if we want.
                // Or just name if user has a name field. Wait, User only has username and email.
                userRepository.save(user);
                return ResponseEntity.ok(user);
            }
        }
        return ResponseEntity.status(401).build();
    }

    @PutMapping("/password")
    public ResponseEntity<?> updatePassword(@org.springframework.web.bind.annotation.RequestBody java.util.Map<String, String> payload, @Autowired org.springframework.security.crypto.password.PasswordEncoder passwordEncoder) {
        Object principal = SecurityContextHolder.getContext().getAuthentication().getPrincipal();
        if (principal instanceof UserDetails) {
            String username = ((UserDetails) principal).getUsername();
            User user = userRepository.findByUsername(username).orElse(null);
            if (user != null) {
                String newPassword = payload.get("password");
                if (newPassword != null && !newPassword.isEmpty()) {
                    user.setPassword(passwordEncoder.encode(newPassword));
                    userRepository.save(user);
                    return ResponseEntity.ok().body("{\"message\": \"Password updated successfully\"}");
                }
            }
        }
        return ResponseEntity.status(401).build();
    }
}
