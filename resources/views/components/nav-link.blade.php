@props(['active'])

@php
$classes = ($active ?? false)
            ? 'h-16 inline-flex items-center px-2.5 xl:px-3.5 border-b-2 border-blue-600 text-xs xl:text-sm font-semibold leading-5 text-blue-600 whitespace-nowrap focus:outline-hidden transition duration-150 ease-in-out shrink-0'
            : 'h-16 inline-flex items-center px-2.5 xl:px-3.5 border-b-2 border-transparent text-xs xl:text-sm font-medium leading-5 text-slate-500 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap focus:outline-hidden transition duration-150 ease-in-out shrink-0';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
