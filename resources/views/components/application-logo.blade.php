<svg {{ $attributes }} viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="wf-grad-bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#2563EB" />
            <stop offset="60%" stop-color="#1D4ED8" />
            <stop offset="100%" stop-color="#0E7490" />
        </linearGradient>
        <linearGradient id="wf-grad-wave" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#FFFFFF" />
            <stop offset="100%" stop-color="#E0F2FE" />
        </linearGradient>
    </defs>
    <!-- Rounded Container -->
    <rect width="48" height="48" rx="12" fill="url(#wf-grad-bg)" />
    <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="11.25" stroke="#FFFFFF" stroke-opacity="0.2" stroke-width="1.5" />

    <!-- Outer Swirl / Drum Arc -->
    <circle cx="24" cy="24" r="14.5" stroke="#FFFFFF" stroke-opacity="0.22" stroke-width="2" stroke-dasharray="4 3" />

    <!-- WashFlow Fluid Wave Vortex -->
    <path d="M 13 24 C 13 17.9 17.9 13 24 13 C 30.1 13 35 17.9 35 24 C 35 28.5 32.2 32.5 28.3 34 C 24.3 35.5 19.8 33.6 18.5 29.8 C 17.2 26 19.4 21.8 23.2 21 C 26.2 20.2 28.8 22 28.8 24.5 C 28.8 26.5 27 28 25 28 C 23.6 28 22.6 27 22.6 25.8" 
          stroke="url(#wf-grad-wave)" 
          stroke-width="2.75" 
          stroke-linecap="round" />

    <!-- Floating Droplets / Fresh Clean Accents -->
    <circle cx="24" cy="24" r="2.25" fill="#38BDF8" />
    <circle cx="34" cy="14" r="2" fill="#FFFFFF" fill-opacity="0.9" />
    <circle cx="14" cy="33" r="1.5" fill="#BAE6FD" fill-opacity="0.8" />
</svg>
