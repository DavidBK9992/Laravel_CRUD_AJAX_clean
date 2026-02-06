$(document).ready(function() {
    var table = $('#posts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/posts/data', // Route zu getData()
        columns: [
            {data: 'id', name: 'id'},
            {data: 'post_title', name: 'post_title'},
            {data: 'post_description', name: 'post_description'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'post_status', name: 'post_status'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[5, 'desc']],
        dom: 'Bfrtip',
        buttons: ['csv','excel'],
    });

    // Status toggle
    $(document).on('click', '.dt-status-toggle', function () {
        var id = $(this).data('id');
        $.post('/posts/status-update', {_token: $('meta[name="csrf-token"]').attr('content'), id: id}, function(res) {
            if(res.success) table.ajax.reload(null, false);
        });
    });

    // Delete
    $(document).on('click', '.delete-post', function() {
        if(confirm(`Are you sure you want to delete "${$(this).data('post_title')}"?`)){
            var id = $(this).data('id');
            $.post('/posts/delete-ajax', {_token: $('meta[name="csrf-token"]').attr('content'), id: id}, function(res) {
                if(res.success) table.ajax.reload(null, false);
            });
        }
    });
});
