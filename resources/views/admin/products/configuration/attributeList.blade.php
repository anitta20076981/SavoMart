<div class="card-body">
    <h2>Step 1: Select Attributes</h2>
    <div class="border-0 pt-6 show" id="listSelectedAttributesContainer">
        <div class="d-flex flex-stack flex-wrap p-1">
            <ul class="nav flex-wrap border-transparent fw-bold show" id="listSelectedAttributes">
            </ul>
        </div>
    </div>
    <div class="fv-row fv-plugins-icon-container">
        <input type="hidden" id="configure_attributes" name="configure_attributes" value="">
    </div>
</div>

<div class="card-header border-0 pt-6">
    <div class="card-title">
        <div class="d-flex align-items-center position-relative my-1">
            <span class="svg-icon svg-icon-1 position-absolute ms-4 top-25">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                </svg>
            </span>
            <input type="text" data-kt-table-filter="search" class="form-control w-250px ps-15" placeholder="Search" />
        </div>
    </div>
</div>
<div class="card-body pt-0">

    <table class="table align-middle table-row-dashed fs-6 gy-5" id="listAttributes" data-url="{{ route('admin_products_attribute_table') }}">
        <thead>
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">

                </th>
                <th class="min-w-125px">Attribute Code</th>
                <th class="min-w-125px">Attribute Name</th>
            </tr>
        </thead>

        <tbody class="fw-semibold text-gray-600">
        </tbody>
    </table>
</div>
