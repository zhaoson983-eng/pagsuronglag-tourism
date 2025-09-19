-- Add stock management columns to products table
ALTER TABLE products ADD COLUMN stock_limit INT DEFAULT 0;
ALTER TABLE products ADD COLUMN current_stock INT DEFAULT 0;

-- Update existing products to have default stock values
UPDATE products SET stock_limit = 100, current_stock = 50 WHERE stock_limit IS NULL OR current_stock IS NULL;
