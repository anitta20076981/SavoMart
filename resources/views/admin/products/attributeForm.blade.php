@if (isset($attributes))
    @foreach ($attributes as $attribute)
        <div class="mb-10 fv-row fv-plugins-icon-container" data-attribute-input_type="{{ $attribute->input_type }}">
            @switch($attribute->input_type)
                @case('textfield')
                    @if ($attribute->code == 'weight')
                        <label class="@if ($attribute->is_required) required @endif form-label">{{ $attribute->name }}</label>
                        <input type="number" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif class="form-control mb-2" value="{{ old('product_attributes.' . $attribute->id, $attribute['value']) }}" data-placeholder="lbs">
                    @else
                        <label class="@if ($attribute->is_required) required @endif form-label">{{ $attribute->name }}</label>
                        <input type="text" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif class="form-control mb-2" value="{{ old('product_attributes.' . $attribute->id, $attribute['value']) }}">
                    @endif
                @break

                @case('dropdown')
                    @if ($attribute->code == 'brand')
                        <label class="form-label @if ($attribute->is_required) required @endif">Select a {{ $attribute->name }}</label>
                        <select class="form-select" data-kt-select2="true" data-server="true" data-placeholder="Select {{ $attribute->name }}" data-option-url="{{ route('admin_options_brands') }}" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif id="attribute_input_type_select_{{ $attribute->id }}" name="product_attributes[{{ $attribute->id }}]">
                            <option value="">Select {{ $attribute->name }}</option>
                            @if ($attribute->brand)
                                <option value="{{ $attribute->brand->id }}" @if (old('value', $attribute->value) == $attribute->brand->id) selected @endif>{{ $attribute->brand->name }}</option>
                            @endif
                        </select>
                    @else
                        <label class="form-label @if ($attribute->is_required) required @endif">Select a {{ $attribute->name }}</label>
                        <select class="form-select" data-kt-select2="true" data-placeholder="Select {{ $attribute->name }}" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif id="attribute_input_type_select_{{ $attribute->id }}" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->product_type == 'virtual') disabled @endif>
                            @if (isset($attribute->attributeOptions) && count($attribute->attributeOptions) > 0)
                                <option value="">Select {{ $attribute->name }}</option>
                                @foreach ($attribute->attributeOptions as $option)
                                    <option @if (old('value', $attribute->value) == $option->value) selected @endif value="{{ $option->value }}">{{ $option['label'] }}</option>
                                @endforeach
                            @else
                                <option disabled> Options not found</option>
                            @endif
                        </select>
                    @endif
                @break

                @case('textswatch')
                    <label class="form-label @if ($attribute->is_required) required @endif">Select a {{ $attribute->name }}</label>
                    <select class="form-select" data-kt-select2="true" data-placeholder="Select {{ $attribute->name }}" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif id="attribute_input_type_select_{{ $attribute->id }}" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->product_type == 'virtual') disabled @endif>
                        @if (isset($attribute->attributeOptions) && count($attribute->attributeOptions) > 0)
                            <option value="">Select {{ $attribute->name }}</option>
                            @foreach ($attribute->attributeOptions as $option)
                                <option @if (old('value', $attribute->value) == $option->value) selected @endif value="{{ $option->value }}">{{ $option->label }}</option>
                            @endforeach
                        @else
                            <option disabled> Options not found</option>
                        @endif
                    </select>
                @break

                @case('visualswatch')
                    <label class="form-label @if ($attribute->is_required) required @endif">Select a {{ $attribute->name }}</label>
                    <select class="form-select" data-control="select2" data-kt-select2="true" data-placeholder="Select {{ $attribute->name }}" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif id="attribute_input_type_select_{{ $attribute->id }}" name="product_attributes[{{ $attribute->id }}]" data-option-url="{{ route('admin_options_attribute_options', ['id' => $attribute->id]) }}" @if ($attribute->product_type == 'virtual') disabled @endif>
                        @if (isset($attribute->attributeOptions) && count($attribute->attributeOptions) > 0)
                            <option value="">Select {{ $attribute->name }}</option>
                            @foreach ($attribute->attributeOptions as $option)
                                <option @if (old('value', $attribute->value) == $option->value) selected @endif value="{{ $option->value }}">{{ $option['label'] }}</option>
                            @endforeach
                        @else
                            <option disabled> Options not found</option>
                        @endif
                    </select>
                @break

                @case('textarea')
                    <label class="form-label @if ($attribute->is_required) required @endif">{{ $attribute->name }}</label>
                    <textarea id="comments_{{ $attribute->id }}" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif class="form-control mb-2">{{ old('product_attributes.' . $attribute->id, $attribute['value']) }}</textarea>
                @break

                @case('texteditor')
                    <label class="form-label @if ($attribute->is_required) required @endif">{{ $attribute->name }}</label>
                    <textarea id="descriptions_{{ $attribute->id }}" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif data-kt-tinymce-editor="true" data-kt-initialized="false" class="form-control min-h-200px mb-2">{!! old('product_attributes.' . $attribute->id, $attribute['value']) !!}</textarea>
                @break

                @case('date')
                    <label class="form-label @if ($attribute->is_required) required @endif">{{ $attribute->name }}</label>
                    <div class="position-relative d-flex align-items-center w-652px">
                        <input class="form-control fw-bold pe-5"@if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif data-kt-date-input="true" data-kt-initialized="false" data-format="{{ config('date_format.date_only_js') }}" placeholder="Date" value="{{ old('product_attributes.' . $attribute->id, $attribute['value']) }}" name="product_attributes[{{ $attribute->id }}]" id="date" value="" />
                        <span class="svg-icon svg-icon-2 position-absolute end-0 ms-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                @break

                @case('datetime')
                    <label class="form-label @if ($attribute->is_required) required @endif">{{ $attribute->name }}</label>
                    <div class="position-relative d-flex align-items-center w-652px">
                        <input class="form-control fw-bold pe-5"@if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif data-kt-date-input="true" data-kt-time-enabled="true" data-kt-initialized="false" data-format="{{ config('date_format.date_time_js') }}" placeholder="Date" value="{{ old('product_attributes.' . $attribute->id, $attribute['value']) }}" name="product_attributes[{{ $attribute->id }}]" id="date" value="" />
                        <span class="svg-icon svg-icon-2 position-absolute end-0 ms-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                @break

                @case('yesno')
                    <label class="form-label @if ($attribute->is_required) required @endif">{{ $attribute->name }}</label>
                    <div class="d-flex">
                        <div class="form-check me-5">
                            <input class="form-check-input" type="radio" id="attribute-yes-radio" value="yes" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif @if ($attribute->value == 'yes') {{ 'checked' }} @endif>
                            <label class="form-check-label" for="attribute-yes-radio">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="attribute-no-radio" value="no" name="product_attributes[{{ $attribute->id }}]" @if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif @if ($attribute->value == 'no') {{ 'checked' }} @endif>
                            <label class="form-check-label" for="attribute-no-radio">No</label>
                        </div>
                    </div>
                @break

                @case('price')
                    <label class="form-label @if ($attribute->is_required) required @endif">{{ $attribute->name }}</label>
                    <div class="input-group mb-5">
                        <span class="input-group-text">{{ config('app.currency.symbol') }}</span>
                        <input type="number" class="form-control"@if ($attribute->is_required) data-fv-not-empty="true" data-fv-not-empty___message="The {{ $attribute->name }} is required" @endif name="product_attributes[{{ $attribute->id }}]" value="{{ old('product_attributes.' . $attribute->id, $attribute['value']) }}" aria-label="Amount (to the nearest dollar)" />
                    </div>
                @break
            @endswitch
        </div>
    @endforeach
@endif
