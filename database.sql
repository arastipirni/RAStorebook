CREATE DATABASE IF NOT EXISTS bookstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookstore;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE categories(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    sort_order INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books(
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(180) NOT NULL,
    published_year SMALLINT UNSIGNED NULL,
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    synopsis TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE orders(
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(40) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    phone VARCHAR(40) NOT NULL DEFAULT '',
    address TEXT NOT NULL,
    payment_method ENUM('COD','BANK') NOT NULL,
    bank_name VARCHAR(80) NULL,
    bank_account VARCHAR(80) NULL,
    transfer_proof VARCHAR(255) NULL,
    total DECIMAL(12,2) NOT NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'Menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    qty INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY(book_id) REFERENCES books(id) ON DELETE RESTRICT
);

CREATE TABLE messages(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories(name,sort_order) VALUES
('Mystery & Thriller',1),('Romance',2),('Fantasy',3),('Technology & Programming',4),('Comic',5);

INSERT INTO users(name,email,password,role) VALUES
('Admin Bookstore','bookstore@gmail.com','$2y$12$eRTRRwdfadB7NmcrRJDQPeRvbMeARmgX.t7zrFZkIUNDSW7JHv4j2','admin'),
('Demo User','user@bookstore.local','$2y$12$eoXY2SOn6g4ZNUFrE7PSd.NIoiaS1PXBLypwwmMCUrb1xOndHbV1S','user');

INSERT INTO books(category_id,title,author,price,stock,image,description,synopsis) VALUES
(2,'Metropop: Saat-Saat Jauh','Lia Seplia',30000,10,'jauh.jpeg','Novel ini mengisahkan tentang Aline, yang mengabdikan dirinya untuk mengurus Panti Jompo Jel di Kota Teduh. Aline rela menunggu Alex, sang kekasih, untuk mengejar impiannya menjadi dokter di Kota Terik.','Tema dan latar panti jompo yang diangkat memberikan nuansa tersendiri dalam novel setebal 280 halaman ini. Kisah ini juga memberikan cerita tentang keluarga dan pilihan hidup.'),
(3,'The Shark Caller','Zillah Bethell',90000,10,'caller.jpg','Kisah petualangan dan persahabatan yang membawa pembaca menyusuri dunia pulau, tradisi, dan keberanian.','Sebuah cerita hangat tentang perjalanan, keluarga, dan menemukan keberanian untuk menghadapi masa lalu.'),
(1,'The Ghost Cat','Alex Howard',95000,10,'catt.png','Cerita fantasi misteri tentang seekor kucing hantu dan rahasia yang tersimpan di balik rumah tua.','Perjalanan penuh misteri dengan sentuhan fantasi, keluarga, dan keberanian memecahkan teka-teki.'),
(5,'Hai, Miiko! 34','Ono Eriko',45000,10,'miiko.jpeg','Petualangan sehari-hari Miiko dan teman-temannya hadir dengan humor, persahabatan, dan kejadian-kejadian kecil yang menyenangkan.','Volume ini melanjutkan kisah Miiko dengan berbagai kejadian lucu dan hangat di sekolah serta lingkungan pertemanannya.'),
(4,'Pemrograman Android Dengan Android Studio Ide','Seng Hansun, Marcel Bonar Kristanda, Michael Wijaya Saputra',100000,10,'android-studio.jpg','Buku pengantar pengembangan aplikasi Android menggunakan Android Studio dan konsep pemrograman modern.','Cocok untuk pembelajar yang ingin memahami alur pembuatan aplikasi Android dari dasar hingga implementasi fitur.'),
(5,'Detektif Conan 99','Aoyama Gosho',45000,10,'conan.jpg','Kumpulan kasus misteri yang menantang kemampuan analisis dan pengamatan sang detektif cilik.','Conan kembali menghadapi rangkaian kasus yang penuh petunjuk, penyamaran, dan kejutan.'),
(4,'7 in 1 Pemrograman Web untuk Pemula (Update Version)','Rohi Abdullah',145000,10,'web.jpg','Materi pemrograman web untuk pemula yang dirancang bertahap dari konsep dasar hingga pembuatan aplikasi web.','Referensi praktis untuk memahami HTML, CSS, PHP, database, dan alur pengembangan website sederhana.'),
(3,'Little Magacal Piya x Sky Academy','Cindyana H',90000,10,'piya.png','Kisah fantasi remaja dengan dunia akademi, persahabatan, dan petualangan yang penuh warna.','Piya memasuki lingkungan baru dan menghadapi tantangan yang menguji persahabatan serta keberaniannya.'),
(1,'The Mystery Of Anneke Doll','Annala Lashita',40000,10,'doll.png','Sebuah misteri berpusat pada boneka Anneke yang menyimpan rahasia dan petunjuk tersembunyi.','Investigasi demi investigasi membawa pembaca semakin dekat pada jawaban di balik boneka misterius tersebut.'),
(2,'Ancika: Dia Yang Bersamaku Tahun 1995','Pidi Baiq',85000,10,'ancika.jpg','Cerita romansa dan kehidupan remaja yang menghadirkan hubungan, pilihan, dan kenangan yang dekat dengan pembaca.','Kisah tentang pertemuan, kedekatan, dan dinamika hubungan yang berkembang dalam suasana tahun 1995.');


-- Tahun terbit yang diminta untuk buku The Shark Caller.
UPDATE books
SET published_year = 2002
WHERE title = 'The Shark Caller'
LIMIT 1;
