<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="http://www.google.com/search">
        <div> Let's search Google:
            <input name="q" />
            <!-- <input type="text" name="a" value="Colbert Report" />
            <input type="submit" value="Booyah!" /> -->
            <!-- <input name="a" />
            <input type="button" /> -->
            <form method="get">
                <p><input type="text" name="hoten" placeholder="Nhập tên"></p>
                <p>Sở thích của bạn:</p>
                <input type="checkbox" name="sothich[]" value="bongda"> Bóng đá
                <input type="checkbox" name="sothich[]" value="amnhac"> Âm nhạc
                <input type="checkbox" name="sothich[]" value="docsach"> Đọc sách
                <input type="submit" value="Gửi">
            </form>
            <form method="post" enctype="multipart/form-data">
                <p>Chọn file:</p>
                <input type="file" name="tep">
                <br><input type="submit" value="Upload">
            </form>
            <textarea rows="4" cols="20">

            </textarea>
            <input type="radio" name="cc" value="visa" checked="checked" /> Visa

            <input type="radio" name="cc" value="mastercard" />
            MasterCard

            <input type="radio" name="cc" value="amex" /> American
            Express
            <select name="favoritecharacter[]" size="3" multiple="multiple">

                <option>Frodo</option>

                <option>Bilbo</option>

                <option>Gandalf</option>

                <option>Galandriel</option>

                <option selected="selected">Aragorn</option>

            </select>
        </div>

    </form>
</body>

</html>