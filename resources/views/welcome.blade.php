<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badminton Scorer - Track Every Point</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(1deg);
            }
        }

        .nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
        }

        .nav.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav.scrolled .logo {
            color: #667eea;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        .nav.scrolled .nav-links a {
            color: #333;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 2;
            max-width: 800px;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: slideInUp 1s ease-out;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            animation: slideInUp 1s ease-out 0.2s both;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: slideInUp 1s ease-out 0.4s both;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: white;
            color: #667eea;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .app-preview {
            position: absolute;
            right: 10%;
            top: 50%;
            transform: translateY(-50%);
            width: 300px;
            height: 600px;
            background: #1e3a8a;
            border-radius: 30px;
            padding: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: phoneFloat 3s ease-in-out infinite;
        }

        @keyframes phoneFloat {

            0%,
            100% {
                transform: translateY(-50%) rotate(-2deg);
            }

            50% {
                transform: translateY(-60%) rotate(2deg);
            }
        }

        .phone-screen {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .score-display {
            font-size: 3rem;
            font-weight: bold;
            margin: 1rem 0;
        }

        .vs {
            font-size: 1.5rem;
            opacity: 0.8;
            margin: 0.5rem 0;
        }



        .footer {
            background: #0f172a;
            color: white;
            padding: 3rem 2rem 1rem;
            text-align: center;
        }

        .floating-shuttlecock {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            font-size: 3rem;
            animation: floatShuttlecock 15s linear infinite;
        }

        @keyframes floatShuttlecock {
            0% {
                transform: translateX(-100px) translateY(0px) rotate(0deg);
            }

            100% {
                transform: translateX(calc(100vw + 100px)) translateY(-50px) rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .app-preview {
                display: none;
            }

            .nav-links {
                display: none;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .features h2 {
                font-size: 2rem;
            }

            .download h2 {
                font-size: 2rem;
            }
        }

        @keyframes shuttleArc {
            0% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 1;
            }

            25% {
                transform: translate(60px, -120px) rotate(15deg);
                /* rising */
            }

            50% {
                transform: translate(120px, -180px) rotate(-10deg);
                /* peak */
            }

            75% {
                transform: translate(180px, -120px) rotate(5deg);
                /* descending */
            }

            100% {
                transform: translate(240px, 0px) rotate(0deg);
                /* back to ground */
                opacity: 0;
            }
        }


        .floating-shuttlecock {
            pointer-events: none;
        }
    </style>
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-content">
            <a href="#" style="display:flex; align-items:center;" class="logo"><img
                    style="width: 60px; height: 60px; margin-right: 0.5rem;" src="{{ asset('img/newlogo.png') }}"
                    alt=""> <span>Badminton Scorer</span></a>
            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#download">Download</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero">
        <div class="floating-shuttlecock" style="top: 20%; animation-delay: 0s;"><img
                style=" height: 30px; width: 30px;" src="{{ asset('img/logo_bg.png') }}" alt=""></div>
        <div class="floating-shuttlecock" style="top: 60%; animation-delay: 5s;"><img
                style=" height: 30px; width: 30px;" src="{{ asset('img/logo_bg.png') }}" alt=""></div>
        <div class="floating-shuttlecock" style="top: 40%; animation-delay: 10s;"><img
                style=" height: 30px; width: 30px;" src="{{ asset('img/logo_bg.png') }}" alt=""></div>

        <div class="hero-content">
            <h1>Badminton Scorer</h1>
            <p>Track every point, analyze every game. The ultimate companion for badminton enthusiasts.</p>
            <div class="cta-buttons">
                <a wire:navigate 
                href="{{ route('match.list') }}"
                 class="btn btn-primary">View Live Matches</a>
                <a wire:navigate href="{{ route('login') }}" class="btn btn-secondary">Create Match</a>
            </div>
        </div>

        <div class="app-preview">
            <div class="phone-screen">
                <div style="font-size: 1.2rem; opacity: 0.8;">Player 1</div>
                <div class="score-display">15</div>
                <div class="vs">VS</div>
                <div class="score-display">12</div>
                <div style="font-size: 1.2rem; opacity: 0.8;">Player 2</div>
                <div style="position: absolute; bottom: 20px; font-size: 0.9rem; opacity: 0.6;">Set 1 • Game 1</div>
            </div>
        </div>
    </section>


    {{-- <section class="download" id="download">
        <div class="download-container">
            <h2>Ready to Play?</h2>
            <p>Download Badminton Scorer today and take your game to the next level</p>
            <div class="app-badges">
                <a href="#" class="badge">📱 Download for iOS</a>
                <a href="#" class="badge">🤖 Download for Android</a>
                <a href="#" class="badge">💻 Web Version</a>
            </div>
        </div>
    </section> --}}

    {{-- <footer class="footer" id="contact">
        <div style="max-width: 1200px; margin: 0 auto;">
            <p>&copy; 2024 Badminton Scorer. Built for players, by players.</p>
            <p style="margin-top: 1rem; opacity: 0.7;">Contact: hello@badmintonscorer.app</p>
        </div>
    </footer> --}}

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        function addFloatingShuttle() {
            const shuttlecock = document.createElement('img');
            shuttlecock.className = 'floating-shuttlecock';
            shuttlecock.src = "{{ asset('img/logo_bg.png') }}";

            // style basics
            shuttlecock.style.position = 'absolute';
            shuttlecock.style.height = '30px';
            shuttlecock.style.width = '30px';
            shuttlecock.style.left = Math.random() * 80 + '%';
            shuttlecock.style.bottom = '0px'; // start from bottom
            shuttlecock.style.animation = 'shuttleArc 3s ease-out forwards';

            document.querySelector('.hero').appendChild(shuttlecock);

            setTimeout(() => {
                shuttlecock.remove();
            }, 4000); // remove after animation
        }

        // Add random shuttlecock animations
        function addFloatingShuttlecock() {
            const shuttlecock = document.createElement('div');
            shuttlecock.className = 'floating-shuttlecock';
            shuttlecock.innerHTML = '🏸';
            shuttlecock.style.top = Math.random() * 80 + '%';
            shuttlecock.style.animationDelay = '0s';
            document.querySelector('.hero').appendChild(shuttlecock);

            setTimeout(() => {
                shuttlecock.remove();
            }, 15000);
        }
        // setInterval(addFloatingShuttlecock, 500);
        setInterval(addFloatingShuttle, 500);

        // Feature cards animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(50px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>

</html>