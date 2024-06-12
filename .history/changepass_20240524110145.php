<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .box-dashboard {
  box-sizing: border-box;
  padding: 30px;
  background-color: #f4f4f4;
  box-shadow: 0px 3px 5px 0px rgba(204, 204, 204, 0.4509803922);
  border-radius: 5px;
}

.dashboard-title {
  width: 100%;
  padding: 0 30px;

  display: flex;
  align-items: center;
  height: 60px;
  font-size: 1.8em;
  font-weight: 600;
  box-shadow: 0px 3px 5px 0px rgba(204, 204, 204, 0.4509803922);
  background-color: #fff;
}

.text-red {
  color: #ef4444;
}

.btn {
    color : #4caf50;
}

    form {
        width: 300px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    input[type="password"],
    input[type="submit"] {
        display: block;
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>

<h2>Change Password</h2>

<main class="main-dashboard">
      <div class="dashboard-title">Đổi mật khẩu</div>
      <main class="page">
        <div class="box-dashboard">
          <form method="post">

            <div class="mb-3">
              <label for="exampleInputPass1" class="form-label"
                >Mật khẩu cũ
                <i class="text-red">*</i>
              </label>
              <input
                type="text"
                name="oldPass"
                class="form-control"
                id="exampleInputPass1"
                required
              />
            </div>

            <div class="mb-3">
              <label for="exampleInputNewPass1" class="form-label"
                >Mật khẩu mới
                <i class="text-red">*</i>
              </label>
              <input
                type="text"
                name="newPass"
                class="form-control"
                id="exampleInputNewPass1"
                required
              />
            </div>

            <div class="mb-3">
              <label for="exampleInputConfirm" class="form-label">
                Xác nhận mật khẩu
                <i class="text-red">*</i>
              </label>
              <input
                type="text"
                name="confirm"
                class="form-control"
                id="exampleInputConfirm"
                required
              />
            </div>

            <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
          </form>
        </div>
      </main>
    </main>

</body>
</html>
