<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Protected Player Demo</title>
    <?php $ytCss = public_path('css/youtube-protected.css'); $ytJs = public_path('js/youtube-protected.js'); ?>
    <link rel="stylesheet" href="{{ asset('css/youtube-protected.css') }}?v={{ file_exists($ytCss) ? filemtime($ytCss) : time() }}">
    <style>
        body {
            background: #111827;
            color: white;
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 2rem;
        }
        .demo-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .demo-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .demo-item {
            background: #1f2937;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #374151;
        }
        .demo-item h3 {
            margin-top: 0;
            color: #fbbf24;
        }
        .demo-item p {
            color: #9ca3af;
            font-size: 0.875rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="demo-container">
        <div class="demo-title">
            <h1 class="text-4xl font-bold mb-4">YouTube Protected Player Demo</h1>
            <p class="text-xl text-gray-400">TutorLMS-style embedding with custom controls and click blocking</p>
        </div>

        <div class="demo-grid">
            <div class="demo-item">
                <h3>🎥 Demo Video 1</h3>
                <p>This demonstrates the protected YouTube player with custom controls. Users cannot click into YouTube while maintaining full video functionality.</p>
                <x-youtube-protected 
                    playerId="demo-1" 
                    videoId="dQw4w9WgXcQ"
                    title="Demo Video 1" />
            </div>

            <div class="demo-item">
                <h3>🎥 Demo Video 2</h3>
                <p>Another instance showing the component's reusability. Each player instance is completely independent.</p>
                <x-youtube-protected 
                    playerId="demo-2" 
                    videoId="9bZkp7q19f0"
                    title="Demo Video 2" />
            </div>
        </div>

        <div class="demo-item">
            <h3>🔒 Security Features</h3>
            <ul class="text-gray-300 space-y-2">
                <li>✅ <strong>Click Blocking:</strong> Transparent overlay prevents clicks into YouTube</li>
                <li>✅ <strong>Custom Controls:</strong> Play, pause, mute, seek, and volume controls</li>
                <li>✅ <strong>Responsive Design:</strong> 16:9 aspect ratio with mobile optimization</li>
                <li>✅ <strong>YouTube IFrame API:</strong> Full programmatic control</li>
                <li>✅ <strong>Security Parameters:</strong> modestbranding, no related videos, playsinline</li>
                <li>✅ <strong>Accessibility:</strong> ARIA labels, keyboard navigation, high contrast support</li>
            </ul>
        </div>

        <div class="demo-item">
            <h3>📱 Usage Example</h3>
            <pre class="bg-gray-800 p-4 rounded-lg overflow-x-auto text-sm"><code>&lt;x-youtube-protected 
    playerId="unique-id" 
    videoId="youtube-video-id"
    title="Video Title" /&gt;</code></pre>
        </div>
    </div>

    <!-- Include YouTube Protected Player JavaScript -->
    <script src="{{ asset('js/youtube-protected.js') }}?v={{ file_exists($ytJs) ? filemtime($ytJs) : time() }}"></script>
</body>
</html>
