@props(['icon', 'filled' => false])

<span {{ $attributes->merge(['class' => 'material-symbols-outlined' . $filled ? ' material-symbols--filled' : '']) }}>{{ $icon }}</span>
