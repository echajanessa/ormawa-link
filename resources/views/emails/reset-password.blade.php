<x-mail::message>
    @if (!empty($greeting))
        <h1>{{ $greeting }}</h1>
    @else
        <h1>@lang('Hello hello testing!')</h1>
    @endif

    <div style="font-family: Arial, sans-serif; line-height: 1.5;">
        @foreach ($introLines as $line)
            <p>{{ $line }}</p>
        @endforeach

        @isset($actionText)
            <?php
            $color = 'primary'; // Anda dapat mengubah warna sesuai kebutuhan
            ?>
            <x-mail::button :url="$actionUrl" :color="$color">
                {{ $actionText }}
            </x-mail::button>
        @endisset

        @foreach ($outroLines as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>

    @if (!empty($salutation))
        <p>{{ $salutation }}</p>
    @else
        <p>@lang('Regards,')<br>{{ config('app.name') }}</p>
    @endif

    @isset($actionText)
        <x-slot:subcopy>
            @lang("Jika anda mengalami masalah dalam mengakses tombol \":actionText\" , salin dan jalankan URL dibawah\n" . 'pada web browser Anda:', [
                'actionText' => $actionText,
            ]) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
        </x-slot:subcopy>
    @endisset
</x-mail::message>
