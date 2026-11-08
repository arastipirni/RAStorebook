<?php
/**
 * ABOUT.PHP
 *
 * Kegunaan file:
 * - Menampilkan halaman Tentang Kami BookStore.
 * - Menjaga struktur 50:50 seperti desain referensi.
 * - Menampilkan tiga foto, panel biru, CTA, dan footer.
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = 'Tentang Kami - RAStory Bookstore';

require __DIR__ . '/includes/header.php';
?>

<!--
    CSS khusus halaman About.
    untuk halaman About.
-->
<style>
<?php require __DIR__ . '/assets/css/about.css'; ?>
</style>

<main class="about-page">

    <!-- =====================================================
         BAGIAN 1
         Teks kiri + foto buku kanan
         ===================================================== -->

    <section class="about-section">

        <div class="about-text about-light">

            <h1>
                Tentang Kami
            </h1>

            <p>
                Selamat datang di RAStorebook, tempat untuk menemukan
                buku-buku yang menginspirasi, menambah pengetahuan, dan menghibur.
            </p>

        </div>

        <div class="about-photo">

            <img
                src="assets/images/buku.jpg"
                alt="Rak buku"
            >

        </div>

    </section>


    <!-- =====================================================
         BAGIAN 2
         Foto pembaca kiri + panel biru kanan
         ===================================================== -->

    <section class="about-section">

        <div class="about-photo">

            <img
                src="assets/images/bareng.jpg"
                alt="Pembaca buku"
            >

        </div>

        <div class="about-text about-blue">

            <p>
                Di RAStorebook, kami percaya bahwa setiap buku memiliki
                kekuatan untuk membuka sudut pandang baru dan menciptakan pengalaman bermakna.
            </p>

            <p>
                Toko buku online kami menyediakan beragam buku dari berbagai
                genre, seperti Novel, Misteri &amp; Thriller, Romantis, Fantasi,
                Teknologi &amp; Pemrograman, Komik, dan masih banyak lagi.
            </p>

        </div>

    </section>


    <!-- =====================================================
         BAGIAN 3
         Teks kiri + foto meja/kantor kanan
         ===================================================== -->

    <section class="about-section">

        <div class="about-text about-light">

            <p>
                Misi kami adalah membuat buku berkualitas mudah dijangkau semua orang
                melalui pengalaman belanja yang sederhana, aman, dan nyaman.
                Baik kamu sedang mencari novel favorit, buku pelajaran, maupun
                bacaan untuk mendukung perkembangan diri, RAStorebook siap membantu.
            </p>

        </div>

        <div class="about-photo">

            <img
                src="assets/images/digital-books.jpeg"
                alt="Ruang kerja toko buku"
            >

        </div>

    </section>


    <!-- =====================================================
         CALL TO ACTION
         ===================================================== -->

    <section class="about-cta">

        <h2>
            Siap menemukan bacaan baru?
        </h2>

        <p>
            Jelajahi koleksi buku kami dan temukan bacaan berikutnya yang
            bisa menambah wawasan dan sudut pandangmu.
        </p>

        <div class="about-buttons">

            <a
                href="index.php"
                class="about-button"
            >
                Jelajahi Buku
            </a>

            <a
                href="contact.php"
                class="about-button"
            >
                Hubungi Kami
            </a>

        </div>

    </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
