<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = 'Kontak - RAStory Bookstore';

$name = '';
$email = '';
$subject = '';
$message = '';
$message_success = '';
$message_error = '';

/*
 * Pesan kontak disimpan ke tabel messages yang memang tersedia
 * di database bookstore. Tidak membuat tabel/database baru.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_error = 'Lengkapi nama, email yang valid, dan pesan terlebih dahulu.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO messages(name, email, subject, message) VALUES(?,?,?,?)'
            );
            $stmt->execute([
                $name,
                $email,
                $subject !== '' ? $subject : 'Kontak',
                $message,
            ]);

            $message_success = 'Pesan berhasil dikirim ke admin.';
            $name = '';
            $email = '';
            $subject = '';
            $message = '';
        } catch (PDOException $e) {
            $message_error = 'Pesan gagal disimpan. Silakan coba lagi.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="contact-hero">
    <div class="container contact-hero-inner">
        <div class="contact-label">Kontak</div>
        <h1>Kami Siap Membantu</h1>
        <h2>Ada pertanyaan, saran, atau keluhan?</h2>
        <p>Tim kami siap menanggapi pesanmu dalam waktu 24 jam.</p>
    </div>
</section>

<section class="contact-information">
    <div class="container contact-container">
        <div class="contact-section-heading">
            <span>Hubungi kami</span>
            <h3>Informasi Kontak</h3>
            <p>Temukan informasi yang kamu perlukan sebelum mengirim pesan kepada kami.</p>
        </div>

        <div class="contact-info-grid">
            <div class="contact-info-item">
                <div class="contact-info-icon">☎</div>
                <div>
                    <strong>Telepon</strong>
                    <span>+62 845-6789-3543</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon">✉</div>
                <div>
                    <strong>Email</strong>
                    <span>RAStorebook@gmail.com</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-info-icon">◷</div>
                <div>
                    <strong>Jam Operasional</strong>
                    <span>
                        Senin - Jumat: 08.00 - 20.00<br>
                        Sabtu - Minggu: 10.00 - 17.00
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-form-section">
    <div class="container contact-container">
        <div class="contact-form-heading">
            <span>Pesan untuk kami</span>
            <h2>Kirim Pesan ke Admin</h2>
            <p>Isi formulir di bawah dengan lengkap agar admin dapat memberikan tanggapan dengan mudah.</p>
        </div>

        <?php if ($message_success !== ''): ?>
            <div class="contact-success">
                <?= e($message_success) ?>
            </div>
        <?php endif; ?>

        <?php if ($message_error !== ''): ?>
            <div class="contact-error">
                <?= e($message_error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="contact-form">
            <div class="contact-field">
                <label for="contact-name">Nama Lengkap</label>
                <input
                    id="contact-name"
                    type="text"
                    name="name"
                    required
                    placeholder="Masukkan nama lengkap"
                    value="<?= e($name) ?>"
                >
            </div>

            <div class="contact-field">
                <label for="contact-email">Email</label>
                <input
                    id="contact-email"
                    type="email"
                    name="email"
                    required
                    placeholder="Masukkan alamat email"
                    value="<?= e($email) ?>"
                >
            </div>

            <div class="contact-field contact-full">
                <label for="contact-subject">Subjek</label>
                <input
                    id="contact-subject"
                    type="text"
                    name="subject"
                    placeholder="Contoh: Pertanyaan tentang pesanan"
                    value="<?= e($subject) ?>"
                >
            </div>

            <div class="contact-field contact-full">
                <label for="contact-message">Pesan</label>
                <textarea
                    id="contact-message"
                    name="message"
                    required
                    placeholder="Tulis pesanmu di sini..."
                ><?= e($message) ?></textarea>
            </div>

            <button type="submit" class="contact-submit">
                Kirim Pesan
            </button>
        </form>
    </div>
</section>

<style>
/* =========================================================
   CONTACT - RAPI, MUDAH DIBACA, DAN SESUAI TEMA WEBSITE
   ========================================================= */
.contact-hero {
    background: #74b5ec;
    color: #fff;
    padding: 42px 20px;
    text-align: center;
    border-bottom: 7px solid #fff;
}

.contact-hero-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.contact-label,
.contact-section-heading > span,
.contact-form-heading > span {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .4px;
}

.contact-hero h1 {
    margin: 6px 0 7px;
    font-size: 31px;
    line-height: 1.15;
    font-weight: 800;
}

