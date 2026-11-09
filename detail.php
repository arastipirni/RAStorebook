<?php

/*
|--------------------------------------------------------------------------
| DETAIL.PHP
|--------------------------------------------------------------------------
| Kegunaan file:
| 1. Mengambil data buku berdasarkan ID.
| 2. Menampilkan cover, judul, penulis, dan harga.
| 3. Menampilkan form untuk menambahkan buku ke keranjang.
| 4. Menampilkan deskripsi dan sinopsis buku.
| 5. Menampilkan informasi Penulis, Tahun Terbit, Kategori, Stok, dan Harga.
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';


/*
|--------------------------------------------------------------------------
| 1. MENGAMBIL ID BUKU
|--------------------------------------------------------------------------
| ID diambil dari URL.
| Contoh:
| detail.php?id=1
|--------------------------------------------------------------------------
*/

$id = (int) ($_GET['id'] ?? 0);


/*
|--------------------------------------------------------------------------
| 2. MENGAMBIL DATA BUKU DARI DATABASE
|--------------------------------------------------------------------------
| Data buku digabung dengan tabel categories
| supaya nama kategori bisa ikut ditampilkan.
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "
    SELECT
        b.*,
        c.name AS category_name
    FROM books b
    LEFT JOIN categories c
        ON c.id = b.category_id
    WHERE b.id = ?
    "
);

$stmt->execute([$id]);

$book = $stmt->fetch();


/*
|--------------------------------------------------------------------------
| 3. JIKA BUKU TIDAK DITEMUKAN
|--------------------------------------------------------------------------
| User akan dikembalikan ke halaman utama.
|--------------------------------------------------------------------------
*/

if (!$book) {

    redirect('index.php');

}


/*
|--------------------------------------------------------------------------
| 4. JUDUL HALAMAN
|--------------------------------------------------------------------------
*/

$page_title = $book['title'] . ' - RAStory Bookstore';


/*
|--------------------------------------------------------------------------
| 5. MEMANGGIL HEADER WEBSITE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/includes/header.php';

?>

<!-- =========================================================
     DETAIL BUKU
     Menampilkan seluruh informasi utama buku.
     ========================================================= -->

<section class="detail-page">


    <!-- =====================================================
         TOMBOL KEMBALI
         Mengembalikan User ke halaman utama BookStore.
         ===================================================== -->

    <div class="detail-inner">

        <!-- =================================================
             INFORMASI UTAMA BUKU
             ================================================= -->

        <div class="detail-top">


            <!-- =============================================
                 COVER BUKU
                 Menampilkan gambar cover buku.
                 ============================================= -->

            <div class="detail-cover">

                <img
                    src="assets/images/<?= e($book['image']) ?>"
                    alt="<?= e($book['title']) ?>"
                >

            </div>


            <!-- =============================================
                 INFORMASI BUKU
                 
                 Kegunaan:
                 Menampilkan judul, penulis, harga,
                 dan tombol Add To Cart.
                 ============================================= -->

            <div class="detail-info">


                <!-- Judul buku -->
                <h1>
                    <?= e($book['title']) ?>
                </h1>


                <!-- Nama penulis -->
                <div class="author">
                    <?= e($book['author']) ?>
                </div>


                <!-- Harga buku -->
                <div class="price">
                    <?= rupiah($book['price']) ?>
                </div>


                <!-- =========================================
                     FORM ADD TO CART
                     ========================================= -->

                <?php if (book_available($book)): ?>
                <form
                    class="add-form"
                    method="post"
                    action="cart.php"
                >
                    <!-- Memberitahu cart.php bahwa
                         tindakan yang dilakukan adalah add -->

                    <input
                        type="hidden"
                        name="action"
                        value="add"
                    >


                    <!-- ID buku -->

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $book['id'] ?>"
                    >


                    <!-- Jumlah buku -->

                    <input
                        class="qty"
                        type="number"
                        name="qty"
                        min="1"
                        max="<?= $book['stock'] ?>"
                        value="1"
                    >


                    <!-- Tombol tambah ke keranjang -->

                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        <span class="cart-plus" aria-hidden="true">+</span>
                        <span>Keranjang</span>
                    </button>


                </form>
                <?php else: ?>
                    <div class="unavailable-detail">Tidak Tersedia • Stok 0</div>
                <?php endif; ?>

            </div>

        </div>

        <!-- =================================================
             DESKRIPSI BUKU
             ================================================= -->

        <div class="description">


            <!-- Judul Deskripsi -->

            <h3>
                Deskripsi
            </h3>


            <!-- Isi Deskripsi -->

            <p>
                <?= nl2br(e($book['description'])) ?>
            </p>

        <!-- =================================================
             KOTAK INFORMASI BUKU
            
             Menampilkan Penulis, Kategori, Stok,
             dan Harga dalam SATU kotak putih.
             
             Kotak ini hanya untuk informasi buku.
             Footer berada DI LUAR section ini.
             ================================================= -->

        <div class="info-strip">


            <!-- =============================================
                 AUTHOR
                 ============================================= -->

            <div>

                <strong>
                    Penulis
                </strong>

                <span>
                    <?= e($book['author']) ?>
                </span>

            </div>


            <!-- =============================================
                 PUBLICATION YEAR
                 ============================================= -->

            <div>

                <strong>
                    Tahun Terbit
                </strong>

                <span>
                    <?= !empty($book['published_year']) ? e($book['published_year']) : '-' ?>
                </span>

            </div>


            <!-- =============================================
                 CATEGORY
                 ============================================= -->

            <div>

                <strong>
                    Kategori
                </strong>

                <span>
                    <?= e($book['category_name'] ?? '-') ?>
                </span>

            </div>


            <!-- =============================================
                 STOCK
                 ============================================= -->

            <div>

                <strong>
                    Stok
                </strong>

                <span>
                    <?= (int)$book['stock'] > 0 ? (int)$book['stock'] : 'Tidak Tersedia' ?>
                </span>

            </div>


            <!-- =============================================
                 PRICE
                 ============================================= -->

            <div>

                <strong>
                    Harga
                </strong>

                <span>
                    <?= rupiah($book['price']) ?>
                </span>

            </div>


        </div>


    </div>

</section>



<!-- =========================================================
     FOOTER   
     Footer berada di luar detail-page supaya
     tidak menyatu dengan kotak informasi buku.
     ========================================================= -->

<?php require __DIR__ . '/includes/footer.php'; ?>