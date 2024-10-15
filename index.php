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
            <div class="col-lg-7">
                <div class="container-fluid zone">
                    <h3>Azure AI Object Recognition</h3>
                    
                    <!-- Buttons for switching display modes -->
                    <div class="btn-group" role="group" aria-label="Display Mode">
                        <button id="showFull" class="btn btn-primary" disabled>Show Full Data</button>
                        <button id="showObjects" class="btn btn-secondary" disabled>Show Objects Only</button>
                    </div>

                    <pre id="result"></pre>
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
        const showFullButton = document.getElementById('showFull');
        const showObjectsButton = document.getElementById('showObjects');

        let apiResponseData = null;  // Variable to store the API response

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

            const file = pictureInput.files[0];
            if (!file) {
                resultDisplay.textContent = 'Veuillez sélectionner une image.';
                return;
            }

            // Envoyer la requête à l'API Azure Vision
            fetch('https://iterationoptimum.cognitiveservices.azure.com/vision/v3.2/detect', { // Remplacez par l'URL de votre API
                method: 'POST',
                headers: {
                    'Ocp-Apim-Subscription-Key': '3882cf1d781a48179aa1beede4c60e50',
                    'Content-Type': 'application/octet-stream' // Important pour envoyer l'image binaire
                },
                body: file
            })
            .then(response => response.json())
            .then(data => {
                apiResponseData = data;  // Store the API response in the variable
                showFullButton.disabled = false;
                showObjectsButton.disabled = false;
                displayFullData();  // Default display is full data
            })
            .catch(error => {
                resultDisplay.textContent = 'Erreur lors de l\'envoi : ' + error;
            });
        });

        // Display the full API data
        function displayFullData() {
            resultDisplay.textContent = JSON.stringify(apiResponseData, null, 2);
        }

        // Display only the objects found in plain text format
        function displayObjectsOnly() {
            resultDisplay.textContent = ''; // Clear the previous content

            if (apiResponseData && apiResponseData.objects && apiResponseData.objects.length > 0) {
                apiResponseData.objects.forEach((obj, index) => {
                    const objectText = `Object ${index + 1}: ${obj.object} (Confidence: ${(obj.confidence * 100).toFixed(2)}%)\n`;
                    resultDisplay.textContent += objectText;
                });
            } else {
                resultDisplay.textContent = 'No objects found.';
            }
        }

        // Event listeners for the buttons
        showFullButton.addEventListener('click', displayFullData);
        showObjectsButton.addEventListener('click', displayObjectsOnly);
    </script>
</body>
</html>
