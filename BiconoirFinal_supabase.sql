-- BiconoirFinal Database Schema for Supabase (PostgreSQL)
-- Updated based on Class Diagram & Inventory System

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    user_id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    phone TEXT,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 2. MenuItems Table
CREATE TABLE IF NOT EXISTS menu_items (
    item_id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    price NUMERIC(10, 2) NOT NULL,
    image_url TEXT,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 3. Ingredients Table
CREATE TABLE IF NOT EXISTS ingredients (
    sku_code TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    unit_of_measurement TEXT NOT NULL
);

-- 4. MenuItem Ingredients (Relationship: MenuItem references Ingredient)
CREATE TABLE IF NOT EXISTS menu_item_ingredients (
    item_id TEXT REFERENCES menu_items(item_id) ON DELETE CASCADE,
    sku_code TEXT REFERENCES ingredients(sku_code) ON DELETE CASCADE,
    quantity_required NUMERIC(10, 2) DEFAULT 0,
    PRIMARY KEY (item_id, sku_code)
);

-- 5. InventoryBatches Table (FIFO Tracking)
CREATE TABLE IF NOT EXISTS inventory_batches (
    batch_id TEXT PRIMARY KEY,
    sku_code TEXT REFERENCES ingredients(sku_code) ON DELETE CASCADE,
    quantity NUMERIC(10, 2) NOT NULL,
    cost_per_unit NUMERIC(10, 2) NOT NULL,
    purchase_date DATE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 6. Orders Table
CREATE TABLE IF NOT EXISTS orders (
    order_id TEXT PRIMARY KEY,
    customer_name TEXT NOT NULL,
    customer_email TEXT NOT NULL,
    order_date_time BIGINT NOT NULL,
    total_amount NUMERIC(10, 2) NOT NULL,
    transaction_fee NUMERIC(10, 2) DEFAULT 0,
    status TEXT NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 7. OrderDetails Table
CREATE TABLE IF NOT EXISTS order_details (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    order_id TEXT REFERENCES orders(order_id) ON DELETE CASCADE,
    item_id TEXT REFERENCES menu_items(item_id),
    quantity INTEGER NOT NULL,
    selling_price NUMERIC(10, 2) NOT NULL,
    ingredient_cost NUMERIC(10, 2) NOT NULL,
    removed_ingredients JSONB DEFAULT '[]',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 8. Reservations Table
CREATE TABLE IF NOT EXISTS reservations (
    reservation_id TEXT PRIMARY KEY,
    customer_id TEXT REFERENCES users(user_id) ON DELETE CASCADE,
    date DATE NOT NULL,
    time TEXT NOT NULL,
    number_of_people INTEGER NOT NULL,
    status TEXT NOT NULL DEFAULT 'Confirmed',
    notes TEXT, -- Columna añadida para peticiones especiales
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 9. AuditLogs Table
CREATE TABLE IF NOT EXISTS audit_logs (
    log_id TEXT PRIMARY KEY,
    timestamp TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    action_type TEXT NOT NULL,
    user_id TEXT REFERENCES users(user_id) ON DELETE SET NULL,
    details TEXT
);

-- 10. FinancialReports Table
CREATE TABLE IF NOT EXISTS financial_reports (
    report_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    simulation_days INTEGER NOT NULL,
    total_profit_loss NUMERIC(10, 2) NOT NULL,
    profit_loss_per_day NUMERIC(10, 2) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 11. Surveys Table
CREATE TABLE IF NOT EXISTS surveys (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    customer_name TEXT NOT NULL,
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- INITIAL DATA
INSERT INTO ingredients (sku_code, name, unit_of_measurement) VALUES
('ING-001', 'Carne de Res', 'kg'),
('ING-002', 'Pan Brioche', 'unidad'),
('ING-003', 'Queso Cheddar', 'kg'),
('ING-004', 'Lechuga Orgánica', 'unidad')
ON CONFLICT (sku_code) DO NOTHING;
