<html>

<head>
    <title>GSM Encoder</title>
    <style>
        .wrap {
            margin: auto; width: 550px;
        }
        .tArea {
            float: left; margin: 5px;
        }
        .sInput {
            position: absolute; top: 330px; margin: 5px;
        }
        input[type=submit] {
            width: 537px; height: 40px;
        }
        h2 {
            text-align: center;
        }
    </style>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
</head>

<body>
    <div class="wrap">
        <h2>GSM Encoder</h2>
        <form>
            <div class="tArea"><textarea name="message" rows="15" cols="30" wrap="virtual">*710#</textarea></div>
            <div class="sInput"><input name="submit" type="submit" value="Convert"></div>
        </form>
        <div class="tArea"><textarea id="response" name="response" rows="15" cols="30" wrap="virtual"></textarea></div>
    </div>
    <script>
        $(function() {
            $('form').on('submit', function(e) {

                e.preventDefault();

                $.ajax({
                    type: 'post',
                    url: 'post.php',
                    data: $('form').serialize(),
                    success: function(response) {
                        $("#response").val(response);
                    }
                });

            });
        });
    </script>
</body>
</html>