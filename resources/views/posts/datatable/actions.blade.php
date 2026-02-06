<a href="{{ route('posts.edit', $row->id) }}" class="dt-btn">Edit</a>
<a href="{{ route('posts.show', $row->id) }}" class="dt-btn">Show</a>
<button class="dt-btn dt-btn--danger delete-post" data-id="{{ $row->id }}" data-post_title="{{ $row->post_title }}">
    Delete
</button>
