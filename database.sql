CREATE DATABASE IF NOT EXISTS rosea_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rosea_store;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,full_name VARCHAR(150) NOT NULL,email VARCHAR(190) NOT NULL UNIQUE,phone VARCHAR(30),password_hash VARCHAR(255) NOT NULL,role ENUM('customer','admin') NOT NULL DEFAULT 'customer',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE products(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(190) NOT NULL,category VARCHAR(60) NOT NULL,description TEXT,price BIGINT UNSIGNED NOT NULL,stock INT NOT NULL DEFAULT 0,image VARCHAR(255) NOT NULL,rating DECIMAL(2,1) DEFAULT 5,is_featured TINYINT(1) DEFAULT 0,is_active TINYINT(1) DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE articles(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(255) NOT NULL,image VARCHAR(255) NOT NULL,body MEDIUMTEXT NOT NULL,is_published TINYINT(1) DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE reviews(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,product_id INT NOT NULL,rating TINYINT NOT NULL,body TEXT NOT NULL,is_approved TINYINT(1) DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id),FOREIGN KEY(product_id) REFERENCES products(id));
CREATE TABLE orders(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,total_amount BIGINT UNSIGNED NOT NULL,status VARCHAR(30) DEFAULT 'pending',shipping_name VARCHAR(150),shipping_phone VARCHAR(30),shipping_address TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id));
CREATE TABLE order_items(id INT AUTO_INCREMENT PRIMARY KEY,order_id INT NOT NULL,product_id INT NOT NULL,quantity INT NOT NULL,unit_price BIGINT UNSIGNED NOT NULL,FOREIGN KEY(order_id) REFERENCES orders(id),FOREIGN KEY(product_id) REFERENCES products(id));

-- حساب مدیر اولیه برای اجرای محلی پروژه؛ پس از ورود، رمز را تغییر دهید.
INSERT INTO users(full_name,email,phone,password_hash,role) VALUES('مدیر رُزآرا','admin@roseara.local','', '$2y$12$UfxoNd8fWVrFJwxWMsyFj.gx8mxLSeju6HhD6tKNa.ryDoHby4KYC','admin') ON DUPLICATE KEY UPDATE role='admin';
INSERT INTO products(name,category,description,price,stock,image,rating,is_featured) VALUES
('عطر زنانه رُزآرا','perfume','رایحه‌ای لطیف و ماندگار برای استفاده روزانه و لحظه‌های خاص.',2499000,12,'img/product-perfume.jpg',4.9,1),
('کرم مرطوب‌کننده روز','hydration','بافت سبک برای کمک به حفظ رطوبت و لطافت پوست.',1299000,18,'img/product-cream.jpg',4.8,1),
('تونر و میست صورت','cleansing','برای تکمیل روتین پاک‌سازی و طراوت روزانه پوست.',899000,20,'img/category-cleanse.jpg',4.7,1),
('سرم آبرسان پوست','hydration','سرم سبک آبرسان برای روتین مراقبت روزانه.',1299000,15,'img/product-serum.jpg',4.8,1),
('ضدآفتاب SPF50+','protection','محافظت روزانه در برابر پرتوهای خورشید.',999000,24,'img/product-sunscreen.jpg',4.7,1),
('کرم شب تغذیه‌کننده','antiaging','کرم شب برای مراقبت و نرمی پوست.',1199000,10,'img/product-cream.jpg',4.6,1),
('خوشبوکننده خانه','home-fragrance','رایحه‌ای آرامش‌بخش برای فضای خانه.',799000,14,'img/banner-homefragrance.jpg',4.8,1),
('سرم ویتامین C','brightening','سرم مراقبتی برای کمک به شفافیت ظاهری پوست.',1599000,8,'img/product-serum.jpg',4.8,1),
('فوم شوینده صورت ملایم','cleansing','پاک‌کننده ملایم برای استفاده روزانه.',499000,22,'img/category-cleanse.jpg',4.7,0),
('سرم نیاسینامید','special','محصول مراقبت تخصصی برای روتین پوست.',1399000,9,'img/product-serum.jpg',4.7,0);
INSERT INTO articles(title,image,body) VALUES
('راهنمای ساخت روتین مراقبت پوست','img/magazine-skincare.jpg','برای ساخت یک روتین مؤثر، ابتدا نوع و نیازهای پوست خود را بشناسید. محصولات را به‌آرامی و مرحله‌به‌مرحله وارد برنامه کنید و به دستور مصرف هر محصول توجه داشته باشید.'),
('راهنمای انتخاب عطر متناسب با سلیقه شما','img/magazine-perfume.jpg','در انتخاب عطر به خانواده بویایی، موقعیت استفاده و سلیقه شخصی توجه کنید. بهتر است رایحه را روی پوست امتحان کنید و پس از گذشت زمان، نت‌های پایانی آن را نیز بسنجید.'),
('آشنایی با سرم‌های پوستی','img/magazine-serum.jpg','سرم‌ها معمولاً برای هدف‌های مراقبتی مشخص طراحی می‌شوند. ترکیبات و روش مصرف را بررسی کنید و از ترکیب هم‌زمان محصولات فعال بدون آگاهی خودداری کنید.'),
('نکاتی برای انتخاب محصولات مراقبت پوست','img/magazine-flowers.jpg','به ترکیبات، تاریخ مصرف، سازگاری محصول با نیاز پوست و دستورالعمل سازنده توجه کنید. در صورت حساسیت یا نگرانی پوستی، از متخصص مشورت بگیرید.');
