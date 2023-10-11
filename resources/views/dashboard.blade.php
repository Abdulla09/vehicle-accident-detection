<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folder Images</title>
    <style>
        .folder {
            margin: 10px;
        }
        .images {
            display: flex;
            flex-wrap: wrap;
        }
        .images img {
            margin: 5px;
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div id="folders-container"></div>

    <img src="http://localhost:3000/vad/resources/views/yolov5/runs//detect//exp5//crops//person//0.jpg" alt="">

    <script>
        async function fetchData() {
            try {
                const response = await fetch('http://localhost:3000/vad/resources/views/yolov5/getImages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ key: 'value' })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                const foldersContainer = document.getElementById('folders-container');
                data.folders.forEach(folder => {
                    const folderDiv = document.createElement('div');
                    folderDiv.className = 'folder';
                    folderDiv.textContent = `Folder: ${folder.folder}`;

                    const imagesDiv = document.createElement('div');
                    imagesDiv.className = 'images';
                    folder.images.forEach(imagePath => {
                        if (typeof imagePath === 'string') {
                            const imgElement = document.createElement('img');
                            imgElement.src = imagePath;
                            imagesDiv.appendChild(imgElement);
                        }
                    });

                    folderDiv.appendChild(imagesDiv);
                    foldersContainer.appendChild(folderDiv);
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Call the fetchData function when the page loads
        fetchData();
    </script>
</body>
</html>
