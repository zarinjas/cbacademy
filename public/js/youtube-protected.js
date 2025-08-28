/**
 * YouTube Protected Player - Custom Controls with IFrame API
 * Prevents users from clicking into YouTube while maintaining full functionality
 * Mobile-first fullscreen implementation with iframe priority
 */

(function() {
    'use strict';

    // Global YouTube API state
    let youtubeAPIReady = false;
    let pendingComponents = [];
    const players = {};

    // YouTube IFrame API loader
    function loadYouTubeAPI() {
        if (window.YT && window.YT.Player) {
            youtubeAPIReady = true;
            initializePendingComponents();
            return;
        }

        // Load YouTube IFrame API
        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    // YouTube API ready callback
    window.onYouTubeIframeAPIReady = function() {
        youtubeAPIReady = true;
        initializePendingComponents();
    };

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
        component.dataset.originalParent = component.parentNode;
        component.dataset.originalIndex = Array.from(component.parentNode.children).indexOf(component);
        
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
        const originalParent = component.dataset.originalParent;
        const originalIndex = parseInt(component.dataset.originalIndex);
        
        if (originalParent) {
            if (originalIndex === 0) {
                originalParent.insertBefore(component, originalParent.firstChild);
            } else {
                const referenceNode = originalParent.children[originalIndex];
                originalParent.insertBefore(component, referenceNode);
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

    // Create player with security parameters
    function createPlayer(playerId, videoId) {
        const player = new YT.Player(`yt-player-${playerId}`, {
            videoId: videoId,
            playerVars: {
                enablejsapi: 1,        // Enable JavaScript API
                modestbranding: 1,     // Hide YouTube branding
                rel: 0,                // Don't show related videos
                playsinline: 1,        // Play inline on mobile (essential for iOS)
                fs: 1,                 // Enable fullscreen
                controls: 1,           // Show some controls for better compatibility
                showinfo: 0,           // Hide video info
                iv_load_policy: 3,     // Disable annotations
                cc_load_policy: 0,     // Disable captions
                autoplay: 0,           // Don't autoplay
                mute: 0                // Ensure NOT muted by default
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
                console.log('Iframe attributes set for fullscreen support');
            }
        }, 100);
        
        return player;
    }

    // Player ready event handler
    function onPlayerReady(event, playerId) {
        console.log(`Player ${playerId} ready`);
        const component = document.querySelector(`[data-player-id="${playerId}"]`);
        if (component) {
            const player = players[playerId];
            if (player) {
                console.log(`Initial audio state for ${playerId}:`, {
                    isMuted: player.isMuted(),
                    volume: player.getVolume(),
                    playerState: player.getPlayerState()
                });
                try {
                    if (player.isMuted()) {
                        console.log(`Unmuting player ${playerId}`);
                        player.unMute();
                    }
                    player.setVolume(100);
                    console.log(`Set volume to 100 for player ${playerId}`);
                    console.log(`Final audio state for ${playerId}:`, {
                        isMuted: player.isMuted(),
                        volume: player.getVolume(),
                        playerState: player.getPlayerState()
                    });
                } catch (e) {
                    console.warn(`Could not set audio state for player ${playerId}:`, e);
                }
            }
            wireUpControls(playerId, component);
            startTimeUpdates(playerId);
        }
    }

    // Player state change event handler
    function onPlayerStateChange(event, playerId) {
        const component = document.querySelector(`[data-player-id="${playerId}"]`);
        if (!component) return;

        const playBtn = component.querySelector('.yt-btn-play');
        const pauseBtn = component.querySelector('.yt-btn-pause');
        const muteBtn = component.querySelector('.yt-btn-mute');
        const unmuteBtn = component.querySelector('.yt-btn-unmute');
        const player = players[playerId];

        if (event.data === YT.PlayerState.PLAYING) {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-flex';
            if (player && player.isMuted()) {
                console.log(`Player ${playerId} was muted while playing, unmuting...`);
                player.unMute();
            }
            console.log(`Audio state while playing for ${playerId}:`, {
                isMuted: player.isMuted(),
                volume: player.getVolume(),
                playerState: player.getPlayerState()
            });
        } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
            playBtn.style.display = 'inline-flex';
            pauseBtn.style.display = 'none';
        }

        if (player.isMuted()) {
            muteBtn.style.display = 'none';
            unmuteBtn.style.display = 'inline-flex';
        } else {
            muteBtn.style.display = 'inline-flex';
            unmuteBtn.style.display = 'none';
        }
    }

    // Player error event handler
    function onPlayerError(event, playerId) {
        console.error(`Player ${playerId} error:`, event.data);
    }

    // Wire up custom controls
    function wireUpControls(playerId, component) {
        const player = players[playerId];
        if (!player) return;

        // Play button
        const playBtn = component.querySelector('.yt-btn-play');
        if (playBtn) {
            playBtn.addEventListener('click', (e) => {
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
            pauseBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                player.pauseVideo();
            });
        }

        // Mute button
        const muteBtn = component.querySelector('.yt-btn-mute');
        if (muteBtn) {
            muteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                player.mute();
            });
        }

        // Unmute button
        const unmuteBtn = component.querySelector('.yt-btn-unmute');
        if (unmuteBtn) {
            unmuteBtn.addEventListener('click', (e) => {
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
            fsBtn.addEventListener('pointerup', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleFullscreen(component);
            }, { passive: true });
            
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

    // Initialize component
    function initComponent(playerId, videoId) {
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
