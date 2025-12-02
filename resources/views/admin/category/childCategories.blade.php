<li @if ($category->status == 'active') data-jstree='{"opened":true}'   @else data-jstree='{"opened":true,  "disabled": true}' @endif id="category_{{ $category->id }}" class="fv-row">
    <input type="checkbox" name="categories[]" hidden class="tree-checkbox category_{{ $category->id }} categories" value="{{ $category->id }}" @if (in_array($category->id, $selectedCategories)) checked @endif>
    @if ($category->status == 'active')
        {{ $category->name }}
    @else
        <del> {{ $category->name }}</del>
    @endif


    <ul>
        @if (!empty($category->childrenRecursive))
            @foreach ($category->childrenRecursive as $category)
                @include('admin.category.childCategories', ['category' => $category])
            @endforeach
        @endif
    </ul>
</li>
