<?php
/**
 * Script utilitas untuk menghasilkan hash password yang aman.
 * 
 * [Password Hashing] Menggunakan algoritma bcrypt (PASSWORD_DEFAULT) 
 * untuk memastikan penyimpanan kredensial login yang aman di database.
 * Hash ini nantinya digunakan dalam proses autentikasi dan pengecekan 
 * Role Based Access Control (RBAC) saat session management dimulai.
 */
echo password_hash("user123", PASSWORD_DEFAULT);
?>