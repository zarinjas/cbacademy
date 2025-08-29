@props(['playerId', 'videoId', 'title' => 'Protected YouTube Video'])

<!-- 
IMPORTANT: Ensure your layout includes this viewport meta tag for mobile fullscreen:
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
-->

<div class="yt-protected" data-player-id="{{ $playerId }}" data-video-id="{{ $videoId }}">
    <div class="yt-stage">
        <!-- YouTube IFrame API will create iframe with proper attributes:
             - allowfullscreen
             - allow="fullscreen; autoplay; encrypted-media; picture-in-picture; web-share"
             - referrerpolicy="strict-origin-when-cross-origin"
        -->
        <div id="yt-player-{{ $playerId }}"></div>
        <!-- Large central play button for mobile friendliness -->
        <button class="yt-big-play" aria-label="Play video" type="button">
            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M7 6v12l10-6z" />
            </svg>
        </button>
        <div class="yt-mask" aria-hidden="true"></div>
        <!-- Optional small watermark (no tint) -->
        <span class="yt-watermark">Protected</span>
    </div>

    <div class="yt-controls" data-player="{{ $playerId }}">
        <button class="yt-btn yt-btn-play" data-act="play" aria-label="Play">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v14l11-7z"></path>
            </svg>
        </button>
        
        <button class="yt-btn yt-btn-pause" data-act="pause" aria-label="Pause" style="display: none;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"></path>
            </svg>
        </button>
        
        <button class="yt-btn yt-btn-mute" data-act="mute" aria-label="Mute">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
            </svg>
        </button>
        
        <button class="yt-btn yt-btn-unmute" data-act="unmute" aria-label="Unmute" style="display: none;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
            </svg>
        </button>
        
        <button type="button" class="yt-btn yt-btn-fs" data-act="fs" aria-label="Fullscreen">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
            </svg>
        </button>
        
        <div class="yt-volume-container">
            <span class="yt-volume-icon">🔊</span>
            <input type="range" class="yt-volume" min="0" max="100" value="100" step="1" aria-label="Volume control" />
        </div>
        
        <input type="range" class="yt-seek" min="0" max="100" value="0" step="0.1" aria-label="Seek video" />
    </div>
</div>

<script>
// Initialize this component instance
document.addEventListener('DOMContentLoaded', function() {
    const component = document.querySelector('[data-player-id="{{ $playerId }}"]');
    if (!component) return;

    // Try to initialize immediately if the player API is ready, otherwise poll until available.
    const tryInit = () => {
        try {
            if (window && window.YouTubeProtectedPlayer && typeof window.YouTubeProtectedPlayer.initComponent === 'function') {
                window.YouTubeProtectedPlayer.initComponent('{{ $playerId }}', '{{ $videoId }}');
                return true;
            }
        } catch (e) {
            // ignore
        }
        return false;
    };

    if (!tryInit()) {
        const maxAttempts = 50; // ~5 seconds
        let attempts = 0;
        const id = setInterval(() => {
            attempts++;
            if (tryInit() || attempts >= maxAttempts) {
                clearInterval(id);
            }
        }, 100);
    }
});
</script>
