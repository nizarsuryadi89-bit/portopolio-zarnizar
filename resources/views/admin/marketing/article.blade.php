<p><strong>DRAFT UNTUK REVIEW — hapus catatan ini sebelum publikasi.</strong> {{ $article['review'] }}</p>
<p>{{ $article['intro'] }}</p>
@foreach($article['sections'] as $heading => $paragraph)
<h2>{{ $heading }}</h2>
<p>{{ $paragraph }}</p>
@endforeach
<h2>Diskusikan kebutuhan usaha Anda</h2>
@if($article['product'] === 'kulapos')
<p><a href="{{ route('kulapos', absolute: false) }}">Kenali KulaPOS dan paket pilotingnya</a>, lalu <a href="https://wa.me/{{ config('marketing.whatsapp_number') }}?text={{ rawurlencode(config('marketing.whatsapp_messages.kulapos')) }}" target="_blank" rel="noopener noreferrer" data-product="kulapos" data-lead-source="blog" data-lead-position="content" data-lead-intent="demo">minta demo KulaPOS</a> untuk menilai kecocokan alur toko Anda.</p>
@else
<p><a href="{{ route('services', absolute: false) }}">Lihat layanan pengembangan aplikasi dan sistem informasi</a>, lalu <a href="https://wa.me/{{ config('marketing.whatsapp_number') }}?text={{ rawurlencode(config('marketing.whatsapp_messages.jasa-aplikasi')) }}" target="_blank" rel="noopener noreferrer" data-product="jasa-aplikasi" data-lead-source="blog" data-lead-position="content" data-lead-intent="consultation">konsultasikan kebutuhan sistem</a> berdasarkan proses usaha Anda.</p>
@endif
