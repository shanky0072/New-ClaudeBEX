<?php
// Initialize the database connection
$db = new SQLite3('database/consultancy.db');

// Fetch case studies data
$stmt = $db->prepare('SELECT * FROM case_studies ORDER BY id DESC');
$result = $stmt->execute();
$caseStudies = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $caseStudies[] = $row;
}

// Fetch industries for filter
$stmt = $db->prepare('SELECT DISTINCT industry FROM case_studies ORDER BY industry ASC');
$result = $stmt->execute();
$industries = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $industries[] = $row['industry'];
}

// Handle filters
$industryFilter = isset($_GET['industry']) ? $_GET['industry'] : '';
if (!empty($industryFilter)) {
    $filteredCases = [];
    foreach ($caseStudies as $case) {
        if ($case['industry'] == $industryFilter) {
            $filteredCases[] = $case;
        }
    }
    $caseStudies = $filteredCases;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Studies | Process Excellence Consultancy</title>
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
        <section class="page-hero case-studies-hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Client Success Stories</h1>
                    <p class="hero-subtitle">Real results for real businesses through process excellence</p>
                </div>
            </div>
        </section>

        <!-- Case Studies Listing -->
        <section class="case-studies-listing">
            <div class="container">
                <!-- Filters -->
                <div class="case-study-filters">
                    <h3>Filter by Industry:</h3>
                    <div class="filter-buttons">
                        <a href="case-studies.php" class="filter-btn <?php echo empty($industryFilter) ? 'active' : ''; ?>">All Industries</a>
                        <?php foreach ($industries as $industry): ?>
                        <a href="case-studies.php?industry=<?php echo urlencode($industry); ?>" class="filter-btn <?php echo $industryFilter === $industry ? 'active' : ''; ?>"><?php echo htmlspecialchars($industry); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Case Studies Grid -->
                <div class="case-studies-grid">
                    <?php if (empty($caseStudies)): ?>
                    <div class="no-results">
                        <p>No case studies found matching your filter criteria.</p>
                        <a href="case-studies.php" class="btn btn-secondary">View All Case Studies</a>
                    </div>
                    <?php else: ?>
                    <?php foreach ($caseStudies as $case): ?>
                    <div class="case-study-card">
                        <div class="case-image">
                            <img src="<?php echo htmlspecialchars($case['image_url']); ?>" alt="<?php echo htmlspecialchars($case['title']); ?>">
                        </div>
                        <div class="case-content">
                            <span class="industry-tag"><?php echo htmlspecialchars($case['industry']); ?></span>
                            <h3><?php echo htmlspecialchars($case['title']); ?></h3>
                            <p><?php echo htmlspecialchars($case['summary']); ?></p>
                            <div class="case-results">
                                <div class="result-item">
                                    <span class="result-number"><?php echo htmlspecialchars($case['result_metric1']); ?></span>
                                    <span class="result-label"><?php echo htmlspecialchars($case['result_label1']); ?></span>
                                </div>
                                <div class="result-item">
                                    <span class="result-number"><?php echo htmlspecialchars($case['result_metric2']); ?></span>
                                    <span class="result-label"><?php echo htmlspecialchars($case['result_label2']); ?></span>
                                </div>
                            </div>
                            <a href="case-study.php?id=<?php echo $case['id']; ?>" class="btn btn-outline">Read Case Study</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Achieve Similar Results?</h2>
                    <p>Book a consultation to discuss how we can help your business achieve process excellence.</p>
                    <a href="contact.php" class="btn btn-primary btn-large">Let's Talk <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
