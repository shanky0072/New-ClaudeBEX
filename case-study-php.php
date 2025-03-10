<?php
// Initialize the database connection
$db = new SQLite3('database/consultancy.db');

// Get case study ID from URL
$caseStudyId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID provided, redirect to case studies page
if ($caseStudyId === 0) {
    header('Location: case-studies.php');
    exit;
}

// Fetch case study data
$stmt = $db->prepare('SELECT * FROM case_studies WHERE id = :id');
$stmt->bindValue(':id', $caseStudyId, SQLITE3_INTEGER);
$result = $stmt->execute();
$caseStudy = $result->fetchArray(SQLITE3_ASSOC);

// If case study not found, redirect to case studies page
if (!$caseStudy) {
    header('Location: case-studies.php');
    exit;
}

// Fetch related case studies
$stmt = $db->prepare('SELECT * FROM case_studies WHERE industry = :industry AND id != :id ORDER BY id DESC LIMIT 3');
$stmt->bindValue(':industry', $caseStudy['industry'], SQLITE3_TEXT);
$stmt->bindValue(':id', $caseStudyId, SQLITE3_INTEGER);
$result = $stmt->execute();
$relatedCases = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $relatedCases[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($caseStudy['title']); ?> | Process Excellence Consultancy</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <!-- Case Study Header -->
        <section class="case-study-header">
            <div class="container">
                <div class="case-header-content">
                    <a href="case-studies.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Case Studies</a>
                    <span class="industry-tag"><?php echo htmlspecialchars($caseStudy['industry']); ?></span>
                    <h1><?php echo htmlspecialchars($caseStudy['title']); ?></h1>
                    <p class="case-summary"><?php echo htmlspecialchars($caseStudy['summary']); ?></p>
                    
                    <!-- Key Results -->
                    <div class="key-results">
                        <div class="result-card">
                            <span class="result-number"><?php echo htmlspecialchars($caseStudy['result_metric1']); ?></span>
                            <span class="result-label"><?php echo htmlspecialchars($caseStudy['result_label1']); ?></span>
                        </div>
                        <div class="result-card">
                            <span class="result-number"><?php echo htmlspecialchars($caseStudy['result_metric2']); ?></span>
                            <span class="result-label"><?php echo htmlspecialchars($caseStudy['result_label2']); ?></span>
                        </div>
                        <?php if (!empty($caseStudy['result_metric3'])): ?>
                        <div class="result-card">
                            <span class="result-number"><?php echo htmlspecialchars($caseStudy['result_metric3']); ?></span>
                            <span class="result-label"><?php echo htmlspecialchars($caseStudy['result_label3']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="case-featured-image">
                    <img src="<?php echo htmlspecialchars($caseStudy['image_url']); ?>" alt="<?php echo htmlspecialchars($caseStudy['title']); ?>">
                </div>
            </div>
        </section>

        <!-- Case Study Content -->
        <section class="case-study-content">
            <div class="container">
                <div class="case-main-content">
                    <!-- Client Overview -->
                    <div class="case-section">
                        <h2>Client Overview</h2>
                        <p><?php echo nl2br(htmlspecialchars($caseStudy['client_overview'])); ?></p>
                    </div>
                    
                    <!-- Challenge -->
                    <div class="case-section">
                        <h2>The Challenge</h2>
                        <p><?php echo nl2br(htmlspecialchars($caseStudy['challenge'])); ?></p>
                    </div>
                    
                    <!-- Solution -->
                    <div class="case-section">
                        <h2>Our Solution</h2>
                        <p><?php echo nl2br(htmlspecialchars($caseStudy['solution'])); ?></p>
                    </div>
                    
                    <!-- Process -->
                    <div class="case-section">
                        <h2>Implementation Process</h2>
                        <div class="process-steps">
                            <?php 
                            $steps = explode('|', $caseStudy['implementation_steps']);
                            foreach ($steps as $index => $step):
                            ?>
                            <div class="process-step">
                                <div class="step-number"><?php echo $index + 1; ?></div>
                                <div class="step-content">
                                    <p><?php echo htmlspecialchars($step); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Results -->
                    <div class="case-section">
                        <h2>Results & Impact</h2>
                        <p><?php echo nl2br(htmlspecialchars($caseStudy['results'])); ?></p>
                    </div>
                    
                    <!-- Testimonial (if available) -->
                    <?php if (!empty($caseStudy['testimonial'])): ?>
                    <div class="case-testimonial">
                        <div class="quote-mark"><i class="fas fa-quote-left"></i></div>
                        <blockquote><?php echo htmlspecialchars($caseStudy['testimonial']); ?></blockquote>
                        <div class="testimonial-author">
                            <p><?php echo htmlspecialchars($caseStudy['testimonial_author']); ?></p>
                            <p class="author-title"><?php echo htmlspecialchars($caseStudy['testimonial_author_title']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="case-sidebar">
                    <!-- Case Study Meta Info -->
                    <div class="case-meta">
                        <h3>Project Details</h3>
                        <ul>
                            <li><strong>Industry:</strong> <?php echo htmlspecialchars($caseStudy['industry']); ?></li>
                            <li><strong>Service Type:</strong> <?php echo htmlspecialchars($caseStudy['service_type']); ?></li>
                            <li><strong>Duration:</strong> <?php echo htmlspecialchars($caseStudy['duration']); ?></li>
                            <li><strong>Team Size:</strong> <?php echo htmlspecialchars($caseStudy['team_size']); ?></li>
                        </ul>
                    </div>
                    
                    <!-- Related Services -->
                    <div class="related-services">
                        <h3>Related Services</h3>
                        <ul>
                            <?php
                            $relatedServices = explode('|', $caseStudy['related_services']);
                            foreach ($relatedServices as $service):
                            ?>
                            <li><a href="services.php#<?php echo strtolower(str_replace(' ', '-', $service)); ?>"><?php echo htmlspecialchars($service); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!-- CTA -->
                    <div class="sidebar-cta">
                        <h3>Need Similar Results?</h3>
                        <p>Contact us to discuss how we can help your business achieve process excellence.</p>
                        <a href="contact.php?ref=case-<?php echo $caseStudyId; ?>" class="btn btn-primary">Get in Touch</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Case Studies -->
        <?php if (!empty($relatedCases)): ?>
        <section class="related-cases bg-light">
            <div class="container">
                <h2>Related Case Studies</h2>
                <div class="related-cases-grid">
                    <?php foreach ($relatedCases as $case): ?>
                    <div class="case-study-card">
                        <div class="case-image">
                            <img src="<?php echo htmlspecialchars($case['image_url']); ?>" alt="<?php echo htmlspecialchars($case['title']); ?>">
                        </div>
                        <div class="case-content">
                            <span class="industry-tag"><?php echo htmlspecialchars($case['industry']); ?></span>
                            <h3><?php echo htmlspecialchars($case['title']); ?></h3>
                            <p><?php echo htmlspecialchars($case['summary']); ?></p>
                            <a href="case-study.php?id=<?php echo $case['id']; ?>" class="btn btn-outline">Read Case Study</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- CTA Section -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Transform Your Business Operations?</h2>
                    <p>Book a consultation call to discuss your unique challenges and opportunities.</p>
                    <a href="contact.php" class="btn btn-primary btn-large">Let's Talk <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
