<?php
session_start();
if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: index.php');
    exit;
}
?>

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
        <label>Items für Entity ID:</label>
        <input type="number" name="id" required>
        <button type="submit">Anzeigen</button>
    </form>
    <div id="item-container"></div>
    <a href="./index.php">Item hinzufügen</a>

    <script>
        window.onload = () => {}

        const form = document.getElementById('entity-id-form');

        // Attach a submit event listener to the form
        form.addEventListener('submit', (event) => {
            // Prevent the default form submission behavior
            event.preventDefault();

            document.getElementById("item-container").innerHTML = "";

            // Get the form data
            const formData = new FormData(form);
            const entityId = formData.get("id");

            console.log(formData);

            const url = `./php/api.php/entity/items?id=${entityId}`
            const request = new Request(url, {
                method: 'POST',
                body: formData
            });

            fetch(`./php/api.php/entity/items?id=${entityId}`)
                .then(response => response.json())
                .then(data => {

                    console.log(data);
                    document.getElementById("error-container").innerHTML = JSON.stringify(data);

                    if (data.length > 0) {
                        data.forEach(itemData => {
                            buildItemElement(itemData);
                        });
                    } else {
                        document.getElementById("item-container").innerText = "Keine Items";
                    }

                    console.log(location.href);
                })
                .catch(error => console.error("Error: ", error));
        });

        function buildItemElement(itemData) {
            let div = document.createElement('div');
            div.setAttribute("id", "item-" + itemData.id)
            div.classList.add('item');
            let title = document.createTextNode(itemData.name + "[" + itemData.amount + "]");
            div.appendChild(title);
            let img = document.createElement("img");
            img.src = "./uploads/" + itemData.image;
            img.width = 100;
            div.appendChild(img);
            document.getElementById("item-container").appendChild(div)
        }
    </script>

</body>

</html>