<?php

/*
|--------------------------------------------------------------------------
| ADMIN/MESSAGES.PHP
|--------------------------------------------------------------------------
| Kegunaan:
| - Menampilkan semua pesan yang dikirim Pengguna melalui halaman Kontak.
| - Admin dapat membaca nama pengirim, email, subject, isi pesan,
|   dan tanggal pesan dikirim.
| - Data diambil dari tabel messages.
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$page_title = 'Pesan Pengguna - Admin';


/*
|--------------------------------------------------------------------------
| AMBIL DATA PESAN
|--------------------------------------------------------------------------
| Pesan terbaru ditampilkan paling atas.
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query(
    "
    SELECT
        id,
        name,
        email,
        subject,
        message,
        created_at
    FROM messages
    ORDER BY created_at DESC, id DESC
    "
);

$messages = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| JUMLAH PESAN
|--------------------------------------------------------------------------
*/

$total_messages = count($messages);


/*
|--------------------------------------------------------------------------
| HEADER ADMIN
|--------------------------------------------------------------------------
| File ini menggunakan header admin yang sudah ada.
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../includes/admin_header.php';

?>


<!-- =========================================================
     HALAMAN PESAN ADMIN
     ========================================================= -->

<main class="admin-page">


    <!-- =====================================================
         JUDUL HALAMAN
         ===================================================== -->

    <div class="admin-page-heading">

        <div>

            <h1>
                Pesan Pengguna
            </h1>

            <p>
                Semua pesan yang dikirim melalui halaman Kontak.
            </p>

        </div>


        <!-- Jumlah pesan -->

        <div class="message-total">

            <?= $total_messages ?>

            Pesan

        </div>

    </div>


    <!-- =====================================================
         JIKA BELUM ADA PESAN
         ===================================================== -->

    <?php if (!$messages): ?>

        <div class="admin-message-empty">

            <strong>
                Belum ada pesan.
            </strong>

            <p>
                Pesan dari pengguna melalui halaman Kontak
                akan muncul di halaman ini.
            </p>

        </div>

    <?php else: ?>


        <!-- =================================================
             DAFTAR PESAN
             ================================================= -->

        <div class="admin-message-list">

            <?php foreach ($messages as $item): ?>

                <article class="admin-message-card">


                    <!-- =====================================
                         HEADER PESAN
                         ===================================== -->

                    <div class="admin-message-head">

                        <div>

                            <h2>
                                <?= e($item['subject']) ?>
                            </h2>

                            <div class="admin-message-sender">

                                <strong>
                                    <?= e($item['name']) ?>
                                </strong>

                                <a
                                    href="mailto:<?= e($item['email']) ?>"
                                >
                                    <?= e($item['email']) ?>
                                </a>

                            </div>

                        </div>


                        <!-- Tanggal -->

                        <time>

                            <?= date(
                                'd M Y, H:i',
                                strtotime($item['created_at'])
                            ) ?>

                        </time>

                    </div>


                    <!-- =====================================
                         ISI PESAN
                         ===================================== -->

                    <div class="admin-message-body">

                        <?= nl2br(
                            e($item['message'])
                        ) ?>

                    </div>


                    <!-- =====================================
                         TOMBOL BALAS
                         ===================================== -->

                    <div class="admin-message-actions">

                        <a
                            href="mailto:<?= e($item['email']) ?>?subject=Re:%20<?= urlencode($item['subject']) ?>"
                            class="btn btn-primary"
                        >
                            Balas Email
                        </a>

                    </div>


                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


</main>


<!-- =========================================================
     CSS KHUSUS HALAMAN PESAN
     ========================================================= -->

<style>

/*
|--------------------------------------------------------------------------
| HALAMAN PESAN ADMIN
|--------------------------------------------------------------------------
*/

.admin-page {

    padding:
        30px
        37px
        50px;

    min-height:
        calc(
            100vh - 108px
        );

}


/*
|--------------------------------------------------------------------------
| HEADING
|--------------------------------------------------------------------------
*/

.admin-page-heading {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 24px;

}


.admin-page-heading h1 {

    margin: 0;

    color: #315975;

    font-size: 28px;

}


.admin-page-heading p {

    margin:
        7px
        0
        0;

    color: #5f7787;

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| JUMLAH PESAN
|--------------------------------------------------------------------------
*/

.message-total {

    padding:
        10px
        16px;

    border-radius:
        999px;

    background: #ffffff;

    border:
        1px solid
        #c5d3dd;

    color: #315975;

    font-size: 13px;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| KARTU PESAN
|--------------------------------------------------------------------------
*/

.admin-message-list {

    display: flex;

    flex-direction: column;

    gap: 18px;

}


.admin-message-card {

    background: #ffffff;

    border:
        1px solid
        #d5e0e7;

    border-radius: 10px;

    padding: 20px 22px;

    box-shadow:
        0 2px 8px
        rgba(
            0,
            0,
            0,
            0.05
        );

}


/*
|--------------------------------------------------------------------------
| HEADER PESAN
|--------------------------------------------------------------------------
*/

.admin-message-head {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 20px;

    padding-bottom:
        13px;

    border-bottom:
        1px solid
        #e7edf1;

}


.admin-message-head h2 {

    margin: 0 0 8px;

    color: #315975;

    font-size: 17px;

}


.admin-message-sender {

    display: flex;

    flex-direction: column;

    gap: 4px;

    font-size: 12px;

}


.admin-message-sender strong {

    color: #1f5279;

}


.admin-message-sender a {

    color: #4e89b6;

    text-decoration: none;

}


.admin-message-sender a:hover {

    text-decoration: underline;

}


.admin-message-head time {

    white-space: nowrap;

    color: #7c8e9a;

    font-size: 11px;

}


/*
|--------------------------------------------------------------------------
| ISI PESAN
|--------------------------------------------------------------------------
*/

.admin-message-body {

    padding-top:
        16px;

    color: #2f3d46;

    font-size: 13px;

    line-height: 1.7;

    white-space: normal;

}


/*
|--------------------------------------------------------------------------
| TOMBOL
|--------------------------------------------------------------------------
*/

.admin-message-actions {

    margin-top:
        16px;

    padding-top:
        13px;

    border-top:
        1px solid
        #e7edf1;

}


/*
|--------------------------------------------------------------------------
| KOSONG
|--------------------------------------------------------------------------
*/

.admin-message-empty {

    background: #ffffff;

    border:
        1px solid
        #d5e0e7;

    border-radius: 10px;

    padding: 40px;

    text-align: center;

}


.admin-message-empty strong {

    color: #315975;

    font-size: 18px;

}


.admin-message-empty p {

    margin:
        8px
        0
        0;

    color: #7c8e9a;

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 720px) {

    .admin-page {

        padding:
            20px
            14px
            40px;

    }


    .admin-page-heading {

        align-items: flex-start;

        flex-direction: column;

    }


    .admin-message-head {

        flex-direction: column;

    }


    .admin-message-head time {

        white-space: normal;

    }

}

</style>


<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
