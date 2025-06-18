# PHP cURL Code Generator

This single-page tool builds customizable PHP cURL scripts directly in your browser. Open `curl-gen.php` and fill out the form to describe your request. When you're done, copy or download the resulting snippet as a ready-to-run PHP file.

## Features

- Works with **GET** or **POST** requests
- Easily add any number of custom headers
- Optional cookie jar handling for session persistence
- Choose to parse response headers or capture a redirect URL
- Extract a substring from the response body or read a specific JSON key
- Toggle sections show and hide automatically when options are checked

Everything runs client side – no PHP installation is required until you want to run the generated script.

## Usage

1. Open `curl-gen.php` in a modern web browser.
2. Enter the request URL and select the HTTP method.
3. Fill in any optional sections such as headers or body data.
4. Click **Generate Code** to view the PHP snippet.
5. Use **Download File** to save it as `curl_request.php`.

Feel free to adapt the snippet or extend the page for your own needs. Pull requests and suggestions are welcome!
