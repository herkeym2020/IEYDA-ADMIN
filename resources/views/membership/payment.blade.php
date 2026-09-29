<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IEYDA - Collective Drive: 5K Commitment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero-section {
            padding: 100px 0 80px;
            position: relative;
            color: white;
        }
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255,255,255,.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,.05) 0%, transparent 50%);
            position: absolute;
            inset: 0;
            pointer-events: none;
        }
        .badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, rgba(255, 179, 0, 0.2), rgba(255, 179, 0, 0.1));
            backdrop-filter: blur(10px);
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 24px;
            border: 1px solid rgba(255, 179, 0, 0.3);
        }
        .hero-title {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.2;
            text-shadow: 0 2px 20px rgba(0,0,0,0.2);
        }
        .hero-description {
            font-size: 1.125rem;
            line-height: 1.7;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto 40px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 50px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }
        
        /* Content Section */
        .content-section {
            background: #f8f9fa;
            padding: 80px 0;
        }
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(46, 125, 50, 0.1);
            color: #2E7D32;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
        }
        .section-description {
            font-size: 1.125rem;
            color: #6c757d;
            line-height: 1.7;
            max-width: 900px;
            margin: 0 auto;
        }
        
        /* Mission Cards */
        .mission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin: 50px 0;
        }
        .mission-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        .mission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(46, 125, 50, 0.15);
        }
        .mission-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 20px;
        }
        .mission-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
        }
        .mission-text {
            font-size: 0.9375rem;
            color: #6c757d;
            line-height: 1.6;
        }
        
        /* Form Section */
        .form-section {
            background: white;
            padding: 60px 0;
        }
        .form-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.9375rem;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
            outline: none;
        }
        
        /* Tier Selection */
        .tier-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }
        .tier-option {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 16px;
            padding: 24px 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .tier-option:hover {
            border-color: #2E7D32;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(46, 125, 50, 0.15);
        }
        .tier-option.selected {
            border-color: #2E7D32;
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.08) 0%, rgba(27, 94, 32, 0.08) 100%);
            box-shadow: 0 8px 24px rgba(46, 125, 50, 0.2);
        }
        .tier-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        .tier-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }
        .tier-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2E7D32;
            margin-bottom: 4px;
        }
        .tier-label {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        /* Bank Details Section */
        .bank-details-card {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            border-radius: 20px;
            padding: 40px;
            color: white;
            margin-top: 40px;
            position: relative;
            overflow: hidden;
        }
        .bank-details-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        .bank-details-card .content {
            position: relative;
            z-index: 1;
        }
        .bank-info-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }
        .bank-info-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .bank-info-value {
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .copy-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.875rem;
        }
        .copy-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            border: none;
            padding: 18px 48px;
            border-radius: 50px;
            font-size: 1.125rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(46, 125, 50, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(46, 125, 50, 0.4);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0 40px;
            }
            .content-section, .form-section {
                padding: 50px 0;
            }
            .form-card, .bank-details-card {
                padding: 24px;
            }
            .tier-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="text-center">
                <div class="badge-custom">
                    <i class="bi bi-heart-fill" style="color: #FFB300;"></i>
                    Financial Membership
                </div>
                <h1 class="hero-title">
                    Collective Drive:<br>A 5K Commitment for Community Development
                </h1>
                <p class="hero-description">
                    Welcome to the <strong>Ilorin Emirate Youth Development Association (IEYDA)</strong>, a collective of sons and daughters of Ilorin Emirate who believe that community progress is not accidental, but intentional.
                </p>
                
                <div class="stats-grid">
                    <div class="stat-card" style="background: rgba(46, 125, 50, 0.2); border: 1px solid rgba(46, 125, 50, 0.3);">
                        <i class="bi bi-people-fill" style="font-size: 2rem; margin-bottom: 12px; color: #2E7D32;"></i>
                        <div class="stat-number">Community</div>
                        <div class="stat-label">United Together</div>
                    </div>
                    <div class="stat-card" style="background: rgba(255, 179, 0, 0.2); border: 1px solid rgba(255, 179, 0, 0.3);">
                        <i class="bi bi-heart-pulse-fill" style="font-size: 2rem; margin-bottom: 12px; color: #FFB300;"></i>
                        <div class="stat-number">₦5,000</div>
                        <div class="stat-label">Monthly Commitment</div>
                    </div>
                    <div class="stat-card" style="background: rgba(25, 118, 210, 0.2); border: 1px solid rgba(25, 118, 210, 0.3);">
                        <i class="bi bi-graph-up-arrow" style="font-size: 2rem; margin-bottom: 12px; color: #1976D2;"></i>
                        <div class="stat-number">Growth</div>
                        <div class="stat-label">Sustainable Impact</div>
                    </div>
                    <div class="stat-card" style="background: rgba(46, 125, 50, 0.2); border: 1px solid rgba(46, 125, 50, 0.3);">
                        <i class="bi bi-shield-check" style="font-size: 2rem; margin-bottom: 12px; color: #2E7D32;"></i>
                        <div class="stat-number">Verified</div>
                        <div class="stat-label">Confidential</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge">
                    <i class="bi bi-lightbulb-fill" style="color: #FFB300;"></i>
                    Our Mission
                </div>
                <h2 class="section-title">Built on a Simple Truth</h2>
                <p class="section-description">
                    When individuals take responsibility together, communities grow stronger and more resilient. 
                    By committing ₦5,000 monthly, you are supporting community-based initiatives, and long-term 
                    projects designed to improve the social and economic well-being of Ilorin Emirate.
                </p>
            </div>

            <div class="mission-grid">
                <div class="mission-card">
                    <div class="mission-icon" style="background: rgba(25, 118, 210, 0.1); color: #1976D2;">
                        <i class="bi bi-droplet-fill"></i>
                    </div>
                    <h3 class="mission-title">Empowerment to Less Privileged</h3>
                    <p class="mission-text">Providing essential resources like water and environmental support to those who need it most in our communities.</p>
                </div>

                <div class="mission-card">
                    <div class="mission-icon" style="background: rgba(255, 179, 0, 0.1); color: #FFB300;">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <h3 class="mission-title">Support for Widows</h3>
                    <p class="mission-text">Standing with widows in our community through practical assistance and sustainable livelihood programs.</p>
                </div>

                <div class="mission-card">
                    <div class="mission-icon" style="background: rgba(46, 125, 50, 0.1); color: #2E7D32;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h3 class="mission-title">Education & Moral Support</h3>
                    <p class="mission-text">Investing in educational opportunities and character development for the next generation.</p>
                </div>

                <div class="mission-card">
                    <div class="mission-icon" style="background: rgba(25, 118, 210, 0.1); color: #1976D2;">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <h3 class="mission-title">Awareness & Orientation</h3>
                    <p class="mission-text">Creating awareness and providing proper orientation on community development and civic responsibility.</p>
                </div>
            </div>

            <div class="text-center mt-5">
                <p style="font-size: 1.25rem; font-style: italic; color: #2E7D32; font-weight: 600;">
                    "This is not a one-time gesture, but a steady contribution towards shared growth and sustainable development as a financial member of IEYDA."
                </p>
                <p style="font-size: 1.125rem; color: #6c757d; margin-top: 20px; font-weight: 500;">
                    Progress begins when responsibility is shared.
                </p>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="form-section">
        <div class="container form-container">
            <div class="text-center mb-5">
                <div class="section-badge">
                    <i class="bi bi-person-check-fill" style="color: #1976D2;"></i>
                    Commitment Form
                </div>
                <h2 class="section-title">Join the Movement</h2>
                <p class="section-description">
                    This form signifies your commitment and willingness to stand in solidarity with the community. 
                    Please complete it carefully. All information provided will be verified and treated with strict confidentiality.
                </p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger rounded-3 mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="form-card">
                        <form id="paymentForm">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-cash-stack me-2" style="color: #FFB300;"></i>
                                    Select Monthly Commitment Amount *
                                </label>
                                <div class="tier-grid">
                                    <label class="tier-option" onclick="selectTier(this, 5000)">
                                        <input type="radio" name="tier" value="5000" required>
                                        <div class="tier-icon">🥉</div>
                                        <div class="tier-amount">₦5,000</div>
                                        <div class="tier-label">Bronze</div>
                                    </label>

                                    <label class="tier-option" onclick="selectTier(this, 10000)">
                                        <input type="radio" name="tier" value="10000">
                                        <div class="tier-icon">🥈</div>
                                        <div class="tier-amount">₦10,000</div>
                                        <div class="tier-label">Silver</div>
                                    </label>

                                    <label class="tier-option" onclick="selectTier(this, 25000)">
                                        <input type="radio" name="tier" value="25000">
                                        <div class="tier-icon">🥇</div>
                                        <div class="tier-amount">₦25,000</div>
                                        <div class="tier-label">Gold</div>
                                    </label>

                                    <label class="tier-option" onclick="selectTier(this, 50000)">
                                        <input type="radio" name="tier" value="50000">
                                        <div class="tier-icon">⭐</div>
                                        <div class="tier-amount">₦50,000</div>
                                        <div class="tier-label">Premium</div>
                                    </label>
                                </div>
                                
                                <div style="text-align: center; margin: 20px 0; color: #6c757d; font-weight: 500;">
                                    OR
                                </div>
                                
                                <div>
                                    <label class="form-label">
                                        <i class="bi bi-pencil-square me-2" style="color: #2E7D32;"></i>
                                        Enter Custom Amount (₦)
                                    </label>
                                    <input 
                                        type="number" 
                                        id="customAmount" 
                                        class="form-control" 
                                        placeholder="Enter custom amount (minimum ₦5,000)" 
                                        min="5000"
                                        step="1000"
                                        style="font-size: 1.125rem; height: 56px; padding: 0 20px;"
                                        oninput="handleCustomAmount(this)">
                                    <small class="text-muted" style="display: block; margin-top: 8px;">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Minimum commitment: ₦5,000
                                    </small>
                                </div>
                                
                                <input type="hidden" name="amount" id="amount" required>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-person-fill me-2" style="color: #2E7D32;"></i>
                                        Full Name *
                                    </label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-envelope-fill me-2" style="color: #1976D2;"></i>
                                        Email Address *
                                    </label>
                                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-telephone-fill me-2" style="color: #FFB300;"></i>
                                    Phone Number
                                </label>
                                <input type="tel" name="phone" class="form-control" placeholder="+234 xxx xxx xxxx">
                            </div>

                            <button type="submit" class="btn-submit" id="submitBtn">
                                <span id="btnText">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Complete My Commitment
                                </span>
                                <span id="btnLoading" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Processing...
                                </span>
                            </button>

                            <p class="text-center text-muted mt-3 small">
                                <i class="bi bi-shield-check me-1"></i>
                                Secure payment powered by Paystack
                            </p>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bank-details-card">
                        <div class="content">
                            <h3 class="mb-4" style="font-size: 1.75rem; font-weight: 700;">
                                <i class="bi bi-bank2 me-2"></i>
                                Bank Transfer Details
                            </h3>
                            <p style="font-size: 0.9375rem; opacity: 0.95; margin-bottom: 30px;">
                                Prefer bank transfer? Use the details below to make your commitment payment directly.
                            </p>

                            <div class="bank-info-item">
                                <div class="bank-info-label">Account Number</div>
                                <div class="bank-info-value">
                                    <span>2999563017</span>
                                    <button class="copy-btn" onclick="copyToClipboard('2999563017')">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </div>
                            </div>

                            <div class="bank-info-item">
                                <div class="bank-info-label">Account Name</div>
                                <div class="bank-info-value">
                                    <span style="font-size: 1rem;">ILORIN EMIRATE YOUTH DEVELOPMENT ASSOCIATION</span>
                                </div>
                            </div>

                            <div class="bank-info-item">
                                <div class="bank-info-label">Bank Name</div>
                                <div class="bank-info-value">
                                    <span>FCMB</span>
                                    <i class="bi bi-bank" style="font-size: 1.5rem; opacity: 0.8;"></i>
                                </div>
                            </div>

                            <div style="background: rgba(255,255,255,0.1); padding: 16px; border-radius: 12px; margin-top: 24px;">
                                <p style="font-size: 0.875rem; margin: 0; opacity: 0.95;">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    After transfer, please complete the form above with your details for proper record keeping.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="background: white; border-radius: 16px; padding: 24px; margin-top: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                        <h4 style="font-size: 1.125rem; font-weight: 700; color: #2E7D32; margin-bottom: 16px;">
                            <i class="bi bi-question-circle-fill me-2"></i>
                            Need Help?
                        </h4>
                        <p style="font-size: 0.9375rem; color: #6c757d; margin-bottom: 16px;">
                            Have questions about the commitment or payment process?
                        </p>
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <a href="mailto:info@ieyda.org" style="text-decoration: none; color: #2E7D32; font-size: 0.875rem; font-weight: 600;">
                                <i class="bi bi-envelope-fill me-1"></i>
                                info@ieyda.org
                            </a>
                            <a href="tel:+2348012345678" style="text-decoration: none; color: #2E7D32; font-size: 0.875rem; font-weight: 600;">
                                <i class="bi bi-telephone-fill me-1"></i>
                                +234 801 234 5678
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function selectTier(element, amount) {
            // Remove selected class from all tiers
            document.querySelectorAll('.tier-option').forEach(tier => {
                tier.classList.remove('selected');
            });
            // Add selected class to clicked tier
            element.classList.add('selected');
            // Update amount
            document.getElementById('amount').value = amount;
            // Check the radio
            element.querySelector('input[type="radio"]').checked = true;
            // Fill custom amount field
            document.getElementById('customAmount').value = amount;
        }

        function handleCustomAmount(input) {
            const amount = parseInt(input.value);
            if (amount >= 5000) {
                // Deselect all tiers
                document.querySelectorAll('.tier-option').forEach(tier => {
                    tier.classList.remove('selected');
                    tier.querySelector('input[type="radio"]').checked = false;
                });
                // Update hidden amount field
                document.getElementById('amount').value = amount;
            } else {
                document.getElementById('amount').value = '';
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target.closest('.copy-btn');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                }, 2000);
            });
        }

        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const amount = document.getElementById('amount').value;
            if (!amount || parseInt(amount) < 5000) {
                alert('Please select a tier or enter a custom amount (minimum ₦5,000)');
                return;
            }
            
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const submitBtn = document.getElementById('submitBtn');
            
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            submitBtn.disabled = true;

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('{{ route("financial.member.initiate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (result.success) {
                    // Redirect to Paystack payment page
                    window.location.href = result.authorization_url;
                } else {
                    alert(result.message || 'Payment initialization failed');
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                    submitBtn.disabled = false;
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
