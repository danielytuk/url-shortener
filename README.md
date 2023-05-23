This repository contains PHP code for a simple URL shortener. The code provides functionality to shorten long URLs into shorter ones and redirect users to the original URLs when the short codes are accessed. It utilizes a MySQL database to store the mappings between original and short URLs.

## Getting Started

To use this code, you need to have a PHP environment set up with a MySQL database. Follow these steps to get started:

1. Clone the repository: `git clone https://github.com/danielytuk/url-shortener.git`
2. Set up a MySQL database and note down the database host, username, password, and database name.
3. Modify the code in the `<?php ... ?>` block to update the database connection details:
```php
$db = new mysqli("host", "user", "pass", "database_name");
```
Replace the placeholders (`host`, `user`, `pass`, `database_name`) with your actual database information.

4. Create a table in your MySQL database called `links` with two columns: `original` and `short`. This table will store the original and shortened URLs.

5. Ensure that your PHP environment is properly configured and can execute the code.

## Usage

The code supports two types of requests: redirect requests and shorten requests.

### Redirect Requests

To redirect a user to the original URL associated with a short code, make a GET request to the PHP file with the `code` parameter set to the short code. For example:
```
https://example.com/?code=ABC123
```
If the short code exists in the database, the user will be redirected to the original URL.

### Shorten Requests

To shorten a URL, make a GET request to the PHP file with the `url` parameter set to the URL you want to shorten. For example:
```
https://example.com/?url=https://www.example.com/very-long-url
```
If the URL is valid, the code will either return an existing short URL for the same original URL or generate a new short code and store it in the database. The response will be a JSON object containing the short URL.

You can also provide a custom short code by including the `code` parameter in the request. If the specified short code is available and valid, it will be used; otherwise, a new short code will be generated.

## Error Handling

The code includes basic error handling for different scenarios:

- If an invalid request is made (e.g., missing parameters), a JSON response with an appropriate error message and a 400 status code will be returned.
- If the requested short code does not exist in the database, a JSON response with a 404 status code will be returned.
- If an error occurs during the execution of a request (e.g., database connection issues), a JSON response with an error message and a 500 status code will be returned.

## Security Considerations

This code implements basic input sanitization by using the `htmlspecialchars` and `trim` functions to sanitize user input. However, it is important to ensure that your PHP environment is properly configured and secure to prevent potential security vulnerabilities. Additionally, consider implementing further security measures, such as input validation, user authentication, and authorization, depending on your specific use case and requirements.

## License

The code in this repository is provided under the [MIT License](https://opensource.org/licenses/MIT). Feel free to modify and use it according to your needs.

## Contributions

Contributions to this code repository are welcome. If you encounter any issues or have suggestions for improvements, please open an issue or submit a pull request.
