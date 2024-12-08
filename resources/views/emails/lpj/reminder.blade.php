@component('mail::message')
    # Pengingat Pengumpulan LPJ

    @switch($reminderType)
        @case('7 days after end_date')
            Anda belum mengumpulkan LPJ untuk kegiatan berikut ini yang telah berakhir 7 hari lalu:
        @break

        @case('7 days before deadline')
            Batas waktu pengumpulan LPJ untuk kegiatan berikut akan berakhir dalam 7 hari:
        @break
    @endswitch

    **Judul Kegiatan**: {{ $proposal->event_name }}
    **Tanggal Kegiatan**: {{ $proposal->start_date->format('d M Y') }} - {{ $proposal->end_date->format('d M Y') }}

    @component('mail::button', ['url' => route('lpj.submit', $proposal->id)])
        Kirim LPJ Sekarang
    @endcomponent

    Terima kasih atas perhatiannya.

    Salam,
    {{ config('app.name') }}
@endcomponent
