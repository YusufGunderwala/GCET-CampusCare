<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Your Campus. Your Voice. Your Solution.</h1>
                <p class="lead mb-5">A centralized digital platform for students of G. H. Patel College of Engineering & Technology to submit complaints, request campus services, and track resolutions seamlessly.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?= base_url('login.php') ?>" class="btn btn-accent btn-lg px-4 shadow">Submit a Complaint</a>
                    <a href="<?= base_url('track.php') ?>" class="btn btn-outline-light btn-lg px-4 shadow">Track Your Request</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted">A simple and transparent process to keep our campus running smoothly.</p>
        </div>
        
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="card card-hover h-100 py-5 px-4 text-center">
                    <div class="feature-icon-box">
                        <i class="bi bi-pencil-square fs-1 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">1. Submit Request</h5>
                    <p class="text-muted small mb-0">Log in to your student dashboard and submit a complaint or service request.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-hover h-100 py-5 px-4 text-center">
                    <div class="feature-icon-box">
                        <i class="bi bi-building-gear fs-1 text-warning"></i>
                    </div>
                    <h5 class="fw-bold mb-3">2. Department Review</h5>
                    <p class="text-muted small mb-0">The relevant campus department reviews your request and assigns a staff member.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-hover h-100 py-5 px-4 text-center">
                    <div class="feature-icon-box">
                        <i class="bi bi-search fs-1 text-info"></i>
                    </div>
                    <h5 class="fw-bold mb-3">3. Track Updates</h5>
                    <p class="text-muted small mb-0">Check the real-time status of your request directly from your dashboard.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-hover h-100 py-5 px-4 text-center">
                    <div class="feature-icon-box">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-3">4. Resolution</h5>
                    <p class="text-muted small mb-0">The issue is resolved and closed by the department, keeping you informed.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ / Common Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h3 class="fw-bold mb-4">Common Service Categories</h3>
                <ul class="list-group list-group-flush shadow-sm rounded">
                    <li class="list-group-item bg-white"><i class="bi bi-pc-display me-2 text-primary"></i> Computer Laboratory Support</li>
                    <li class="list-group-item bg-white"><i class="bi bi-wifi me-2 text-info"></i> Network and Wi-Fi Issues</li>
                    <li class="list-group-item bg-white"><i class="bi bi-lightning-charge me-2 text-warning"></i> Electrical and Maintenance</li>
                    <li class="list-group-item bg-white"><i class="bi bi-book me-2 text-secondary"></i> Library Services</li>
                    <li class="list-group-item bg-white"><i class="bi bi-building me-2 text-danger"></i> Hostel & Sanitation</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3 class="fw-bold mb-4">Frequently Asked Questions</h3>
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            Who can use this portal?
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            All currently enrolled students and staff of G. H. Patel College of Engineering & Technology can register and use the portal.
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            How long does it take to resolve a complaint?
                        </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Resolution time depends on the nature and priority of the request. Most routine service requests are addressed within 24-48 working hours.
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
