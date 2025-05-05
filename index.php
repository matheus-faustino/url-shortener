<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>URL Shortener</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">URL Shortener</h1>

        <div class="space-y-4">
            <input
                type="url"
                id="url"
                placeholder="Paste or type your URL here"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button
                onclick="shortenUrl()"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                Shorten
            </button>
        </div>

        <div id="result" class="mt-6 p-4 bg-gray-50 rounded-md border border-gray-200 hidden">
            <p class="text-gray-700">Shortened URL:</p>
            <div class="mt-2 flex">
                <a
                    id="shortUrl"
                    href="#"
                    target="_blank"
                    class="text-blue-600 hover:text-blue-800 truncate mr-2 flex-1"></a>
                <button
                    onclick="copyToClipboard(this)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded text-sm">
                    Copy to clipboard
                </button>
            </div>
        </div>
    </div>

    <script>
        function shortenUrl() {
            const url = document.getElementById('url').value;
            if (!url) {
                alert('Please, provide a valid URL.');
                return;
            }

            fetch('api/create.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        url: url
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.shortUrl) {
                        document.getElementById('shortUrl').href = data.shortUrl;
                        document.getElementById('shortUrl').textContent = data.shortUrl;
                        document.getElementById('result').classList.remove('hidden');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an unexpected error while trying to shorten the provided URL. Please, try again.');
                });
        }

        function copyToClipboard(button) {

            console.log(button);

            const shortUrl = document.getElementById('shortUrl').textContent;

            navigator.clipboard.writeText(shortUrl)
                .then(() => {
                    button.textContent = 'Copied!';
                    button.classList.add('bg-green-200');

                    setTimeout(() => {
                        button.textContent = 'Copy to clipboard';
                        button.classList.remove('bg-green-200');
                    }, 2000);
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('There was an unexpected error while trying to copy the URL. Please, try again.');
                });
        }
    </script>
</body>

</html>