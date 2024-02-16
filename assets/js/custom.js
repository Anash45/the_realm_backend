$(document).ready(function(){
    $('.deleteBtn').each(function () {
        $(this).click(function (e) {
            if (!confirm('You really want to delete this item?')) {
                e.preventDefault();
            }
        })
    })
})

$(document).ready(function() {
    // Initialize DataTable
    $('.dataTable').DataTable();
});