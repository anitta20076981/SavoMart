@if (isset($returnArray['id']) && $returnArray['id'])
    <input type="hidden" name="images[id][]" value="{{ $returnArray['id'] }}">
    {{-- <input type="hidden" name="images[file][{{ $returnArray['id'] }}]" value="{{ $returnArray['fileName'] }}"> --}}
@endif
<input type="hidden" name="images[file_name][]" value="{{ $returnArray['fileName'] }}">
@if (!isset($returnArray['id']))
    <input type="hidden" name="images[new_file][]" value="{{ $returnArray['fileName'] }}">
@endif
