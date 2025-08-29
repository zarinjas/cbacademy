<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <title>YouTube Protected Player - Fullscreen Test</title>
    <?php $ytCss = public_path('css/youtube-protected.css'); $ytJs = public_path('js/youtube-protected.js'); ?>
    <link rel="stylesheet" href="{{ asset('css/youtube-protected.css') }}?v={{ file_exists($ytCss) ? filemtime($ytCss) : time() }}">
    <style>
        body {
            background: #111827;
            color: white;
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 2rem;
            min-height: 100vh;
        }
        .test-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .test-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        .test-info {
            background: #1f2937;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #374151;
            margin-bottom: 2rem;
        }
        .test-info h3 {
            margin-top: 0;
            color: #fbbf24;
        }
        .test-info ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        .test-info li {
            margin-bottom: 0.5rem;
            color: #d1d5db;
        }
        .mobile-test {
            background: #059669;
            border: 1px solid #047857;
        }
        .mobile-test h3 {
            color: #10b981;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="test-title">
            <h1>🎬 YouTube Protected Player - Fullscreen Test</h1>
            <p style="color: #9ca3af;">Testing fullscreen functionality with click-blocking overlay</p>
        </div>

        <div class="test-info">
            <h3>🧪 Test Instructions</h3>
            <ul>
                <li><strong>Fullscreen Test:</strong> Click the green fullscreen button (⛶) to enter fullscreen mode</li>
                <li><strong>Exit Fullscreen:</strong> Press ESC key or click the red fullscreen button to exit</li>
                <li><strong>Click Blocking:</strong> Try clicking anywhere on the video - it should be blocked</li>
                <li><strong>Custom Controls:</strong> All controls should work in both windowed and fullscreen modes</li>
                <li><strong>Browser Support:</strong> Tested on Chrome, Edge, Firefox, and Safari</li>
            </ul>
        </div>

        <div class="test-info mobile-test">
            <h3>📱 Mobile Testing (iOS/Android)</h3>
            <ul>
                <li><strong>Touch Targets:</strong> All buttons should be at least 44px for easy tapping</li>
                <li><strong>Fullscreen:</strong> Should work on mobile (native if supported, fallback if not)</li>
                <li><strong>Orientation:</strong> Test both portrait and landscape modes</li>
                <li><strong>Touch Controls:</strong> Volume slider should respond to touch gestures</li>
                <li><strong>Safe Areas:</strong> Controls should respect device safe areas (notches, home indicators)</li>
                <li><strong>No Double-tap Zoom:</strong> Buttons should not trigger zoom on double-tap</li>
            </ul>
        </div>

        <div class="test-info">
            <h3>🎯 Expected Behavior</h3>
            <ul>
                <li>✅ <strong>No Red Tint:</strong> Video appears completely normal with no red overlay</li>
                <li>✅ <strong>Transparent Mask:</strong> Click blocking works without visual interference</li>
                <li>✅ <strong>Fullscreen Button:</strong> Green in windowed mode, red in fullscreen mode</li>
                <li>✅ <strong>Video Quality:</strong> Full resolution with no blur or distortion</li>
                <li>✅ <strong>Custom Controls:</strong> All buttons work in both modes</li>
                <li>✅ <strong>Click Protection:</strong> Cannot access YouTube UI elements</li>
                <li>✅ <strong>Small Watermark:</strong> "Protected" label in top-left corner</li>
            </ul>
        </div>

        <div class="test-info">
            <h3>🔊 Audio Testing & Debugging</h3>
            <ul>
                <li><strong>Volume Control:</strong> Use the volume slider to adjust audio level</li>
                <li><strong>Mute/Unmute:</strong> Click the volume icon to toggle mute</li>
                <li><strong>Audio Test:</strong> Open browser console and run: <code>YouTubeProtectedPlayer.testAudio('fullscreen-test')</code></li>
                <li><strong>Player State:</strong> Check console for detailed audio state logs</li>
                <li><strong>Browser Audio:</strong> Check if browser has blocked autoplay or audio</li>
                <li><strong>System Volume:</strong> Ensure your system volume is not muted</li>
            </ul>
            <button onclick="testAudio()" style="background: #059669; color: white; padding: 8px 16px; border: none; border-radius: 8px; margin-top: 12px; cursor: pointer;">
                🧪 Test Audio Functionality
            </button>
            <button onclick="debugPlayer()" style="background: #dc2626; color: white; padding: 8px 16px; border: none; border-radius: 8px; margin-top: 12px; margin-left: 8px; cursor: pointer;">
                🐛 Debug Player State
            </button>
        </div>

        <!-- YouTube Protected Player -->
        <div style="margin: 2rem 0;">
            <x-youtube-protected 
                playerId="fullscreen-test" 
                videoId="dQw4w9WgXcQ"
                title="Fullscreen Test Video" />
        </div>

        <div class="test-info">
            <h3>🔧 Browser Compatibility</h3>
            <ul>
                <li><strong>Chrome/Edge:</strong> Uses standard fullscreen API</li>
                <li><strong>Safari:</strong> Uses webkit prefixed API</li>
                <li><strong>Firefox:</strong> Uses moz prefixed API</li>
                <li><strong>IE/Edge Legacy:</strong> Uses ms prefixed API</li>
            </ul>
        </div>
    </div>

    <!-- Include YouTube Protected Player JavaScript -->
    <script src="{{ asset('js/youtube-protected.js') }}?v={{ file_exists($ytJs) ? filemtime($ytJs) : time() }}"></script>
    
    <script>
        // Additional test logging
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🧪 Fullscreen Test Page Loaded');
            
            // Log fullscreen state changes
            const events = ['fullscreenchange', 'webkitfullscreenchange', 'msfullscreenchange'];
            events.forEach(event => {
                document.addEventListener(event, function() {
                    const isFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
                    console.log('🎬 Fullscreen state:', isFullscreen ? 'ENTERED' : 'EXITED');
                });
            });
        });

        function testAudio() {
            const result = YouTubeProtectedPlayer.testAudio('fullscreen-test');
            if (result) {
                alert('Audio test completed. Check browser console for results.');
            } else {
                alert('Audio test failed. Check browser console for errors.');
            }
        }

        function debugPlayer() {
            const player = YouTubeProtectedPlayer.getPlayer('fullscreen-test');
            if (player) {
                try {
                    const state = {
                        playerState: player.getPlayerState(),
                        isMuted: player.isMuted(),
                        volume: player.getVolume(),
                        currentTime: player.getCurrentTime(),
                        duration: player.getDuration(),
                        readyState: player.getPlayerState()
                    };
                    
                    console.log('🔍 Player Debug Info:', state);
                    console.log('🔍 Player Element:', document.getElementById('yt-player-fullscreen-test'));
                    console.log('🔍 YouTube IFrame API:', window.YT);
                    
                    alert(`Player Debug Info:\nState: ${state.playerState}\nMuted: ${state.isMuted}\nVolume: ${state.volume}\nCheck console for details.`);
                } catch (e) {
                    console.error('Debug failed:', e);
                    alert('Debug failed. Check console for errors.');
                }
            } else {
                alert('Player not found. Please ensure the playerId is correct.');
            }
        }
    </script>
</body>
</html>
