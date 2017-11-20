#Movie API
This API offers information on Movies, Actors and Genres and allows users to read and write data from a variety of endpoints

##Requirements
1. docker (=> 17.05)
2. docker-compose (=> 1.17.0)

##Installation
1. Clone the Github Repository with `git clone https://github.com/cpave3/movie-api`
2. Move into the new directory with `cd movie-api`
3. Make the installer executable with `chmod +x ./install.sh`
4. Run the installer with `./install.sh`

##Usage
Once the Installer script has finished, the API should be accessible at http://localhost:8080/api/v1/

For interacting with the API, I reccomend [Postman](https://www.getpostman.com)

Please note that most endpoints require authentication. You can authenticate witht eh API by providing your API key in the header of your request like so: `X-Authorization: {api_key_goes_here}`

In order to get an API key, you can submit the following request:

```
POST: /api/v1/login
+ Request (application/json)

      {
        "email": "admin@admin.com",
        "password": "secret"
      }

+ Response 200 (application/json)

      {
          "name": "Administrator",
          "email": "admin@admin.com",
          "keys": [
              "76b73559bdb40c371f46d07b82757a1f75a32dff"
          ]
      }
```

Your API key will be different to the example above, but for testing purposes, the above credentials are valid (email and password).

If you want to make a new user, this can be done through the UI. Navigate to `/register` and complete the registration form. You can now submit the above request with your new credentials.

For a list of all supported endpoints and requests, please see the included `apiary.apib` file or view it online at [Apiary.io](https://movieapi62.docs.apiary.io/#)

##Testing

The project was developed using Test Driven Development. As such, each method in each controller is backed by a collection of tests to ensure that the output of the API meets expectations.

Further to this, **Dredd** was used to test the API against the API Blueprint documentation. It must be noted that some Dredd tests failed. This is due to inexperience with Dredd, as I was unfamiliar with best practices when it comes to testing endpoints using this tool. Some tests would fail simply due to the order that the tests were performed in (deleting something and then later checking if it exists). This is because I wrote the API Blueprint with informing in mind, instead of testing. Although these tests failed, I have verified through other automated and manual tests that the endpoints in question do actually work as intended.


##What could be Improved

If I were to do the project again, there are a few things I would like to change. The most significant of these is Image uploads. At the moment, Images can only be uploaded in base64 format via JSON. While this works, it is not ideal as the images bloat in size by about 30% when converted. I would not remove this option, but I would include an additional endpoint to allow image uploads with normal `multipart/form-data` style uploads. Ultimately, I would like to find a better solution for uploads which does not rely on base64, but does not break away from the JSON style requests of all the other endpoints.

As mentioned above, some Dredd tests failed due to the example ids used in the `.apib` file. If I had more time, I would like to restructure the API Blueprint to allow the Dredd test to pass.
