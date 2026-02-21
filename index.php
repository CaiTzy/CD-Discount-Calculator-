<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Online Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .hero-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .hero-card {
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .hero-title {
            color: #667eea;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .hero-subtitle {
            color: #6c757d;
            font-size: 1.3rem;
            margin-bottom: 40px;
        }
        .feature-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            transition: transform 0.3s;
        }
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }
        .btn-hero {
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 50px;
            margin: 10px;
            font-weight: bold;
        }
        .btn-primary-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary-hero:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container hero-container">
        <div class="hero-card">
            <div class="mb-5">
                <i class="bi bi-mortarboard-fill" style="font-size: 5rem; color: #667eea;"></i>
            </div>
            
            <h1 class="hero-title">Smart Online Enrollment System</h1>
            <p class="hero-subtitle">
                Streamline your enrollment process with our modern, secure, and user-friendly platform
            </p>
            
            <div class="d-flex justify-content-center flex-wrap mb-5">
                <a href="enrollment_form.php" class="btn btn-primary btn-primary-hero btn-hero">
                    <i class="bi bi-pencil-square"></i> Enroll Now
                </a>
                <a href="login.php" class="btn btn-outline-secondary btn-hero">
                    <i class="bi bi-shield-lock"></i> Admin Login
                </a>
            </div>
            
            <hr class="my-5">
            
            <!-- Features -->
            <div class="row text-start">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h4>Quick & Easy</h4>
                        <p class="text-muted">
                            Complete your enrollment in minutes with our intuitive online form
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Secure</h4>
                        <p class="text-muted">
                            Your data is protected with enterprise-grade security and encryption
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h4>Track Status</h4>
                        <p class="text-muted">
                            Monitor your application status and document verification in real-time
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="row text-start">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-upload"></i>
                        </div>
                        <h4>Document Upload</h4>
                        <p class="text-muted">
                            Upload your requirements digitally - no need to visit the school
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4>Mobile Friendly</h4>
                        <p class="text-muted">
                            Enroll from any device - desktop, tablet, or smartphone
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h4>Support</h4>
                        <p class="text-muted">
                            Our staff is ready to assist you throughout the enrollment process
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-5 text-start">
                <h5><i class="bi bi-info-circle"></i> Available Tracks/Strands:</h5>
                <ul class="mb-0">
                    <li><strong>STEM</strong> - Science, Technology, Engineering, and Mathematics</li>
                    <li><strong>ABM</strong> - Accountancy, Business and Management</li>
                    <li><strong>HUMSS</strong> - Humanities and Social Sciences</li>
                    <li><strong>GAS</strong> - General Academic Strand</li>
                    <li><strong>TVL-ICT</strong> - Technical-Vocational-Livelihood (ICT)</li>
                </ul>
            </div>
            
            <div class="mt-5 text-center">
                <h5 class="text-muted">Enrollment Process</h5>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="badge bg-primary rounded-circle p-3 mb-2" style="font-size: 1.5rem;">1</div>
                        <p class="small"><strong>Fill Form</strong><br>Complete enrollment information</p>
                    </div>
                    <div class="col-md-3">
                        <div class="badge bg-primary rounded-circle p-3 mb-2" style="font-size: 1.5rem;">2</div>
                        <p class="small"><strong>Upload Documents</strong><br>Submit required files</p>
                    </div>
                    <div class="col-md-3">
                        <div class="badge bg-primary rounded-circle p-3 mb-2" style="font-size: 1.5rem;">3</div>
                        <p class="small"><strong>Verification</strong><br>Wait for document review</p>
                    </div>
                    <div class="col-md-3">
                        <div class="badge bg-success rounded-circle p-3 mb-2" style="font-size: 1.5rem;">✓</div>
                        <p class="small"><strong>Enrolled</strong><br>You're officially enrolled!</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-white">
                <small>&copy; <?php echo date('Y'); ?> Smart Online Enrollment System. All rights reserved.</small>
            </p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
