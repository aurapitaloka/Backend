<div class="flex justify-center mb-4">
    <img src="{{ $appBranding->image_url }}" alt="{{ $appBranding->title }} Logo" class="h-24 w-auto drop-shadow-xl">
</div>
<h1 class="login-title">{{ $heading ?? ('Masuk ke ' . $appBranding->title) }}</h1>
<p class="login-subtitle">{{ $subtitle ?? $appBranding->subtitle }}</p>
