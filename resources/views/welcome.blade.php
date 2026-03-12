<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dr. Phua's Dental Clinic | Management System</title>
</head>

<body>

    <header>
        <div>
            <img src="{{ asset('tooth_logo.ico') }}" alt="Dr. Phua's Logo">
        </div>
        <div>
            <h2>
                Dr. Phua's <span>Dental Clinic</span>
            </h2>
        </div>
    </header>

    <section>
        <span>
            Authorized Personnel Only
        </span>

        <h1>
            <span>Dental Clinic Management Information System</span>
        </h1>

        <p>
            A specialized administrative tool for Dr. Phua's Dental Clinic to manage patient records, clinical examinations, and radiology data.
        </p>

        <div>
            @auth
                <a href="{{ url('/dashboard') }}">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}">
                    Admin Access
                </a>
            @endauth
        </div>
    </section>

    <section id="about-us">
        <div>
            <div>
                <div>
                    <h2>
                        About Us:
                    </h2>
                    <p>
                        Dr. Phua's Dental Clinic is committed to providing top-tier oral healthcare through innovation. This Management Information System (MIS) serves as the digital foundation of our practice.
                    </p>
                    <p>
                        By centralizing patient records, imaging, and treatment history, we ensure our clinical team can focus on what matters most: delivering precise, patient-centered care in a modern environment.
                    </p>
                </div>
                
                <div>
                    <div>
                        <div>
                            <h4>Data Security</h4>
                            <p>Encrypted storage for sensitive medical records.</p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <h4>Operational Speed</h4>
                            <p>Instant retrieval of patient radiology and history.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact-us">
        <div>
            <h2>Contact Us</h2>
            Tel No. 345-8870<br>
            <div>
                <div>
                    <div>
                        <div>
                            <h3>Clinic Location</h3>
                            <p>
                                A. Borbajo St. Talamban,<br>
                                Cebu City, Philippines
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>© {{ date('Y') }} Dr. Phua's Dental Clinic. Administrative Interface.</p>
    </footer>

</body>
</html>