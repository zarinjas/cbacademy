/**
 * YouTube Protected Player - Custom Controls with IFrame API
 * Prevents users from clicking into YouTube while maintaining full functionality
 * Mobile-first fullscreen implementation with iframe priority
 */

(function() {
    'use strict';

    // Version stamp to verify browser loaded the latest file
    try {
        console.log('[youtube-protected.js] loaded (modified at: 2025-08-29 14:38)');
    } catch(e) {}

    // Global YouTube API state
    let youtubeAPIReady = false;
    let pendingComponents = [];
    const players = {};

    // Load YouTube IFrame API
    function loadYouTubeAPI() {
        if (window.YT) {
            youtubeAPIReady = true;
            onYouTubeIframeAPIReady();
            return;
        }
        
        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    // YouTube API ready callback
    function onYouTubeIframeAPIReady() {
        console.log('[youtube-api] YouTube IFrame API ready');
        youtubeAPIReady = true;
        
        // Initialize any pending components
        pendingComponents.forEach(({ playerId, videoId }) => {
            createPlayer(playerId, videoId);
        });
        pendingComponents = [];
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[player] DOM ready, initializing components');
        
        // TEMP diagnostics - detect iOS video player blockers
        runIOSDiagnostics();
        
        initializePendingComponents();
    });

    // Also listen for any SPA/page-swap events
    window.addEventListener('load', function() {
        console.log('[player] Window loaded, checking for pending components');
        initializePendingComponents();
    });

    // Initialize all pending components
    function initializePendingComponents() {
        pendingComponents.forEach(component => {
            createPlayer(component.playerId, component.videoId);
        });
        pendingComponents = [];
    }

    // Robust fullscreen toggle with iframe priority for mobile
    function enterFSPreferIframe(iframeEl, stageEl) {
        const reqs = [
            () => iframeEl?.requestFullscreen?.(),
            () => iframeEl?.webkitRequestFullscreen?.(),   // Safari
            () => stageEl?.requestFullscreen?.(),
            () => stageEl?.webkitRequestFullscreen?.()
        ];
        
        for (const fn of reqs) {
            try {
                const r = fn && fn();
                if (r && typeof r.then === 'function') return r;
                if (r === undefined) return Promise.resolve();
            } catch(e) {
                console.log('Fullscreen method failed:', e);
            }
        }
        return Promise.reject(new Error('no-native-fs'));
    }

    function exitFS() {
        const d = document;
        return (d.exitFullscreen?.() || d.webkitExitFullscreen?.() || Promise.resolve());
    }

    async function toggleFullscreen(root) {
        const stage = root.querySelector('.yt-stage');
        const iframe = stage?.querySelector('iframe');
        const d = document;
        
        try {
            if (!d.fullscreenElement && !d.webkitFullscreenElement) {
                console.log('Attempting to enter fullscreen...');
                await enterFSPreferIframe(iframe, stage);
                root.classList.add('is-fs');
                console.log('Fullscreen entered successfully');
            } else {
                console.log('Exiting fullscreen...');
                await exitFS();
                root.classList.remove('is-fs');
                console.log('Fullscreen exited successfully');
            }
        } catch (e) {
            console.warn('Native fullscreen failed:', e?.message);
            // Fallback: fake fullscreen via top-level portal
            enterFakeFullscreen(root);
        }
    }

    // Fallback fake fullscreen for devices that don't support native FS
    function enterFakeFullscreen(el) {
        console.log('Entering fake fullscreen mode');
        
        const component = el.closest('.yt-protected');
        if (!component) return Promise.reject(new Error('Component not found'));
        
        // Create portal container if it doesn't exist
        let portal = document.getElementById('yt-fs-portal');
        if (!portal) {
            portal = document.createElement('div');
            portal.id = 'yt-fs-portal';
            document.body.appendChild(portal);
        }
        
        // Store original parent for restoration
        // Store original parent selector and index for restoration
        try {
            const parent = component.parentNode;
            // Use a simple unique key by assigning an id if missing
            if (!parent.id) parent.id = 'yt-original-parent-' + Math.random().toString(36).slice(2, 9);
            component.dataset.originalParentId = parent.id;
            component.dataset.originalIndex = Array.from(parent.children).indexOf(component);
        } catch (e) {
            console.warn('Failed to store original parent info for fake fullscreen', e);
        }
        
        // Move component to portal
        portal.appendChild(component);
        
        // Add classes
        component.classList.add('is-fs-fake');
        document.documentElement.classList.add('no-scroll');
        document.body.classList.add('no-scroll');
        
        // Update fullscreen state
        component.classList.add('is-fs');
        
        console.log('Fake fullscreen entered');
        return Promise.resolve();
    }

    // Exit fake fullscreen
    function exitFakeFullscreen() {
        console.log('Exiting fake fullscreen mode');
        
        const portal = document.getElementById('yt-fs-portal');
        if (!portal) return;
        
        const component = portal.querySelector('.yt-protected');
        if (!component) return;
        
        // Restore to original parent
        const originalParentId = component.dataset.originalParentId;
        const originalIndex = parseInt(component.dataset.originalIndex || '0');
        if (originalParentId) {
            const originalParent = document.getElementById(originalParentId);
            if (originalParent) {
                if (originalIndex === 0) {
                    originalParent.insertBefore(component, originalParent.firstChild);
                } else {
                    // If index is larger than number of children, append
                    const referenceNode = originalParent.children[originalIndex] || null;
                    originalParent.insertBefore(component, referenceNode);
                }
            }
        }
        
        // Remove classes
        component.classList.remove('is-fs-fake', 'is-fs');
        document.documentElement.classList.remove('no-scroll');
        document.body.classList.remove('no-scroll');
        
        // Clean up portal if empty
        if (portal.children.length === 0) {
            portal.remove();
        }
        
        console.log('Fake fullscreen exited');
    }

    // Handle fullscreen change events
    function handleFullscreenChange() {
        const doc = document;
        const isFullscreen = !!(doc.fullscreenElement || doc.webkitFullscreenElement || doc.msFullscreenElement);
        
        console.log('Fullscreen state changed:', isFullscreen);
        
        // Find all YouTube components and update their state
        document.querySelectorAll('.yt-protected').forEach(component => {
            if (isFullscreen) {
                component.classList.add('is-fs');
            } else {
                component.classList.remove('is-fs');
            }
        });
    }

    // Handle orientation change for mobile devices
    function handleOrientationChange() {
        console.log('Orientation changed, updating fullscreen layout');
        
        // Force layout recalculation for fullscreen components
        document.querySelectorAll('.yt-protected.is-fs, .yt-protected.is-fs-fake').forEach(component => {
            const stage = component.querySelector('.yt-stage');
            if (stage) {
                // Trigger reflow
                stage.style.transform = 'translateZ(0)';
                setTimeout(() => {
                    stage.style.transform = '';
                }, 10);
            }
        });
    }

    // Wire up fullscreen and orientation event listeners
    function wireUpFullscreenEvents() {
        const events = ['fullscreenchange', 'webkitfullscreenchange', 'msfullscreenchange'];
        events.forEach(event => {
            document.addEventListener(event, handleFullscreenChange);
        });
        
        // Listen for orientation changes on mobile
        if ('onorientationchange' in window) {
            window.addEventListener('orientationchange', handleOrientationChange);
        } else {
            window.addEventListener('resize', handleOrientationChange);
        }
        
        // Listen for ESC key to exit fullscreen
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const doc = document;
                const isFullscreen = !!(doc.fullscreenElement || doc.webkitFullscreenElement || doc.msFullscreenElement);
                
                if (isFullscreen) {
                    exitFS();
                } else {
                    // Check for fake fullscreen
                    const fakeFullscreen = document.querySelector('.yt-protected.is-fs-fake');
                    if (fakeFullscreen) {
                        exitFakeFullscreen();
                    }
                }
            }
        });
    }

    // Create YouTube player
    function createPlayer(playerId, videoId) {
        console.log(`[player-create] Creating player ${playerId} for video ${videoId}`);
        
        const player = new YT.Player(`yt-player-${playerId}`, {
            videoId: videoId,
            playerVars: {
                enablejsapi: 1,
                modestbranding: 1,
                rel: 0,
                playsinline: 1,
                fs: 1,
                controls: 0, // hide native controls, we provide custom controls
                showinfo: 0,
                iv_load_policy: 3,
                cc_load_policy: 0,
                autoplay: 0,
                mute: 0
            },
            events: {
                onReady: (event) => onPlayerReady(event, playerId),
                onStateChange: (event) => onPlayerStateChange(event, playerId),
                onError: (event) => onPlayerError(event, playerId)
            }
        });
        
        players[playerId] = player;
        
        // Ensure iframe has proper fullscreen attributes after creation
        setTimeout(() => {
            const iframe = document.querySelector(`#yt-player-${playerId} iframe`);
            if (iframe) {
                iframe.setAttribute('allowfullscreen', '');
                iframe.setAttribute('allow', 'fullscreen; autoplay; encrypted-media; picture-in-picture; web-share');
                iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
                console.log(`[player-create] Iframe attributes set for fullscreen support`);
            }
        }, 100);
        
        return player;
    }

    // Player ready event
    function onPlayerReady(event, playerId) {
        const player = players[playerId];
        if (player) {
            player.unMute();
            player.setVolume(100);
            console.log(`[player-ready] Player ${playerId} ready. Muted: ${player.isMuted()}, Volume: ${player.getVolume()}`);
            wireUpControls(playerId, document.querySelector(`[data-player-id="${playerId}"]`));
            startTimeUpdates(playerId);
        }
    }

    // Player state change event
    function onPlayerStateChange(event, playerId) {
        const player = players[playerId];
        const component = document.querySelector(`[data-player-id="${playerId}"]`);
        if (!player || !component) return;

        const playBtn = component.querySelector('.yt-btn-play');
        const pauseBtn = component.querySelector('.yt-btn-pause');
        const muteBtn = component.querySelector('.yt-btn-mute');
        const unmuteBtn = component.querySelector('.yt-btn-unmute');
        const volumeBar = component.querySelector('.yt-volume');
        const volumeIcon = component.querySelector('.yt-volume-icon');

        switch (event.data) {
            case YT.PlayerState.PLAYING:
                playBtn.style.display = 'none';
                pauseBtn.style.display = 'inline-flex';
                // Hide large central play button when playing
                const bigPlayHide = component.querySelector('.yt-big-play');
                if (bigPlayHide) bigPlayHide.classList.add('hidden');
                if (player.isMuted()) {
                    player.unMute();
                    console.log(`[player-state] Player ${playerId} was muted while playing, unmuted. Volume: ${player.getVolume()}`);
                }
                break;
            case YT.PlayerState.PAUSED:
                playBtn.style.display = 'inline-flex';
                pauseBtn.style.display = 'none';
                // Show big play on pause
                const bigPlayShow = component.querySelector('.yt-big-play');
                if (bigPlayShow) bigPlayShow.classList.remove('hidden');
                break;
            case YT.PlayerState.ENDED:
                playBtn.style.display = 'inline-flex';
                pauseBtn.style.display = 'none';
                component.querySelector('.yt-seek').value = 0;
                const bigPlayEnd = component.querySelector('.yt-big-play');
                if (bigPlayEnd) bigPlayEnd.classList.remove('hidden');
                break;
            case YT.PlayerState.BUFFERING:
                // Optionally show a buffering indicator
                break;
            case YT.PlayerState.CUED:
                // Video is cued, ready to play
                break;
        }

        // Update mute/unmute button state
        if (player.isMuted()) {
            muteBtn.style.display = 'none';
            unmuteBtn.style.display = 'inline-flex';
            volumeIcon.textContent = '🔇';
        } else {
            muteBtn.style.display = 'inline-flex';
            unmuteBtn.style.display = 'none';
            volumeIcon.textContent = '🔊';
        }
        volumeBar.value = player.getVolume();
    }

    // Player error event
    function onPlayerError(event, playerId) {
        console.error(`[player-error] Player ${playerId} error:`, event.data);
    }

    // Wire up custom controls
    function wireUpControls(playerId, component) {
        const player = players[playerId];
        if (!player) return;
        // Auto-hide controls helpers
        const CONTROL_HIDE_DELAY = 3000; // ms
        let hideTimer = null;

        function showControls() {
            component.classList.add('show-controls');
            // ensure controls are visible
            const controls = component.querySelector('.yt-controls');
            if (controls) {
                controls.classList.remove('hidden');
            }
            // reset hide timer
            if (hideTimer) clearTimeout(hideTimer);
            hideTimer = setTimeout(() => {
                hideControls();
            }, CONTROL_HIDE_DELAY);
        }

        function hideControls() {
            component.classList.remove('show-controls');
            const controls = component.querySelector('.yt-controls');
            if (controls) {
                controls.classList.add('hidden');
            }
            if (hideTimer) {
                clearTimeout(hideTimer);
                hideTimer = null;
            }
        }

        // Show controls on any pointer activity and schedule hide
        const activityHandler = function (e) {
            e && e.preventDefault && e.preventDefault();
            showControls();
        };

        // Add pointer/touch listeners to reveal controls
        component.addEventListener('pointermove', activityHandler, { passive: true });
        component.addEventListener('pointerup', activityHandler);
        component.addEventListener('touchstart', activityHandler, { passive: true });
        component.addEventListener('mousemove', activityHandler);

        // When component is removed/destroyed, clear timer
        const cleanupUI = () => {
            if (hideTimer) clearTimeout(hideTimer);
            component.removeEventListener('pointermove', activityHandler);
            component.removeEventListener('pointerup', activityHandler);
            component.removeEventListener('touchstart', activityHandler);
            component.removeEventListener('mousemove', activityHandler);
        };
        // Large central play button (mobile friendly)
        const bigPlay = component.querySelector('.yt-big-play');
        if (bigPlay) {
            bigPlay.addEventListener('pointerup', (e) => {
                e.preventDefault();
                e.stopPropagation();
                try {
                    if (player.isMuted()) player.unMute();
                    player.playVideo();
                    bigPlay.classList.add('hidden');
                } catch (err) {
                    console.error('Big play failed', err);
                }
            });
        }

        // Play button
        const playBtn = component.querySelector('.yt-btn-play');
        if (playBtn) {
            // use pointerup for better mobile responsiveness
            playBtn.addEventListener('pointerup', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                console.log(`Play button clicked for player ${playerId}`);
                
                // Ensure audio is ready before playing
                try {
                    // Unmute if muted
                    if (player.isMuted()) {
                        console.log(`Unmuting player ${playerId} before play`);
                        player.unMute();
                    }
                    
                    // Set volume from UI slider
                    const volumeSlider = component.querySelector('.yt-volume');
                    if (volumeSlider) {
                        const uiVolume = parseInt(volumeSlider.value);
                        player.setVolume(uiVolume);
                        console.log(`Set volume to ${uiVolume} for player ${playerId}`);
                    }
                    
                    // Log audio state before play
                    console.log(`Audio state before play for ${playerId}:`, {
                        isMuted: player.isMuted(),
                        volume: player.getVolume(),
                        playerState: player.getPlayerState()
                    });
                    
                    // Play the video
                    player.playVideo();
                    
                    // Log audio state after play
                    setTimeout(() => {
                        console.log(`Audio state after play for ${playerId}:`, {
                            isMuted: player.isMuted(),
                            volume: player.getVolume(),
                            playerState: player.getPlayerState()
                        });
                    }, 100);
                    
                } catch (error) {
                    console.error(`Error playing video for player ${playerId}:`, error);
                }
            });
        }

        // Pause button
        const pauseBtn = component.querySelector('.yt-btn-pause');
        if (pauseBtn) {
            pauseBtn.addEventListener('pointerup', (e) => {
                e.preventDefault();
                e.stopPropagation();
                player.pauseVideo();
            });
        }

        // Mute button
        const muteBtn = component.querySelector('.yt-btn-mute');
        if (muteBtn) {
            muteBtn.addEventListener('pointerup', (e) => {
                e.preventDefault();
                e.stopPropagation();
                player.mute();
            });
        }

        // Unmute button
        const unmuteBtn = component.querySelector('.yt-btn-unmute');
        if (unmuteBtn) {
            unmuteBtn.addEventListener('pointerup', (e) => {
                e.preventDefault();
                e.stopPropagation();
                player.unMute();
            });
        }

        // Seek bar
        const seekBar = component.querySelector('.yt-seek');
        if (seekBar) {
            let isSeeking = false;
            
            seekBar.addEventListener('mousedown', (e) => {
                e.preventDefault();
                isSeeking = true;
            });
            
            seekBar.addEventListener('mouseup', (e) => {
                e.preventDefault();
                isSeeking = false;
            });
            
            seekBar.addEventListener('input', (e) => {
                e.preventDefault();
                if (isSeeking) {
                    const duration = player.getDuration();
                    const seekTime = (e.target.value / 100) * duration;
                    player.seekTo(seekTime, true);
                }
            });
            
            // Mobile touch events
            seekBar.addEventListener('touchstart', (e) => {
                e.preventDefault();
                isSeeking = true;
            });
            
            seekBar.addEventListener('touchend', (e) => {
                e.preventDefault();
                isSeeking = false;
            });
        }

        // Volume control
        const volumeBar = component.querySelector('.yt-volume');
        const volumeIcon = component.querySelector('.yt-volume-icon');
        
        if (volumeBar && volumeIcon) {
            volumeBar.addEventListener('input', (e) => {
                e.preventDefault();
                const volume = parseInt(e.target.value);
                player.setVolume(volume);
                
                // Unmute if volume is set above 0
                if (volume > 0 && player.isMuted()) {
                    console.log(`Unmuting player ${playerId} due to volume increase`);
                    player.unMute();
                }
                
                // Update volume icon based on volume level
                if (volume === 0) {
                    volumeIcon.textContent = '🔇';
                } else if (volume < 50) {
                    volumeIcon.textContent = '🔉';
                } else {
                    volumeIcon.textContent = '🔊';
                }
                
                console.log(`Volume set to ${volume} for player ${playerId}`);
            });
            
            // Click volume icon to mute/unmute
            volumeIcon.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                if (player.isMuted()) {
                    player.unMute();
                    volumeIcon.textContent = '🔊';
                    volumeBar.value = 100;
                    console.log(`Unmuted player ${playerId}`);
                } else {
                    player.mute();
                    volumeIcon.textContent = '🔇';
                    volumeBar.value = 0;
                    console.log(`Muted player ${playerId}`);
                }
            });
        }

    // Fullscreen button
    const fsBtn = component.querySelector('.yt-btn-fs');
    if (fsBtn) {
            // Use pointerup for better mobile support, with click fallback
            // Do NOT mark as passive: we need to call preventDefault on some platforms
            fsBtn.addEventListener('pointerup', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const stage = component.querySelector('.yt-stage');
                const iframe = stage?.querySelector('iframe');
                const mask = component.querySelector('.yt-mask');
                const prevPointer = mask ? mask.style.pointerEvents : null;

                // Temporarily disable mask pointer events so iframe receives the gesture
                if (mask) mask.style.pointerEvents = 'none';

                const restoreMask = () => {
                    if (mask) mask.style.pointerEvents = prevPointer || '';
                    document.removeEventListener('fullscreenchange', restoreMask);
                    document.removeEventListener('webkitfullscreenchange', restoreMask);
                };

                document.addEventListener('fullscreenchange', restoreMask);
                document.addEventListener('webkitfullscreenchange', restoreMask);

                const tryIframeFS = async () => {
                    try {
                        if (iframe && (iframe.requestFullscreen || iframe.webkitRequestFullscreen)) {
                            if (iframe.requestFullscreen) {
                                await iframe.requestFullscreen();
                            } else if (iframe.webkitRequestFullscreen) {
                                iframe.webkitRequestFullscreen();
                            }
                            component.classList.add('is-fs');
                            return true;
                        }
                    } catch (err) {
                        console.warn('Iframe fullscreen failed, falling back to stage:', err);
                    }
                    return false;
                };

                (async () => {
                    const ok = await tryIframeFS();
                    if (!ok) {
                        // restore if iframe FS didn't happen quickly
                        if (mask) setTimeout(() => { mask.style.pointerEvents = prevPointer || ''; }, 300);
                        toggleFullscreen(component);
                    } else {
                        // on successful iframe fullscreen, hide controls after short delay
                        setTimeout(() => {
                            const controls = component.querySelector('.yt-controls');
                            if (controls) controls.classList.add('hidden');
                            component.classList.remove('show-controls');
                        }, 500);
                    }
                })();
            });

            // Fallback for devices without pointer events
            fsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleFullscreen(component);
            });
        }
    }

    // Start time updates for seek bar
    function startTimeUpdates(playerId) {
        setInterval(() => {
            const player = players[playerId];
            if (!player) return;
            
            try {
                const currentTime = player.getCurrentTime();
                const duration = player.getDuration();
                
                if (duration > 0) {
                    const progress = (currentTime / duration) * 100;
                    const component = document.querySelector(`[data-player-id="${playerId}"]`);
                    if (component) {
                        const seekBar = component.querySelector('.yt-seek');
                        if (seekBar) {
                            seekBar.value = progress;
                        }
                    }
                }
            } catch (e) {
                // Player might not be ready yet
            }
        }, 1000);
    }

    // Toggle fullscreen
    function toggleFullscreen(root) {
        const stageEl = root.querySelector('.yt-stage');
        const iframe = stageEl?.querySelector('iframe');

        const isDocFull = !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement);

        if (!isDocFull) {
            // Try iframe-first (helps some iOS/Safari builds)
            try {
                if (iframe && (iframe.requestFullscreen || iframe.webkitRequestFullscreen)) {
                    if (iframe.requestFullscreen) iframe.requestFullscreen();
                    else if (iframe.webkitRequestFullscreen) iframe.webkitRequestFullscreen();
                    return;
                }
            } catch (e) {
                // ignore and fallback to stage
            }

            // Fallback to stage element
            if (stageEl.requestFullscreen) {
                stageEl.requestFullscreen();
            } else if (stageEl.webkitRequestFullscreen) {
                stageEl.webkitRequestFullscreen();
            } else if (stageEl.msRequestFullscreen) {
                stageEl.msRequestFullscreen();
            } else {
                // As last resort, use fake fullscreen
                enterFakeFullscreen(root);
            }
        } else {
            // Exit fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else {
                exitFakeFullscreen();
            }
        }
    }

    // Global callback for YouTube API
    window.onYouTubeIframeAPIReady = function() {
        console.log('[youtube-api] YouTube IFrame API ready (global callback)');
        onYouTubeIframeAPIReady();
    };

    // Initialize component
    function initComponent(playerId, videoId) {
        // TEMP DEBUG: Log player container dimensions and user agent
        console.log('[player]', {
            shell: document.querySelector('.lesson-player-shell')?.getBoundingClientRect(),
            wrap: document.querySelector('.lesson-player-wrap')?.getBoundingClientRect(),
            stage: document.querySelector('.yt-stage, .vp-stage')?.getBoundingClientRect(),
            media: document.querySelector('.yt-stage iframe, .vp-stage video')?.getBoundingClientRect(),
            ua: navigator.userAgent
        });
        
        if (youtubeAPIReady) {
            createPlayer(playerId, videoId);
        } else {
            pendingComponents.push({ playerId, videoId });
        }
    }

    // Public API
    window.YouTubeProtectedPlayer = {
        initComponent: initComponent,
        getPlayer: function(playerId) {
            return players[playerId];
        },
        destroyPlayer: function(playerId) {
            const player = players[playerId];
            if (player) {
                player.destroy();
                delete players[playerId];
            }
        },
        testAudio: function(playerId) {
            const player = players[playerId];
            if (player) {
                console.log(`Audio test for player ${playerId}:`, {
                    isMuted: player.isMuted(),
                    volume: player.getVolume(),
                    playerState: player.getPlayerState()
                });
            }
        }
    };

    // Initialize fullscreen events
    wireUpFullscreenEvents();
    
    // Load YouTube API
    loadYouTubeAPI();
})();

