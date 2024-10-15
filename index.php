<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="fav.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Geologica">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Azure AI Object Recognition</title>
</head>

<body>
    <nav class="navbar navbar-dark sticky-top navbar-top">
        <h2>Azure AI Object Recognition</h2>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col col-lg-5">
                <div class="container-fluid div-zone">
                    <h3>Import a picture</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input class="input-hidden" type="file" id="picture" name="input-img" accept="image/png, image/jpeg"><br>
                        <img class="img-overview" id="img-overview" src="icon.png"><br>
                        <input class="input input-button" type="submit" id="envoyer" value="Submit">
                    </form>
                </div>
            </div>
            <div class="col col-lg-7">
                <div class="container-fluid div-zone">
                    <h3>AI Output</h3>
                    <pre class="ai-output" id="ai-output">output</pre>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-dark justify-content-center fixed-bottom navbar-bottom">
        <p class="copyright">&copy; 2024 OUVRARD Emilien, MERYET Benjamin and ASTRUC Mathieu</p>
    </nav>

    <script>
        const pictureInput = document.getElementById('picture');
        const envoyerButton = document.getElementById('envoyer');
        const apercu = document.getElementById('apercu');
        const resultDisplay = document.getElementById('result');

        // Activer le bouton envoyer quand une image est sélectionnée
        pictureInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    apercu.src = e.target.result;
                    apercu.style.display = 'block';
                };
                reader.readAsDataURL(file);
                envoyerButton.disabled = false;
            }
        });

        // Envoyer le formulaire
        document.getElementById('imageForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêcher le rechargement de la page

            const formData = new FormData();
            formData.append('picture', pictureInput.files[0]);

            // Envoyer la requête à l'API
            fetch('https://raphsonnewton99.cognitiveservices.azure.com/vision/v3.2/detect', { // Remplacez par l'URL de votre API
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Afficher la réponse de l'API
                resultDisplay.textContent = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                resultDisplay.textContent = 'Erreur lors de l\'envoi : ' + error;
            });
        });
    </script>
</body>

</html>
