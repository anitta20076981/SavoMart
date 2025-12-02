<h2>Step 2: Attribute Values</h2>
<p>Select values from each attribute to include in this product. Each unique combination of values creates a unique product SKU.</p>
<div class="mb-10"></div>
<div class="mh-900px scroll-y me-n7 pe-7">
    <div class="fv-row fv-plugins-icon-container">
        <input type="hidden" name="attribute_options" value="" id="attribute_options">
    </div>
    @foreach ($attributes as $attribute)
        <div class="border border-hover-primary p-7 rounded mb-7" id="attributeContainer" data-attribute-container="{{ $attribute->id }}">
            <div class="d-flex flex-stack pb-3">
                <div class="d-flex">
                    <div class="ms-5">
                        <div class="d-flex align-items-center">
                            <a href="" class="text-dark fw-bold text-hover-primary fs-5 me-4">{{ $attribute->name }}</a>
                            <span class="text-muted fw-semibold"> ({{ count($attribute->attributeOptions) }} Options)</span>
                        </div>

                    </div>
                </div>
                <div clas="d-flex">
                    <div class="text-end pb-3">
                        <a href="javascript:void(0)" data-attribute-id="{{ $attribute->id }}" data-attribute-selection="select-all"><span class="text-muted fs-5">Select All</span></a>
                        &nbsp;|&nbsp;
                        <a href="javascript:void(0)" data-attribute-id="{{ $attribute->id }}" data-attribute-selection="de-select-all"><span class="text-muted fs-5">Deselect All</span></a>
                    </div>
                </div>
            </div>
            <div class="p-0">
                <div class="d-flex flex-column">

                    <div class="d-flex text-gray-700 fw-semibold fs-7">
                        @foreach ($attribute->attributeOptions as $key => $attributeOption)
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start p-3 mb-5 me-3" data-radio-attribute-option="{{ $attributeOption->id }}" data-radio-attribute="{{ $attribute->id }}" data-radio-name="attribute[{{ $attribute->id }}][{{ $attributeOption->id }}]">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="option-check-box" data-check-attribute-option="{{ $attributeOption->id }}" data-attribute-option="attribute[{{ $attribute->id }}][{{ $attributeOption->id }}]" name="attribute_options[{{ $attribute->id }}][]" value="{{ $attributeOption->id }}" hidden>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold opacity-50">
                                            {{ $attributeOption->label }}
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="d-flex flex-row-reverse">
                        <button type="button" data-attribute-row-delete="{{ $attribute->id }}" class="btn btn-sm btn-icon btn-light-danger">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor"></rect>
                                    <rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