// TEMP diagnostics function - remove after verification on real iPhone
function runIOSDiagnostics() {
    const stage = document.querySelector('.yt-stage, .vp-stage');
    if (!stage) {
        console.log('[ios-diagnostics] No stage element found');
        return;
    }
    
    console.log('[ios-diagnostics] Stage found:', stage);
    
    // Build ancestor chain up to body
    const ancestors = [];
    let current = stage;
    
    while (current && current !== document.body) {
        const rect = current.getBoundingClientRect();
        const computed = window.getComputedStyle(current);
        
        // Check for known iOS blockers
        const isBlocker = (
            computed.display === 'none' ||
            rect.height === 0 ||
            computed.visibility === 'hidden' ||
            computed.transform !== 'none' ||
            computed.filter !== 'none' ||
            computed.backdropFilter !== 'none' ||
            parseFloat(computed.opacity) < 1 ||
            (computed.overflow === 'hidden' && parseFloat(computed.borderRadius) > 0)
        );
        
        ancestors.push({
            el: current.tagName + (current.className ? '.' + current.className.split(' ')[0] : ''),
            w: rect.width,
            h: rect.height,
            display: computed.display,
            overflow: computed.overflow,
            transform: computed.transform,
            filter: computed.filter,
            backdropFilter: computed.backdropFilter,
            opacity: computed.opacity,
            radius: computed.borderRadius,
            position: computed.position,
            visibility: computed.visibility,
            blocker: isBlocker
        });
        
        current = current.parentElement;
    }
    
    // Log ancestor chain analysis
    console.table(ancestors);
    
    // Check for first blocker
    const firstBlocker = ancestors.find(a => a.blocker);
    if (firstBlocker) {
        console.warn('[ios-diagnostics] FIRST BLOCKER FOUND:', firstBlocker);
    } else {
        console.log('[ios-diagnostics] No obvious blockers found in ancestor chain');
    }
    
    // Check ::before pseudo-element for aspect-ratio fallback
    try {
        const beforeContent = window.getComputedStyle(stage, '::before').content;
        console.log('[ios-diagnostics] ::before content:', beforeContent);
    } catch (e) {
        console.log('[ios-diagnostics] ::before check failed:', e.message);
    }
    
    // Log stage dimensions
    const stageRect = stage.getBoundingClientRect();
    console.log('[ios-diagnostics] Stage dimensions:', {
        width: stageRect.width,
        height: stageRect.height,
        top: stageRect.top,
        left: stageRect.left,
        visible: stageRect.width > 0 && stageRect.height > 0
    });
}