.contact-hero h2 {
    margin: 0 0 9px;
    font-size: 20px;
    line-height: 1.25;
    font-weight: 700;
}

.contact-hero p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
}

.contact-information {
    background: #eaf8ff;
    padding: 38px 0 44px;
    border-bottom: 7px solid #fff;
}

.contact-container {
    width: min(970px, 92%);
    margin: 0 auto;
}

.contact-section-heading,
.contact-form-heading {
    text-align: center;
}

.contact-section-heading > span,
.contact-form-heading > span {
    color: #4e91c4;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 1px;
}

.contact-information h3,
.contact-form-section h2 {
    margin: 5px 0 8px;
    color: #163f60;
    font-size: 26px;
    line-height: 1.2;
    font-weight: 800;
}

.contact-section-heading p,
.contact-form-heading p {
    margin: 0 auto 24px;
    max-width: 650px;
    color: #5d7180;
    font-size: 12px;
    line-height: 1.6;
}

.contact-info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

.contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    min-height: 122px;
    padding: 20px;
    background: #fff;
    border: 1px solid #d4e4ee;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(31,82,121,.08);
    box-sizing: border-box;
    transition: transform .2s ease, box-shadow .2s ease;
}

.contact-info-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(31,82,121,.13);
}

.contact-info-icon {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    display: grid;
    place-items: center;
    border-radius: 11px;
    background: #eaf6ff;
    color: #1f6496;
    font-size: 17px;
    font-weight: 700;
}

.contact-info-item strong {
    display: block;
    margin: 1px 0 7px;
    color: #1f5279;
    font-size: 14px;
    font-weight: 800;
}

.contact-info-item span {
    display: block;
    color: #2f4657;
    font-size: 12px;
    line-height: 1.7;
}

.contact-form-section {
    background: #67acf0;
    padding: 42px 0 48px;
}

.contact-form-heading h2 {
    color: #fff;
    margin-top: 5px;
}

.contact-form-heading p,
.contact-form-heading > span {
    color: #f5fbff;
}

.contact-success,
.contact-error {
    padding: 12px 15px;
    border-radius: 9px;
    margin-bottom: 14px;
    font-size: 12px;
    font-weight: 600;
}

.contact-success {
    background: #effff4;
    color: #1b6d3d;
}

.contact-error {
    background: #fff1f1;
    color: #b42323;
}

.contact-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    padding: 26px;
    background: #fff;
    border: 1px solid rgba(255,255,255,.6);
    border-radius: 16px;
    box-shadow: 0 10px 26px rgba(24,73,108,.16);
    box-sizing: border-box;
}

.contact-field {
    min-width: 0;
}

.contact-field label {
    display: block;
    margin-bottom: 7px;
    color: #2b5677;
    font-size: 12px;
    font-weight: 800;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 13px 14px;
    border: 1.5px solid #d2e0e9;
    border-radius: 9px;
    outline: none;
    background: #fff;
    color: #263d4e;
    font-family: "Poppins", Arial, sans-serif;
    font-size: 12px;
    line-height: 1.5;
    box-sizing: border-box;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.contact-form input::placeholder,
.contact-form textarea::placeholder {
    color: #8997a0;
}

.contact-form input:focus,
.contact-form textarea:focus {
    border-color: #69b6ef;
    box-shadow: 0 0 0 3px rgba(105,182,239,.14);
}

.contact-full {
    grid-column: 1 / -1;
}

.contact-form textarea {
    min-height: 150px;
    resize: vertical;
}

.contact-submit {
    grid-column: 1 / -1;
    width: 155px;
    min-height: 42px;
    margin: 3px auto 0;
    padding: 10px 20px;
    border: 0;
    border-radius: 9px;
    background: #69b6ef;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 5px 13px rgba(31,82,121,.15);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}

.contact-submit:hover {
    background: #579fd6;
    transform: translateY(-2px);
    box-shadow: 0 8px 17px rgba(31,82,121,.19);
}

.contact-submit:active {
    transform: translateY(1px);
}

@media (max-width: 700px) {
    .contact-info-grid,
    .contact-form {
        grid-template-columns: 1fr;
    }

    .contact-form {
        padding: 19px;
    }

    .contact-full {
        grid-column: auto;
    }

    .contact-submit {
        width: 100%;
    }

    .contact-hero h1 {
        font-size: 26px;
    }

    .contact-hero h2 {
        font-size: 18px;
    }
}
</style>

<?php require __DIR__ . '/includes/footer.php'; ?>
