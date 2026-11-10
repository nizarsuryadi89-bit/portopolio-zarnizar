@extends('layouts.admin')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Artikel Blog</h1>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul Artikel</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">Pilih Kategori...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tag</label>
                <select name="tags[]" class="form-select" multiple>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Tahan CTRL/CMD untuk memilih lebih dari satu.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Artikel</label>
                <textarea id="editor" name="content" class="form-control" rows="10"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Sampul (Thumbnail)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_published" class="form-check-input" id="isPublished" checked>
                <label class="form-check-label" for="isPublished">Publikasikan Langsung</label>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .ck-editor__editable_inline {
        min-height: 400px;
    }
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/super-build/ckeditor.js"></script>
<script>
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);
                    data.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route('admin.upload.image') }}', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.error) {
                            reject(result.error.message);
                        } else {
                            resolve({ default: result.url });
                        }
                    })
                    .catch(error => {
                        reject('Upload failed');
                    });
                }));
        }

        abort() {
        }
    }

    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    CKEDITOR.ClassicEditor
        .create(document.querySelector('#editor'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', '|',
                    'link', 'uploadImage', 'insertTable', 'blockQuote', 'mediaEmbed', '|',
                    'bulletedList', 'numberedList', 'outdent', 'indent'
                ],
                shouldNotGroupWhenFull: true
            },
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://'
            },
            image: {
                toolbar: [
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side',
                    '|',
                    'imageStyle:alignLeft',
                    'imageStyle:alignCenter',
                    'imageStyle:alignRight',
                    '|',
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'linkImage',
                    '|',
                    'resizeImage'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        value: null,
                        label: 'Original'
                    },
                    {
                        name: 'resizeImage:25',
                        value: '25',
                        label: '25%'
                    },
                    {
                        name: 'resizeImage:50',
                        value: '50',
                        label: '50%'
                    },
                    {
                        name: 'resizeImage:75',
                        value: '75',
                        label: '75%'
                    }
                ],
                resizeUnit: '%'
            },
            removePlugins: [
                'ExportPdf',
                'ExportWord',
                'CloudServices',
                'CloudServicesCore',
                'CloudServicesUploadAdapter',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'CommentsRepository',
                'CommentsOnly',
                'TrackChanges',
                'TrackChangesData',
                'TrackChangesEditing',
                'TrackChangesDataGetter',
                'RevisionHistory',
                'RevisionTracker',
                'RevisionViewer',
                'RevisionViewerIntegration',
                'RevisionsRepository',
                'Pagination',
                'PaginationLookup',
                'WProofreader',
                'MathType',
                'SlashCommand',
                'SlashCommandUI',
                'Template',
                'TemplateUI',
                'DocumentOutline',
                'DocumentOutlineUI',
                'FormatPainter',
                'FormatPainterUI',
                'TableOfContents',
                'TableOfContentsEditing',
                'PasteFromOfficeEnhanced',
                'PasteFromOfficeEnhancedInliner'
            ]
        })
        .then(editor => {
            // Jika konten artikel hanya berupa gambar/widget tanpa baris paragraf,
            // tambahkan paragraf kosong di bawahnya agar pengguna langsung bisa mengetik teks
            const root = editor.model.document.getRoot();
            if (root.childCount === 1 && !root.getChild(0).is('element', 'paragraph')) {
                editor.model.change(writer => {
                    const p = writer.createElement('paragraph');
                    writer.insert(p, root, 'end');
                });
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
