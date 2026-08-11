<?php

class Challenge {
    private $config;
    private $challengeFile;
    
    public function __construct($config) {
        $this->config = $config;
        $this->challengeFile = __DIR__ . '/data/challenges.json';
        $this->ensureDataDirectory();
    }
    
    /**
     * Generate a JavaScript challenge for bot detection
     */
    public function generateChallenge() {
        if (!$this->config['javascript_challenge']) {
            return ['challenge_required' => false];
        }
        
        // Check if challenge was already completed in this session
        if (isset($_SESSION['challenge_completed']) && $_SESSION['challenge_completed']) {
            return ['challenge_required' => false];
        }
        
        // Generate unique challenge ID
        $challengeId = bin2hex(random_bytes(16));
        $_SESSION['challenge_id'] = $challengeId;
        $_SESSION['challenge_timestamp'] = time();
        
        // Generate challenge data
        $challengeData = $this->createChallengeData();
        
        // Store challenge for verification
        $this->storeChallengeData($challengeId, $challengeData);
        
        return [
            'challenge_required' => true,
            'html' => $this->generateChallengeHTML($challengeId, $challengeData),
            'challenge_id' => $challengeId
        ];
    }
    
    /**
     * Create challenge data (math problems, timing, etc.)
     */
    private function createChallengeData() {
        $challenges = [];
        
        // Simple math challenge
        $num1 = rand(1, 20);
        $num2 = rand(1, 20);
        $operations = ['+', '-'];
        $operation = $operations[array_rand($operations)];
        
        $challenges['math'] = [
            'question' => "{$num1} {$operation} {$num2} = ?",
            'answer' => $operation === '+' ? $num1 + $num2 : $num1 - $num2
        ];
        
        // Timing challenge (measure how long it takes to complete)
        $challenges['timing'] = [
            'start_time' => time(),
            'min_time' => 2, // Minimum 2 seconds (humans need time to read)
            'max_time' => $this->config['challenge_timeout'] ?? 30
        ];
        
        // Mouse movement detection
        $challenges['interaction'] = [
            'mouse_required' => true,
            'focus_required' => true
        ];
        
        return $challenges;
    }
    
