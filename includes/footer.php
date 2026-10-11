</main>

<footer class="footer-custom text-white pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-mortarboard-fill fs-3 text-warning me-2"></i>
                    <h5 class="fw-bold mb-0 text-white">GCET CampusCare</h5>
                </div>
                <p class="text-white-50 small mb-3">Providing a transparent, centralized digital governance platform for G. H. Patel College of Engineering & Technology students and administration.</p>
                <div class="small text-white-50">
                    <i class="bi bi-shield-check text-success me-1"></i> Official Institutional Grievance & Service Portal
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-3">
                <h6 class="text-uppercase fw-bold text-white mb-3" style="letter-spacing: 0.05em; font-size: 0.85rem;">Portal Links</h6>
                <ul class="list-unstyled footer-links small">
                    <li class="mb-2"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="mb-2"><a href="<?= base_url('about.php') ?>">About the Portal</a></li>
                    <li class="mb-2"><a href="<?= base_url('track.php') ?>">Track a Request</a></li>
                    <li class="mb-2"><a href="<?= base_url('login.php') ?>">Portal Login</a></li>
                    <li class="mb-2"><a href="<?= base_url('register.php') ?>">Student Register</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <h6 class="text-uppercase fw-bold text-white mb-3" style="letter-spacing: 0.05em; font-size: 0.85rem;">Campus Services</h6>
                <ul class="list-unstyled footer-links small">
                    <li class="mb-2"><i class="bi bi-chevron-right me-1 text-warning"></i> IT & Wi-Fi Helpdesk</li>
                    <li class="mb-2"><i class="bi bi-chevron-right me-1 text-warning"></i> Laboratory Maintenance</li>
                    <li class="mb-2"><i class="bi bi-chevron-right me-1 text-warning"></i> Electrical & Facilities</li>
                    <li class="mb-2"><i class="bi bi-chevron-right me-1 text-warning"></i> Hostel Administration</li>
                    <li class="mb-2"><i class="bi bi-chevron-right me-1 text-warning"></i> Central Library Support</li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <h6 class="text-uppercase fw-bold text-white mb-3" style="letter-spacing: 0.05em; font-size: 0.85rem;">Contact & Location</h6>
                <address class="text-white-50 small mb-0 lh-lg">
                    <strong class="text-white">G. H. Patel College of Engineering & Technology</strong><br>
                    Bakrol Road, Vallabh Vidyanagar,<br>
                    Anand, Gujarat 388120, India<br>
                    <span class="text-white"><i class="bi bi-telephone me-1 text-warning"></i> +91-2692-231651</span><br>
                    <a href="mailto:info@gcet.ac.in" class="text-white-50"><i class="bi bi-envelope me-1 text-warning"></i> info@gcet.ac.in</a>
                </address>
            </div>
        </div>
        
        <hr class="border-secondary my-4 opacity-25">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-white-50">
            <div class="mb-2 mb-md-0">
                &copy; <?= date('Y') ?> G. H. Patel College of Engineering & Technology (GCET). All Rights Reserved.
            </div>
            <div>
                Affiliated with <strong class="text-white">Charutar Vidya Mandal (CVM) University</strong>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
