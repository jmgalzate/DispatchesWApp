DROP TABLE IF EXISTS message_type CASCADE;
DROP TABLE IF EXISTS log CASCADE;
DROP TABLE IF EXISTS message CASCADE;
DROP TABLE IF EXISTS product CASCADE;
DROP TABLE IF EXISTS delivery CASCADE;
DROP TABLE IF EXISTS orders CASCADE;

CREATE TABLE message_type
(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE message
(
    id SERIAL PRIMARY KEY,
    message_type INT NOT NULL,
    order_number INT DEFAULT NULL,
    endpoint VARCHAR(255) NOT NULL,
    http_status INT NOT NULL,
    payload JSON NOT NULL,
    response JSON NOT NULL,
    created_at TIMESTAMP NOT NULL,
    FOREIGN KEY (message_type) REFERENCES message_type(id)
);

CREATE TABLE log
(
    id SERIAL PRIMARY KEY,
    log_type INT NOT NULL,
    log_details JSON NOT NULL,
    created_at TIMESTAMP NOT NULL
);

CREATE TABLE product
(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    barcode VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE NOT NULL
);

INSERT INTO message_type (id, name) VALUES
(1, 'Get Auth'),
(2, 'Unprocess'),
(3, 'Load'),
(4, 'Save'),
(5, 'Taxes'),
(6, 'Process'),
(7, 'Get Products'),
(8, 'Close Agent');

CREATE TABLE delivery
(
    id SERIAL PRIMARY KEY,
    order_number INT NOT NULL,
    customer_id BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    total_requested INT NOT NULL,
    total_dispatched INT NOT NULL,
    efficiency DECIMAL(5,4) NOT NULL,
    products_list JSON NOT NULL,
    is_dispatched BOOLEAN NOT NULL
);

CREATE TABLE orders
(
    id SERIAL PRIMARY KEY,
    order_number INT NOT NULL,
    encabezado JSON NOT NULL,
    liquidacion JSON NOT NULL,
    datos_principales JSON NOT NULL,
    lista_productos JSON NOT NULL,
    qoprsok VARCHAR(10) NOT NULL
);

CREATE INDEX idx_product_barcode ON product(barcode);
CREATE INDEX idx_product_code ON product(code);