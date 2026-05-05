-- Enforce unique usernames for local + OAuth users (multiple NULL usernames remain allowed in MySQL).
ALTER TABLE `users`
  ADD UNIQUE KEY `uq_users_username` (`username`);
