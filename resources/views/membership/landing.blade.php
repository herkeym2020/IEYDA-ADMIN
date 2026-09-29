<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IEYDA - Become a Financial Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .hero-section {
            padding: 100px 0;
            text-align: center;
            color: white;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        .cta-button {
            background: white;
            color: #667eea;
            padding: 15px 50px;
            font-size: 1.2rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: all 0.3s;
        }
        .cta-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        .benefits {
            background: white;
            padding: 80px 0;
        }
        .benefit-card {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background: #f8f9fa;
            margin-bottom: 30px;
            transition: all 0.3s;
        }
        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }
        .benefit-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <h1>Join IEYDA Financial Membership</h1>
            <p>Empower the youth of Ilorin Emirate and be part of positive change</p>
            <a href="{{ route('membership.payment') }}" class="btn cta-button">
                Become a Member Today
            </a>
        </div>
    </div>

    <div class="benefits">
        <div class="container">
            <h2 class="text-center mb-5">Membership Benefits</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">🎯</div>
                        <h4>Make an Impact</h4>
                        <p>Directly contribute to youth development programs and initiatives</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">🤝</div>
                        <h4>Network</h4>
                        <p>Connect with like-minded individuals committed to community development</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">📢</div>
                        <h4>Stay Informed</h4>
                        <p>Get regular updates on IEYDA programs and community initiatives</p>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <h3 class="mb-4">Membership Tiers</h3>
                    <div class="row justify-content-center">
                        <div class="col-md-3 col-6">
                            <div class="benefit-card">
                                <h5>🥉 Bronze</h5>
                                <h3 class="text-primary">₦5,000</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="benefit-card">
                                <h5>🥈 Silver</h5>
                                <h3 class="text-primary">₦10,000</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="benefit-card">
                                <h5>🥇 Gold</h5>
                                <h3 class="text-primary">₦25,000</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="benefit-card">
                                <h5>⭐ Premium</h5>
                                <h3 class="text-primary">₦50,000</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('membership.payment') }}" class="btn btn-primary btn-lg">
                    Get Started Now →
                </a>
            </div>
        </div>
    </div>

    <footer class="text-center py-4" style="background: rgba(0,0,0,0.1); color: white;">
        <p class="mb-0">&copy; {{ date('Y') }} IEYDA - Ilorin Emirate Youths Development Association</p>
    </footer>
</body>
</html>
