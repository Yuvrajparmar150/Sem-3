<?php
/**
 * Practical 7: Submissions & CSV/JSON Data Records Viewer
 * File: php/submissions.php
 */

$dataDir = dirname(__DIR__) . '/data';
$jsonFile = $dataDir . '/registrations.json';
$csvFile  = $dataDir . '/registrations.csv';

$registrations = [];
if (file_exists($jsonFile)) {
    $registrations = json_decode(file_get_contents($jsonFile), true) ?? [];
}

$contacts = [];
$contactFile = $dataDir . '/contacts.json';
if (file_exists($contactFile)) {
    $contacts = json_decode(file_get_contents($contactFile), true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 7: CSV & JSON Submissions | StudentHub</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <a href="#main-content" class="skip-link">Skip to Content</a>

  <!-- Header -->
  <header class="site-header">
    <div class="container nav-container">
      <a href="../index.html" class="brand-logo">
        <span class="logo-icon">🎓</span>
        <span>StudentHub</span>
        <span class="badge-tag">PHP Backend</span>
      </a>
      <nav aria-label="Main Navigation">
        <ul class="nav-links">
          <li class="nav-item"><a href="../index.html" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="../dashboard.html" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="../events.html" class="nav-link">Events</a></li>
          <li class="nav-item"><a href="../register.html" class="nav-link">Register</a></li>
          <li class="nav-item"><a href="../admin.html" class="nav-link">Admin Portal</a></li>
          <li class="nav-item"><a href="submissions.php" class="nav-link active">Submissions (PHP)</a></li>
        </ul>
      </nav>
      <div class="nav-actions">
        <button class="theme-toggle-btn" aria-label="Toggle theme">🌙</button>
      </div>
    </div>
  </header>

  <main id="main-content" class="page-wrapper container">
    <div class="breadcrumb">
      <a href="../index.html">Home</a>
      <span class="separator">/</span>
      <a href="../admin.html">Admin</a>
      <span class="separator">/</span>
      <span class="current">PHP File Storage Submissions (Practical 7)</span>
    </div>

    <div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, rgba(37,99,235,0.08), rgba(6,182,212,0.08));">
      <h2>Server-Side Stored Records (CSV & JSON)</h2>
      <p>This page dynamically parses records stored by <code>php/process_register.php</code> in both <code>data/registrations.json</code> and <code>data/registrations.csv</code> with strict validation and sanitization.</p>
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <button onclick="exportTableToCSV('studenthub_registrations.csv')" class="btn btn-primary btn-sm">
          📥 Download CSV File
        </button>
        <a href="../register.html" class="btn btn-outline-primary btn-sm">
          ➕ Add New Registration
        </a>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid-4" style="margin-bottom: 2rem;">
      <div class="stat-card">
        <div class="stat-icon blue">📋</div>
        <div class="stat-info">
          <div class="stat-value"><?= count($registrations); ?></div>
          <div class="stat-label">Total Registrations</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green">💾</div>
        <div class="stat-info">
          <div class="stat-value"><?= file_exists($jsonFile) ? round(filesize($jsonFile) / 1024, 2) : 0; ?> KB</div>
          <div class="stat-label">JSON Storage Size</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon purple">📊</div>
        <div class="stat-info">
          <div class="stat-value"><?= file_exists($csvFile) ? round(filesize($csvFile) / 1024, 2) : 0; ?> KB</div>
          <div class="stat-label">CSV Storage Size</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon amber">✉️</div>
        <div class="stat-info">
          <div class="stat-value"><?= count($contacts); ?></div>
          <div class="stat-label">Contact Inquiries</div>
        </div>
      </div>
    </div>

    <!-- Registration Records Table -->
    <section class="card" style="margin-bottom: 2.5rem;">
      <div class="card-header">
        <h3>Student Registration Records (From <code>data/registrations.json</code>)</h3>
        <span class="badge badge-primary"><?= count($registrations); ?> Records</span>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Reg ID</th>
              <th>Full Name</th>
              <th>Student ID</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Course</th>
              <th>Year</th>
              <th>Location</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($registrations)): ?>
              <tr><td colspan="9" style="text-align: center; padding: 2rem;">No registrations recorded yet.</td></tr>
            <?php else: ?>
              <?php foreach ($registrations as $row): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($row['id'] ?? 'REG-N/A'); ?></strong></td>
                  <td><strong><?= htmlspecialchars($row['name'] ?? ''); ?></strong></td>
                  <td><?= htmlspecialchars($row['studentId'] ?? '-'); ?></td>
                  <td><a href="mailto:<?= htmlspecialchars($row['email'] ?? ''); ?>"><?= htmlspecialchars($row['email'] ?? ''); ?></a></td>
                  <td><?= htmlspecialchars($row['mobile'] ?? '-'); ?></td>
                  <td><?= htmlspecialchars($row['course'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($row['year'] ?? ''); ?></td>
                  <td><?= htmlspecialchars(($row['city'] ?? '') . ', ' . ($row['state'] ?? '')); ?></td>
                  <td><span class="badge badge-success"><?= htmlspecialchars($row['status'] ?? 'Verified'); ?></span></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Contacts Table -->
    <section class="card">
      <div class="card-header">
        <h3>Contact Us Messages (From <code>data/contacts.json</code>)</h3>
        <span class="badge badge-primary"><?= count($contacts); ?> Messages</span>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($contacts)): ?>
              <tr><td colspan="6" style="text-align:center; padding: 2rem;">No contact messages yet.</td></tr>
            <?php else: ?>
              <?php foreach ($contacts as $c): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($c['id'] ?? ''); ?></strong></td>
                  <td><?= htmlspecialchars($c['name'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($c['email'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($c['subject'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($c['message'] ?? ''); ?></td>
                  <td><small><?= htmlspecialchars($c['timestamp'] ?? ''); ?></small></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container footer-bottom">
      <p>&copy; 2026 StudentHub - CHARUSAT FTE (ITUE203).</p>
      <span class="developer-tag">Yuvraj Parmar (25DCE070)</span>
    </div>
  </footer>

  <script src="../js/main.js"></script>
</body>
</html>
