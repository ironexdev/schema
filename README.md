# Schema

- self-descriptive and self-validating JSON API framework

## Example

- src/Schema/Example
- point localhost to index.php
- send application/json GET request to /test
- request body (even for GET requests) should look like this:

      {
        "title": "hello"
      }
      
- response should look like this:

      {
        "data": {
          "id": 1
        },
        "errors": [],
        "status": true
      }
