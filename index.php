<?php include('run.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joke of the Day</title>
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/custom.css">
</head>

<body>
    <header>
        <div class="row">
            <div class="logo col-3 pe-5 d-flex flex-row-reverse">
                <img src="img/images.jpg" alt="Logo" class="logo">
            </div>
            <div class="handcrafted col-7 d-flex flex-row-reverse">
                <img src="img/images.jpg" alt="Jim HLS">
                <span class="text-end">Handcrafted by </br> <span class="name">Jim HLS</span></span>
            </div>
            <div class="col-2"></div>
        </div>
    </header>
    <div class="content">
        <div class="header py-5">
            <h1>A joke a day keeps the doctor away</h1>
            <p>If you joke wrong way, your teeth have to pay. (Serious)</p>
        </div>
        <div class="joke-text my-5">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8 text-start mt-4">
                    <p class="fs-6">
                        <!-- Initial joke content will be loaded via JavaScript -->
                    </p>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
        <section>
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4 border-top border-dark py-5 border-opacity-25">
                    <div class="buttons">
                        <button type="button" class="btn btn-primary" id="likeButton">This is Funny!</button>
                        <button type="button" class="btn btn-success" id="notLikeButton">This is not funny.</button>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </section>
        <div class="footer border-top border-dark py-5 border-opacity-25">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <p>This website is created as part of HLSolutions program. The materials contained on this website
                        are provided for general information only and do not constitute any form of advice. HLS assumes
                        no responsibility for the accuracy of any particular statement and accepts no liability for any
                        loss or damage which may arise from reliance on the information contained on this site.</p>
                    <p>Copyright 2021 HLS</p>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
    </div>

    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>

    <script>
        let currentJokeId = 0;

        function loadNextJoke() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_next_joke.php?currentJokeId=' + encodeURIComponent(currentJokeId), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    console.log('Joke response:', response); // Debug line
                    const jokeText = document.querySelector('.joke-text .fs-6');
                    if (response.joke === 'No jokes available.') {
                        jokeText.textContent = "That's all the jokes for today! Come back another day!";
                        document.getElementById('likeButton').style.display = 'none';
                        document.getElementById('notLikeButton').style.display = 'none';
                    } else {
                        jokeText.textContent = response.joke;
                        currentJokeId = response.nextJokeId; // Update current joke ID
                    }
                } else {
                    console.error('Error:', xhr.statusText);
                }
            };
            xhr.send();
        }

        document.getElementById('likeButton').addEventListener('click', function() {
            sendReaction('like');
        });

        document.getElementById('notLikeButton').addEventListener('click', function() {
            sendReaction('not_like');
        });

        function sendReaction(reactionType) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'handle.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    console.log('Reaction response:', response); // Debug line
                    if (response.status === 'success') {
                        loadNextJoke();
                    } else {
                        console.error('Error:', response.message);
                    }
                } else {
                    console.error('Error:', xhr.statusText);
                }
            };
            xhr.send('reactionType=' + encodeURIComponent(reactionType) + '&currentJokeId=' + encodeURIComponent(currentJokeId));
        }

        // Load initial joke
        loadNextJoke();
    </script>
</body>

</html>