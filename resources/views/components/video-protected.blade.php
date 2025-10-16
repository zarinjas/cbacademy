@props(['videoUrl', 'title' => 'Protected Video'])

<div class="cb-video-protected">
    <div class="vp-stage">
        <!-- Google Drive Video Player -->
        <iframe 
            src="{{ $videoUrl }}" 
            allowfullscreen
            title="{{ $title }}"
            class="vp-iframe"
        ></iframe>
        
        <!-- Security Overlay -->
        <div class="vp-mask" aria-hidden="true"></div>
        
        <!-- Security Watermark -->
        <span class="vp-watermark">Protected</span>
    </div>
</div>

<style>
.cb-video-protected {
    max-width: 960px;
    margin: 0 auto;
    background: #1f2937;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.vp-stage {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
    background: #000;
}

.vp-iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

.vp-mask {
    position: absolute;
    inset: 0;
    z-index: 3;
    background: transparent !important;
    pointer-events: auto;
    cursor: not-allowed;
}

.vp-watermark {
    position: absolute;
    left: 12px;
    top: 12px;
    z-index: 4;
    pointer-events: none;
    background: rgba(0, 0, 0, 0.35);
    color: #fff;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 6px;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .cb-video-protected {
        margin: 0 0.5rem;
        border-radius: 0.5rem;
    }
}
</style>
