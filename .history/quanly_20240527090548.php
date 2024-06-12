<?php
// Include database connection
include 'config.php';

// Số hàng hiển thị trên mỗi trang
$records_per_page = 5;

// Xác định trang hiện tại. Nếu không có trang nào được chỉ định, mặc định là trang 1
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Tính toán offset (vị trí bắt đầu của hàng trong truy vấn)
$offset = ($current_page - 1) * $records_per_page;

// Truy vấn dữ liệu từ cơ sở dữ liệu, bao gồm cả cột water_amount và total_amount từ bảng water_usages
$sql = "
    SELECT users.id, users.username, users.email, users.role,
    COALESCE(SUM(water_usages.water_amount), 0) AS water_amount, 
    COALESCE(SUM(water_usages.total_amount), 0) AS total_amount
    FROM users
    LEFT JOIN water_usages ON users.id = water_usages.user_id
    GROUP BY users.id, users.username, users.email, users.role
    LIMIT ?, ?
";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ii", $offset, $records_per_page);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Đếm tổng số hàng trong bảng
$total_rows_query = "SELECT COUNT(*) AS total FROM users";
$total_rows_result = mysqli_query($link, $total_rows_query);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];




// Tính toán số trang
$total_pages = ceil($total_rows / $records_per_page);

// Đóng kết nối
mysqli_stmt_close($stmt);
mysqli_close($link);


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Table</title>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid black;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
    .pagination {
        display: flex;
        justify-content: center;
        margin: 1em 0;
    }
    .pagination a {
        margin: 0 0.5em;
        padding: 0.5em 1em;
        text-decoration: none;
        color: #007bff;
        border: 1px solid #dee2e6;
        border-radius: 0.25em;
    }
    .pagination a.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .btn-block {
        padding: 10px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 8px 16px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: inline-block;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 8px 16px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: inline-block;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    /* Thêm CSS cho bảng và phân trang */
.table {
  width: 100%;
  border-collapse: collapse;
}
.table caption {
  font-size: 1.5em;
  margin: 0.5em 0 0.75em;
}
.table th,
.table td {
  padding: 0.625em;
  text-align: center;
}
.table th {
  background-color: #f2f2f2;
}
    th {
         cursor: pointer;
    }


    /* CSS for Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0, 0, 0);
  background-color: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(5px);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 600px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  animation: fadeIn 0.3s;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.closeAddModal {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closeAddModal:hover,
.closeAddModal:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-content h2 {
  margin-top: 0;
  font-size: 24px;
  font-weight: bold;
  color: #333;
  text-align: center;
}

.modal-content form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.modal-content form label {
  margin-top: 10px;
  font-size: 18px;
  color: #555;
  width: 100%;
}

.modal-content form input {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

.modal-content form button {
  margin-top: 20px;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  background-color: #4caf50;
  color: white;
  font-size: 16px;
  cursor: pointer;
}

.modal-content form button:hover {
  background-color: #45a049;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 14px;
  cursor: pointer;

  transition: background-color 0.3s ease, color 0.3s ease;
}

.update-btn {
  background-color: #4caf50 !important;
  color: white !important;
}

.update-btn:hover {
  background-color: #45a049 !important;
}

.delete-btn {
  background-color: #f44336 !important;
  color: white !important;
  margin-left: 10px;
}

.delete-btn:hover {
  background-color: #d32f2f !important;
}

/* Buttons */
.modal-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.modal-buttons button {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

#confirmYes {
  background-color: #f44336;
  color: white;
}
#confirmYes:hover {
  opacity: 80%;
}

#confirmNo {
  background-color: gainsboro;
  color: white;
}
#confirmNo:hover {
  opacity: 80%;
}
</style>
<script>
    function showInvoiceDetails(username, waterAmount, totalAmount) {
        document.getElementById('modalUsername').innerText = username;
        document.getElementById('modalWaterAmount').innerText = waterAmount;
        document.getElementById('modalTotalAmount').innerText = totalAmount;

        var modal = document.getElementById('invoiceModal');
        var span = document.getElementsByClassName('close')[0];
        modal.style.display = 'block';

        span.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }
