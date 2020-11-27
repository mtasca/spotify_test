## Endpoints

| Name                                           | Method | URI            |
| :---                                           | :---   | :---           |
| [Get List of Artist Albums](#GET-albums-list)  | `GET`  | /api/v1/albums |

## <a name="GET-albums-list"></a>Get List of artist albums
Get Albums

### Request

#### Example

```http
GET /api/v1/albums?q=nirvana
```

### Response

#### Success response

```http
HTTP/1.1 200 Ok
Content-type: application/json
```

```json
{
  "metadata": {
    "code": 200,
    "message": "OK"
  },
  "data": [
    {
      "name": "MTV Unplugged In New York (25th Anniversary – Live)",
      "released": "2019-11-01",
      "tracks": 19,
      "cover": {
        "height": 640,
        "width": 640,
        "url": "https://i.scdn.co/image/ab67616d0000b273227f708373c1587bdd803ea6"
      }
    },
    ...
    {
      "name": "Live And Loud",
      "released": "2019-07-26",
      "tracks": 17,
      "cover": {
        "height": 640,
        "width": 640,
        "url": "https://i.scdn.co/image/ab67616d0000b273b74f766214d58b11d076c447"
      }
    }
  ]
}
```

### Failed Response

#### Bad request (400)
- I send and empty `q`

```http
HTTP/1.1 400 Bad Request
Content-type: application/json
```

```json
{
  "metadata": {
    "code": 400,
    "message": "Bad Request"
  },
  "data": {
    "message": "the `q` parameter is required"
  }
}
```