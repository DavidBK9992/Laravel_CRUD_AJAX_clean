@if ($row->image)
    <img src="{{ asset('storage/' . $row->image) }}" class="dt-post-image">
@endif
