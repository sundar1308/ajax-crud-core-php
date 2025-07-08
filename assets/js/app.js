let currentSort = "id";
let currentOrder = "DESC";
let currentPage = 1;
let currentSearch = "";

$(document).ready(function () {
  loadEmployees();

  $("#searchInput").on("input", function () {
    currentSearch = $(this).val();
    currentPage = 1;
    loadEmployees();
  });

  $(document).on("click", ".sort-icon", function () {
    const newSort = $(this).data("sort");
    if (currentSort === newSort) {
      currentOrder = currentOrder === "ASC" ? "DESC" : "ASC";
    } else {
      currentSort = newSort;
      currentOrder = "ASC";
    }
    loadEmployees();
  });
});

function loadEmployees() {
  $.ajax({
    url: "ajax/fetch.php",
    data: {
      search: currentSearch,
      sort: currentSort,
      order: currentOrder,
      page: currentPage,
    },
    dataType: "json",
    success: function (res) {
      // console.log(res);
      if (res.status === "success") {
        $("#employeeTable tbody").html(res.data);
        buildPagination(res.total, res.page, res.limit);
      }
    },
  });
}

function buildPagination(total, current, limit) {
  const pages = Math.ceil(total / limit);
  // const pages = 3;
  let html = '<nav><ul class="pagination">';
  let endPage = current < pages ? current + 1 : pages;
  let startPage = current > 1 ? current - 1 : current;

  if (pages >= 3) {
    if (current == 1) {
      endPage = current + 2;
    }

    if (current == pages) {
      startPage = current - 2;
    }
  }
  if (current > 1) {
    html += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${
      current - 1
    })">&laquo;</a></li>`;
  }

  for (let i = startPage; i <= endPage; i++) {
    html += `<li class="page-item ${i === current ? "active" : ""}">
                    <a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>
                 </li>`;
  }
  if (current < pages) {
    html += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${
      current + 1
    })">&raquo;</a></li>`;
  }

  html += "</ul></nav>";
  $("#paginationControls").html(html);
}

function goToPage(page) {
  currentPage = page;
  loadEmployees();
}

$("#employeeForm .modal-footer .spinner-border").hide();
$("#employeeForm").on("submit", function (e) {
  e.preventDefault();
  let formData = new FormData(this);
  $(".text-danger").text("");

  $.ajax({
    url: "ajax/create.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function () {
      $("#employeeForm .modal-footer button").hide();
      $("#employeeForm .modal-footer .spinner-border").show();
    },
    complete: function () {
      $("#employeeForm .modal-footer .spinner-border").hide();
      $("#employeeForm .modal-footer button").show();
    },
    success: function (res) {
      if (res.status === "success") {
        $("#addModal").modal("hide");
        $("#employeeForm")[0].reset();
        loadEmployees();
        Swal.fire("Success", res.message, "success");
      } else if (res.errors) {
        Object.entries(res.errors).forEach(([key, msg]) => {
          $("#err_" + key).text(msg);
        });
      } else {
        Swal.fire("Error", res.message, "error");
      }
    },
  });
});

function deleteEmployee(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "This will permanently delete the employee.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        "ajax/delete.php",
        { id },
        function (res) {
          if (res.status === "success") {
            loadEmployees();
            Swal.fire("Deleted!", res.message, "success");
          } else {
            Swal.fire("Error", res.message, "error");
          }
        },
        "json"
      );
    }
  });
}

function viewEmployee(id) {
  $.get(
    "ajax/view.php",
    { id },
    function (res) {
      if (res.status === "success") {
        const emp = res.data;
        $("#viewImage").attr("src", "uploads/" + emp.profileImg);
        $("#viewName").text(emp.name);
        $("#viewEmail").text(emp.email);
        $("#viewCreated").text(emp.created_at);
        $("#viewUpdated").text(emp.updated_at);
        $("#viewModal").modal("show");
      } else {
        Swal.fire("Error", res.message, "error");
      }
    },
    "json"
  );
}

function editEmployee(id) {
  $.get(
    "ajax/view.php",
    { id },
    function (res) {
      if (res.status === "success") {
        const emp = res.data;
        $("#edit_id").val(emp.id);
        $("#edit_name").val(emp.name);
        $("#edit_email").val(emp.email);
        $("#edit_preview").attr("src", "uploads/" + emp.profileImg);
        $("#editModal").modal("show");
      } else {
        Swal.fire("Error", res.message, "error");
      }
    },
    "json"
  );
}

$("#editEmployeeForm").on("submit", function (e) {
  e.preventDefault();
  let formData = new FormData(this);
  $(".text-danger").text("");

  $.ajax({
    url: "ajax/update.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (res) {
      if (res.status === "success") {
        $("#editModal").modal("hide");
        loadEmployees();
        $('input[name="profileImg"]').val("");
        Swal.fire("Updated!", res.message, "success");
      } else if (res.errors) {
        // console.log(res.errors);
        Object.entries(res.errors).forEach(([key, msg]) => {
          var isImgExists = $("#edit_preview").attr("src");
          // if (key == "profileImg" && isImgExists) {
          //   return;
          // }
          $("#edit_error_" + key).text(msg);
        });
      } else {
        Swal.fire("Error", res.message, "error");
      }
    },
  });
});

function downloadPDF(id) {
  // window.open("ajax/download_pdf.php?id=" + id);
  window.location.href = `ajax/download_pdf.php?id=${id}`;
}
