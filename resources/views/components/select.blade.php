@props(['disabled' => false])

<select name="redeemAmount" id="redeemAmount" @disabled($disabled)
    {{ $attributes->merge(['class' => 'form-select  text-primary border-gray-300 rounded-xl font-semibold focus:border-[#B71C1C] focus:ring-[#B71C1C] shadow-sm']) }}>
    {{ $slot }}
</select>
