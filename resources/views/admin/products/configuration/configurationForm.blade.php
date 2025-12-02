<div id="configurable_product_drawer" class="bg-white drawer" data-kt-drawer="true" data-kt-drawer-name="configuration" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="90%">
    <div class="card w-100 rounded-0">
        <div class="card-header pe-5">
            <div class="card-title">
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="javascript:void(0)" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 lh-1">Configurations </a>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="configurable_product_drawer_close" data-kt-drawer-dismiss="true">
                    <span class="svg-icon svg-icon-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body hover-scroll-overlay-y" id="product_configuration_container">

            <form class="fv-plugins-bootstrap5 fv-plugins-framework w-100" novalidate="novalidate" id="product_configuration_form">
                <input type="hidden" id="selectedAttributeSetId" value="{{ $attributeSetId }}">
                @csrf
                <div class="stepper stepper-pills" id="" data-kt-stepper="true">
                    <div class="stepper-nav flex-wrap mb-12 py-10 position-relative" style="background: #e3e4ea2e;">
                        <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
                            <div class="stepper-wrapper d-flex align-items-center">
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">1</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Step 1
                                    </h3>
                                    <div class="stepper-desc">
                                        Select Attributes
                                    </div>
                                </div>
                            </div>
                            <div class="stepper-line h-40px"></div>
                        </div>
                        <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                            <div class="stepper-wrapper d-flex align-items-center">
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">2</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Step 2
                                    </h3>

                                    <div class="stepper-desc">
                                        Attribute Values
                                    </div>
                                </div>
                            </div>
                            <div class="stepper-line h-40px"></div>
                        </div>
                        <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                            <div class="stepper-wrapper d-flex align-items-center">
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">3</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Step 3
                                    </h3>
                                    <div class="stepper-desc">
                                        Summary
                                    </div>
                                </div>
                            </div>
                            <div class="stepper-line h-40px"></div>
                        </div>

                        <div class="d-flex flex-stack pt-10" style="position: absolute;right:30px;top:10px;">
                            <div class="mr-2">
                                <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="currentColor"></rect>
                                            <path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    Back
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-lg btn-primary me-3" data-kt-stepper-action="submit">
                                    <span class="indicator-label">
                                        Submit
                                        <span class="svg-icon svg-icon-3 ms-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
                                    Continue
                                    <span class="svg-icon svg-icon-4 ms-1 me-0">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card d-flex flex-row-fluid">
                        <div data-kt-stepper-element="content" class="flex-column current">
                            <div class="w-100">
                                <div id="listAttributeContainer" data-url="{{ route('admin_products_attribute_option_list') }}">
                                    @include('admin.products.configuration.attributeList')
                                </div>
                            </div>
                        </div>
                        <div data-kt-stepper-element="content" class="flex-column">
                            <div class="w-100">
                                <div id="attributeValuesContainer" class="card-body"></div>
                            </div>
                        </div>
                        <div data-kt-stepper-element="content" class="flex-column">
                            <div class="w-100">
                                <div id="configurationSummaryContainer" class="card-body" data-url="{{ route('admin_products_configuration_summary') }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