</script>
</head>
<body>

<h2>Quản lý người dùng</h2>

<table>
    <thead>
    <tr>
        <th scope="col" onclick="sortTable(0, true, true)">ID &#x2191;</th>
        <th scope="col" onclick="sortTable(1, false, true)">Username &#x2191;</th>
        <th scope="col" onclick="sortTable(2, false, true)">Email &#x2191;</th>
        <th scope="col" onclick="sortTable(3, true, true)">Số nước tiêu thụ (m³) &#x2191;</th>
        <th scope="col" onclick="sortTable(4, true, true)">Tổng tiền (VND) &#x2191;</th>
        <th>Role</th>
        <th>Hành động</th>
    </tr>
    </thead>    
    <?php
    // Hiển thị dữ liệu từ kết quả truy vấn
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["water_amount"] . "</td>";
        echo "<td>" . number_format($row["total_amount"], 0, ',', '.') . "</td>";
        echo "<td>" . $row["role"] . "</td>";
        echo "<td>
        <button class='btn-primary' onclick=\"showInvoiceDetails('{$row['username']}', '{$row['water_amount']}', '" . number_format($row["total_amount"], 0, ',', '.') . "')\">In hóa đơn</button>

        <button class='btn update-btn' onclick='updateProduct(" . $row["id"] . ")'>Cập nhật</button>

        <button class='btn delete-btn' onclick='deleteProduct(" . $row["id"] . ")'>Xóa</button>
        </td>";
        echo "</tr>";
    }
    ?>
</table>

<div class="pagination">
    <?php
    // Hiển thị các nút phân trang
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'" . ($current_page == $i ? " class='active'" : "") . ">$i</a>";
    }
    ?>
</div>

<a href="index.php" class="btn-secondary btn-block">Quay về trang chủ</a>

<!-- Invoice Modal -->
<div id="invoiceModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Chi tiết hóa đơn</h2>
        <p><strong>Tên người dùng:</strong> <span id="modalUsername"></span></p>
        <p><strong>Số nước tiêu thụ:</strong> <span id="modalWaterAmount"></span> m³</p>
        <p><strong>Tổng số tiền:</strong> <span id="modalTotalAmount"></span> VND</p>
    </div>
</div>

</body>

         <!-- modal update -->
         <div id="updateModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Update Product</h2>
    <form id="updateForm" method="POST" action="./database.php">
      <input type="hidden" id="userId" name="userId" readonly>
      <label for="userName">userName:</label>
      <input type="text" id="userName" name="userName"><br>
      <label for="Email">Email</label>
      <input type="text" id="Email" name="Email"><br>
      <button type="button" onclick="submitUpdate()">Cập nhật</button>
    </form>
  </div>
</div>

<script>
    // sorting 
    function sortTable(columnIndex, isNumeric) {
        var table = document.querySelector("table tbody");
        var rows = Array.from(table.rows);

        var isAscending = table.dataset.sortOrder !== 'asc';
        table.dataset.sortOrder = isAscending ? 'asc' : 'desc';

        rows.sort((a, b) => {
            var cellA = a.cells[columnIndex].innerText;
            var cellB = b.cells[columnIndex].innerText;

            if (isNumeric) {
                cellA = parseFloat(cellA);
                cellB = parseFloat(cellB);
            }

            if (cellA < cellB) {
                return isAscending ? -1 : 1;
            } else if (cellA > cellB) {
                return isAscending ? 1 : -1;
            } else {
                return 0;
            }
        });

        rows.forEach(row => table.appendChild(row));

function updateProduct(id) {
  // Fetch product data using AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "database.php?id=" + id, true);
  xhr.onload = function () {
    if (xhr.status == 200) {
      console.log("Response received: ", xhr.responseText);
      var product = JSON.parse(xhr.responseText);
      if (product.error) {
        alert(product.error);
      } else {
        document.getElementById("productId").value = product.ID; // Cập nhật productId
        document.getElementById("productName").value = product.TenSP;
        document.getElementById("productPrice").value = product.Price;
        document.getElementById("updateModal").style.display = "block";
      }
    }
  };
  xhr.send();
}
    }
</script>
</html>
