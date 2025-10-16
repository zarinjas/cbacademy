<div class="local-video-component" data-local-player>
    <link rel="stylesheet" href="{{ asset('css/youtube-protected.css') }}?v={{ file_exists(public_path('css/youtube-protected.css')) ? filemtime(public_path('css/youtube-protected.css')) : time() }}">

    <div class="yt-protected local-player" style="background:#000;color:#fff;border-radius:12px;overflow:hidden;">
        <div class="yt-stage">
            <video
                class="vp-iframe"
                src="{{ $src }}"
                controls
                controlsList="nodownload noplaybackrate"
                disablePictureInPicture
                oncontextmenu="return false;"
                playsinline
                preload="metadata"
            ></video>
            <div class="yt-mask" aria-hidden="true"></div>
        </div>
        <div class="yt-controls" style="background:linear-gradient(180deg, rgba(0,0,0,0.6), rgba(0,0,0,0.85)); border-top: 1px solid rgba(255,215,0,0.06);">
            <!-- Minimal controls copied from existing design could go here if needed -->
            <div style="padding:8px; color:#f59e0b; font-weight:600;">Local video</div>
        </div>
    </div>
</div>
