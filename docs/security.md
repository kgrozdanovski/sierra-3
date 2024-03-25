# Symfony API Authentication and Token Management

## Overview
This document details the process of authentication, token generation, encryption, and decryption within a Symfony API 
framework, leveraging PHP for cryptographic operations.

## Authentication Process

### Bearer Token Extraction
The client sends a request with an Authorization header containing a Bearer token.
The BearerTokenAuthenticator service fetches the token from the Authorization header.
The token is then processed to remove the "Bearer " prefix to isolate the actual token string.
```plaintext
+------------+     +-----------------------+
|  Client    | --> |  Authorization Header |
+------------+     +-----------------------+
                              |
                              v
                     Extract Bearer Token
```

### Token Decryption and Validation

The token undergoes URL-safe Base64 decoding and decryption using the Cryptography service.
The decrypted token reveals the user's UUID (first 36 characters) and the token base (remainder of the string).
The system retrieves the user and their corresponding access token from the database.
It validates the token against expiration, deletion status, and integrity by comparing the hash values.
```plaintext
     Decrypted Token
            |
            v
+-----------+-----------+
| User UUID | Token Base|
+-----------+-----------+
            |
            v
     Database Check
```

### Passport Creation

Upon successful validation, a Symfony Passport object is created, embedding the user's UUID and roles.
This passport is then used to authenticate the user within the Symfony security system.
```plaintext
 Validated Token
       |
       v
+----------------+
| Create Passport|
+----------------+
```

> Note: `$userIdentifier` is matched against whatever User property you define in the security.yaml `app_user_provider`.

## Token Generation

1. A secure random key base is generated and combined with the user's UUID;
2. The combination is encrypted and URL-safe Base64 encoded to form the final token;
3. This token is then hashed and stored in the database alongside the user entity and expiration time.

```plaintext
+-------------+    +------------+    +------------------+
| Generate Key| -> | Combine &  | -> | Encrypt & Encode |
+-------------+    | Encrypt    |    +------------------+
                          |
                          v
                +------------------+
                |  SHA-3 Hash Base |
                +------------------+
                          |
                          v
                +------------------+
                | Store in Database|
                +------------------+
```

## Cryptography

### Encryption

1. The encrypt method in Cryptography loads a private key and uses AES-256-CBC encryption;
2. The method concatenates an initialization vector (IV) with the encrypted data to produce the output.
```plaintext
+---------------+    +----------------+    +-----------------+
| Load Private  | -> | Generate IV &  | -> | Encrypt & Concat|
| Key           |    | Encrypt Data   |    | IV + Data       |
+---------------+    +----------------+    +-----------------+
```

### Decryption

1. The decrypt method extracts the IV and decrypts the remaining data using the same private key;
2. The original data is then extracted from the encrypted payload.
```plaintext
+---------------+    +----------------+    +--------------+
| Load Private  | -> | Extract IV &   | -> | Decrypt Data |
| Key           |    | Decrypt Data   |    +--------------+
+---------------+    +----------------+
```

## Docker Configuration

- SSL certificates and keys are managed in the Docker environment.
- Permissions are set to ensure the application can access these cryptographic assets.
```plaintext
+-------------+    +---------------------+
| Set Up SSL  | -> | Generate OpenSSL    |
| Permissions |    | Certificates & Keys |
+-------------+    +---------------------+
```

Make sure the paths to the SSL certificates and keys are correctly set in the .env file 
(e.g. ENV var `USER_SIGNATURE_PRIVATE_KEY_FILENAME`).