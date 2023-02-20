<?php
session_start();
if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: index.html');
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

        form {
            width: 500px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        #item-properties {
            margin-top: 10px;
        }

        .item-property {
            padding-top: 10px;
            margin-bottom: 20px;
            border-top: 1px solid black;
        }
    </style>
</head>

<body>
    <div id="error-container"></div>
    <form id="add-item-form" action="add_item.php" method="POST" enctype="multipart/form-data">
        <label>Item Name:</label>
        <input type="text" name="itemname" required>
        <label>Category:</label>
        <select name="category" required>
            <option value="Weapon">Waffe</option>
            <option value="Armor">Rüstung</option>
            <option value="Spaceship">Raumschiff</option>
        </select>
        <label>Rarity:</label>
        <select name="rarity" required>
            <option value="Common">Common</option>
            <option value="Rare">Rare</option>
            <option value="Legendary">Legendary</option>
        </select>
        <label>Description:</label>
        <input type="text" name="description" required>
        <label>Cost:</label>
        <input type="number" name="cost" required>
        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>
        <div id="item-properties">
            <div class="item-property">
                <label>Property Name:</label>
                <input type="text" name="prop_names[]" required>
                <label>Property Type:</label>
                <select name="prop_types[]" required>
                    <option value="Playerstats">Playerstats</option>
                    <option value="Buff">Buff</option>
                </select>
                <label>Stat Type:</label>
                <select name="prop_stat_types[]" required>
                    <option value="Hp">Hp</option>
                    <option value="Armor">Rüstung</option>
                    <option value="Damage">Schaden</option>
                    <option value="Speed">Geschwindigkeit</option>
                    <option value="Strength">Stärke</option>
                    <option value="Charism">Charisma</option>
                    <option value="Intelligence">Intelligenz</option>
                    <option value="Skill">Skill</option>
                </select>
                <label>Value:</label>
                <input type="number" name="prop_values[]" required>
            </div>
        </div>
        <button type="button" id="add-property-button">Add Property</button>
        <input type="submit" value="Submit">
    </form>

    <a href="./index.php">Home</a>

    <script>
        const form = document.getElementById('add-item-form');

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(form);

            console.log(formData);

            const url = './php/api.php/shop/item/add'
            const request = new Request(url, {
                method: 'POST',
                body: formData
            });

            fetch(request)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    document.getElementById("error-container").innerHTML = data.message;
                })
                .catch(error => console.error("Error: ", error));
        });

        document.getElementById('add-property-button').addEventListener('click', function() {
            var lastProperty = document.querySelector('.item-property:last-child');
            // clone the last item property
            var newProperty = lastProperty.cloneNode(true);
            // clear the values of the new item property
            newProperty.querySelectorAll('input, select').forEach(function(input) {
                input.value = '';
            });
            document.getElementById('item-properties').appendChild(newProperty);
        });
    </script>

</body>

</html>