@extends('layouts.admin')

@section('content')
<div class="admin-page-header pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Portfolio</h1>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.portfolios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea id="editor" name="description" class="form-control" rows="10"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">URL Project</label>
                <input type="url" name="url" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Rilis</label>
                <input type="date" name="date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar/Thumbnail</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                <label class="form-check-label" for="isActive">Aktif</label>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.portfolios.index') }}" class="btn btn-secondary">Batal</a>
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
            // Jika konten hanya berupa gambar/widget tanpa baris paragraf,
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
