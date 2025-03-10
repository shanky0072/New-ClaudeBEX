<?php
// Initialize the database connection
$db = new SQLite3('database/consultancy.db');

// Fetch services data
$stmt = $db->prepare('SELECT * FROM services ORDER BY id ASC');
$result = $stmt->execute();
$services = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $services[] = $row;
}

// Fetch dynamic content
$stmt = $db->prepare('SELECT * FROM site_content WHERE page = "services" AND section = "hero"');
$result = $stmt->execute();
$heroContent = $result->fetchArray(SQLITE3_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services | Process Excellence Consultancy</title>
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
        <section class="page-hero services-hero">
            <div class="container">
                <div class="hero-content">
                    <h1><?php echo htmlspecialchars($heroContent['title'] ?? 'Our Services'); ?></h1>
                    <p class="hero-subtitle"><?php echo htmlspecialchars($heroContent['subtitle'] ?? 'Comprehensive process excellence solutions tailored to your business needs'); ?></p>
                </div>
            </div>
        </section>

        <!-- Services Introduction -->
        <section class="services-intro">
            <div class="container">
                <div class="intro-content">
                    <h2>How We Help</h2>
                    <p>Our consultancy specializes in optimizing business processes, enhancing automation, and integrating AI-driven solutions to improve operational performance. We work across industries with a focus on healthcare, financial services, and operations-intensive businesses.</p>
                    <p>We partner with our clients to identify inefficiencies, implement solutions, and drive measurable improvements that impact the bottom line.</p>
                </div>
            </div>
        </section>

        <!-- Services Detailed -->
        <section class="services-detailed">
            <div class="container">
                <?php foreach ($services as $index => $service): ?>
                <div id="<?php echo htmlspecialchars($service['slug']); ?>" class="service-detailed <?php echo $index % 2 == 0 ? '' : 'service-alt'; ?>">
                    <div class="service-image">
                        <img src="<?php echo htmlspecialchars($service['image_url']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                    </div>
                    <div class="service-info">
                        <h2><?php echo htmlspecialchars($service['title']); ?></h2>
                        <p class="service-description"><?php echo htmlspecialchars($service['description']); ?></p>
                        
                        <h3>What We Deliver</h3>
                        <ul class="service-deliverables">
                            <?php 
                            $deliverables = explode('|', $service['deliverables']);
                            foreach ($deliverables as $deliverable):
                            ?>
                            <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($deliverable); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="service-cta">
                            <a href="contact.php?service=<?php echo urlencode($service['title']); ?>" class="btn btn-primary">Inquire About This Service</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Industries We Serve -->
        <section class="industries bg-light">
            <div class="container">
                <div class="section-header">
                    <h2>Industries We Serve</h2>
                    <p>Specialized expertise across key sectors</p>
                </div>
                <div class="industries-grid">
                    <div class="industry-card">
                        <div class="industry-icon"><i class="fas fa-hospital"></i></div>
                        <h3>Healthcare</h3>
                        <p>Optimizing revenue cycle, claims processing, patient experience, and compliance workflows</p>
                    </div>
                    <div class="industry-card">
                        <div class="industry-icon"><i class="fas fa-landmark"></i></div>
                        <h3>Financial Services</h3>
                        <p>Streamlining loan processing, document management, and regulatory compliance</p>
                    </div>
                    <div class="industry-card">
                        <div class="industry-icon"><i class="fas fa-industry"></i></div>
                        <h3>Manufacturing</h3>
                        <p>Enhancing production workflows, quality control processes, and supply chain management</p>
                    </div>
                    <div class="industry-card">
                        <div class="industry-icon"><i class="fas fa-shopping-cart"></i></div>
                        <h3>Retail</h3>
                        <p>Improving inventory management, customer service processes, and omnichannel operations</p>
                    </div>
                    <div class="industry-card">
                        <div class="industry-icon"><i class="fas fa-truck"></i></div>
                        <h3>Logistics</h3>
                        <p>Optimizing transportation management, warehouse operations, and delivery processes</p>
                    </div>
                    <div class="industry-card">
                        <div class="industry-icon"><i class="fas fa-building"></i></div>
                        <h3>Professional Services</h3>
                        <p>Enhancing project management, client onboarding, and service delivery workflows</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Engagement Models -->
        <section class="engagement-models">
            <div class="container">
                <div class="section-header">
                    <h2>Engagement Models</h2>
                    <p>Flexible partnership options to meet your needs</p>
                </div>
                <div class="models-grid">
                    <div class="model-card">
                        <div class="model-icon"><i class="fas fa-search"></i></div>
                        <h3>Assessment & Strategy</h3>
                        <p>Comprehensive evaluation of current processes with strategic recommendations for improvement</p>
                        <ul>
                            <li>Process mapping and analysis</li>
                            <li>Opportunity identification</li>
                            <li>Prioritized recommendations</li>
                            <li>ROI projections</li>
                        </ul>
                        <div class="duration">Typical Duration: 2-4 weeks</div>
                    </div>
                    <div class="model-card featured">
                        <div class="model-icon"><i class="fas fa-project-diagram"></i></div>
                        <h3>Project-Based Implementation</h3>
                        <p>End-to-end execution of specific process improvement or automation initiatives</p>
                        <ul>
                            <li>Detailed implementation plan</li>
                            <li>Solution development and testing</li>
                            <li>Team training and knowledge transfer</li>
                            <li>Post-implementation support</li>
                        </ul>
                        <div class="duration">Typical Duration: 1-3 months</div>
                    </div>
                    <div class="model-card">
                        <div class="model-icon"><i class="fas fa-handshake"></i></div>
                        <h3>Retainer Partnership</h3>
                        <p>Ongoing support and optimization for continuous process excellence</p>
                        <ul>
                            <li>Regular process reviews</li>
                            <li>Continuous improvement initiatives</li>
                            <li>Performance monitoring</li>
                            <li>On-demand advisory support</li>
                        </ul>
                        <div class="duration">Minimum Term: 6 months</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Methodologies -->
        <section class="methodologies bg-light">
            <div class="container">
                <div class="section-header">
                    <h2>Our Methodologies</h2>
                    <p>Proven approaches that deliver consistent results</p>
                </div>
                <div class="methodologies-wrapper">
                    <div class="methodology">
                        <div class="methodology-header">
                            <div class="methodology-icon"><i class="fas fa-sync-alt"></i></div>
                            <h3>Lean Six Sigma</h3>
                        </div>
                        <p>We combine Lean principles for eliminating waste with Six Sigma methodologies for reducing variation to create highly efficient, consistent processes.</p>
                    </div>
                    <div class="methodology">
                        <div class="methodology-header">
                            <div class="methodology-icon"><i class="fas fa-sitemap"></i></div>
                            <h3>Business Process Management (BPM)</h3>
                        </div>
                        <p>Our structured BPM approach ensures processes are aligned with strategic objectives and continuously optimized for maximum business value.</p>
                    </div>
                    <div class="methodology">
                        <div class="methodology-header">
                            <div class="methodology-icon"><i class="fas fa-robot"></i></div>
                            <h3>Robotic Process Automation (RPA)</h3>
                        </div>
                        <p>We leverage RPA technologies to automate repetitive tasks, reducing human error and freeing up employees to focus on higher-value activities.</p>
                    </div>
                    <div class="methodology">
                        <div class="methodology-header">
                            <div class="methodology-icon"><i class="fas fa-brain"></i></div>
                            <h3>AI-Enhanced Workflow Optimization</h3>
                        </div>
                        <p>Our proprietary approach integrates artificial intelligence to create intelligent workflows that adapt and improve over time.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Technologies -->
        <section class="technologies">
            <div class="container">
                <div class="section-header">
                    <h2>Technology Expertise</h2>
                    <p>Leading platforms and tools we leverage for process excellence</p>
                </div>
                <div class="tech-logos">
                    <div class="tech-logo">
                        <img src="images/tech/microsoft.svg" alt="Microsoft">
                        <span>Microsoft Power Platform</span>
                    </div>
                    <div class="tech-logo">
                        <img src="images/tech/uipath.svg" alt="UiPath">
                        <span>UiPath</span>
                    </div>
                    <div class="tech-logo">
                        <img src="images/tech/sharepoint.svg" alt="SharePoint">
                        <span>SharePoint</span>
                    </div>
                    <div class="tech-logo">
                        <img src="images/tech/salesforce.svg" alt="Salesforce">
                        <span>Salesforce</span>
                    </div>
                    <div class="tech-logo">
                        <img src="images/tech/python.svg" alt="Python">
                        <span>Python</span>
                    </div>
                    <div class="tech-logo">
                        <img src="images/tech/tableau.svg" alt="Tableau">
                        <span>Tableau</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Optimize Your Business Processes?</h2>
                    <p>Book a consultation call to discuss your specific challenges and how we can help.</p>
                    <a href="contact.php" class="btn btn-primary btn-large">Schedule Consultation <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
