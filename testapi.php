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
        <input type="number" name="entityId" required>
        <button type="submit">Test</button>
    </form>

    <script>
        window.onload = () => {}

        const form = document.getElementById('entity-id-form');

        // Attach a submit event listener to the form
        form.addEventListener('submit', (event) => {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Get the form data
            const formData = new FormData(form);

            console.log(formData);

            const userId = 123;

            fetch(`./php/api.php/users?id=${userId}`, {
                    method: 'POST',
                    body: JSON.stringify({
                        id: 123
                    })
                })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.error(error));
        });
    </script>

</body>

</html>