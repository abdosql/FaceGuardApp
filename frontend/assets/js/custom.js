function confirmDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-primary w-xs me-2 mt-2',
            cancelButton: 'btn btn-danger w-xs mt-2',
        },
        confirmButtonText: "Yes, delete it!",
        buttonsStyling: false,
        showCloseButton: true
    }).then(function (result) {
        if (result.value) {
            document.getElementById('deleteForm_' + id).submit();
        }
    });
}

//Custom success Message
if (document.getElementById("success_user")) {
    document.getElementById("success_user").addEventListener("click", function (event) {
        // Retrieve the data from the dataset
        var successData = JSON.parse(event.target.dataset.successData);
        var username = successData[0]["username"];
        var password = successData[0]["password"];
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15">' +
                '<h4>Teacher was added successfully!</h4>' +
                '<br>' +
                '<h4>User credentials:</h4>' +
                '<p class="text-muted mx-4 mb-0"> Username: ' + username + '</p>' +
                '<p class="text-muted mx-4 mb-0"> Password: ' + password + '</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            showConfirmButton: false,
            customClass: {
                cancelButton: 'btn btn-primary w-xs mb-1',
            },
            cancelButtonText: 'Back',
            buttonsStyling: false,
            showCloseButton: true
        });
    });
}

