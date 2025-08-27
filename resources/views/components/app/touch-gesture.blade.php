@props([
    'direction' => 'horizontal',
    'onSwipeLeft' => '',
    'onSwipeRight' => '',
    'onSwipeUp' => '',
    'onSwipeDown' => '',
    'threshold' => 50,
    'class' => ''
])

<div 
    x-data="{
        startX: 0,
        startY: 0,
        endX: 0,
        endY: 0,
        threshold: {{ $threshold }},
        direction: '{{ $direction }}',
        onSwipeLeft: '{{ $onSwipeLeft }}',
        onSwipeRight: '{{ $onSwipeRight }}',
        onSwipeUp: '{{ $onSwipeUp }}',
        onSwipeDown: '{{ $onSwipeDown }}',
        
        handleTouchStart(e) {
            this.startX = e.touches[0].clientX;
            this.startY = e.touches[0].clientY;
        },
        
        handleTouchEnd(e) {
            this.endX = e.changedTouches[0].clientX;
            this.endY = e.changedTouches[0].clientY;
            
            const deltaX = this.endX - this.startX;
            const deltaY = this.endY - this.startY;
            
            if (Math.abs(deltaX) > Math.abs(deltaY)) {
                // Horizontal swipe
                if (Math.abs(deltaX) > this.threshold) {
                    if (deltaX > 0 && this.onSwipeRight) {
                        this.executeSwipe(this.onSwipeRight);
                    } else if (deltaX < 0 && this.onSwipeLeft) {
                        this.executeSwipe(this.onSwipeLeft);
                    }
                }
            } else {
                // Vertical swipe
                if (Math.abs(deltaY) > this.threshold) {
                    if (deltaY > 0 && this.onSwipeDown) {
                        this.executeSwipe(this.onSwipeDown);
                    } else if (deltaY < 0 && this.onSwipeUp) {
                        this.executeSwipe(this.onSwipeUp);
                    }
                }
            }
        },
        
        executeSwipe(action) {
            if (action.startsWith('http')) {
                window.location.href = action;
            } else if (action.startsWith('route:')) {
                const route = action.replace('route:', '');
                window.location.href = route;
            } else if (action.startsWith('function:')) {
                const funcName = action.replace('function:', '');
                if (typeof window[funcName] === 'function') {
                    window[funcName]();
                }
            }
        }
    }"
    @touchstart="handleTouchStart"
    @touchend="handleTouchEnd"
    {{ $attributes->merge(['class' => 'touch-manipulation ' . $class]) }}
>
    {{ $slot }}
</div>
