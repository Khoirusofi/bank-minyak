@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border-gray-300 rounded-xl font-semibold focus:border-[#7B0000] focus:ring-[#7B0000] rounded-md shadow-sm']) }}>
