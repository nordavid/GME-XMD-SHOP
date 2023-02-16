<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add Items</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: small;
        }
    </style>
</head>

<body>
    <div id="error-container"></div>
    <form id="entity-id-form">
        <label>ID:</label>
        <input type="number" name="id" required>
        <button type="submit">Test</button>
    </form>

    <script>
        window.onload = () => {}

        const form = document.getElementById('entity-id-form');

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const formData = new FormData(form);
            const userId = 123;

            fetch(`./php/api.php/account/register?id=${userId}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log(response.status);
                    if (response.status === 200) return response.json();
                    else throw new Error("HTTP status " + response.status);
                })
                .then(data => console.log(data))
                .catch(error => console.error(error));
        });
    </script>

</body>

</html>