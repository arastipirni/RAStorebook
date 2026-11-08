<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$page_title = 'RAStory Bookstore - Beranda';


/*
|--------------------------------------------------------------------------
| AMBIL PENCARIAN DAN KATEGORI
|--------------------------------------------------------------------------
*/

$q = trim(
    $_GET['q'] ?? ''
);

$category = trim(
    $_GET['category'] ?? ''
);


/*
|--------------------------------------------------------------------------
| QUERY BUKU
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        b.*,
        c.name AS category_name
    FROM books b
    LEFT JOIN categories c
        ON c.id = b.category_id
    WHERE 1";

$params = [];

/*
|--------------------------------------------------------------------------
| PENCARIAN BERDASARKAN JUDUL ATAU PENULIS
|--------------------------------------------------------------------------
*/

if ($q !== '') {

    $sql .= "
        AND (
            b.title LIKE ?
            OR b.author LIKE ?
        )
    ";

    $params[] = "%{$q}%";
    $params[] = "%{$q}%";
}

/*
|--------------------------------------------------------------------------
| FILTER KATEGORI
|--------------------------------------------------------------------------
*/

if ($category !== '') {

    $sql .= "
        AND c.name = ?
    ";

    $params[] = $category;
}


/*
|--------------------------------------------------------------------------
| URUTAN BUKU
|--------------------------------------------------------------------------
*/

$sql .= "
    ORDER BY b.id ASC
";


/*
|--------------------------------------------------------------------------
| JALANKAN QUERY
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| AMBIL 5 KATEGORI RESMI
|--------------------------------------------------------------------------
|
| 1. Mystery & Thriller
| 2. Romance
| 3. Fantasy
| 4. Technology & Programming
| 5. Comic
|--------------------------------------------------------------------------
*/

$categories = $pdo->query(
    'SELECT * FROM categories ORDER BY sort_order, id'
)->fetchAll();


/*
|--------------------------------------------------------------------------
| HITUNG JUMLAH BUKU
|--------------------------------------------------------------------------
*/

$bookCount = count($books);


/*
|--------------------------------------------------------------------------
| PANGGIL HEADER
|--------------------------------------------------------------------------
*/

require __DIR__ . '/includes/header.php';

?>


<!-- =========================================================
     HERO SECTION
     ========================================================= -->

<section class="hero">

    <div class="hero-copy">

        <div class="hero-copy-inner">

            <h1>
                Buku pilihan untuk<br>
                menemani setiap<br>
                ceritamu
            </h1>

            <p>
                Temukan buku untuk inspirasi, hiburan, dan pengetahuan.
                Pilih bacaan favoritmu dan mulai membaca hari ini!
            </p>

            <form
                class="search-box"
                method="get"
            >

                <input
                    type="text"
                    name="q"
                    value="<?= e($q) ?>"
                    placeholder="Cari judul atau penulis buku"
                >

                <button
                    type="submit"
                >
                    ⌕ &nbsp; Cari Buku
                </button>

            </form>

        </div>

    </div>


    <div class="hero-image">

        <img
            src="assets/promos/hero-books.jpg"
            alt="Koleksi buku"
        >

    </div>

</section>


<!-- =========================================================
     CATEGORY BAR
     ========================================================= -->

<section class="category-bar">

    <a
        class="category-pill <?= $category === '' ? 'active' : '' ?>"
        href="index.php"
    >
        Semua Buku
    </a>


    <?php foreach ($categories as $c): ?>

        <a
            class="category-pill
                <?= $category === $c['name'] ? 'active' : '' ?>"
            href="?category=<?= urlencode($c['name']) ?>"
        >
            <?= e($c['name']) ?>
        </a>

    <?php endforeach; ?>

</section>


<!-- =========================================================
     BOOK SECTION
     ========================================================= -->

