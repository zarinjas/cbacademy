/**
 * YouTube Protected Player - Custom Controls with IFrame API
 * Prevents users from clicking into YouTube while maintaining full functionality
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

    // Robust fullscreen toggle with native support and fallback
    function toggleFullscreen(stageEl) {
        const doc = document;
        const isFullscreen = !!(doc.fullscreenElement || doc.webkitFullscreenElement || doc.msFullscreenElement);
        
        if (isFullscreen) {
            // Exit fullscreen
            return exitFullscreen();
        } else {
            // Enter fullscreen
            return enterFullscreen(stageEl);
        }
    }

    // Enter fullscreen with native support
    function enterFullscreen(el) {
        console.log('Attempting native fullscreen...');
        
        try {
            if (el.requestFullscreen) {
                return el.requestFullscreen({ navigationUI: 'hide' });
            } else if (el.webkitRequestFullscreen) {
                return el.webkitRequestFullscreen(); // Safari
            } else if (el.msRequestFullscreen) {
                return el.msRequestFullscreen();
            } else {
                throw new Error('no-native-fs');
            }
        } catch (error) {
            console.log('Native fullscreen failed, using fallback:', error.message);
            return enterFakeFullscreen(el);
        }
    }

    // Exit fullscreen
    function exitFullscreen() {
        const doc = document;
        
        if (doc.exitFullscreen) {
            return doc.exitFullscreen();
        } else if (doc.webkitExitFullscreen) {
            return doc.webkitExitFullscreen();
        } else if (doc.msExitFullscreen) {
            return doc.msExitFullscreen();
        }
        
        return Promise.resolve();
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
                    exitFullscreen();
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

    // Initialize fullscreen events
    wireUpFullscreenEvents();

    // Create YouTube player instance
    function createPlayer(playerId, videoId) {
        if (players[playerId]) {
            return players[playerId];
        }

        const playerElement = document.getElementById(`yt-player-${playerId}`);
        if (!playerElement) {
            console.error(`Player element not found: yt-player-${playerId}`);
            return null;
        }

        // Create player with security parameters
        const player = new YT.Player(`yt-player-${playerId}`, {
            videoId: videoId,
            playerVars: {
                enablejsapi: 1,        // Enable JavaScript API
                modestbranding: 1,     // Hide YouTube branding
                rel: 0,                // Don't show related videos
                playsinline: 1,        // Play inline on mobile
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
        return player;
    }

    // Player ready event handler
    function onPlayerReady(event, playerId) {
        console.log(`Player ${playerId} ready`);
        const component = document.querySelector(`[data-player-id="${playerId}"]`);
        if (component) {
            // Debug audio state
            const player = players[playerId];
            if (player) {
                console.log(`Audio state for ${playerId}:`, {
                    isMuted: player.isMuted(),
                    volume: player.getVolume(),
                    playerState: player.getPlayerState()
                });
                
                // Ensure audio is not muted and volume is set
                try {
                    if (player.isMuted()) {
                        console.log(`Unmuting player ${playerId}`);
                        player.unMute();
                    }
                    player.setVolume(100);
                    console.log(`Set volume to 100 for player ${playerId}`);
                    
                    // Log final audio state
                    console.log(`Final audio state for ${playerId}:`, {
                        isMuted: player.isMuted(),
                        volume: player.getVolume(),
                        playerState: player.getPlayerState()
                    });
                } catch (e) {
                    console.warn(`Could not set audio state for player ${playerId}:`, e);
                }
            }
            
            // Wire up controls
            wireUpControls(playerId, component);
            
            // Start time updates for seek bar
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

        // Update play/pause button states
        if (event.data === YT.PlayerState.PLAYING) {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-flex';
            
            // Ensure audio is not muted when playing
            const player = players[playerId];
            if (player && player.isMuted()) {
                console.log(`Player ${playerId} was muted while playing, unmuting...`);
                player.unMute();
            }
            
            // Log audio state when playing
            console.log(`Audio state while playing for ${playerId}:`, {
                isMuted: player.isMuted(),
                volume: player.getVolume(),
                playerState: player.getPlayerState()
            });
        } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
            playBtn.style.display = 'inline-flex';
            pauseBtn.style.display = 'none';
        }

        // Update mute button states
        if (event.target.isMuted()) {
            muteBtn.style.display = 'none';
            unmuteBtn.style.display = 'inline-flex';
        } else {
            muteBtn.style.display = 'inline-flex';
            unmuteBtn.style.display = 'none';
        }
    }

    // Player error event handler
    function onPlayerError(event, playerId) {
        console.error(`YouTube player error: ${playerId}`, event.data);
        const component = document.querySelector(`[data-player-id="${playerId}"]`);
        if (component) {
            const loading = component.querySelector(`#yt-loading-${playerId}`);
            if (loading) {
                loading.innerHTML = `
                    <div class="yt-error">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Video failed to load</p>
                        <small>Error code: ${event.data}</small>
                    </div>
                `;
            }
        }
    }

    // Wire up custom controls
    function wireUpControls(playerId, component) {
        const player = players[playerId];
        if (!player) return;

        // Play button
        const playBtn = component.querySelector('.yt-btn-play');
        if (playBtn) {
            playBtn.addEventListener('click', () => {
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
                    
                } catch (e) {
                    console.error(`Error playing video for player ${playerId}:`, e);
                }
            });
        }

        // Pause button
        const pauseBtn = component.querySelector('.yt-btn-pause');
        if (pauseBtn) {
            pauseBtn.addEventListener('click', () => {
                player.pauseVideo();
            });
        }

        // Mute button
        const muteBtn = component.querySelector('.yt-btn-mute');
        if (muteBtn) {
            muteBtn.addEventListener('click', () => {
                player.mute();
            });
        }

        // Unmute button
        const unmuteBtn = component.querySelector('.yt-btn-unmute');
        if (unmuteBtn) {
            unmuteBtn.addEventListener('click', () => {
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
            fsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const stageEl = component.querySelector('.yt-stage');
                if (stageEl) {
                    toggleFullscreen(stageEl);
                }
            });
        }
    }

    // Start time updates for seek bar
    function startTimeUpdates(playerId) {
        const component = document.querySelector(`[data-player-id="${playerId}"]`);
        if (!component) return;
        
        const seekBar = component.querySelector('.yt-seek');
        if (!seekBar) return;
        
        setInterval(() => {
            const player = players[playerId];
            if (player && player.getCurrentTime && player.getDuration) {
                try {
                    const currentTime = player.getCurrentTime();
                    const duration = player.getDuration();
                    
                    if (duration > 0) {
                        const progress = (currentTime / duration) * 100;
                        seekBar.value = progress;
                    }
                } catch (e) {
                    // Player not ready yet
                }
            }
        }, 1000);
    }

    // Initialize a component instance
    window.YouTubeProtectedPlayer = {
        // Initialize a component instance
        initComponent: function(playerId, videoId) {
            if (youtubeAPIReady) {
                createPlayer(playerId, videoId);
            } else {
                pendingComponents.push({ playerId, videoId });
            }
        },

        // Get player instance
        getPlayer: function(playerId) {
            return players[playerId] || null;
        },

        // Destroy player instance
        destroyPlayer: function(playerId) {
            if (players[playerId]) {
                players[playerId].destroy();
                delete players[playerId];
            }
        },

        // Test audio functionality
        testAudio: function(playerId) {
            const player = players[playerId];
            if (!player) {
                console.error(`Player ${playerId} not found`);
                return false;
            }
            
            try {
                const audioState = {
                    isMuted: player.isMuted(),
                    volume: player.getVolume(),
                    playerState: player.getPlayerState(),
                    canPlay: player.getPlayerState() === YT.PlayerState.PLAYING
                };
                
                console.log(`Audio test for player ${playerId}:`, audioState);
                
                // Try to unmute and set volume
                if (audioState.isMuted) {
                    player.unMute();
                    console.log(`Unmuted player ${playerId}`);
                }
                
                player.setVolume(100);
                console.log(`Set volume to 100 for player ${playerId}`);
                
                return true;
            } catch (e) {
                console.error(`Audio test failed for player ${playerId}:`, e);
                return false;
            }
        }
    };

    // Auto-load YouTube API when script loads
    loadYouTubeAPI();

})();
