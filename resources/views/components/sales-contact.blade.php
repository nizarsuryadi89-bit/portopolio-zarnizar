@props(['product' => 'jasa-aplikasi', 'source' => 'home'])
<section id="konsultasi" class="sales-section sales-contact" aria-labelledby="contact-heading">
    <div class="sales-shell sales-contact-inner">
        <div>
            <p class="sales-eyebrow">{{ $source === 'home' ? 'Kolaborasi & Diskusi' : 'Mulai dari percakapan' }}</p>
            <h2 id="contact-heading" class="sales-heading">{{ $source === 'home' ? 'Tertarik berdiskusi atau kolaborasi seputar pembelajaran & teknologi?' : ($product === 'kulapos' ? 'Coba alurnya. Nilai kecocokannya.' : 'Ceritakan usaha Anda. Kita petakan kebutuhannya.') }}</h2>
            <p>{{ $source === 'home' ? 'Terbuka untuk diskusi materi Informatika, implementasi teknologi sekolah, berbagi praktik baik, maupun konsultasi pengembangan perangkat lunak.' : ($product === 'kulapos' ? 'Jadwalkan demo KulaPOS dengan data contoh sebelum menentukan paket.' : 'Siapkan jenis usaha, kendala utama, dan alur kerja saat ini. Kita mulai dari sana.') }}</p>
            <p class="sales-small">{{ $source === 'home' ? 'SMA Negeri 4 Tasikmalaya · Komunikasi & Diskusi via WhatsApp' : 'Tasikmalaya · Konsultasi online · Jadwal dikonfirmasi melalui WhatsApp' }}</p>
        </div>
        <x-whatsapp-link :product="$product" :source="$source" position="footer" :intent="$product === 'kulapos' ? 'demo' : 'consultation'" class="sales-button-light">
            {{ $source === 'home' ? 'Mulai Diskusi via WhatsApp' : ($product === 'kulapos' ? 'Minta Demo KulaPOS' : 'Konsultasi Kebutuhan Aplikasi') }}
        </x-whatsapp-link>
    </div>
</section>
