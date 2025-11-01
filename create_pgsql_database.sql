-- Script untuk membuat database PostgreSQL
-- Jalankan dengan: psql -U postgres -f create_pgsql_database.sql

-- Buat database jika belum ada
CREATE DATABASE rs_sehat_selalu;

-- Beri akses (opsional)
-- GRANT ALL PRIVILEGES ON DATABASE rs_sehat_selalu TO postgres;

-- Connect ke database
\c rs_sehat_selalu

-- Buat extension jika diperlukan
-- CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

