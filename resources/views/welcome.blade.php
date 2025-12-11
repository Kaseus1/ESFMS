<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ESFMS - Efficient School Facility Management System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* Define the core palette */
:root {
--color-dark-navy: #003366; /* Primary Background / Dark Accent */
--color-royal-blue: #0066CC; /* Secondary Accent / Buttons */
--color-gold: #FFC107; /* Tertiary Accent / Highlight */
--color-white: #FFFFFF;
--color-light-gray: #f7fafc; /* Light BG for modal/hover */
--color-text-dark: #1a202c;
--color-text-light: #718096;
}
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}
body {
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
background: var(--color-dark-navy);
min-height: 100vh;
display: flex;
align-items: center;
justify-content: center;
padding: 1rem;
}
.landing-container {
max-width: 1000px;
width: 100%;
}
/* Compact Header */
.header-section {
text-align: center;
color: var(--color-white);
margin-bottom: 2rem;
}
.logo-text {
font-size: 2.5rem;
font-weight: 700;
color: var(--color-gold); /* Gold accent */
letter-spacing: 1px;
margin-bottom: 0.5rem;
}
.header-section h1 {
font-size: 1.1rem;
font-weight: 400;
margin-bottom: 0.5rem;
color: rgba(255, 255, 255, 0.95);
}
.header-section p {
font-size: 0.9rem;
opacity: 0.8;
color: rgba(255, 255, 255, 0.8);
}
/* Compact Login Cards - Side by Side */
.login-options {
display: grid;
grid-template-columns: repeat(2, 1fr);
gap: 1.5rem;
margin-bottom: 1.5rem;
}
.login-card {
background: var(--color-white);
border-radius: 12px;
padding: 2rem 1.5rem;
text-align: center;
transition: all 0.3s ease;
cursor: pointer;
display: flex;
flex-direction: column;
align-items: center;
border: 2px solid transparent; /* Added for clearer hover state */
}
.login-card:hover {
transform: translateY(-4px);
box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
/* Added accent border for primary card hover */
}
.campus-card:hover {
border-color: var(--color-dark-navy);
}
.guest-card:hover {
border-color: var(--color-royal-blue);
}
.login-card-icon {
width: 70px;
height: 70px;
margin-bottom: 1rem;
border-radius: 12px;
display: flex;
align-items: center;
justify-content: center;
font-size: 2rem;
transition: transform 0.3s ease;
}
.login-card:hover .login-card-icon {
transform: scale(1.05);
}
.campus-card .login-card-icon {
background: var(--color-dark-navy);
color: var(--color-gold);
}
.guest-card .login-card-icon {
background: var(--color-royal-blue);
color: var(--color-white);
}
.login-card h2 {
font-size: 1.35rem;
font-weight: 600;
margin-bottom: 0.75rem;
color: var(--color-text-dark);
}
.login-card p {
color: var(--color-text-light);
font-size: 0.9rem;
margin-bottom: 1.25rem;
line-height: 1.5;
}
/* Compact Feature List */
.features-list {
list-style: none;
text-align: left;
width: 100%;
margin-bottom: 1rem;
}
.features-list li {
padding: 0.4rem 0;
color: var(--color-text-light);
font-size: 0.85rem;
display: flex;
align-items: center;
gap: 0.5rem;
}
.features-list li i {
/* Changed from green to Gold accent color */
color: var(--color-gold); 
font-size: 0.75rem;
width: 14px;
}
.login-card-badge {
display: inline-flex;
align-items: center;
gap: 0.4rem;
padding: 0.4rem 0.9rem;
border-radius: 6px;
font-size: 0.8rem;
font-weight: 600;
margin-top: 0.5rem;
}
.campus-card .login-card-badge {
background: var(--color-dark-navy);
color: var(--color-gold);
}
.guest-card .login-card-badge {
background: var(--color-royal-blue);
color: var(--color-white);
}
/* Compact Footer */
.footer-info {
text-align: center;
color: rgba(255, 255, 255, 0.85);
font-size: 0.85rem;
}
.footer-info a {
color: var(--color-gold);
font-weight: 600;
text-decoration: none;
transition: opacity 0.3s ease;
}
.footer-info a:hover {
opacity: 0.8;
}
/* Modal */
.modal {
display: none;
position: fixed;
z-index: 1000;
left: 0;
top: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.5);
}
.modal.active {
display: flex;
justify-content: center;
align-items: center;
}
.modal-content {
background: var(--color-white);
border-radius: 12px;
padding: 2rem;
max-width: 450px;
width: 90%;
position: relative;
box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}
.modal-close {
position: absolute;
top: 1rem;
right: 1rem;
font-size: 1.3rem;
color: var(--color-text-light);
cursor: pointer;
transition: color 0.3s ease;
width: 32px;
height: 32px;
display: flex;
align-items: center;
justify-content: center;
border-radius: 6px;
}
.modal-close:hover {
background: var(--color-light-gray);
color: var(--color-text-dark);
}
.modal-title {
font-size: 1.4rem;
font-weight: 600;
color: var(--color-text-dark);
margin-bottom: 0.5rem;
text-align: center;
}
.modal-subtitle {
color: var(--color-text-light);
text-align: center;
margin-bottom: 1.5rem;
font-size: 0.9rem;
}
.role-buttons {
display: flex;
flex-direction: column;
gap: 0.875rem;
}
.role-btn {
display: flex;
align-items: center;
gap: 1rem;
padding: 1rem;
background: var(--color-white);
border: 2px solid #e2e8f0;
border-radius: 8px;
cursor: pointer;
transition: all 0.3s ease;
text-decoration: none;
color: inherit;
}
.role-btn:hover {
border-color: var(--color-dark-navy);
background: var(--color-light-gray);
transform: translateX(4px);
}
.role-btn-icon {
width: 44px;
height: 44px;
border-radius: 8px;
display: flex;
align-items: center;
justify-content: center;
font-size: 1.3rem;
flex-shrink: 0;
}
.role-btn-icon.faculty {
background: var(--color-royal-blue);
color: var(--color-white);
}
.role-btn-icon.student {
background: var(--color-dark-navy);
color: var(--color-gold);
}
.role-btn-content {
flex: 1;
text-align: left;
}
.role-btn-title {
font-size: 0.95rem;
font-weight: 600;
color: var(--color-text-dark);
margin-bottom: 0.15rem;
}
.role-btn-desc {
font-size: 0.8rem;
color: var(--color-text-light);
}
.role-btn-arrow {
color: #cbd5e0;
font-size: 1.1rem;
transition: all 0.3s ease;
}
.role-btn:hover .role-btn-arrow {
color: var(--color-dark-navy);
transform: translateX(4px);
}
/* Responsive - Mobile First */
@media (max-width: 768px) {
.login-options {
grid-template-columns: 1fr;
gap: 1rem;
}
.login-card {
padding: 1.5rem 1.25rem;
}
.logo-text {
font-size: 2rem;
}
.header-section h1 {
font-size: 1rem;
}
.header-section p {
font-size: 0.85rem;
}
}
@media (max-width: 480px) {
body {
padding: 0.75rem;
}
.header-section {
margin-bottom: 1.5rem;
}
.logo-text {
font-size: 1.75rem;
}
.login-card {
padding: 1.25rem 1rem;
}
.login-card-icon {
width: 60px;
height: 60px;
font-size: 1.75rem;
}
.login-card h2 {
font-size: 1.2rem;
}
.login-card p {
font-size: 0.85rem;
}
.features-list li {
font-size: 0.8rem;
}
}
/* Ensure content fits on screen */
@media (max-height: 700px) {
.header-section {
margin-bottom: 1.25rem;
}
.logo-text {
font-size: 2rem;
margin-bottom: 0.25rem;
}
.header-section h1 {
font-size: 1rem;
}
.header-section p {
display: none; /* Hide tagline on short screens */
}
.login-card {
padding: 1.5rem 1.25rem;
}
.features-list li {
padding: 0.3rem 0;
}
}
</style>
</head>
<body>
<div class="landing-container">
<div class="header-section">
<div class="logo-text">ESFMS</div>
<h1>Efficient School Facility Management System</h1>
<p>Streamline facility reservations and management</p>
</div>
<div class="login-options">
<div class="login-card campus-card" onclick="openModal()">
<div class="login-card-icon">
<i class="fas fa-university"></i>
</div>
<h2>Campus Portal</h2>
<p>Access for faculty and students to manage facility reservations</p>
<ul class="features-list">
<li>
<i class="fas fa-check"></i>
<span>Faculty & Student access</span>
</li>
<li>
<i class="fas fa-check"></i>
<span>Browse & reserve facilities</span>
</li>
<li>
<i class="fas fa-check"></i>
<span>Track booking status</span>
</li>
<li>
<i class="fas fa-check"></i>
<span>Manage your schedule</span>
</li>
</ul>
<div class="login-card-badge">
<i class="fas fa-graduation-cap"></i>
Internal Access
</div>
</div>
<a href="/guest/login" class="login-card guest-card" style="text-decoration: none;">
<div class="login-card-icon">
<i class="fas fa-users"></i>
</div>
<h2>Guest Portal</h2>
<p>Access for alumni and visitors to request facility bookings</p>
<ul class="features-list">
<li>
<i class="fas fa-check"></i>
<span>Alumni & visitor access</span>
</li>
<li>
<i class="fas fa-check"></i>
<span>Submit booking requests</span>
</li>
<li>
<i class="fas fa-check"></i>
<span>Track approval status</span>
</li>
<li>
<i class="fas fa-check"></i>
<span>View available facilities</span>
</li>
</ul>
<div class="login-card-badge">
<i class="fas fa-globe"></i>
External Access
</div>
</a>
</div>
<div class="footer-info">
<p>
<span style="opacity: 0.7;">© 2024 ESFMS</span>
</p>
</div>
</div>
<div id="roleModal" class="modal">
<div class="modal-content">
<div class="modal-close" onclick="closeModal()">
<i class="fas fa-times"></i>
</div>
<h2 class="modal-title">Select Your Role</h2>
<p class="modal-subtitle">Choose how you'd like to access the system</p>
<div class="role-buttons">
<a href="/faculty/login" class="role-btn">
<div class="role-btn-icon faculty">
<i class="fas fa-chalkboard-teacher"></i>
</div>
<div class="role-btn-content">
<div class="role-btn-title">Faculty</div>
<div class="role-btn-desc">For teaching staff and instructors</div>
</div>
<i class="fas fa-arrow-right role-btn-arrow"></i>
</a>
<a href="/student/login" class="role-btn">
<div class="role-btn-icon student">
<i class="fas fa-user-graduate"></i>
</div>
<div class="role-btn-content">
<div class="role-btn-title">Student</div>
<div class="role-btn-desc">For enrolled students</div>
</div>
<i class="fas fa-arrow-right role-btn-arrow"></i>
</a>
</div>
</div>
</div>
<script>
function openModal() {
document.getElementById('roleModal').classList.add('active');
document.body.style.overflow = 'hidden';
}
function closeModal() {
document.getElementById('roleModal').classList.remove('active');
document.body.style.overflow = 'auto';
}
document.getElementById('roleModal').addEventListener('click', function(e) {
if (e.target === this) {
closeModal();
}
});
document.addEventListener('keydown', function(e) {
if (e.key === 'Escape') {
closeModal();
}
});
</script>
</body>
</html>