    /**
     * Generate challenge HTML with JavaScript
     */
    private function generateChallengeHTML($challengeId, $challengeData) {
        $mathQuestion = $challengeData['math']['question'];
        
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Security Check</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 500px; 
            margin: 50px auto; 
            padding: 20px;
            background: #f5f5f5;
        }
        .challenge-container { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .challenge-title { 
            color: #333; 
            margin-bottom: 20px; 
        }
        .math-challenge { 
            font-size: 18px; 
            margin: 20px 0; 
        }
        .answer-input { 
            font-size: 16px; 
            padding: 10px; 
            border: 2px solid #ddd; 
            border-radius: 4px; 
            width: 100px; 
            text-align: center;
        }
        .submit-btn { 
            background: #007cba; 
            color: white; 
            border: none; 
            padding: 12px 24px; 
            font-size: 16px; 
            border-radius: 4px; 
            cursor: pointer; 
            margin: 20px 0;
        }
        .submit-btn:hover { background: #005a87; }
        .submit-btn:disabled { background: #ccc; cursor: not-allowed; }
        .progress { 
            text-align: center; 
            color: #666; 
            margin: 10px 0;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="challenge-container">
        <h2 class="challenge-title">🛡️ Security Verification</h2>
        <p>Please solve this simple math problem to continue:</p>
        
        <div class="math-challenge">
            <strong>' . htmlspecialchars($mathQuestion) . '</strong>
        </div>
        
        <form id="challengeForm" method="POST">
            <input type="hidden" name="challenge_id" value="' . htmlspecialchars($challengeId) . '">
            <input type="hidden" name="challenge_response" id="challengeResponse">
            <input type="hidden" name="mouse_data" id="mouseData">
            <input type="hidden" name="timing_data" id="timingData">
            <input type="hidden" name="interaction_data" id="interactionData">
            
            <input type="number" 
                   id="mathAnswer" 
                   class="answer-input" 
                   placeholder="Answer" 
                   required 
                   autofocus>
            
            <br>
            <button type="submit" class="submit-btn" id="submitBtn" disabled>
                Verify & Continue
            </button>
        </form>
        
        <div class="progress" id="progress">
            Loading security check...
        </div>
    </div>

    <script>
    (function() {
        const startTime = Date.now();
        let mouseEvents = [];
        let focusEvents = [];
        let interactionScore = 0;
        
        // Track mouse movement
        document.addEventListener("mousemove", function(e) {
            mouseEvents.push({
                x: e.clientX,
                y: e.clientY,
                time: Date.now() - startTime
            });
            
            if (mouseEvents.length > 50) {
                mouseEvents = mouseEvents.slice(-25); // Keep last 25 events
            }
            
            updateInteractionScore();
        });
        
        // Track focus events
        const mathInput = document.getElementById("mathAnswer");
        mathInput.addEventListener("focus", function() {
            focusEvents.push(Date.now() - startTime);
            updateInteractionScore();
        });
        
        // Update interaction score and enable submit button
        function updateInteractionScore() {
            interactionScore = 0;
            
            // Mouse movement score
            if (mouseEvents.length > 3) {
                interactionScore += 30;
            }
            
            // Focus score
            if (focusEvents.length > 0) {
                interactionScore += 20;
            }
            
            // Time score (must wait at least 2 seconds)
            const elapsed = (Date.now() - startTime) / 1000;
            if (elapsed > 2) {
                interactionScore += 25;
            }
            
            // Enable submit button when sufficient interaction
            const submitBtn = document.getElementById("submitBtn");
            const progress = document.getElementById("progress");
            
            if (interactionScore >= 50 && elapsed >= 2) {
                submitBtn.disabled = false;
                progress.textContent = "You may now submit your answer";
            } else if (elapsed < 2) {
                progress.textContent = "Please wait " + Math.ceil(2 - elapsed) + " seconds...";
            } else {
                progress.textContent = "Please move your mouse and click in the answer field";
            }
        }
        
        // Update progress every 100ms
        setInterval(updateInteractionScore, 100);
        
        // Handle form submission
        document.getElementById("challengeForm").addEventListener("submit", function(e) {
            const elapsed = (Date.now() - startTime) / 1000;
            const mathAnswer = document.getElementById("mathAnswer").value;
            
            // Prepare challenge response data
            const responseData = {
                math_answer: mathAnswer,
                start_time: startTime,
                completion_time: Date.now(),
                elapsed_seconds: elapsed,
                interaction_score: interactionScore,
                mouse_events_count: mouseEvents.length,
                focus_events_count: focusEvents.length
            };
            
            // Set hidden form fields
            document.getElementById("challengeResponse").value = JSON.stringify(responseData);
            document.getElementById("mouseData").value = JSON.stringify(mouseEvents.slice(-10));
            document.getElementById("timingData").value = elapsed;
            document.getElementById("interactionData").value = interactionScore;
            
            // Allow form to submit
            return true;
        });
        
        // Prevent copy/paste in math input
        mathInput.addEventListener("paste", function(e) {
            e.preventDefault();
            interactionScore -= 10; // Penalty for pasting
        });
        
    })();
    </script>
</body>
</html>';
    }
    
    /**
     * Verify challenge response
     */
    public function verifyResponse($responseData) {
        if (!isset($_SESSION['challenge_id'])) {
            return ['valid' => false, 'reason' => 'No challenge session'];
        }
        
        $challengeId = $_SESSION['challenge_id'];
        $storedChallenge = $this->loadChallengeData($challengeId);
        
        if (!$storedChallenge) {
            return ['valid' => false, 'reason' => 'Challenge data not found'];
        }
        
        // Parse response data
        $response = is_string($responseData) ? json_decode($responseData, true) : $responseData;
        
        if (!$response) {
            return ['valid' => false, 'reason' => 'Invalid response format'];
        }
        
        $score = 0;
        $maxScore = 100;
        $reasons = [];
        
        // Verify math answer
        if (isset($response['math_answer'])) {
            if ((int)$response['math_answer'] === $storedChallenge['math']['answer']) {
                $score += 40;
            } else {
                $reasons[] = 'Incorrect math answer';
            }
        } else {
            $reasons[] = 'Missing math answer';
        }
        
        // Verify timing
        if (isset($response['elapsed_seconds'])) {
            $elapsed = (float)$response['elapsed_seconds'];
            $minTime = $storedChallenge['timing']['min_time'];
            $maxTime = $storedChallenge['timing']['max_time'];
            
            if ($elapsed >= $minTime && $elapsed <= $maxTime) {
                $score += 30;
            } else {
                $reasons[] = 'Suspicious timing: ' . $elapsed . 's';
            }
        }
        
        // Verify interaction
        if (isset($response['interaction_score'])) {
            $interactionScore = (int)$response['interaction_score'];
            if ($interactionScore >= 50) {
                $score += 30;
            } else {
                $reasons[] = 'Insufficient interaction: ' . $interactionScore;
            }
        }
        
        // Determine if challenge passed
        $passed = $score >= 70; // Need 70% to pass
        
        if ($passed) {
            $_SESSION['challenge_completed'] = true;
            $this->removeChallengeData($challengeId);
        }
        
        return [
            'valid' => $passed,
            'score' => $score,
            'max_score' => $maxScore,
            'reasons' => $reasons
        ];
    }
    
    /**
     * Store challenge data for later verification
     */
    private function storeChallengeData($challengeId, $data) {
        $challenges = $this->loadAllChallenges();
        $challenges[$challengeId] = [
            'data' => $data,
            'created' => time(),
            'ip' => $this->getClientIP()
        ];
        
        // Clean up old challenges
        $this->cleanupOldChallenges($challenges);
        $this->saveAllChallenges($challenges);
    }
    
    /**
     * Load specific challenge data
     */
    private function loadChallengeData($challengeId) {
        $challenges = $this->loadAllChallenges();
        return $challenges[$challengeId]['data'] ?? null;
    }
    
    /**
     * Remove challenge data after successful verification
     */
    private function removeChallengeData($challengeId) {
        $challenges = $this->loadAllChallenges();
        unset($challenges[$challengeId]);
        $this->saveAllChallenges($challenges);
    }
    
    /**
     * Load all challenges from storage
     */
    private function loadAllChallenges() {
        if (!file_exists($this->challengeFile)) {
            return [];
        }
        
        $content = @file_get_contents($this->challengeFile);
        return $content ? json_decode($content, true) : [];
    }
    
    /**
     * Save all challenges to storage
     */
    private function saveAllChallenges($challenges) {
        @file_put_contents($this->challengeFile, json_encode($challenges, JSON_PRETTY_PRINT), LOCK_EX);
    }
    
    /**
     * Clean up old challenge data
     */
    private function cleanupOldChallenges(&$challenges) {
        $now = time();
        $maxAge = $this->config['challenge_timeout'] * 2; // Double the timeout
        
        foreach ($challenges as $id => $challenge) {
            if (($now - $challenge['created']) > $maxAge) {
                unset($challenges[$id]);
            }
        }
    }
    
    /**
     * Get client IP address
     */
    private function getClientIP() {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP', 
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Ensure data directory exists
     */
    private function ensureDataDirectory() {
        $dir = dirname($this->challengeFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
}