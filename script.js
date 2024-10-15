const inputPicture = document.getElementById("input-picture");
const imgOverview = document.getElementById("img-overview");
const submitButton = document.getElementById("submit-button");
const outputDisplay = document.getElementById("output-display");
const checkDisplay = document.getElementById("check-display");

let creds = null;
let output = null;

function get_creds() {
    fetch("creds.json")
        .then(response => response.json())
        .then(data => {
            creds = data;
        });
}

function set_creds() {
    creds["endpoint"] = prompt("Endpoint:")
    creds["subscription-key"] = prompt("Subscription key:")
}

function import_picture() {
    display_reset();
    if (inputPicture.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            imgOverview.src = e.target.result;
        };
        reader.readAsDataURL(inputPicture.files[0]);
        submitButton.hidden = false;
    }
    else {
        imgOverview.src = "";
        submitButton.hidden = true;
    }
}

async function submit_picture() {
    try {
        const inputFile = inputPicture.files[0];

        if (!inputFile) {
            display_error("please import a picture")
            return;
        }

        fetch(creds["endpoint"] + "/vision/v3.2/detect", {
            method: "POST",
            headers: {
                "Ocp-Apim-Subscription-Key": creds["subscription-key"],
                "Content-Type": "application/octet-stream"
            },
            body: inputFile
        })
            .then(response => response.json())
            .then(data => {
                output = data;
                display_output(data);
            })
            .catch(error => {
                display_error(error)
            });

    } catch (error) {
        display_error(error)
    }
}

function display_output() {
    if (!checkDisplay.checked) {
        outputDisplay.textContent = null;
        if (output && output.objects && output.objects.length > 0) {
            output.objects.forEach((obj, index) => {
                const objectText = `Object ${index + 1}: ${obj.object} (Confidence: ${(obj.confidence * 100).toFixed(2)}%)\n`;
                outputDisplay.textContent += objectText;
            });
        }
    }
    else {
        outputDisplay.textContent = JSON.stringify(output, null, 2);
    }
}

function display_error(error) {
    console.log("Error: ", error);
    display_reset();
    outputDisplay.textContent = "Error: " + error;
}

function display_reset() {
    output = null;
    display_output();
}

get_creds();