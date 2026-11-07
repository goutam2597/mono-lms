@props([
    'name',
    'value' => '1',
    'checked' => false,
    'label' => null,
    'type' => 'checkbox', // checkbox | radio
    'size' => 'mt-0.5',   // alignment tweak for the control next to text
])

<label {{ $attributes->merge(['class' => 'flex items-start gap-2.5 cursor-pointer select-none group']) }}>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" @checked($checked)
        class="control {{ $size }}" {{ $attributes->only('id', 'required', 'disabled', 'form') }}>

    @if ($label)
        <span class="text-sm text-[#191919] group-hover:text-[#ff4667] transition-colors">{{ $label }}</span>
    @endif

    {{ $slot }}
</label>
