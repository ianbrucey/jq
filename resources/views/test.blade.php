<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justice Quest - Sky-High Legal Solutions</title>
    <style>
        :root {
            --neon-blue: #00f3ff;
            --electric-purple: #7d12ff;
            --cyber-pink: #ff00ff;
            --sky-gradient: linear-gradient(135deg, #87CEEB 0%, #E0F6FF 100%);
        }

        body {
            margin: 0;
            background: var(--sky-gradient);
            font-family: 'Orbitron', sans-serif;
            color: #2a2a72;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        /* Floating Clouds */
        .cloud {
            position: absolute;
            background: white;
            border-radius: 50px;
            opacity: 0.9;
            animation: floatCloud 50s linear infinite;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 120px;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            background: linear-gradient(45deg, var(--electric-purple), var(--neon-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(125, 18, 255, 0.3);
        }

        /* Hero Section */
        .hero {
            position: relative;
            text-align: center;
            padding: 100px 0;
            perspective: 1000px;
        }

        .hero-title {
            font-size: 4rem;
            background: linear-gradient(45deg, #2a2a72, #7d12ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            transform: translateZ(50px);
        }

        .hero-subtitle {
            font-family: 'Exo 2', sans-serif;
            font-size: 1.5rem;
            color: #4a4a8a;
            margin-bottom: 50px;
        }

        /* Cyber Button */
        .cta-button {
            background: linear-gradient(45deg, var(--electric-purple), var(--neon-blue));
            padding: 20px 50px;
            border: none;
            border-radius: 15px;
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(125, 18, 255, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(125, 18, 255, 0.5);
        }

        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin: 100px 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 25px;
            text-align: center;
            transition: transform 0.3s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--sky-gradient);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: grid;
            place-items: center;
        }

        /* Floating Island */
        .platform {
            position: relative;
            height: 400px;
            margin: 100px 0;
        }

        .floating-island {
            width: 600px;
            height: 300px;
            background: rgba(255, 255, 255, 0.95);
            margin: 0 auto;
            border-radius: 40px;
            transform-style: preserve-3d;
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotateX(0deg); }
            50% { transform: translateY(-20px) rotateX(5deg); }
            100% { transform: translateY(0px) rotateX(0deg); }
        }

        @keyframes floatCloud {
            from { transform: translateX(-100%); }
            to { transform: translateX(100vw); }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Exo+2:wght@300;500&display=swap" rel="stylesheet">
</head>
<body>
<!-- Animated Clouds -->
<div class="cloud" style="top: 20%; left: -200px; width: 150px; height: 60px;"></div>
<div class="cloud" style="top: 40%; right: -200px; width: 180px; height: 70px;"></div>

<div class="container">
    <header class="header">
        <div class="logo">Justice Quest</div>
        <nav>
            <button class="cta-button">Launch App</button>
        </nav>
    </header>

    <section class="hero">
        <h1 class="hero-title">Elevate Your Legal Experience</h1>
        <p class="hero-subtitle">Cloud-based legal solutions that reach new heights</p>
        <button class="cta-button">Start Free Trial</button>

        <div class="platform">
            <div class="floating-island">
                <!-- Add 3D dashboard illustration here -->
            </div>
        </div>
    </section>

    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">⚖️</div>
            <h3>Cloud Case Management</h3>
            <p>Access your legal matters from any device, anywhere</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📡</div>
            <h3>Real-Time Updates</h3>
            <p>Instant notifications on case developments</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Secure Vault</h3>
            <p>Military-grade encryption for all documents</p>
        </div>
    </section>

    <section class="steps">
        <div class="step-container">
            <div class="step">
                <div class="step-circle">1</div>
                <h4>Describe Issue</h4>
                <p>Chat with our AI legal assistant</p>
            </div>
            <div class="step">
                <div class="step-circle">2</div>
                <h4>Get Strategy</h4>
                <p>Receive customized action plan</p>
            </div>
            <div class="step">
                <div class="step-circle">3</div>
                <h4>Take Action</h4>
                <p>Execute with confidence</p>
            </div>
        </div>
    </section>
</div>

<footer style="text-align: center; padding: 60px; background: rgba(255,255,255,0.9); margin-top: 100px;">
    <p style="color: #2a2a72;">© 2023 Justice Quest - Soaring Through Legal Complexity</p>
</footer>
</body>
</html>
