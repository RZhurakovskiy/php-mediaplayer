<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Медиаплеер</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }
        #videoContainer {
            position: relative;
            width: 100%;
            height: 100vh;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        video,
        img {
            max-width: 100%;
            max-height: 100vh;
            height: 100%;
        }
        #image {
            display: none;
        }
        .nav a {
            color: black;
            margin-right: 10px;
            text-decoration: none;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div id="videoContainer">
        <video id="videoPlayer" controls></video>
        <img id="image" alt="Media Image">
    </div>
    <script>
    let mediaFiles = [];
    let currentIndex = 0;
    let imageInterval;
    let videoPlayer = document.getElementById('videoPlayer');
    let image = document.getElementById('image');
    let previousMediaFiles = [];

    async function fetchMediaFiles() {
        try {
            let response = await fetch('get_media_files.php');
            if (!response.ok) {
                throw new Error('Успешный ответ сервера');
            }
            let fetchedMediaFiles = await response.json();
            if (JSON.stringify(fetchedMediaFiles) !== JSON.stringify(previousMediaFiles)) {
                let previousIndex = currentIndex;
                mediaFiles = fetchedMediaFiles;
                previousMediaFiles = fetchedMediaFiles;
                currentIndex = previousIndex >= mediaFiles.length ? 0 : previousIndex;
                showNextMedia(false);
            }
        } catch (error) {
            console.error('Ошибка при получении медиафайлов:', error);
        }
    }

    function showNextMedia(auto=true) {
        if (currentIndex < mediaFiles.length) {
            let currentFile = mediaFiles[currentIndex];

            if (imageInterval) {
                clearTimeout(imageInterval);
            }

            videoPlayer.pause(); 
            videoPlayer.src = '';
            videoPlayer.style.display = 'none';

            if (currentFile.file_type === 'video') {
                videoPlayer.src = currentFile.file_name;
                videoPlayer.style.display = 'block';
                image.style.display = 'none';
                videoPlayer.play();
                videoPlayer.onended = () => {
                    currentIndex++;
                    showNextMedia();
                };
            } else if (currentFile.file_type === 'image') {
                image.style.display = 'block';
                image.src = currentFile.file_name;
                imageInterval = setTimeout(() => {
                    currentIndex++;
                    showNextMedia();
                }, 10000);
            }
        } else if(auto) {
            currentIndex = 0;
            showNextMedia();
        }
    }

    setInterval(fetchMediaFiles, 15000); 
    fetchMediaFiles();
    </script>
</body>
</html>
