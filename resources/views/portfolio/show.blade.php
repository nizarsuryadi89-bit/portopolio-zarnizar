<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-marketing-head :title="$portfolio->title.' | Zarnizar'" :description="Str::limit(preg_replace('/\s+/', ' ', strip_tags($portfolio->description)), 160)" :canonical="url()->current()" :settings="$settings" :image="$portfolio->image ? url(Storage::url($portfolio->image)) : null" />
    
    @if(!empty($settings['favicon']))
    <link rel="icon" href="{{ Storage::url($settings['favicon']) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        body { 
            font-family: 'Space Grotesk', sans-serif; 
            background-color: #f4f4f0;
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .brutal-border {
            border: 3px solid #000;
        }

        .brutal-shadow {
            box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);
        }

        .brutal-shadow-hover {
            box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);
            transition: all 0.2s ease-in-out;
        }
        
        .brutal-shadow-hover:hover {
            box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);
            transform: translate(4px, 4px);
        }

        .text-stroke {
            -webkit-text-stroke: 2px #000;
            color: transparent;
        }

        .text-stroke-white {
            -webkit-text-stroke: 1px #000;
            color: #fff;
        }

                .bg-navy-blue { background-color: #1e3a8a; }
        .text-navy-blue { color: #1e3a8a; }
        .border-navy-blue { border-color: #1e3a8a; }

        /* CKEditor Content Styling */
        .ck-content {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #000;
            text-align: justify;
            text-justify: inter-word;
        }
        .ck-content::after {
            content: "";
            display: table;
            clear: both;
        }
        .ck-content p {
            margin-bottom: 1.5rem;
            text-align: justify;
            text-justify: inter-word;
        }
        .ck-content strong, .ck-content b {
            font-weight: 800;
            color: #000;
        }
        .ck-content a {
            font-weight: 700;
            color: #1e3a8a;
            text-decoration: underline;
            text-decoration-thickness: 3px;
            text-underline-offset: 4px;
            transition: all 0.2s;
        }
        .ck-content a:hover {
            background-color: #1e3a8a;
            color: #fff;
        }
        .ck-content ul {
            list-style-type: square;
            padding-left: 2rem;
            margin-bottom: 1.5rem;
        }
        .ck-content ol {
            list-style-type: decimal-leading-zero;
            padding-left: 2rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        .ck-content li {
            margin-bottom: 0.5rem;
        }
        .ck-content li p {
            margin-bottom: 0.5rem;
        }

        /* CKEditor Images & Resizing */
        .ck-content figure.image {
            margin: 2rem auto;
            display: table;
            text-align: center;
            max-width: 100%;
        }
        .ck-content figure.image.image_resized {
            max-width: 100%;
            display: block;
            box-sizing: border-box;
        }
        .ck-content figure.image img {
            width: 100%;
            height: auto;
            border: 4px solid #000;
            box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);
            display: block;
            margin: 0 auto;
        }
        .ck-content figure.image figcaption {
            margin-top: 0.75rem;
            font-size: 0.875rem;
            color: #4b5563;
            font-style: italic;
            text-align: center;
        }
        .ck-content figure.image.image-style-side,
        .ck-content figure.image.image-style-align-right,
        .ck-content figure.image.image-style-block-align-right,
        .ck-content .image-inline.image-style-align-right {
            float: right;
            margin: 1rem 0 1.5rem 2rem;
            max-width: 50%;
        }
        .ck-content figure.image.image-style-align-left,
        .ck-content figure.image.image-style-block-align-left,
        .ck-content .image-inline.image-style-align-left {
            float: left;
            margin: 1rem 2rem 1.5rem 0;
            max-width: 50%;
        }
        .ck-content figure.image.image-style-align-center {
            margin-left: auto;
            margin-right: auto;
        }
        .ck-content .image-inline {
            display: inline-flex;
            max-width: 100%;
            align-items: flex-start;
            margin: 0.5rem;
        }
        .ck-content .image-inline img {
            border: 3px solid #000;
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
            max-width: 100%;
            height: auto;
        }
        .ck-content > img,
        .ck-content p > img {
            max-width: 100%;
            height: auto;
            border: 4px solid #000;
            box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);
            margin: 2rem auto;
            display: block;
        }

        .ck-content h2 {
            font-size: 2.25rem;
            font-weight: 900;
            text-transform: uppercase;
            margin-top: 3rem;
            margin-bottom: 1rem;
            border-bottom: 4px solid #000;
            padding-bottom: 0.5rem;
            display: inline-block;
        }
        .ck-content h3 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .ck-content h4 {
            font-size: 1.25rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            background-color: #000;
            color: #fff;
            padding: 0.25rem 0.75rem;
            display: inline-block;
        }
        .ck-content blockquote {
            border-left: 6px solid #000;
            background-color: #f4f4f0;
            padding: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            font-weight: 600;
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
        }
    </style>
</head>
<body class="text-gray-900 antialiased selection:bg-navy-blue selection:text-white">
    
    <x-sales-navigation />

    <!-- Main Content -->
    <main class="py-12 lg:py-24 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white brutal-border brutal-shadow p-6 md:p-12">
            <!-- Header -->
            <div class="mb-8 border-b-[3px] border-black pb-8">
                <div class="flex items-center gap-4 mb-4">
                    <span class="bg-navy-blue text-white px-3 py-1 font-bold uppercase text-xs tracking-widest brutal-border">PROJECT LOG</span>
                    @if($portfolio->date)
                        <span class="bg-navy-blue text-white px-3 py-1 font-bold uppercase text-xs tracking-widest brutal-border">{{ \Carbon\Carbon::parse($portfolio->date)->format('F Y') }}</span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-4">{{ $portfolio->title }}</h1>
                
                @if($portfolio->url)
                    <a href="{{ $portfolio->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 font-bold uppercase tracking-widest hover:bg-navy-blue hover:text-white hover:text-black transition brutal-border">
                        LAUNCH EXTERNAL PROJECT <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                @endif
            </div>

            <!-- Image Thumbnail -->
            @if($portfolio->image)
                <div class="mb-12 border-[3px] border-black bg-gray-200">
                    <x-storage-image :path="$portfolio->image" alt="{{ $portfolio->title }}" class="w-full h-auto object-cover max-h-[600px] grayscale hover:grayscale-0 transition duration-500" />
                </div>
            @endif

            <!-- Description -->
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tighter border-l-4 border-navy-blue pl-4 mb-6">PROJECT_DETAILS</h2>
                <div class="ck-content prose prose-lg max-w-none font-medium leading-relaxed">
                    {!! $portfolio->description !!}
                </div>
            </div>
            
        </div>
        
    </main>

    <x-sales-contact source="portfolio" />
    <!-- Footer -->
    <footer class="bg-black text-white py-8 border-t-[4px] border-navy-blue mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-stroke-white mb-6">{{ $profile->name ?? 'SYSTEM_END' }}</h2>
            
            <div class="flex justify-center gap-4 mb-6">
                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white text-black brutal-border hover:bg-navy-blue hover:text-white transition flex items-center justify-center text-lg">
                        <i class="{{ $link->icon ?? 'bi bi-link-45deg' }}"></i>
                    </a>
                @endforeach
            </div>
            
            <div class="font-bold uppercase tracking-widest text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ $profile->name ?? '' }}
            </div>
        </div>
    </footer>

</body>
</html>
