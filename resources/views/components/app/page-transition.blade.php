@props(['class' => ''])

<div 
    x-data="{
        loaded: false,
        init() {
            this.$nextTick(() => {
                this.loaded = true;
            });
        }
    }"
    x-show="loaded"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-4"
    class="animate-fade-in {{ $class }}"
>
    {{ $slot }}
</div>
