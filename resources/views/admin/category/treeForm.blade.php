<div id="category-error-div"></div>
<div class="mb-10 fv-row">
    <input type="text" name="search" class="form-control mb-2 searchCategory" placeholder="Search category" />
</div>
<div id="categoryTree" kt-jstree-container="true" class="categoryTree mt-3" data-selected=@if ($selectedCategories) @json($selectedCategories) @endif>
    <ul>
        @foreach ($categories as $category)
            @if ($category->parent_category_id == 1)
                <li @if ($category->status == 'active') data-jstree='{"opened":true}'   @else data-jstree='{"opened":true,  "disabled": true}' @endif id="category_{{ $category->id }}">
                    <input type="checkbox" name="categories[]" hidden class="tree-checkbox category_{{ $category->id }} categories" value="{{ $category->id }}" @if (in_array($category->id, $selectedCategories)) checked @endif>
                    @if ($category->status == 'active')
                        {{ $category->name }}
                    @else
                        <del> {{ $category->name }}</del>
                    @endif
                    @if (!empty($category->childrenRecursive))
                        <ul>
                            @foreach ($category->childrenRecursive as $category)
                                @include('admin.category.childCategories', ['category' => $category])
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
</div>
