function confirmDelete(t){Swal.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,customClass:{confirmButton:"btn btn-primary w-xs me-2 mt-2",cancelButton:"btn btn-danger w-xs mt-2"},confirmButtonText:"Yes, delete it!",buttonsStyling:!1,showCloseButton:!0}).then((function(e){e.value&&document.getElementById("deleteForm_"+t).submit()}))}document.getElementById("success_user")&&document.getElementById("success_user").addEventListener("click",(function(t){var e=JSON.parse(t.target.dataset.successData),s=e[0].username,n=e[0].password;Swal.fire({html:'<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Teacher was added successfully!</h4><br><h4>User credentials:</h4><p class="text-muted mx-4 mb-0"> Username: '+s+'</p><p class="text-muted mx-4 mb-0"> Password: '+n+"</p></div></div>",showCancelButton:!0,showConfirmButton:!1,customClass:{cancelButton:"btn btn-primary w-xs mb-1"},cancelButtonText:"Back",buttonsStyling:!1,showCloseButton:!0})}));