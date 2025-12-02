@section('title', 'Edit Content')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin/contents/editContents.css') }}">
@endpush

@push('script')
<script src="{{ mix('js/admin/contents/editContents.js') }}"></script>
@endpush

<x-admin-layout>
    <x-toolbar :breadcrumbs="$breadcrumbs"></x-toolbar>

    <form novalidate="novalidate" id="ContentsForm" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin_contents_update') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $contents->id }}">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Thumbnail</h2>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="fv-row fv-plugins-icon-container">
                        <div class="image-input @if (!$contents->thumbnail || !Storage::disk('grocery')->exists($contents->thumbnail)) image-input-empty @endif image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" @if ($contents->thumbnail && Storage::disk('grocery')->exists($contents->thumbnail)) style="background-image:
                                url({{ Storage::disk('grocery')->url($contents->thumbnail) }})" @endif></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change" data-kt-initialized="1">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="thumbnail" accept=".png,.jpg,.jpeg">
                                <input type="hidden" name="thumbnail_remove">
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the profile picture. Only .png, .jpg and *.jpeg image files are accepted</div>
                        @error('thumbnail')
                        <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                    <div class="card-toolbar">
                        @if ($contents->status == 'active')
                        <div class="rounded-circle bg-success w-15px h-15px" id="contents_status"></div>
                        @else
                        <div class="rounded-circle bg-danger w-15px h-15px" id="contents_status"></div>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-0">
                    <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="contents_status_select">
                        <option value="active" @if ($contents->status == 'active') selected @endif>Active</option>
                        <option value="inactive" @if ($contents->status == 'inactive') selected @endif>Inactive</option>
                    </select>
                    <div class="text-muted fs-7">Set the content status.</div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Content Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" placeholder="Name" value="{{ old('name', $contents->name) }}">
                        @error('name')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="required form-label">Title</label>
                        <input type="text" name="title" class="form-control mb-2" placeholder="Title" value="{{ old('title', $contents->title) }}">
                        @error('title')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control mb-2" placeholder="Slug" value="{{ old('name', $contents->slug) }}" readonly>
                        @error('slug')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <!-- <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Content Category</label>
                        <select data-option-url="{{ route('admin_options_content_categories') }}" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-server="true" name="content_category_id" id="content_category_id" data-placeholder="Select category">
                            @if (isset($old['content_category_id']) && $old['content_category_id'] != '')
                            <option value="{{ $old['content_category_id']->id }}">
                                {{ $old['content_category_id']->name }}
                            </option>
                            @endif
                        </select>
                        @error('content_category_id')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div> -->
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <input type="hidden" name="file_label" value="0">
                        <div class="file-input">
                            <label class="required form-label">File</label>
                            @if ($contents->file)
                            <div class="d-flex align-items-center file-container">
                                <a href="{{ Storage::disk('grocery')->url($contents->file) }}" class="text-gray-800 text-hover-primary">
                                    <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="currentColor"></path>
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    {{ basename($contents->file) }}</a>
                                <span class="btn btn-icon btn-active-color-primary w-25px h-25px" data-kt-file-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove" data-kt-initialized="1">
                                    <i class="bi bi-x fs-2" id="removeFile"></i>
                                </span>
                            </div>
                            @else
                            <input type="hidden" name="file_label" value="1">
                            @endif
                            <input type="hidden" name="file_remove" id="file_remove">
                            <input type="file" name="file" class="form-control mb-2" placeholder="File" value="" @if ($contents->file && Storage::disk('grocery')->exists($contents->file)) style="display:none;" @endif>
                            @error('file')
                            <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <label class="form-label">Content</label>
                        <textarea id="content" name="content" data-kt-tinymce-editor="true" data-kt-initialized="false" class="min-h-200px mb-2">
                            {!! $contents->content !!}
                        </textarea>
                        @error('content')
                        <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin_contents_list') }}" class="btn btn-light me-5">Cancel</a>
                <button type="submit" id="btnSubmit" class="btn btn-primary fv-button-submit">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>

        </div>
    </form>
</x-admin-layout>