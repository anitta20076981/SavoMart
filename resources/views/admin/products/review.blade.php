<div class="modal" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Review</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-modal-action="close">
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 my-7">
                <div class="row">
                    @if ($images)
                        @foreach ($images as $image)
                            <div class="col-3 mb-2">
                                <img src="{{ $image }}" width="150px" class="list-image">
                            </div>
                        @endforeach

                    @endif
                </div>


                <input type="hidden" name="review_id" value="{{ $review->id }}" id="review_id">
                <div class="mb-10 fv-row">
                    <label class="required form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control mb-2" placeholder="Title" value="{{ old('name') }}" />
                    @error('title')
                        <div class="fv-plugins-message-container invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="mb-10 fv-row">
                    <button type="button" class="btn btn-success btn-sm  review-accept-button" id="reviewAccept" data-url="{{ route('admin_products_review_update') }}">
                        Publish</button>
                    <button type="button" class="btn btn-danger btn-sm review-reject-button" id="reviewReject" data-url="{{ route('admin_products_review_update') }}">
                        Reject</button>
                </div>

            </div>
        </div>
    </div>
</div>
