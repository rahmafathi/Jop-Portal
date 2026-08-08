<!-- تضمين Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ============================================================
   SOFT NEON LED BAR (TOP OF FOOTER) - HADI & BLINK EFFECT
============================================================ */
.neon-led-bar {
    width: 100% !important;
    height: 2.5px !important; /* سمك أرفع وأرقى */
    background: linear-gradient(90deg, #00d2ff, #0d6efd, #00ffcc, #00d2ff) !important;
    background-size: 300% 100% !important;
    position: relative !important;
    z-index: 10 !important;
    /* انيميشن التنفس الإنسيابي (يطفي وينور) مع حركة تدفق الألوان */
    animation: softNeonBlink 3.5s infinite alternate ease-in-out, moveGradient 6s linear infinite !important;
}

/* حركة التهدئة والوميض (تقفل وتنور بنعومة) */
@keyframes softNeonBlink {
    0% {
        opacity: 0.15; /* شبه مطفي */
        box-shadow: 0 0 2px rgba(0, 210, 255, 0.2);
    }
    50% {
        opacity: 0.85; /* منور بشكل ناعم غير مزعج */
        box-shadow: 
            0 0 6px rgba(0, 210, 255, 0.6),
            0 0 14px rgba(0, 210, 255, 0.3);
    }
    100% {
        opacity: 0.15; /* يعود ويقفل */
        box-shadow: 0 0 2px rgba(0, 210, 255, 0.2);
    }
}

/* حركة تدفق الألوان داخل الخط */
@keyframes moveGradient {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
}

/* ============================================================
   ULTRA-CLEAN UNIFIED NEON FOOTER (SUBTLE GLOW EDITION)
============================================================ */
html, body {
    margin: 0 !important;
    padding: 0 !important;
}

.custom-footer {
    background: #050b14 !important;
    color: #94a3b8 !important;
    position: relative !important;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif !important;
    padding: 40px 0 0 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    clear: both !important;
}

.custom-footer .footer-container {
    max-width: 1200px !important;
    margin: 0 auto !important;
}

/* 1. Brand Header (Pure Clean Soft Neon Logo) */
.custom-footer .brand-wrapper {
    display: inline-flex !important;
    align-items: center !important;
    gap: 10px !important;
}

.custom-footer .brand-icon-footer {
    width: auto !important;
    height: auto !important;
    background: transparent !important;
    border: none !important;
    color: #00d2ff !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 26px !important;
    box-shadow: none !important;
    flex-shrink: 0 !important;
    filter: drop-shadow(0 0 5px rgba(0, 210, 255, 0.4)) !important; /* توهج هادئ لللوجو */
}

.custom-footer .brand-title {
    font-size: 22px !important;
    font-weight: 800 !important;
    color: #ffffff !important;
    letter-spacing: -0.3px !important;
}

.custom-footer .footer-desc {
    color: #94a3b8 !important;
    font-size: 14px !important;
    line-height: 1.6 !important;
    margin-top: 14px !important;
    margin-bottom: 22px !important;
    max-width: 330px !important;
}

/* 2. Unified Social Media Icons */
.custom-footer .social-links {
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    margin-top: 15px !important;
}

.custom-footer .social-link-item {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 36px !important;
    height: 36px !important;
    background: transparent !important;
    border: none !important;
    color: #00d2ff !important;
    text-decoration: none !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: none !important;
    padding: 0 !important;
}

.custom-footer .social-link-item i {
    font-size: 22px !important;
    line-height: 1 !important;
    color: inherit !important;
    transition: transform 0.3s ease, color 0.3s ease, filter 0.3s ease !important;
}

.custom-footer .social-link-item:hover {
    transform: translateY(-3px) !important;
}

.custom-footer .social-link-item:hover i {
    color: #ffffff !important;
    transform: scale(1.15) !important;
    filter: drop-shadow(0 0 6px rgba(0, 210, 255, 0.6)) !important;
}

/* 3. Section Titles */
.custom-footer .footer-title {
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 16px !important;
    margin-bottom: 18px !important;
    position: relative !important;
    padding-bottom: 6px !important;
    display: inline-block !important;
}

.custom-footer .footer-title::after {
    content: '' !important;
    position: absolute !important;
    bottom: 0 !important;
    left: 0 !important;
    width: 30px !important;
    height: 2.5px !important;
    background: linear-gradient(90deg, #00d2ff, #0d6efd) !important;
    border-radius: 4px !important;
}

/* 4. Quick Links */
.custom-footer .footer-links {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.custom-footer .footer-links li {
    margin-bottom: 12px !important;
}

.custom-footer .footer-links a {
    color: #94a3b8 !important;
    text-decoration: none !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    transition: all 0.25s ease !important;
    background: transparent !important;
    padding: 0 !important;
}

.custom-footer .footer-links a i {
    font-size: 12px !important;
    color: #00d2ff !important;
    transition: transform 0.25s ease, filter 0.25s ease !important;
}

.custom-footer .footer-links a:hover {
    color: #ffffff !important;
    transform: translateX(5px) !important;
}

.custom-footer .footer-links a:hover i {
    filter: drop-shadow(0 0 5px rgba(0, 210, 255, 0.5)) !important;
}

/* 5. Contact Section Icons */
.custom-footer .footer-contact {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.custom-footer .footer-contact li {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    color: #94a3b8 !important;
    margin-bottom: 16px !important;
    font-size: 14px !important;
    background: transparent !important;
    transition: color 0.3s ease !important;
}

.custom-footer .footer-contact .contact-icon {
    width: 24px !important;
    height: 24px !important;
    background: transparent !important;
    border: none !important;
    color: #00d2ff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 18px !important;
    flex-shrink: 0 !important;
    transition: all 0.3s ease !important;
    box-shadow: none !important;
}

.custom-footer .footer-contact li:hover {
    color: #ffffff !important;
}

.custom-footer .footer-contact li:hover .contact-icon {
    color: #ffffff !important;
    transform: scale(1.1) !important;
    filter: drop-shadow(0 0 6px rgba(0, 210, 255, 0.6)) !important;
}

/* 6. Copyright Bar */
.custom-footer .footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
    background: rgba(0, 0, 0, 0.3) !important;
    font-size: 13.5px !important;
    color: #64748b !important;
    padding: 16px 0 !important;
    margin-top: 30px !important;
}

.custom-footer .footer-bottom p {
    margin: 0 !important;
    padding: 0 !important;
}

.custom-footer .footer-bottom span {
    color: #00d2ff !important;
    font-weight: 600 !important;
}

/* Responsive Overrides */
@media (max-width: 767.98px) {
    .custom-footer {
        text-align: center !important;
    }
    .custom-footer .brand-wrapper {
        justify-content: center !important;
    }
    .custom-footer .footer-desc {
        margin-left: auto !important;
        margin-right: auto !important;
    }
    .custom-footer .social-links {
        justify-content: center !important;
        margin-bottom: 25px !important;
    }
    .custom-footer .footer-title::after {
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
    .custom-footer .footer-links a {
        justify-content: center !important;
    }
    .custom-footer .footer-contact li {
        justify-content: center !important;
    }
}
</style>

<!-- خط الليد النيون الذكي -->
<div class="neon-led-bar"></div>

<footer class="custom-footer">
    <div class="container footer-container">
        <div class="row g-4 justify-content-between">

            <!-- Col 1: Brand & Socials -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="brand-wrapper">
                    <div class="brand-icon-footer">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <span class="brand-title">Job Portal</span>
                </div>

                <p class="footer-desc">
                    Discover thousands of job opportunities with top companies. Build your career and take your professional path to the next level.
                </p>

                <div class="social-links">
                    <a href="#" class="social-link-item" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-link-item" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="social-link-item" title="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" class="social-link-item" title="Twitter / X">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-3 col-md-6 col-12 ps-lg-4">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li>
                        <a href="<?php echo (file_exists('index.php') ? 'index.php' : '../index.php'); ?>">
                            <i class="bi bi-chevron-right"></i>Home
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo (file_exists('jobs.php') ? 'jobs.php' : '../jobs.php'); ?>">
                            <i class="bi bi-chevron-right"></i>Browse Jobs
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo (file_exists('login.php') ? 'login.php' : '../login.php'); ?>">
                            <i class="bi bi-chevron-right"></i>Login
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo (file_exists('register.php') ? 'register.php' : '../register.php'); ?>">
                            <i class="bi bi-chevron-right"></i>Register Now
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Contact Info -->
            <div class="col-lg-4 col-md-12 col-12">
                <h5 class="footer-title">Contact Us</h5>
                <ul class="footer-contact">
                    <li>
                        <div class="contact-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <span>info@jobportal.com</span>
                    </li>
                    <li>
                        <div class="contact-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <span>+20 123 456 789</span>
                    </li>
                    <li>
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <span>Alexandria, Egypt</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Copyright Bar -->
    <div class="footer-bottom text-center">
        <div class="container">
            <p>
                © <?php echo date("Y"); ?> <span>Job Portal</span>. All Rights Reserved.
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>