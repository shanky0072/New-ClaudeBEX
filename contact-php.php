<?php
// Initialize the database connection
$db = new SQLite3('database/consultancy.db');

// Initialize variables
$name = $email = $phone = $company = $service = $message = '';
$errors = [];
$success = false;

// Pre-populate service if passed in URL
if (isset($_GET['service'])) {
    $service = $_GET['service'];
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name)) {
        $errors['name'] = 'Please enter your name';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Please enter your email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    if (empty($message)) {
        $errors['message'] = 'Please enter your message';
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        $stmt = $db->prepare('INSERT INTO contact_submissions (name, email, phone, company, service, message, submission_date) 
                             VALUES (:name, :email, :phone, :company, :service, :message, :date)');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
        $stmt->bindValue(':company', $company, SQLITE3_TEXT);
        $stmt->bindValue(':service', $service, SQLITE3_TEXT);
        $stmt->bindValue(':message', $message, SQLITE3_TEXT);
        $stmt->bindValue(':date', date('Y-m-d H:i:s'), SQLITE3_TEXT);
        
        $result = $stmt->execute();
        
        if ($result) {
            $success = true;
            // Reset form fields
            $name = $email = $phone = $company = $service = $message = '';
        } else {
            $errors['database'] = 'There was an error saving your submission. Please try again.';
        }
    }
}

// Fetch services for dropdown
$stmt = $db->prepare('SELECT title FROM services ORDER BY title ASC');
$result = $stmt->execute();
$services = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $services[] = $row['title'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Process Excellence Consultancy</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="page-hero contact-hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Get In Touch</h1>
                    <p class="hero-subtitle">Let's discuss how we can help optimize your business operations</p>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="contact-info">
                        <h2>Contact Information</h2>
                        <p>Ready to eliminate inefficiencies and maximize your business potential? Reach out to our team today.</p>
                        
                        <div class="contact-details">
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <h3>Email</h3>
                                    <p><a href="mailto:contact@processexcellence.com">contact@processexcellence.com</a></p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                                <div>
                                    <h3>Phone</h3>
                                    <p><a href="tel:+15551234567">+1 (555) 123-4567</a></p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <h3>Office</h3>
                                    <p>123 Business Avenue<br>Suite 500<br>New York, NY 10001</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-social">
                            <h3>Follow Us</h3>
                            <div class="social-icons">
                                <a href="#" target="_blank" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="#" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                                <a href="#" target="_blank" class="social-icon"><i class="fab fa-facebook"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-form-container">
                        <?php if ($success): ?>
                        <div class="success-message">
                            <div class="success-icon"><i class="fas fa-check-circle"></i></div>
                            <h2>Thank You!</h2>
                            <p>Your message has been sent successfully. We'll get back to you shortly.</p>
                            <a href="index.php" class="btn btn-primary">Back to Home</a>
                        </div>
                        <?php else: ?>
                        <form action="contact.php" method="post" class="contact-form">
                            <h2>Send Us a Message</h2>
                            
                            <?php if (isset($errors['database'])): ?>
                            <div class="error-alert"><?php echo $errors['database']; ?></div>
                            <?php endif; ?>
                            
                            <div class="form-row">
                                <div class="form-group <?php echo isset($errors['name']) ? 'has-error' : ''; ?>">
                                    <label for="name">Full Name *</label>
                                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                                    <?php if (isset($errors['name'])): ?>
                                    <div class="error-message"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    <?php if (isset($errors['email'])): ?>
                                    <div class="error-message"><?php echo $errors['email']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="company">Company Name</label>
                                    <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($company); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="service">Service of Interest</label>
                                <select id="service" name="service">
                                    <option value="">Select a service</option>
                                    <?php foreach ($services as $serviceOption): ?>
                                    <option value="<?php echo htmlspecialchars($serviceOption); ?>" <?php echo $service === $serviceOption ? 'selected' : ''; ?>><?php echo htmlspecialchars($serviceOption); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group <?php echo isset($errors['message']) ? 'has-error' : ''; ?>">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message" rows="4" required><?php echo htmlspecialchars($message); ?></textarea>
                                <?php if (isset($errors['message'])): ?>
                                <div class="error-message"><?php echo $errors['message']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-submit">
                                <button type="submit" class="btn btn-primary btn-large">Send Message</button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section bg-light">
            <div class="container">
                <div class="section-header">
                    <h2>Frequently Asked Questions</h2>
                    <p>Common questions about our consulting services</p>
                </div>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How long does a typical consulting engagement last?</h3>
                            <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                        </div>
                        <div class="faq-answer">
                            <p>The duration of our engagements varies based on the scope and complexity of the project. Assessment projects typically take 2-4 weeks, implementation projects range from 1-3 months, and ongoing partnerships can be established for 6+ months. We'll provide a detailed timeline during our initial consultation.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What industries do you specialize in?</h3>
                            <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                        </div>
                        <div class="faq-answer">
                            <p>We have deep expertise in healthcare, financial services, and manufacturing industries, but our process excellence methodologies can be applied across various sectors. Our team has successfully completed projects in retail, logistics, professional services, and more.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do you measure the success of your consulting projects?</h3>
                            <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                        </div>
                        <div class="faq-answer">
                            <p>We establish clear, measurable KPIs at the beginning of each engagement. These typically include efficiency gains, cost savings, error reduction rates, and turnaround time improvements. We provide regular reports tracking these metrics throughout the project lifecycle.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What is your pricing structure?</h3>
                            <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                        </div>
                        <div class="faq-answer">
                            <p>Our pricing is customized based on project scope, complexity, and duration. We offer fixed-price projects, hourly rates, and retainer models. After our initial consultation, we'll provide a detailed proposal with transparent pricing that aligns with your specific needs and budget.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How quickly can you start a new project?</h3>
                            <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                        </div>
                        <div class="faq-answer">
                            <p>For most clients, we can initiate the discovery phase within 1-2 weeks of signing an engagement letter. Our agile team structure allows us to mobilize quickly for urgent needs. During our initial consultation, we'll discuss your timeline requirements and work to accommodate them.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
