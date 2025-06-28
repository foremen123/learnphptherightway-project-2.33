<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
</head>
<body>
    <h1>Create Transactions</h1>
    <hr>
    <form enctype="multipart/form-data" method="post" action="/store">
        <label>
            <input type="file" name="upload" accept=".csv" required>
            <input type="submit" name="button" placeholder="Отправить">
        </label>
    </form>
</body>
</html>