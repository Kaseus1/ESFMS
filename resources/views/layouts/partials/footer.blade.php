<style>
    .universal-footer {
        background: linear-gradient(135deg, #172030 0%, #1D2636 100%);
        color: white;
        padding: 3rem 0 1.5rem;
        margin-top: auto;
        border-top: 5px solid #002366; /* Your Primary Brand Color */
        font-family: 'Inter', sans-serif;
    }

    .footer-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2.5rem;
    }

    .footer-brand h2 { font-size: 1.25rem; font-weight: 800; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .footer-brand p { font-size: 0.9rem; opacity: 0.7; line-height: 1.6; }
    
    .footer-links h3 { font-size: 1rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; }
    .footer-links ul { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 0.5rem; }
    .footer-links a { color: #cbd5e1; text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
    .footer-links a:hover { color: white; }

    .footer-bottom {
        max-width: 1400px;
        margin: 2.5rem auto 0;
        padding: 1.5rem 1.5rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .footer-bottom { flex-direction: row; justify-content: space-between; }
    }
</style>

<footer class="universal-footer">
    <div class="footer-content">
        <div class="footer-brand">
            <h2><i class="fa-solid fa-graduation-cap"></i> CPAC ESFMS</h2>
            <p>
                Events Scheduling and Facilities Management System.<br>
                Streamlining campus operations for students and faculty.
            </p>
        </div>

        <div class="footer-links">
            <h3>Contact Us</h3>
            <ul>
                <li><i class="fa-solid fa-location-dot w-5"></i> CPAC, Bacolod, Negros Occ.</li>
                <li><i class="fa-solid fa-phone w-5"></i> +63 (33) 320-0870</li>
                <li><i class="fa-solid fa-envelope w-5"></i> esfms@cpac.edu.ph</li>
            </ul>
        </div>

        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                @auth
                    @if(Auth::user()->role == 'student')
                        <li><a href="{{ route('student.dashboard') }}">Student Dashboard</a></li>
                        <li><a href="{{ route('student.facilities.index') }}">Book a Facility</a></li>
                    @elseif(Auth::user()->role == 'faculty')
                        <li><a href="#">Faculty Dashboard</a></li>
                        <li><a href="#">My Schedule</a></li>
                    @elseif(Auth::user()->role == 'admin')
                        <li><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
                <li><a href="#">Help Center</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p style="font-size: 0.85rem; opacity: 0.6;">&copy; {{ date('Y') }} Central Philippine Adventist College. All rights reserved.</p>
        <div style="font-size: 0.85rem;">
            <a href="#" style="color: inherit; opacity: 0.6; margin-left: 1rem; text-decoration: none;">Privacy Policy</a>
            <a href="#" style="color: inherit; opacity: 0.6; margin-left: 1rem; text-decoration: none;">Terms of Service</a>
        </div>
    </div>
</footer>