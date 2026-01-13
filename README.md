# Google Drive PHP OAuth Integration

This project is a PHP-based web application that integrates with the Google Drive API using OAuth 2.0.
It allows authenticated users to securely access their Google Drive and interact with folders and files programmatically.

The application uses the official Google API PHP Client and handles authentication using access and refresh tokens.

---

## How It Works

This application follows the standard **Google OAuth 2.0 authorization flow**.

### Authentication Flow

* When the application is accessed for the first time, the user is redirected to Google’s OAuth consent screen.
* After granting permission, Google redirects the user back to the application with an authorization code.
* The application exchanges this code for:

  * an **access token**
  * a **refresh token**

The tokens are stored locally and used for subsequent requests.

---

### Token Handling

* Authorization is required **only once**.
* The access token is reused for API calls.
* When the access token expires, it is automatically refreshed using the refresh token.
* The user is not required to log in again unless:

  * the token file is deleted
  * access is revoked from the Google account
  * OAuth credentials are changed

---

### Google Drive Access

Once authenticated, the application:

* Initializes a Google Drive service instance
* Makes authorized API requests using the stored token
* Can list and access Google Drive folders and files

---

## Features

* Google OAuth 2.0 authentication
* Secure access to Google Drive API
* Automatic access token refresh
* One-time authorization flow
* PHP backend using Google API Client
* Composer-based dependency management
* Secure handling of sensitive credentials

---

## Technologies Used

* PHP 8.x
* Google Drive API
* Google OAuth 2.0
* Google API PHP Client
* Composer
* HTML5

---

## Security Notes

* OAuth credentials and tokens are excluded from the repository
* Sensitive files are ignored using `.gitignore`
* No API keys or tokens are exposed in the source code

---


## Author

**harrisstef**

This project is part of my personal portfolio.