<section class="books-section">

    <h2>
        Pilih Buku Favoritmu
    </h2>


    <?php if (!$books): ?>

        <div
            class="panel"
            style="
                max-width: 600px;
                margin: 0 auto;
                text-align: center;
            "
        >
            Buku yang kamu cari belum tersedia.
        </div>

    <?php endif; ?>


    <!-- =====================================================
         BOOK GRID PERTAMA
         Menampilkan 5 buku pertama
         ===================================================== -->

    <div class="book-grid">

        <?php foreach (
            array_slice($books, 0, 5)
            as $book
        ): ?>

            <article
                class="book-card"
                data-detail-url="detail.php?id=<?= $book['id'] ?>"
                tabindex="0"
                role="link"
                aria-label="Lihat detail <?= e($book['title']) ?>"
            >

                <!-- =========================================
                     COVER BUKU

                     Kegunaan:
                     Menampilkan gambar cover dari folder
                     assets/images/.
                     ========================================= -->

                <div
                    class="cover-wrap book-click-area"
                >

                    <img
                        src="assets/images/<?= e($book['image']) ?>"
                        alt="<?= e($book['title']) ?>"
                    >

                </div>


                <!-- =========================================
                     JUDUL BUKU
                     ========================================= -->

                <div
                    class="book-title book-click-area"
                >
                    <?= e($book['title']) ?>
                </div>


                <!-- PENULIS -->

                <div class="book-author">
                    <?= e($book['author']) ?>
                </div>


                <!-- HARGA -->

                <div class="book-price">
                    <?= rupiah($book['price']) ?>
                </div>

                <?php if (!book_available($book)): ?>
                    <span class="unavailable-badge">Tidak Tersedia</span>
                <?php endif; ?>


                <!-- =========================================
                     TOMBOL
                     ========================================= -->

                <div class="card-actions">

                    <a
                        class="card-btn"
                        href="detail.php?id=<?= $book['id'] ?>"
                    >
                        Detail
                    </a>


                    <?php if (book_available($book)): ?>
                        <a class="card-btn primary cart-btn" href="cart.php?add=<?= $book['id'] ?>">
                            <span class="cart-plus" aria-hidden="true">+</span>
                            <span>Keranjang</span>
                        </a>
                    <?php else: ?>
                        <span class="card-btn disabled-btn">Stok Habis</span>
                    <?php endif; ?>

                </div>

            </article>

        <?php endforeach; ?>

    </div>


    <!-- =====================================================
         PROMO
         ===================================================== -->

    <?php if ($bookCount >= 6): ?>

        <div class="promo-grid">

            <a
                class="promo-card"
                href="detail.php?id=4"
            >
                <img
                    src="assets/promos/promo-miiko.png"
                    alt="Promo Miiko"
                >
            </a>


            <a
                class="promo-card"
                href="detail.php?id=9"
            >
                <img
                    src="assets/promos/promo-doll.png"
                    alt="Promo Doll"
                >
            </a>


            <a
                class="promo-card"
                href="detail.php?id=7"
            >
                <img
                    src="assets/promos/promo-web.png"
                    alt="Promo Web"
                >
            </a>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         BOOK GRID SELANJUTNYA
         Menampilkan semua buku setelah 5 buku pertama
         ===================================================== -->

    <?php if ($bookCount > 5): ?>

        <div
            class="book-grid"
            style="padding-bottom: 30px;"
        >

            <?php foreach (
                array_slice($books, 5)
                as $book
            ): ?>

                <article
                    class="book-card"
                    data-detail-url="detail.php?id=<?= $book['id'] ?>"
                    tabindex="0"
                    role="link"
                    aria-label="Lihat detail <?= e($book['title']) ?>"
                >

                    <!-- COVER -->

                    <div
                        class="cover-wrap book-click-area"
                    >

                        <img
                            src="assets/images/<?= e($book['image']) ?>"
                            alt="<?= e($book['title']) ?>"
                        >

                    </div>


                    <!-- JUDUL -->

                    <div
                        class="book-title book-click-area"
                    >
                        <?= e($book['title']) ?>
                    </div>


                    <!-- PENULIS -->

                    <div class="book-author">
                        <?= e($book['author']) ?>
                    </div>


                    <!-- HARGA -->

                    <div class="book-price">
                        <?= rupiah($book['price']) ?>
                    </div>


                    <!-- TOMBOL -->

                    <div class="card-actions">

                        <a
                            class="card-btn"
                            href="detail.php?id=<?= $book['id'] ?>"
                        >
                            Detail
                        </a>


                        <?php if (book_available($book)): ?>
                        <a class="card-btn primary cart-btn" href="cart.php?add=<?= $book['id'] ?>">
                            <span class="cart-plus" aria-hidden="true">+</span>
                            <span>Keranjang</span>
                        </a>
                    <?php else: ?>
                        <span class="card-btn disabled-btn">Stok Habis</span>
                    <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>


<?php require __DIR__ . '/includes/footer.php'; ?>


<!-- =========================================================
     JAVASCRIPT KLIK BUKU
     
     Kegunaan:
     Membuat cover dan judul buku dapat diklik
     untuk membuka halaman detail.
     ========================================================= -->

<script>

    /*
    |--------------------------------------------------------------------------
    | Ambil semua kartu buku
    |--------------------------------------------------------------------------
    */

    const bookCards =
        document.querySelectorAll('.book-card');


    /*
    |--------------------------------------------------------------------------
    | Tambahkan interaksi ke setiap kartu
    |--------------------------------------------------------------------------
    */

    bookCards.forEach(function (card) {

        /*
        |--------------------------------------------------------------------------
        | Cover dan judul dapat diklik
        |--------------------------------------------------------------------------
        */

        const clickableAreas =
            card.querySelectorAll(
                '.book-click-area'
            );

        clickableAreas.forEach(function (area) {

            area.addEventListener(
                'click',
                function () {

                    const url =
                        card.dataset.detailUrl;

                    if (url) {

                        window.location.href =
                            url;
                    }
                }
            );

        });

        /*
        |--------------------------------------------------------------------------
        | Tombol Enter / Space pada keyboard
        |--------------------------------------------------------------------------
        */

        card.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Enter' ||
                    event.key === ' '
                ) {

                    if (
                        event.target.tagName === 'A'
                    ) {

                        return;

                    }

                    event.preventDefault();

                    const url =
                        card.dataset.detailUrl;


                    if (url) {

                        window.location.href =
                            url;

                    }
                }
            }
        );
    });
</script>